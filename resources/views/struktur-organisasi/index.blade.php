@extends('layouts.app')

@section('title', 'Struktur Organisasi - Dinas Perikanan Bolmut')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 text-center">
                <h2 class="text-3xl font-heading font-bold text-gray-900 mb-2">Struktur Organisasi</h2>
                <p class="text-gray-600">Dinas Perikanan Kabupaten Bolaang Mongondow Utara</p>
                <div class="w-20 h-1.5 bg-fish-blue mx-auto mt-4 rounded-full"></div>
            </div>

            @if ($pejabat->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-sm italic">Belum ada data pejabat.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($pejabat as $item)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-center hover:shadow-md transition group">
                            <div class="w-full aspect-square overflow-hidden bg-gradient-to-br from-fish-dark to-fish-blue">
                                @if ($item->foto)
                                    <img src="{{ Storage::url($item->foto) }}" alt="{{ $item->nama }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white/40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1">{{ $item->nama }}</h3>
                                <p class="text-xs text-fish-blue font-semibold mb-1">{{ $item->jabatan }}</p>
                                @if ($item->nip)
                                    <p class="text-xs text-gray-400">NIP. {{ $item->nip }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection
