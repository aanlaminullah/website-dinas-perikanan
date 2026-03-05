@extends('layouts.admin')

@section('title', 'Visi & Misi')
@section('page-title', 'Visi & Misi')

@section('header-actions')
    <a href="{{ route('admin.visi-misi.edit') }}"
        class="inline-flex items-center gap-2 bg-primary text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 transition">
        <i class="bx bx-edit"></i> Edit Visi & Misi
    </a>
@endsection

@section('content')
    @if (!$visiMisi)
        <div class="bg-card rounded-xl shadow-card p-10 text-center text-secondary">
            <i class="bx bx-notepad text-5xl mb-3 block opacity-30"></i>
            <p class="text-sm italic mb-4">Belum ada data visi & misi.</p>
            <a href="{{ route('admin.visi-misi.edit') }}"
                class="inline-flex items-center gap-2 bg-primary text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 transition">
                <i class="bx bx-plus"></i> Buat Sekarang
            </a>
        </div>
    @else
        <div class="space-y-6">
            <div class="bg-card rounded-xl shadow-card p-6">
                <h5 class="text-heading font-bold text-lg mb-3 flex items-center gap-2">
                    <i class="bx bx-show text-primary"></i> Visi
                </h5>
                <p class="text-text leading-relaxed bg-gray-50 rounded-lg p-4 border border-gray-100">
                    {{ $visiMisi->visi }}
                </p>
            </div>

            <div class="bg-card rounded-xl shadow-card p-6">
                <h5 class="text-heading font-bold text-lg mb-4 flex items-center gap-2">
                    <i class="bx bx-list-check text-primary"></i> Misi
                </h5>
                @if ($visiMisi->misi->count() > 0)
                    <ol class="space-y-3">
                        @foreach ($visiMisi->misi as $item)
                            <li class="flex items-start gap-3 bg-gray-50 rounded-lg p-3 border border-gray-100">
                                <span
                                    class="shrink-0 w-7 h-7 bg-primary text-white rounded-full flex items-center justify-center text-xs font-bold">
                                    {{ $loop->iteration }}
                                </span>
                                <p class="text-text text-sm leading-relaxed pt-0.5">{{ $item->isi }}</p>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="text-secondary text-sm italic">Belum ada poin misi.</p>
                @endif
            </div>
        </div>
    @endif
@endsection
