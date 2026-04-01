<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\LensaKegiatan;
use App\Models\InstansiTerkait;

class LandingController extends Controller
{
    public function index()
    {
        $latestAnnouncements = Announcement::orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $lensaKegiatan = LensaKegiatan::where('aktif', true)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $instansiTerkait = InstansiTerkait::where('aktif', true)
            ->orderBy('urutan')
            ->get();

        return view('home', compact('latestAnnouncements', 'lensaKegiatan', 'instansiTerkait'));
    }
}
