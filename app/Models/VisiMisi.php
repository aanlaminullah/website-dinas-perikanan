<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisiMisi extends Model
{
    protected $table = 'visi_misi';

    protected $fillable = ['visi'];

    public function misi()
    {
        return $this->hasMany(Misi::class)->orderBy('urutan');
    }
}
