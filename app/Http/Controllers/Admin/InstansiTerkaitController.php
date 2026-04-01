<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstansiTerkait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class InstansiTerkaitController extends Controller
{
    public function index()
    {
        $instansi = InstansiTerkait::orderBy('urutan')->get();
        return view('admin.instansi-terkait.index', compact('instansi'));
    }

    public function create()
    {
        return view('admin.instansi-terkait.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:150',
            'logo'      => 'required|string',
            'url'       => 'nullable|url|max:255',
            'urutan'    => 'nullable|integer',
        ]);

        $logoPath = $this->saveBase64Logo($request->logo);

        $maxUrutan = InstansiTerkait::max('urutan') ?? 0;

        InstansiTerkait::create([
            'nama'   => $request->nama,
            'logo'   => $logoPath,
            'url'    => $request->url,
            'urutan' => $maxUrutan + 1,
            'aktif'  => $request->boolean('aktif', true),
        ]);

        return redirect()->route('admin.instansi-terkait.index')
            ->with('success', 'Instansi berhasil ditambahkan.');
    }

    public function edit(InstansiTerkait $instansiTerkait)
    {
        return view('admin.instansi-terkait.edit', compact('instansiTerkait'));
    }

    public function update(Request $request, InstansiTerkait $instansiTerkait)
    {
        $request->validate([
            'nama'   => 'required|string|max:150',
            'logo'   => 'nullable|string',
            'url'    => 'nullable|url|max:255',
            'urutan' => 'nullable|integer',
        ]);

        $data = [
            'nama'   => $request->nama,
            'url'    => $request->url,
            'urutan' => $request->urutan ?? 0,
            'aktif'  => $request->boolean('aktif'),
        ];

        if ($request->filled('logo') && str_starts_with($request->logo, 'data:image')) {
            Storage::disk('public')->delete($instansiTerkait->logo);
            $data['logo'] = $this->saveBase64Logo($request->logo);
        }

        $instansiTerkait->update($data);

        return redirect()->route('admin.instansi-terkait.index')
            ->with('success', 'Instansi berhasil diperbarui.');
    }

    public function destroy(InstansiTerkait $instansiTerkait)
    {
        Storage::disk('public')->delete($instansiTerkait->logo);
        $instansiTerkait->delete();

        return redirect()->route('admin.instansi-terkait.index')
            ->with('success', 'Instansi berhasil dihapus.');
    }

    private function saveBase64Logo(string $base64): string
    {
        // Decode base64
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($imageData);

        // Buat gambar dari binary
        $manager = new ImageManager(new Driver());
        $image = $manager->read($imageData);

        // Crop jadi persegi dari tengah, resize ke 200x200
        $size = min($image->width(), $image->height());
        $image->cover($size, $size);
        $image->resize(200, 200);

        // Encode ke JPEG kualitas 70
        $encoded = $image->toJpeg(70);

        // Simpan
        $filename = 'instansi-terkait/' . uniqid() . '.jpg';
        Storage::disk('public')->put($filename, $encoded);

        return $filename;
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:instansi_terkait,id',
        ]);

        foreach ($request->ids as $urutan => $id) {
            InstansiTerkait::where('id', $id)->update(['urutan' => $urutan + 1]);
        }

        return response()->json(['success' => true]);
    }
}
