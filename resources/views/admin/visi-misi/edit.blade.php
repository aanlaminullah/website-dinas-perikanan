@extends('layouts.admin')

@section('title', 'Edit Visi & Misi')
@section('page-title', 'Edit Visi & Misi')
@section('back', route('admin.visi-misi.index'))

@section('content')
    <div class="max-w-2xl bg-card rounded-xl shadow-card p-6">
        <form method="POST" action="{{ route('admin.visi-misi.update') }}" class="space-y-6" id="formVisiMisi">
            @csrf @method('PUT')

            {{-- Visi --}}
            <div>
                <label class="block text-sm font-medium text-heading mb-1">
                    Visi <span class="text-danger">*</span>
                </label>
                <textarea name="visi" rows="4" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                    placeholder="Tuliskan visi dinas perikanan...">{{ old('visi', $visiMisi->visi ?? '') }}</textarea>
                @error('visi')
                    <p class="text-danger text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Misi --}}
            <div>
                <label class="block text-sm font-medium text-heading mb-3">Poin-poin Misi</label>
                <div id="misiContainer" class="space-y-3">
                    @if ($visiMisi && $visiMisi->misi->count() > 0)
                        @foreach ($visiMisi->misi as $i => $item)
                            <div class="flex items-start gap-2 misi-item">
                                <span
                                    class="shrink-0 w-7 h-7 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xs font-bold mt-2 nomor-misi">
                                    {{ $i + 1 }}
                                </span>
                                <textarea name="misi[]" rows="2"
                                    class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                    placeholder="Tuliskan poin misi...">{{ old("misi.$i", $item->isi) }}</textarea>
                                <button type="button" onclick="hapusMisi(this)"
                                    class="shrink-0 text-danger hover:bg-danger/10 p-1.5 rounded-lg transition mt-1">
                                    <i class="bx bx-trash text-lg"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="flex items-start gap-2 misi-item">
                            <span
                                class="shrink-0 w-7 h-7 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xs font-bold mt-2 nomor-misi">1</span>
                            <textarea name="misi[]" rows="2"
                                class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                                placeholder="Tuliskan poin misi..."></textarea>
                            <button type="button" onclick="hapusMisi(this)"
                                class="shrink-0 text-danger hover:bg-danger/10 p-1.5 rounded-lg transition mt-1">
                                <i class="bx bx-trash text-lg"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <button type="button" onclick="tambahMisi()"
                    class="mt-3 inline-flex items-center gap-2 text-primary hover:bg-primary/10 px-3 py-2 rounded-lg text-sm font-medium transition">
                    <i class="bx bx-plus"></i> Tambah Poin Misi
                </button>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.visi-misi.index') }}"
                    class="px-5 py-2 rounded-lg text-sm font-semibold text-secondary hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function tambahMisi() {
            const container = document.getElementById('misiContainer');
            const items = container.querySelectorAll('.misi-item');
            const nomor = items.length + 1;

            const div = document.createElement('div');
            div.className = 'flex items-start gap-2 misi-item';
            div.innerHTML = `
            <span class="shrink-0 w-7 h-7 bg-primary/10 text-primary rounded-full flex items-center justify-center text-xs font-bold mt-2 nomor-misi">${nomor}</span>
            <textarea name="misi[]" rows="2"
                class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                placeholder="Tuliskan poin misi..."></textarea>
            <button type="button" onclick="hapusMisi(this)"
                class="shrink-0 text-danger hover:bg-danger/10 p-1.5 rounded-lg transition mt-1">
                <i class="bx bx-trash text-lg"></i>
            </button>
        `;
            container.appendChild(div);
        }

        function hapusMisi(btn) {
            const container = document.getElementById('misiContainer');
            const items = container.querySelectorAll('.misi-item');
            if (items.length <= 1) return;
            btn.closest('.misi-item').remove();
            // Update nomor urutan
            container.querySelectorAll('.nomor-misi').forEach((el, i) => {
                el.textContent = i + 1;
            });
        }
    </script>
@endpush
