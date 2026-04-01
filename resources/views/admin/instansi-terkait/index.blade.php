@extends('layouts.admin')

@section('title', 'Instansi Terkait')
@section('page-title', 'Instansi Terkait')

@section('header-actions')
    <a href="{{ route('admin.instansi-terkait.create') }}"
        class="inline-flex items-center gap-2 bg-primary text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 transition">
        <i class="bx bx-plus"></i> Tambah Instansi
    </a>
@endsection

@section('content')
    @if ($instansi->count() > 0)
        <p class="text-sm text-secondary mb-4 flex items-center gap-2">
            <i class="bx bx-info-circle"></i>
            Drag & drop kartu untuk mengubah urutan tampil.
        </p>
    @endif

    <div id="sortableGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        @forelse($instansi as $item)
            <div class="bg-card rounded-xl shadow-card p-4 flex flex-col items-center gap-3 cursor-grab active:cursor-grabbing select-none"
                data-id="{{ $item->id }}">
                {{-- Handle drag --}}
                <div class="w-full flex justify-center mb-1 text-gray-300 hover:text-gray-400 transition">
                    <i class="bx bx-grid-vertical text-xl"></i>
                </div>

                <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-gray-100 bg-gray-50">
                    <img src="{{ Storage::url($item->logo) }}" alt="{{ $item->nama }}"
                        class="w-full h-full object-cover" />
                </div>
                <div class="text-center">
                    <p class="text-sm font-semibold text-heading">{{ $item->nama }}</p>
                    @if ($item->url)
                        <a href="{{ $item->url }}" target="_blank"
                            class="text-xs text-primary hover:underline truncate block max-w-[120px]">
                            {{ parse_url($item->url, PHP_URL_HOST) }}
                        </a>
                    @endif
                    <span
                        class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block
                        {{ $item->aktif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $item->aktif ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.instansi-terkait.edit', $item) }}"
                        class="text-primary hover:bg-primary/10 p-1.5 rounded-lg transition">
                        <i class="bx bx-edit text-lg"></i>
                    </a>
                    <form method="POST" action="{{ route('admin.instansi-terkait.destroy', $item) }}"
                        onsubmit="return confirm('Hapus instansi ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-danger hover:bg-danger/10 p-1.5 rounded-lg transition">
                            <i class="bx bx-trash text-lg"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-5 py-16 text-center text-secondary">
                <i class="bx bx-buildings text-5xl block mb-3 opacity-30"></i>
                <p class="text-sm italic">Belum ada instansi terkait.</p>
            </div>
        @endforelse
    </div>

    {{-- Toast notif --}}
    <div id="toastSaved"
        class="fixed bottom-6 right-6 bg-green-600 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-lg opacity-0 transition-opacity duration-300 pointer-events-none flex items-center gap-2">
        <i class="bx bx-check-circle text-lg"></i> Urutan berhasil disimpan
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
    <script>
        const grid = document.getElementById('sortableGrid');
        const toast = document.getElementById('toastSaved');

        if (grid) {
            Sortable.create(grid, {
                animation: 150,
                ghostClass: 'opacity-40',
                onEnd: function() {
                    const ids = [...grid.querySelectorAll('[data-id]')].map(el => el.dataset.id);

                    fetch('{{ route('admin.instansi-terkait.reorder') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                ids
                            }),
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                toast.classList.remove('opacity-0');
                                toast.classList.add('opacity-100');
                                setTimeout(() => {
                                    toast.classList.remove('opacity-100');
                                    toast.classList.add('opacity-0');
                                }, 2500);
                            }
                        });
                }
            });
        }
    </script>
@endpush
