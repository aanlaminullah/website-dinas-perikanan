<div>
    <label class="block text-sm font-medium text-heading mb-1">Nama <span class="text-danger">*</span></label>
    <input type="text" name="nama" value="{{ old('nama', $pejabat->nama ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        required />
    @error('nama')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">Jabatan <span class="text-danger">*</span></label>
    <input type="text" name="jabatan" value="{{ old('jabatan', $pejabat->jabatan ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        required />
    @error('jabatan')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">NIP</label>
    <input type="text" name="nip" value="{{ old('nip', $pejabat->nip ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" />
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">Urutan Tampil</label>
    <input type="number" name="urutan" value="{{ old('urutan', $pejabat->urutan ?? 0) }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" />
    <p class="text-xs text-secondary mt-1">Angka lebih kecil tampil lebih dulu.</p>
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">Foto</label>
    @if (isset($pejabat) && $pejabat->foto)
        <div class="mb-2">
            <img src="{{ Storage::url($pejabat->foto) }}" class="w-20 h-20 rounded-full object-cover border" />
            <p class="text-xs text-secondary mt-1">Foto saat ini. Upload baru untuk mengganti.</p>
        </div>
    @endif
    <input type="file" name="foto" accept="image/*"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" />
    @error('foto')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="aktif" id="aktif" value="1"
        {{ old('aktif', $pejabat->aktif ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-primary" />
    <label for="aktif" class="text-sm font-medium text-heading">Tampilkan di halaman publik</label>
</div>
