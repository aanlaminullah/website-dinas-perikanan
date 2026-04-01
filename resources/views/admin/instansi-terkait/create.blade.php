@extends('layouts.admin')

@section('title', 'Tambah Instansi Terkait')
@section('page-title', 'Tambah Instansi Terkait')
@section('back', route('admin.instansi-terkait.index'))

@section('content')
    <div class="max-w-lg bg-card rounded-xl shadow-card p-6">
        <form method="POST" action="{{ route('admin.instansi-terkait.store') }}" class="space-y-5" id="formInstansi">
            @csrf
            @include('admin.instansi-terkait._form')
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition">
                    Simpan
                </button>
                <a href="{{ route('admin.instansi-terkait.index') }}"
                    class="px-5 py-2 rounded-lg text-sm font-semibold text-secondary hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
