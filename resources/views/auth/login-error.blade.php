@extends('layouts.app')

@section('title', 'Login Gagal - Dinas Perikanan Bolmut')

@section('content')
<div class="min-h-[70vh] flex items-center justify-center px-4 py-12 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 via-gray-100 to-blue-50/30">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-xl border border-gray-100/80 transition-all duration-300 hover:shadow-2xl">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 text-red-600 mb-6 animate-pulse">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h2 class="text-3xl font-heading font-bold text-gray-900 tracking-tight">Login Gagal</h2>
            <p class="mt-2 text-sm text-gray-500">Terjadi kendala saat melakukan autentikasi SSO</p>
        </div>

        <div class="bg-red-50/80 border-l-4 border-red-500 p-4 rounded-r-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-semibold text-red-800">Detail Kesalahan:</h3>
                    <div class="mt-2 text-sm text-red-700 space-y-1">
                        {{-- Error dari query param (paling reliable) --}}
                        @if (!empty($ssoError))
                            <p>{{ $ssoError }}</p>
                        @elseif ($errors->any())
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        @else
                            <p>Sesi autentikasi telah kedaluwarsa atau terjadi masalah koneksi.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <a href="{{ route('sso.redirect') }}" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-fish-blue hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-fish-blue transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                Coba Login Kembali via SSO
            </a>

            <a href="{{ route('landing') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-xl shadow-sm text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
