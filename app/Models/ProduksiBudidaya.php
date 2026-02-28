<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProduksiBudidaya extends Model
{
    protected $table = 'produksi_budidaya';

    protected $fillable = [
        'kode_kecamatan',
        'kecamatan',
        'komoditas',
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
        'desember',
        'jumlah',
        'tahun',
    ];

    protected $casts = [
        'januari'   => 'float',
        'februari'  => 'float',
        'maret'     => 'float',
        'april'     => 'float',
        'mei'       => 'float',
        'juni'      => 'float',
        'juli'      => 'float',
        'agustus'   => 'float',
        'september' => 'float',
        'oktober'   => 'float',
        'november'  => 'float',
        'desember'  => 'float',
        'jumlah'    => 'float',
    ];
}
