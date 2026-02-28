<?php

namespace App\Http\Controllers;

use App\Models\ProduksiBudidaya;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = date('Y');

        $totalProduksi  = ProduksiBudidaya::where('tahun', $tahun)->sum('jumlah');
        $totalKomoditas = ProduksiBudidaya::where('tahun', $tahun)->distinct('komoditas')->count('komoditas');
        $totalKecamatan = ProduksiBudidaya::where('tahun', $tahun)->distinct('kecamatan')->count('kecamatan');

        // Top 5 komoditas berdasarkan jumlah produksi
        $topKomoditas = ProduksiBudidaya::where('tahun', $tahun)
            ->selectRaw('komoditas, SUM(jumlah) as total')
            ->groupBy('komoditas')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Produksi per kecamatan
        $produksiPerKecamatan = ProduksiBudidaya::where('tahun', $tahun)
            ->selectRaw('kode_kecamatan, kecamatan, SUM(jumlah) as total')
            ->groupBy('kode_kecamatan', 'kecamatan')
            ->orderBy('kode_kecamatan')
            ->get();

        return view('dashboard.index', compact(
            'tahun',
            'totalProduksi',
            'totalKomoditas',
            'totalKecamatan',
            'topKomoditas',
            'produksiPerKecamatan'
        ));
    }
}
