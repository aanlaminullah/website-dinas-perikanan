<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pejabat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PejabatController extends Controller
{
    public function index()
    {
        $pejabat = Pejabat::orderBy('urutan')->get();
        return view('admin.pejabat.index', compact('pejabat'));
    }

    public function create()
    {
        return view('admin.pejabat.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'jabatan' => 'required|string|max:150',
            'nip'     => 'nullable|string|max:30',
            'urutan'  => 'nullable|integer',
            'foto'    => 'nullable|image|max:2048',
        ]);

        $data = $request->only('nama', 'jabatan', 'nip', 'urutan');
        $data['aktif'] = $request->boolean('aktif', true);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pejabat', 'public');
        }

        Pejabat::create($data);

        return redirect()->route('admin.pejabat.index')
            ->with('success', 'Data pejabat berhasil ditambahkan.');
    }

    public function edit(Pejabat $pejabat)
    {
        return view('admin.pejabat.edit', compact('pejabat'));
    }

    public function update(Request $request, Pejabat $pejabat)
    {
        $request->validate([
            'nama'    => 'required|string|max:100',
            'jabatan' => 'required|string|max:150',
            'nip'     => 'nullable|string|max:30',
            'urutan'  => 'nullable|integer',
            'foto'    => 'nullable|image|max:2048',
        ]);

        $data = $request->only('nama', 'jabatan', 'nip', 'urutan');
        $data['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('foto')) {
            if ($pejabat->foto) {
                Storage::disk('public')->delete($pejabat->foto);
            }
            $data['foto'] = $request->file('foto')->store('pejabat', 'public');
        }

        $pejabat->update($data);

        return redirect()->route('admin.pejabat.index')
            ->with('success', 'Data pejabat berhasil diperbarui.');
    }

    public function destroy(Pejabat $pejabat)
    {
        if ($pejabat->foto) {
            Storage::disk('public')->delete($pejabat->foto);
        }
        $pejabat->delete();

        return redirect()->route('admin.pejabat.index')
            ->with('success', 'Data pejabat berhasil dihapus.');
    }
}
