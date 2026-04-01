@extends('layouts.admin')

@section('title', 'Tambah Dokumen')
@section('page-title', 'Tambah Dokumen')
@section('back', route('admin.publikasi-dokumen.index'))

@section('content')
    <div class="max-w-xl bg-card rounded-xl shadow-card p-6">
        <form method="POST" action="{{ route('admin.publikasi-dokumen.store') }}" enctype="multipart/form-data"
            class="space-y-5">
            @csrf
            @include('admin.publikasi-dokumen._form')
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.publikasi-dokumen.index') }}"
                    class="px-5 py-2 rounded-lg text-sm font-semibold text-secondary hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
