@extends('layouts.admin')

@section('title', 'Edit Instansi Terkait')
@section('page-title', 'Edit Instansi Terkait')
@section('back', route('admin.instansi-terkait.index'))

@section('content')
    <div class="max-w-lg bg-card rounded-xl shadow-card p-6">
        <form method="POST" action="{{ route('admin.instansi-terkait.update', $instansiTerkait) }}" class="space-y-5"
            id="formInstansi">
            @csrf @method('PUT')
            @include('admin.instansi-terkait._form')
            <div class="flex gap-3 pt-2">
                <button type="submit"
                    class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-primary/90 transition">
                    Perbarui
                </button>
                <a href="{{ route('admin.instansi-terkait.index') }}"
                    class="px-5 py-2 rounded-lg text-sm font-semibold text-secondary hover:bg-gray-100 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
