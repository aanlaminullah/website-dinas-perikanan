<?php

namespace App\Http\Controllers;

use App\Models\VisiMisi;

class VisiMisiController extends Controller
{
    public function index()
    {
        $visiMisi = VisiMisi::with('misi')->latest()->first();

        return view('visi-misi.index', compact('visiMisi'));
    }
}
