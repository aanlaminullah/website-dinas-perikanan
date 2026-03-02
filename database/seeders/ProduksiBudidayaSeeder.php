<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;
use App\Models\ProduksiBudidaya;

class ProduksiBudidayaSeeder extends Seeder
{
    public function run(): void
    {
        ProduksiBudidaya::truncate();

        // Ambil semua kecamatan, index by kode untuk lookup cepat
        $kecamatanMap = Kecamatan::all()->keyBy('kode');

        $data = [
            // ── SANGKUB (71.08.01) ──
            ['kode' => '71.08.01', 'komoditas' => 'Udang Vaname', 'januari' => 1.2, 'februari' => 1.05, 'maret' => 0.4, 'april' => 0.8, 'mei' => 1.5, 'juni' => 0.8, 'juli' => 0.56, 'agustus' => 0.9, 'september' => 1.9, 'oktober' => 1.1, 'november' => 1.073, 'desember' => 1.2, 'jumlah' => 12.483],
            ['kode' => '71.08.01', 'komoditas' => 'Bandeng', 'januari' => 0.3, 'februari' => 0, 'maret' => 0.6, 'april' => 0.5, 'mei' => 0, 'juni' => 0, 'juli' => 0, 'agustus' => 0, 'september' => 0, 'oktober' => 0.2, 'november' => 0.7, 'desember' => 0, 'jumlah' => 2.305],
            ['kode' => '71.08.01', 'komoditas' => 'Nila', 'januari' => 0.4, 'februari' => 0, 'maret' => 0.3, 'april' => 0.2, 'mei' => 0.1, 'juni' => 0.2, 'juli' => 0.4, 'agustus' => 0.5, 'september' => 0.8, 'oktober' => 0.950, 'november' => 1.2, 'desember' => 0.9, 'jumlah' => 5.95],

            // ── BINTAUNA (71.08.02) ──
            ['kode' => '71.08.02', 'komoditas' => 'Udang Vaname', 'januari' => 0.21, 'februari' => 0, 'maret' => 1, 'april' => 0, 'mei' => 0.6, 'juni' => 0.9, 'juli' => 0.5, 'agustus' => 0, 'september' => 0.889, 'oktober' => 0.670, 'november' => 0, 'desember' => 0.09, 'jumlah' => 4.859],
            ['kode' => '71.08.02', 'komoditas' => 'Bandeng', 'januari' => 0, 'februari' => 0, 'maret' => 0.5, 'april' => 0, 'mei' => 0.3, 'juni' => 1.25, 'juli' => 0, 'agustus' => 0, 'september' => 0, 'oktober' => 0, 'november' => 0, 'desember' => 0, 'jumlah' => 2.05],
            ['kode' => '71.08.02', 'komoditas' => 'Nila', 'januari' => 0, 'februari' => 0, 'maret' => 0, 'april' => 0.315, 'mei' => 0.205, 'juni' => 0, 'juli' => 0.059, 'agustus' => 0.43, 'september' => 0, 'oktober' => 0.260, 'november' => 0, 'desember' => 0.1, 'jumlah' => 1.369],

            // ── BOLANGITANG TIMUR (71.08.03) ──
            ['kode' => '71.08.03', 'komoditas' => 'Udang Vaname', 'januari' => 1, 'februari' => 0.3, 'maret' => 2.1, 'april' => 1.9, 'mei' => 4.488, 'juni' => 0.24, 'juli' => 0.9, 'agustus' => 0.405, 'september' => 1.7, 'oktober' => 0, 'november' => 0, 'desember' => 0, 'jumlah' => 13.033],
            ['kode' => '71.08.03', 'komoditas' => 'Bandeng', 'januari' => 0, 'februari' => 0, 'maret' => 0, 'april' => 0, 'mei' => 0, 'juni' => 0, 'juli' => 0, 'agustus' => 0, 'september' => 0, 'oktober' => 0, 'november' => 0, 'desember' => 0, 'jumlah' => 0],
            ['kode' => '71.08.03', 'komoditas' => 'Nila', 'januari' => 0, 'februari' => 0, 'maret' => 0.3, 'april' => 0.17, 'mei' => 0.17, 'juni' => 0.06, 'juli' => 0, 'agustus' => 0.19, 'september' => 0.15, 'oktober' => 0.8, 'november' => 0, 'desember' => 0, 'jumlah' => 1.84],

            // ── BOLANGITANG BARAT (71.08.04) ──
            ['kode' => '71.08.04', 'komoditas' => 'Udang Vaname', 'januari' => 6.725, 'februari' => 11, 'maret' => 5, 'april' => 2.5, 'mei' => 2.5, 'juni' => 0.56, 'juli' => 0.75, 'agustus' => 2.5, 'september' => 5.3, 'oktober' => 5.4, 'november' => 5, 'desember' => 0.14, 'jumlah' => 47.375],
            ['kode' => '71.08.04', 'komoditas' => 'Udang Windu', 'januari' => 0, 'februari' => 0, 'maret' => 0, 'april' => 0, 'mei' => 0, 'juni' => 0, 'juli' => 0, 'agustus' => 0, 'september' => 0, 'oktober' => 2.895, 'november' => 1.880, 'desember' => 0.13, 'jumlah' => 4.775],
            ['kode' => '71.08.04', 'komoditas' => 'Bandeng', 'januari' => 0, 'februari' => 0, 'maret' => 0, 'april' => 0.5, 'mei' => 0, 'juni' => 1.46, 'juli' => 0.78, 'agustus' => 2, 'september' => 0.83, 'oktober' => 2.895, 'november' => 3, 'desember' => 0, 'jumlah' => 11.465],
            ['kode' => '71.08.04', 'komoditas' => 'Nila', 'januari' => 0, 'februari' => 0, 'maret' => 0, 'april' => 0.15, 'mei' => 2, 'juni' => 4.034, 'juli' => 2, 'agustus' => 1.17, 'september' => 0.15, 'oktober' => 1.220, 'november' => 3.3, 'desember' => 0, 'jumlah' => 14.024],

            // ── KAIDIPANG (71.08.05) ──
            ['kode' => '71.08.05', 'komoditas' => 'Nila', 'januari' => 0, 'februari' => 0.25, 'maret' => 0.04, 'april' => 0.25, 'mei' => 0.125, 'juni' => 0.345, 'juli' => 0.115, 'agustus' => 0.05, 'september' => 0.245, 'oktober' => 0.25, 'november' => 0, 'desember' => 0, 'jumlah' => 1.67],

            // ── PINOGALUMAN (71.08.06) ──
            ['kode' => '71.08.06', 'komoditas' => 'Bandeng', 'januari' => 0, 'februari' => 0.5, 'maret' => 0.3, 'april' => 0.2, 'mei' => 0, 'juni' => 0, 'juli' => 0, 'agustus' => 0, 'september' => 0, 'oktober' => 0, 'november' => 0, 'desember' => 0, 'jumlah' => 1],
            ['kode' => '71.08.06', 'komoditas' => 'Nila Salin', 'januari' => 0, 'februari' => 0.2, 'maret' => 0, 'april' => 0.1, 'mei' => 0, 'juni' => 0, 'juli' => 0.04, 'agustus' => 0.2, 'september' => 0.25, 'oktober' => 0, 'november' => 0.06, 'desember' => 0.063, 'jumlah' => 0.913],
            ['kode' => '71.08.06', 'komoditas' => 'Gurame', 'januari' => 0, 'februari' => 0, 'maret' => 0, 'april' => 0, 'mei' => 0, 'juni' => 0, 'juli' => 0, 'agustus' => 0, 'september' => 0, 'oktober' => 0.01, 'november' => 0, 'desember' => 0, 'jumlah' => 0.01],
            ['kode' => '71.08.06', 'komoditas' => 'Kerapu', 'januari' => 0, 'februari' => 0, 'maret' => 0.05, 'april' => 0, 'mei' => 0.03, 'juni' => 0.053, 'juli' => 0, 'agustus' => 0, 'september' => 0, 'oktober' => 0, 'november' => 0, 'desember' => 0, 'jumlah' => 0.133],
        ];

        foreach ($data as $row) {
            $kecamatan = $kecamatanMap->get($row['kode']);

            if (!$kecamatan) {
                $this->command->warn("Kecamatan dengan kode {$row['kode']} tidak ditemukan, baris dilewati.");
                continue;
            }

            ProduksiBudidaya::create([
                'kecamatan_id' => $kecamatan->id,
                'komoditas'    => $row['komoditas'],
                'tahun'        => 2025,
                'januari'      => $row['januari'],
                'februari'     => $row['februari'],
                'maret'        => $row['maret'],
                'april'        => $row['april'],
                'mei'          => $row['mei'],
                'juni'         => $row['juni'],
                'juli'         => $row['juli'],
                'agustus'      => $row['agustus'],
                'september'    => $row['september'],
                'oktober'      => $row['oktober'],
                'november'     => $row['november'],
                'desember'     => $row['desember'],
                'jumlah'       => $row['jumlah'],
            ]);
        }
    }
}
