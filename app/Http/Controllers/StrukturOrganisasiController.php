<?php

namespace App\Http\Controllers;

use App\Models\Pejabat;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        $pejabat = Pejabat::where('aktif', true)
            ->orderBy('urutan')
            ->get();

        return view('struktur-organisasi.index', compact('pejabat'));
    }
}
