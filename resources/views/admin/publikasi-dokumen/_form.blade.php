<div>
    <label class="block text-sm font-medium text-heading mb-1">Judul <span class="text-danger">*</span></label>
    <input type="text" name="judul" value="{{ old('judul', $publikasiDokumen->judul ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        required />
    @error('judul')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">Deskripsi</label>
    <textarea name="deskripsi" rows="3"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary resize-none">{{ old('deskripsi', $publikasiDokumen->deskripsi ?? '') }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-heading mb-1">Kategori</label>
        <input type="text" name="kategori" value="{{ old('kategori', $publikasiDokumen->kategori ?? '') }}"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            placeholder="Contoh: Laporan, SK, Peraturan" />
    </div>
    <div>
        <label class="block text-sm font-medium text-heading mb-1">Tahun <span class="text-danger">*</span></label>
        <input type="number" name="tahun" value="{{ old('tahun', $publikasiDokumen->tahun ?? date('Y')) }}"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            required />
    </div>
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">Tanggal <span class="text-danger">*</span></label>
    <input type="date" name="tanggal"
        value="{{ old('tanggal', isset($publikasiDokumen) ? $publikasiDokumen->tanggal->format('Y-m-d') : now()->format('Y-m-d')) }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        required />
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">
        File <span class="text-danger">*</span>
    </label>
    @if (isset($publikasiDokumen) && $publikasiDokumen->file)
        <div class="mb-2 flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
            <i class="bx {{ $publikasiDokumen->ikonFile() }} text-xl {{ $publikasiDokumen->warnaIkon() }}"></i>
            <div>
                <p class="text-xs font-medium text-heading">{{ $publikasiDokumen->judul }}</p>
                <p class="text-xs text-secondary">{{ $publikasiDokumen->ukuranFormat() }}</p>
            </div>
        </div>
        <p class="text-xs text-secondary mb-2">Upload file baru untuk mengganti.</p>
    @endif
    <input type="file" name="file" {{ isset($publikasiDokumen) ? '' : 'required' }}
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" />
    <p class="text-xs text-secondary mt-1">Maks. 20MB. Format: PDF, Word, Excel, PPT, dan lainnya.</p>
    @error('file')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="aktif" id="aktif" value="1"
        {{ old('aktif', $publikasiDokumen->aktif ?? true) ? 'checked' : '' }}
        class="rounded border-gray-300 text-primary" />
    <label for="aktif" class="text-sm font-medium text-heading">Tampilkan di halaman publik</label>
</div>
