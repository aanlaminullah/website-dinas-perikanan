<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\ProduksiBudidaya;
use App\Imports\ProduksiBudidayaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class PublikasiDataController extends Controller
{
    protected array $bulan = [
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

    // -------------------------------------------------------
    // PUBLIK: halaman tampilan untuk pengunjung
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $tahunList = ProduksiBudidaya::selectRaw('DISTINCT tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $tahun   = $request->get('tahun', $tahunList->first() ?? date('Y'));
        $allRows = ProduksiBudidaya::with('kecamatan')->where('tahun', $tahun)->get();

        $data = $allRows
            ->filter(fn($row) => $row->kecamatan !== null)
            ->sortBy('kecamatan_id')
            ->groupBy(fn($row) => $row->kecamatan->nama);

        $totalPerBulan = collect($this->bulan)
            ->map(fn($b) => round($allRows->sum($b), 3))
            ->values()
            ->toArray();

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

        $komoditasData = $allRows->groupBy('komoditas')->map(
            fn($rows) => collect($this->bulan)->map(fn($b) => round($rows->sum($b), 3))->values()
        );

        return view('publikasi-data.index', compact('data', 'chartData', 'tahun', 'tahunList', 'komoditasData'));
    }

    // -------------------------------------------------------
    // ADMIN CRUD
    // -------------------------------------------------------

    /**
     * Tampilkan daftar semua data di halaman admin.
     */
    public function adminIndex(Request $request)
    {
        $tahunList = ProduksiBudidaya::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');

        // Default ke tahun terbaru yang ada di database, bukan tahun sekarang
        $tahun = $request->get('tahun', $tahunList->first() ?? date('Y'));

        $data = ProduksiBudidaya::with('kecamatan')
            ->where('tahun', $tahun)
            ->orderBy('kecamatan_id')
            ->orderBy('komoditas')
            ->paginate(20)
            ->withQueryString();

        return view('admin.publikasi-data.index', compact('data', 'tahun', 'tahunList'));
    }

    /**
     * Tampilkan form tambah data baru.
     */
    public function create()
    {
        $kecamatanList = Kecamatan::orderBy('kode')->get();

        $komoditasList = ProduksiBudidaya::select('komoditas')
            ->distinct()
            ->orderBy('komoditas')
            ->pluck('komoditas');

        return view('admin.publikasi-data.create', compact('kecamatanList', 'komoditasList'));
    }

    /**
     * Simpan data baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kecamatan_id'   => 'required|exists:kecamatan,id',
            'komoditas'      => 'required|string|max:100',
            'tahun'          => 'required|integer|min:2000|max:2099',
            'januari'        => 'nullable|numeric|min:0',
            'februari'       => 'nullable|numeric|min:0',
            'maret'          => 'nullable|numeric|min:0',
            'april'          => 'nullable|numeric|min:0',
            'mei'            => 'nullable|numeric|min:0',
            'juni'           => 'nullable|numeric|min:0',
            'juli'           => 'nullable|numeric|min:0',
            'agustus'        => 'nullable|numeric|min:0',
            'september'      => 'nullable|numeric|min:0',
            'oktober'        => 'nullable|numeric|min:0',
            'november'       => 'nullable|numeric|min:0',
            'desember'       => 'nullable|numeric|min:0',
        ]);

        $validated['jumlah'] = collect($this->bulan)
            ->sum(fn($b) => (float) ($validated[$b] ?? 0));

        foreach ($this->bulan as $b) {
            $validated[$b] = $validated[$b] ?? 0;
        }

        ProduksiBudidaya::create($validated);

        return redirect()
            ->route('admin.publikasi-data.index')
            ->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit data.
     */
    public function edit(ProduksiBudidaya $produksiBudidaya)
    {
        $kecamatanList = Kecamatan::orderBy('kode')->get();

        $komoditasList = ProduksiBudidaya::select('komoditas')
            ->distinct()
            ->orderBy('komoditas')
            ->pluck('komoditas');

        return view('admin.publikasi-data.edit', compact('produksiBudidaya', 'kecamatanList', 'komoditasList'));
    }

    /**
     * Update data di database.
     */
    public function update(Request $request, ProduksiBudidaya $produksiBudidaya)
    {
        $validated = $request->validate([
            'kecamatan_id'   => 'required|exists:kecamatan,id',
            'komoditas'      => 'required|string|max:100',
            'tahun'          => 'required|integer|min:2000|max:2099',
            'januari'        => 'nullable|numeric|min:0',
            'februari'       => 'nullable|numeric|min:0',
            'maret'          => 'nullable|numeric|min:0',
            'april'          => 'nullable|numeric|min:0',
            'mei'            => 'nullable|numeric|min:0',
            'juni'           => 'nullable|numeric|min:0',
            'juli'           => 'nullable|numeric|min:0',
            'agustus'        => 'nullable|numeric|min:0',
            'september'      => 'nullable|numeric|min:0',
            'oktober'        => 'nullable|numeric|min:0',
            'november'       => 'nullable|numeric|min:0',
            'desember'       => 'nullable|numeric|min:0',
        ]);

        $validated['jumlah'] = collect($this->bulan)
            ->sum(fn($b) => (float) ($validated[$b] ?? 0));

        foreach ($this->bulan as $b) {
            $validated[$b] = $validated[$b] ?? 0;
        }

        $produksiBudidaya->update($validated);

        return redirect()
            ->route('admin.publikasi-data.index')
            ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Hapus data dari database.
     */
    public function destroy(ProduksiBudidaya $produksiBudidaya)
    {
        $produksiBudidaya->delete();

        return redirect()
            ->route('admin.publikasi-data.index')
            ->with('success', 'Data berhasil dihapus.');
    }

    /**
     * Tampilkan halaman form import Excel.
     */
    public function importForm()
    {
        return view('admin.publikasi-data.import');
    }

    /**
     * Proses upload dan import file Excel.
     */
    public function importProcess(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File wajib dipilih.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(
                new \App\Imports\ProduksiBudidayaImport(),
                $request->file('file')
            );

            return redirect()
                ->route('admin.publikasi-data.index')
                ->with('success', 'Import data berhasil.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = collect($failures)->map(
                fn($f) => "Baris {$f->row()}: " . implode(', ', $f->errors())
            )->toArray();

            return back()->with('import_errors', $errors);
        }
    }

    /**
     * Download template Excel untuk panduan import.
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="template_produksi_budidaya.csv"',
        ];

        $columns = [
            'kode_kecamatan',
            'kecamatan',
            'komoditas',
            'tahun',
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

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // Baris contoh
            fputcsv($file, [
                '01',
                'Kec. Contoh',
                'Udang',
                '2025',
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0,
                0
            ]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
