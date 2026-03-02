<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $table = 'kecamatan';

    protected $fillable = ['kode', 'nama'];

    public function produksiBudidaya()
    {
        return $this->hasMany(ProduksiBudidaya::class);
    }
}
