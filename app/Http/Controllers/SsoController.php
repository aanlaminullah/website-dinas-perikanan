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
     * Tampilkan halaman error jika ada masalah login, atau langsung redirect ke SSO jika bersih.
     */
    public function login(Request $request)
    {
        // Cek error lewat query param (paling reliable, tidak tergantung session)
        if ($request->query('sso_error')) {
            return view('auth.login-error', [
                'ssoError' => $request->query('sso_error'),
            ]);
        }

        // Cek error lewat session flash (fallback)
        if ($request->session()->has('errors')) {
            return view('auth.login-error', [
                'ssoError' => null,
            ]);
        }

        return $this->redirect($request);
    }

    /**
     * Redirect user ke halaman login Keycloak.
     */
    public function redirect(Request $request)
    {
        $state = Str::random(40);
        $request->session()->put('sso_state', $state);

        Log::info('SSO redirect: state disimpan ke session', [
            'state' => $state,
            'session_id' => $request->session()->getId(),
        ]);

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
        Log::info('SSO callback dipanggil', [
            'session_id' => $request->session()->getId(),
            'all_session_keys' => array_keys($request->session()->all()),
            'has_sso_state' => $request->session()->has('sso_state'),
            'request_state' => $request->input('state'),
            'request_url' => $request->fullUrl(),
            'is_secure' => $request->isSecure(),
            'scheme' => $request->getScheme(),
            'headers' => [
                'x-forwarded-proto' => $request->header('X-Forwarded-Proto'),
                'x-forwarded-for' => $request->header('X-Forwarded-For'),
                'host' => $request->header('Host'),
            ],
        ]);

        // Validasi state untuk mencegah CSRF
        $state = $request->input('state');
        $sessionState = $request->session()->pull('sso_state');

        if ($state !== $sessionState) {
            Log::warning('SSO state mismatch atau session hilang', [
                'request_state' => $state,
                'session_state' => $sessionState,
                'session_id' => $request->session()->getId(),
                'session_all' => $request->session()->all(),
            ]);

            // PENTING: Kirim error lewat query param, BUKAN session flash
            // Karena jika session bermasalah, flash data juga akan hilang → redirect loop
            return redirect()->route('login', [
                'sso_error' => 'Sesi SSO tidak valid (state mismatch). Session mungkin hilang saat redirect ke Keycloak. Silakan coba lagi.',
            ]);
        }

        // Cek jika ada error dari Keycloak
        if ($request->has('error')) {
            Log::warning('SSO error dari Keycloak', [
                'error' => $request->input('error'),
                'description' => $request->input('error_description'),
            ]);

            return redirect()->route('login', [
                'sso_error' => $request->input('error_description', 'Login SSO gagal. Silakan coba lagi.'),
            ]);
        }

        $code = $request->input('code');

        if (!$code) {
            return redirect()->route('login', [
                'sso_error' => 'Authorization code tidak ditemukan.',
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

                return redirect()->route('login', [
                    'sso_error' => 'Gagal menukar authorization code (HTTP ' . $tokenResponse->status() . '). Silakan coba lagi.',
                ]);
            }

            $tokenData = $tokenResponse->json();
            $accessToken = $tokenData['access_token'];
            $refreshToken = $tokenData['refresh_token'] ?? null;

            // Decode JWT payload untuk ambil data user
            $payload = $this->decodeJwtPayload($accessToken);

            if (!$payload) {
                return redirect()->route('login', [
                    'sso_error' => 'Gagal membaca data token. Silakan coba lagi.',
                ]);
            }

            $ssoId = $payload['sub'] ?? null;
            $email = $payload['email'] ?? null;
            $name = $payload['name'] ?? ($payload['given_name'] ?? '') . ' ' . ($payload['family_name'] ?? '');
            $nip = $payload['preferred_username'] ?? null;
            $sessionId = $payload['sid'] ?? $payload['session_state'] ?? null;

            Log::info('SSO login attempt - user data dari token', [
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

                return redirect()->route('login', [
                    'sso_error' => 'Akun dengan email "' . ($email ?? $nip ?? 'unknown') . '" belum terdaftar di sistem. Hubungi administrator untuk mendaftarkan akun.',
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

            Log::info('SSO login berhasil', [
                'user_id' => $user->id,
                'nip' => $user->nip,
                'session_id' => $request->session()->getId(),
            ]);

            return redirect()->intended(route('dashboard'));

        } catch (\Exception $e) {
            Log::error('SSO callback error (exception)', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login', [
                'sso_error' => 'Terjadi kesalahan saat proses login SSO: ' . $e->getMessage(),
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
