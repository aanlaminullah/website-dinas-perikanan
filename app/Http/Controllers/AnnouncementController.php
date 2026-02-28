<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        // Data dummy, nantinya bisa diambil dari database menggunakan model
        $announcements = [
            [
                'title' => 'Pendaftaran Kartu KUSUKA bagi Nelayan Kecil',
                'date' => '11 September 2025',
                'category' => 'Layanan',
                'description' => 'Pemerintah membuka pendaftaran kartu KUSUKA untuk memudahkan akses bantuan.'
            ],
            [
                'title' => 'Peringatan Gelombang Tinggi Perairan Utara',
                'date' => '08 Juli 2025',
                'category' => 'Keamanan',
                'description' => 'Dihimbau kepada nelayan untuk waspada terhadap potensi gelombang tinggi.'
            ],
            // Tambahkan data lainnya...
        ];

        return view('announcements.index', compact('announcements'));
    }
}
