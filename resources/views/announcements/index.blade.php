@extends('layouts.app')

@section('title', 'Daftar Pengumuman - Dinas Perikanan Bolmut')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <h2 class="text-3xl font-heading font-bold text-gray-900 mb-2">Pengumuman Terkini</h2>
                <p class="text-gray-600">Informasi terbaru seputar layanan dan kebijakan Dinas Perikanan Bolmut</p>
                <div class="w-20 h-1.5 bg-fish-blue mx-auto mt-4 rounded-full"></div>
            </div>

            <div class="flex flex-col gap-3">
                @foreach ($announcements as $item)
                    <a href="{{ route('announcements.show', $item->id) }}"
                        class="flex items-center gap-4 bg-white rounded-xl shadow-sm border border-gray-100 px-5 py-4 hover:shadow-md hover:border-fish-blue/30 transition group">

                        {{-- Icon Kategori --}}
                        <div
                            class="shrink-0 w-11 h-11 rounded-lg bg-blue-50 text-fish-blue flex items-center justify-center group-hover:bg-fish-blue group-hover:text-white transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>

                        {{-- Konten --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-fish-accent uppercase tracking-wider">
                                    {{ $item->category }}
                                </span>
                                <span class="text-gray-300">•</span>
                                <span class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}
                                </span>
                                @if ($item->attachment)
                                    <span class="text-xs text-gray-400 flex items-center gap-1 ml-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                        </svg>
                                        Lampiran
                                    </span>
                                @endif
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 group-hover:text-fish-blue transition truncate">
                                {{ $item->title }}
                            </h3>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">
                                {{ $item->description }}
                            </p>
                        </div>

                        {{-- Panah --}}
                        <div class="shrink-0 text-gray-300 group-hover:text-fish-blue transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
