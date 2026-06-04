<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    /**
     * Redirect user ke halaman login Keycloak.
     */
    public function redirect(Request $request)
    {
        $state = Str::random(40);
        $request->session()->put('sso_state', $state);

        $params = http_build_query([
            'client_id' => config('services.bds.client_id'),
            'redirect_uri' => config('services.bds.redirect_uri'),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => $state,
        ]);

        $loginUrl = config('services.bds.base_url') . '/protocol/openid-connect/auth?' . $params;

        return redirect($loginUrl);
    }

    /**
     * Tangkap authorization code dari Keycloak dan tukar menjadi token.
     */
    public function callback(Request $request)
    {
        // Validasi state untuk mencegah CSRF
        if ($request->input('state') !== $request->session()->pull('sso_state')) {
            return redirect()->route('login')->withErrors([
                'sso' => 'Sesi SSO tidak valid. Silakan coba lagi.',
            ]);
        }

        // Cek jika ada error dari Keycloak
        if ($request->has('error')) {
            Log::warning('SSO error dari Keycloak', [
                'error' => $request->input('error'),
                'description' => $request->input('error_description'),
            ]);

            return redirect()->route('login')->withErrors([
                'sso' => $request->input('error_description', 'Login SSO gagal. Silakan coba lagi.'),
            ]);
        }

        $code = $request->input('code');

        if (!$code) {
            return redirect()->route('login')->withErrors([
                'sso' => 'Authorization code tidak ditemukan.',
            ]);
        }

        // Tukar authorization code dengan token
        try {
            $tokenResponse = Http::asForm()->post(
                config('services.bds.base_url') . '/protocol/openid-connect/token',
                [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('services.bds.client_id'),
                    'client_secret' => config('services.bds.client_secret'),
                    'redirect_uri' => config('services.bds.redirect_uri'),
                    'code' => $code,
                ]
            );

            if ($tokenResponse->failed()) {
                Log::error('SSO token exchange gagal', [
                    'status' => $tokenResponse->status(),
                    'body' => $tokenResponse->body(),
                ]);

                return redirect()->route('login')->withErrors([
                    'sso' => 'Gagal menukar authorization code. Silakan coba lagi.',
                ]);
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'];
            $refreshToken = $tokenData['refresh_token'] ?? null;

            // Decode JWT payload untuk ambil data user
            $payload = $this->decodeJwtPayload($accessToken);

            if (!$payload) {
                return redirect()->route('login')->withErrors([
                    'sso' => 'Gagal membaca data token. Silakan coba lagi.',
                ]);
            }

            $ssoId = $payload['sub'] ?? null;
            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? ($payload['given_name'] ?? '') . ' ' . ($payload['family_name'] ?? '');
            $nip = $payload['preferred_username'] ?? null;
            $sessionId = $payload['sid'] ?? $payload['session_state'] ?? null;

            Log::info('SSO login attempt', [
                'sso_id' => $ssoId,
                'email' => $email,
                'name' => $name,
                'nip' => $nip,
            ]);

            // Cari user yang sudah terdaftar berdasarkan sso_id atau email
            $user = User::where('sso_id', $ssoId)->first();

            if (!$user && $email) {
                $user = User::where('email', $email)->first();
            }

            if (!$user && $nip) {
                $user = User::where('nip', $nip)->first();
            }

            if (!$user) {
                Log::warning('SSO user not found in local DB', [
                    'sso_id' => $ssoId,
                    'email' => $email,
                    'nip' => $nip,
                ]);

                return redirect()->route('login')->withErrors([
                    'sso' => 'Akun dengan email "' . ($email ?? $nip ?? 'unknown') . '" belum terdaftar di sistem. Hubungi administrator untuk mendaftarkan akun.',
                ]);
            }

            // Update data SSO di user
            $user->update([
                'sso_id' => $ssoId,
                'nip' => $nip,
                'name' => $name ?: $user->name,
            ]);

            // Login user
            Auth::login($user);
            $request->session()->regenerate();

            // Simpan SSO session data
            $request->session()->put('sso_refresh_token', $refreshToken);
            $request->session()->put('sso_session_id', $sessionId);
            $request->session()->put('sso_access_token', $accessToken);

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            Log::error('SSO callback error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->withErrors([
                'sso' => 'Terjadi kesalahan saat proses login SSO. Silakan coba lagi.',
            ]);
        }
    }

    /**
     * Logout dari aplikasi dan Keycloak.
     */
    public function logout(Request $request)
    {
        $refreshToken = $request->session()->get('sso_refresh_token');

        // Logout dari Keycloak jika ada refresh token
        if ($refreshToken) {
            try {
                Http::asForm()->post(
                    config('services.bds.base_url') . '/protocol/openid-connect/logout',
                    [
                        'client_id' => config('services.bds.client_id'),
                        'client_secret' => config('services.bds.client_secret'),
                        'refresh_token' => $refreshToken,
                    ]
                );
            } catch (\Exception $e) {
                Log::warning('SSO logout ke Keycloak gagal', [
                    'message' => $e->getMessage(),
                ]);
            }
        }

        // Logout lokal
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Endpoint untuk Keycloak backchannel logout.
     * Keycloak POST ke sini ketika user logout dari aplikasi lain.
     */
    public function backchannelLogout(Request $request)
    {
        $logoutToken = $request->input('logout_token');

        if (!$logoutToken) {
            return response()->noContent(400);
        }

        try {
            $payload = $this->decodeJwtPayload($logoutToken);

            if (!$payload) {
                return response()->noContent(400);
            }

            // Pastikan ini adalah logout token
            if (!isset($payload['events']['http://schemas.openid.net/event/backchannel-logout'])) {
                return response()->noContent(400);
            }

            $sid = $payload['sid'] ?? $payload['sub'] ?? null;

            if ($sid) {
                // Hapus session yang terkait dengan SSO session ID ini
                // Karena session driver = database, kita bisa query langsung
                \Illuminate\Support\Facades\DB::table('sessions')
                    ->where('payload', 'LIKE', '%' . $sid . '%')
                    ->delete();
            }

            return response()->noContent(204);

        } catch (\Exception $e) {
            Log::error('Backchannel logout error', [
                'message' => $e->getMessage(),
            ]);

            return response()->noContent(500);
        }
    }

    /**
     * Decode JWT payload tanpa verifikasi signature.
     * Untuk mendapatkan data user dari access token.
     */
    private function decodeJwtPayload(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        $payload = base64_decode(strtr($parts[1], '-_', '+/'));

        if (!$payload) {
            return null;
        }

        $data = json_decode($payload, true);

        return is_array($data) ? $data : null;
    }
}
