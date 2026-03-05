@extends('layouts.admin')

@section('title', 'Tambah Pejabat')
@section('page-title', 'Tambah Pejabat')
@section('back', route('admin.pejabat.index'))

@section('content')
    <div class="max-w-xl bg-card rounded-xl shadow-card p-6">
        <form method="POST" action="{{ route('admin.pejabat.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @include('admin.pejabat._form')
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.pejabat.index') }}"
                    class="px-5 py-2 rounded-lg text-sm font-semibold text-secondary hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
