<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\ProduksiBudidaya;

class DashboardController extends Controller
{
    public function index()
    {
        $tahun = date('Y');

        $totalProduksi  = ProduksiBudidaya::where('tahun', $tahun)->sum('jumlah');
        $totalKomoditas = ProduksiBudidaya::where('tahun', $tahun)->distinct('komoditas')->count('komoditas');
        $totalKecamatan = ProduksiBudidaya::where('tahun', $tahun)->distinct('kecamatan_id')->count('kecamatan_id');

        // Top 5 komoditas berdasarkan jumlah produksi
        $topKomoditas = ProduksiBudidaya::where('tahun', $tahun)
            ->selectRaw('komoditas, SUM(jumlah) as total')
            ->groupBy('komoditas')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Produksi per kecamatan (join ke tabel kecamatan untuk ambil nama)
        $produksiPerKecamatan = ProduksiBudidaya::where('produksi_budidaya.tahun', $tahun)
            ->join('kecamatan', 'kecamatan.id', '=', 'produksi_budidaya.kecamatan_id')
            ->selectRaw('kecamatan.kode, kecamatan.nama as kecamatan, SUM(produksi_budidaya.jumlah) as total')
            ->groupBy('kecamatan.id', 'kecamatan.kode', 'kecamatan.nama')
            ->orderBy('kecamatan.kode')
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
