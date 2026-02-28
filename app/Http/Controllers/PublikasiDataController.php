<?php

namespace App\Http\Controllers;

use App\Models\ProduksiBudidaya;
use Illuminate\Http\Request;

class PublikasiDataController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', 2025);

        // Ambil semua data, dikelompokkan per kecamatan
        $data = ProduksiBudidaya::where('tahun', $tahun)
            ->orderBy('kode_kecamatan')
            ->orderBy('komoditas')
            ->get()
            ->groupBy('kecamatan');

        // Data untuk grafik: total produksi per bulan dari seluruh kecamatan
        $bulan = [
            'januari',
            'februari',
            'maret',
            'april',
            'mei',
            'juni',
            'juli',
            'agustus',
            'september',
            'oktober',
            'november',
            'desember'
        ];

        $totalPerBulan = [];
        $allRows = ProduksiBudidaya::where('tahun', $tahun)->get();
        foreach ($bulan as $b) {
            $totalPerBulan[] = round($allRows->sum($b), 3);
        }

        $chartData = [
            'labels'   => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            'datasets' => [
                [
                    'label'       => 'Total Produksi Budidaya (Ton)',
                    'data'        => $totalPerBulan,
                    'borderColor' => '#0284c7',
                ]
            ]
        ];

        $tahunList = ProduksiBudidaya::selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $komoditasData = $allRows->groupBy('komoditas')->map(function ($rows) {
            $bulan = [
                'januari',
                'februari',
                'maret',
                'april',
                'mei',
                'juni',
                'juli',
                'agustus',
                'september',
                'oktober',
                'november',
                'desember'
            ];
            return collect($bulan)->map(fn($b) => round($rows->sum($b), 3))->values();
        });

        return view('publikasi-data.index', compact('data', 'chartData', 'tahun', 'tahunList', 'komoditasData'));
    }
}
