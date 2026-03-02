<?php

namespace App\Imports;

use App\Models\ProduksiBudidaya;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ProduksiBudidayaImport implements ToModel, WithHeadingRow, SkipsEmptyRows, WithValidation
{
    public function model(array $row): ?ProduksiBudidaya
    {
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

        $jumlah = collect($bulan)->sum(fn($b) => (float) ($row[$b] ?? 0));

        // Cari kecamatan berdasarkan kode atau nama
        $kecamatan = \App\Models\Kecamatan::where('kode', $row['kode_kecamatan'])
            ->orWhere('nama', $row['kecamatan'])
            ->first();

        if (!$kecamatan) return null;

        return new ProduksiBudidaya([
            'kecamatan_id' => $kecamatan->id,
            'komoditas'    => $row['komoditas'],
            'tahun'        => $row['tahun'],
            'januari'        => $row['januari']   ?? 0,
            'februari'       => $row['februari']  ?? 0,
            'maret'          => $row['maret']      ?? 0,
            'april'          => $row['april']      ?? 0,
            'mei'            => $row['mei']        ?? 0,
            'juni'           => $row['juni']       ?? 0,
            'juli'           => $row['juli']       ?? 0,
            'agustus'        => $row['agustus']    ?? 0,
            'september'      => $row['september']  ?? 0,
            'oktober'        => $row['oktober']    ?? 0,
            'november'       => $row['november']   ?? 0,
            'desember'       => $row['desember']   ?? 0,
            'jumlah'         => $jumlah,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_kecamatan' => 'required',
            'kecamatan'      => 'required',
            'komoditas'      => 'required',
            'tahun'          => 'required|integer',
        ];
    }
}
