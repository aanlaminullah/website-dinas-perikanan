<?php

namespace App\Http\Controllers;

use App\Models\PublikasiDokumen;
use Illuminate\Http\Request;

class PublikasiDokumenController extends Controller
{
    public function index(Request $request)
    {
        $tahun    = $request->get('tahun', date('Y'));
        $kategori = $request->get('kategori');
        $search   = $request->get('search');

        $query = PublikasiDokumen::where('aktif', true)
            ->where('tahun', $tahun);

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $dokumen = $query->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        $tahunList = PublikasiDokumen::selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $kategoriList = PublikasiDokumen::whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        return view('publikasi-dokumen.index', compact(
            'dokumen',
            'tahun',
            'tahunList',
            'kategori',
            'kategoriList',
            'search'
        ));
    }

    public function download(PublikasiDokumen $publikasiDokumen)
    {
        return response()->download(
            storage_path('app/public/' . $publikasiDokumen->file),
            $publikasiDokumen->judul . '.' . pathinfo($publikasiDokumen->file, PATHINFO_EXTENSION)
        );
    }
}
