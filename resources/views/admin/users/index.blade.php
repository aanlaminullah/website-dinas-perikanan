@extends('layouts.admin')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('header-actions')
    <a href="{{ route('admin.users.create') }}"
        class="inline-flex items-center gap-2 bg-primary text-white text-sm font-semibold px-4 py-2 rounded-lg hover:bg-primary/90 transition">
        <i class="bx bx-plus"></i> Tambah User
    </a>
@endsection

@section('content')
    @if (session('error'))
        <div class="mb-4 px-4 py-3 bg-red-50 text-red-700 border border-red-200 rounded-xl text-sm flex items-center gap-2">
            <i class="bx bx-error-circle text-lg"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-card rounded-xl shadow-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary uppercase">Nama</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary uppercase">Email</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary uppercase">NIP</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-secondary uppercase">Role</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-secondary uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-heading">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-secondary">{{ $user->email }}</td>
                            <td class="px-5 py-3 text-secondary font-mono text-xs">{{ $user->nip ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                    {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($user->role ?? 'user') }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="text-primary hover:bg-primary/10 p-1.5 rounded-lg transition">
                                        <i class="bx bx-edit text-lg"></i>
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-danger hover:bg-danger/10 p-1.5 rounded-lg transition">
                                                <i class="bx bx-trash text-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center text-secondary">
                                <i class="bx bx-user text-5xl block mb-3 opacity-30"></i>
                                <p class="text-sm italic">Belum ada user terdaftar.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
