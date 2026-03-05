@extends('layouts.app')

@section('title', 'Visi & Misi - Dinas Perikanan Bolmut')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-10 text-center">
                <h2 class="text-3xl font-heading font-bold text-gray-900 mb-2">Visi & Misi</h2>
                <p class="text-gray-600">Dinas Perikanan Kabupaten Bolaang Mongondow Utara</p>
                <div class="w-20 h-1.5 bg-fish-blue mx-auto mt-4 rounded-full"></div>
            </div>

            @if (!$visiMisi)
                <div class="text-center py-16 text-gray-400">
                    <p class="text-sm italic">Belum ada data visi & misi.</p>
                </div>
            @else
                {{-- Visi --}}
                <div
                    class="bg-gradient-to-br from-fish-dark to-fish-blue rounded-2xl p-8 mb-8 text-white shadow-lg relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-32 h-32 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2">
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-heading font-bold tracking-wide uppercase">Visi</h3>
                        </div>
                        <p class="text-lg leading-relaxed text-blue-50 font-medium">
                            "{{ $visiMisi->visi }}"
                        </p>
                    </div>
                </div>

                {{-- Misi --}}
                @if ($visiMisi->misi->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 bg-fish-blue/10 text-fish-blue rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-heading font-bold text-gray-900 uppercase tracking-wide">Misi</h3>
                        </div>
                        <ol class="space-y-4">
                            @foreach ($visiMisi->misi as $item)
                                <li class="flex items-start gap-4">
                                    <span
                                        class="shrink-0 w-8 h-8 bg-fish-blue text-white rounded-full flex items-center justify-center text-sm font-bold">
                                        {{ $loop->iteration }}
                                    </span>
                                    <p class="text-gray-700 leading-relaxed pt-1">{{ $item->isi }}</p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endif
            @endif

        </div>
    </div>
@endsection
