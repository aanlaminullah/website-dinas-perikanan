<div>
    <label class="block text-sm font-medium text-heading mb-1">Nama Instansi <span class="text-danger">*</span></label>
    <input type="text" name="nama" value="{{ old('nama', $instansiTerkait->nama ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        placeholder="Contoh: Kementerian Kelautan dan Perikanan" required />
    @error('nama')
        <p class="text-danger text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<div>
    <label class="block text-sm font-medium text-heading mb-1">URL Website</label>
    <input type="url" name="url" value="{{ old('url', $instansiTerkait->url ?? '') }}"
        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        placeholder="https://example.go.id" />
</div>

{{-- Upload & Crop Logo --}}
<div>
    <label class="block text-sm font-medium text-heading mb-2">Logo <span class="text-danger">*</span></label>

    {{-- Preview bulat hasil crop --}}
    <div class="flex flex-col items-center gap-3">
        <div
            class="w-28 h-28 rounded-full overflow-hidden border-2 border-gray-200 bg-gray-50 flex items-center justify-center shrink-0">
            @if (isset($instansiTerkait) && $instansiTerkait->logo)
                <img id="previewFinal" src="{{ Storage::url($instansiTerkait->logo) }}"
                    class="w-full h-full object-cover" />
            @else
                <img id="previewFinal" src="" class="w-full h-full object-cover hidden" />
                <div id="previewPlaceholder" class="flex flex-col items-center text-gray-300">
                    <i class="bx bx-image-add text-4xl"></i>
                    <span class="text-xs mt-1">Belum ada logo</span>
                </div>
            @endif
        </div>

        <label for="logoInput"
            class="cursor-pointer inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg transition">
            <i class="bx bx-upload"></i> Pilih Gambar
        </label>
        <input type="file" id="logoInput" accept="image/*" class="hidden" />
        <p class="text-xs text-secondary text-center">Setelah memilih gambar, atur posisi crop sesuai keinginan.</p>
    </div>

    {{-- Modal Cropper --}}
    <div id="cropModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 px-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <h3 class="text-base font-bold text-heading mb-4">Atur Posisi Logo</h3>

            {{-- Area cropper --}}
            <div class="relative w-full" style="height: 300px; background:#000; border-radius:12px; overflow:hidden;">
                <img id="cropperImg" src="" class="block max-w-full" />
            </div>

            {{-- Preview hasil crop --}}
            <div class="flex items-center gap-4 mt-4">
                <div class="shrink-0">
                    <p class="text-xs text-secondary mb-1 text-center">Preview</p>
                    <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-200">
                        <div id="cropperPreview" class="w-full h-full overflow-hidden"></div>
                    </div>
                </div>
                <p class="text-xs text-secondary">Drag untuk menggeser, scroll/pinch untuk zoom. Hasil akan dipotong
                    berbentuk lingkaran.</p>
            </div>

            <div class="flex gap-3 mt-5">
                <button type="button" id="btnCropSave"
                    class="flex-1 bg-primary text-white py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition">
                    Gunakan Gambar Ini
                </button>
                <button type="button" id="btnCropCancel"
                    class="px-4 py-2 rounded-lg text-sm font-semibold text-secondary hover:bg-gray-100 transition">
                    Batal
                </button>
            </div>
        </div>
    </div>

    <input type="hidden" name="logo" id="logoBase64" value="" />
    @error('logo')
        <p class="text-danger text-xs mt-1 mt-2">{{ $message }}</p>
    @enderror
</div>

<div class="flex items-center gap-2">
    <input type="checkbox" name="aktif" id="aktif" value="1"
        {{ old('aktif', $instansiTerkait->aktif ?? true) ? 'checked' : '' }}
        class="rounded border-gray-300 text-primary" />
    <label for="aktif" class="text-sm font-medium text-heading">Tampilkan di halaman utama</label>
</div>

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script>
        const logoInput = document.getElementById('logoInput');
        const cropModal = document.getElementById('cropModal');
        const cropperImg = document.getElementById('cropperImg');
        const btnCropSave = document.getElementById('btnCropSave');
        const btnCropCancel = document.getElementById('btnCropCancel');
        const previewFinal = document.getElementById('previewFinal');
        const previewHolder = document.getElementById('previewPlaceholder');
        const logoBase64 = document.getElementById('logoBase64');

        let cropper = null;

        logoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                // Set gambar ke cropper
                cropperImg.src = e.target.result;

                // Tampilkan modal
                cropModal.classList.remove('hidden');
                cropModal.classList.add('flex');

                // Destroy cropper lama jika ada
                if (cropper) cropper.destroy();

                // Init cropper
                cropper = new Cropper(cropperImg, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    restore: false,
                    guides: false,
                    center: false,
                    highlight: false,
                    cropBoxMovable: false,
                    cropBoxResizable: false,
                    toggleDragModeOnDblclick: false,
                    preview: '#cropperPreview',
                });
            };
            reader.readAsDataURL(file);
        });

        btnCropCancel.addEventListener('click', function() {
            cropModal.classList.add('hidden');
            cropModal.classList.remove('flex');
            if (cropper) cropper.destroy();
            logoInput.value = '';
        });

        btnCropSave.addEventListener('click', function() {
            if (!cropper) return;

            // Export canvas 200x200 dengan crop lingkaran
            const canvas = cropper.getCroppedCanvas({
                width: 200,
                height: 200
            });

            // Buat canvas baru dengan clip lingkaran
            const circleCanvas = document.createElement('canvas');
            circleCanvas.width = 200;
            circleCanvas.height = 200;
            const ctx = circleCanvas.getContext('2d');
            ctx.beginPath();
            ctx.arc(100, 100, 100, 0, Math.PI * 2);
            ctx.closePath();
            ctx.clip();
            ctx.drawImage(canvas, 0, 0, 200, 200);

            const base64 = circleCanvas.toDataURL('image/jpeg', 0.8);

            // Set preview
            previewFinal.src = base64;
            previewFinal.classList.remove('hidden');
            if (previewHolder) previewHolder.classList.add('hidden');

            // Set hidden input
            logoBase64.value = base64;

            // Tutup modal
            cropModal.classList.add('hidden');
            cropModal.classList.remove('flex');
            cropper.destroy();
        });

        // Validasi form
        document.getElementById('formInstansi').addEventListener('submit', function(e) {
            const hasExisting = '{{ isset($instansiTerkait) && $instansiTerkait->logo ? '1' : '' }}';
            if (!logoBase64.value && !hasExisting) {
                e.preventDefault();
                alert('Silakan upload dan crop logo instansi terlebih dahulu.');
            }
        });
    </script>
@endpush
