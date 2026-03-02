<?php

namespace Database\Seeders;

use App\Models\Kecamatan;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => '71.08.01', 'nama' => 'Sangkub'],
            ['kode' => '71.08.02', 'nama' => 'Bintauna'],
            ['kode' => '71.08.03', 'nama' => 'Bolangitang Timur'],
            ['kode' => '71.08.04', 'nama' => 'Bolangitang Barat'],
            ['kode' => '71.08.05', 'nama' => 'Kaidipang'],
            ['kode' => '71.08.06', 'nama' => 'Pinogaluman'],
        ];

        foreach ($data as $item) {
            Kecamatan::updateOrCreate(['kode' => $item['kode']], $item);
        }
    }
}
