<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Data Produksi - Dinas Perikanan Bolmut</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#2563eb",
                        "primary-light": "#dbeafe",
                        secondary: "#8592a3",
                        danger: "#ff3e1d",
                        body: "#f5f5f9",
                        card: "#ffffff",
                        text: "#566a7f",
                        heading: "#697a8d",
                    },
                    fontFamily: {
                        sans: ["Public Sans", "sans-serif"]
                    },
                    boxShadow: {
                        card: "0 2px 6px 0 rgba(67,89,113,0.12)"
                    },
                },
            },
        };
    </script>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap");

        body {
            font-family: "Public Sans", sans-serif;
        }
    </style>
</head>

<body class="bg-body text-text" x-data="{ sidebarOpen: false, kecamatanMode: 'pilih', komoditasMode: 'pilih' }">
    <div class="flex h-screen overflow-hidden">

        {{-- Overlay mobile --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
            class="fixed inset-0 z-20 bg-slate-900 bg-opacity-50 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-card shadow-card transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0">
            <div class="flex items-center justify-center h-16 px-6 mt-2 mb-4">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-2 text-xl font-bold text-heading tracking-tight">
                    <span class="text-primary text-3xl"><i class="bx bx-water"></i></span>
                    <span>Perikanan<span class="text-primary">Bolmut</span></span>
                </a>
            </div>
            <nav class="px-4 space-y-1 overflow-y-auto h-[calc(100vh-180px)]">
                <p class="px-4 text-xs font-semibold text-secondary uppercase mb-2 mt-4">Utama</p>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 text-text hover:bg-primary-light hover:text-primary rounded-lg transition-colors">
                    <i class="bx bx-home-circle text-xl"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                <p class="px-4 text-xs font-semibold text-secondary uppercase mb-2 mt-6">Data</p>
                <a href="{{ route('admin.publikasi-data.index') }}"
                    class="flex items-center gap-3 px-4 py-3 bg-primary text-white rounded-lg shadow-md shadow-primary/30 transition-all">
                    <i class="bx bx-bar-chart-alt-2 text-xl"></i>
                    <span class="font-medium">Publikasi Data</span>
                </a>
                <a href="{{ route('announcements.index') }}"
                    class="flex items-center gap-3 px-4 py-3 text-text hover:bg-primary-light hover:text-primary rounded-lg transition-colors">
                    <i class="bx bxs-megaphone text-xl"></i>
                    <span class="font-medium">Pengumuman</span>
                </a>
            </nav>
        </aside>

        {{-- Main --}}
        <div class="flex flex-col flex-1 h-full overflow-hidden relative">

            {{-- Header --}}
            <header
                class="flex items-center justify-between h-16 px-6 bg-card/80 backdrop-blur-md m-4 rounded-xl shadow-card sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-heading lg:hidden focus:outline-none">
                        <i class="bx bx-menu text-2xl"></i>
                    </button>
                    <a href="{{ route('admin.publikasi-data.index') }}"
                        class="text-secondary hover:text-primary transition">
                        <i class="bx bx-arrow-back text-xl"></i>
                    </a>
                    <h5 class="text-heading font-semibold text-base">Tambah Data Produksi</h5>
                </div>
                <div class="relative group">
                    <div
                        class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center text-sm font-bold cursor-pointer">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div
                        class="absolute right-0 top-12 w-56 bg-card rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div
                            class="absolute -top-1.5 right-3 w-3 h-3 bg-card border-l border-t border-gray-100 rotate-45">
                        </div>
                        <div class="p-2">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-danger hover:bg-danger/10 text-sm transition">
                                    <i class="bx bx-log-out text-lg"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto px-6 pb-6">
                <form action="{{ route('admin.publikasi-data.store') }}" method="POST">
                    @csrf

                    <div class="bg-card rounded-xl shadow-card p-6 mb-4">
                        <h6 class="text-heading font-semibold mb-4">Informasi Umum</h6>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">

                            {{-- Kecamatan --}}
                            <div>
                                <div class="flex items-center justify-between mb-1" style="min-height: 28px;">
                                    <label class="text-sm font-medium text-heading">Kecamatan</label>
                                </div>
                                <select name="kecamatan_id"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none @error('kecamatan_id') border-red-400 @enderror">
                                    <option value="">-- Pilih Kecamatan --</option>
                                    @foreach ($kecamatanList as $kec)
                                        <option value="{{ $kec->id }}"
                                            {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                            {{ $kec->kode }} – {{ $kec->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kecamatan_id')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Komoditas --}}
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-sm font-medium text-heading">Komoditas</label>
                                    <div class="flex gap-1">
                                        <button type="button" @click="komoditasMode = 'pilih'"
                                            :class="komoditasMode === 'pilih' ? 'bg-primary text-white' :
                                                'bg-gray-100 text-text'"
                                            class="text-xs px-3 py-1 rounded-lg transition">Pilih</button>
                                        <button type="button" @click="komoditasMode = 'baru'"
                                            :class="komoditasMode === 'baru' ? 'bg-primary text-white' :
                                                'bg-gray-100 text-text'"
                                            class="text-xs px-3 py-1 rounded-lg transition">+ Baru</button>
                                    </div>
                                </div>
                                <div x-show="komoditasMode === 'pilih'">
                                    <select name="komoditas_pilih"
                                        onchange="document.getElementById('komoditas_hidden').value = this.value"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none @error('komoditas') border-red-400 @enderror">
                                        <option value="">-- Pilih Komoditas --</option>
                                        @foreach ($komoditasList as $kom)
                                            <option value="{{ $kom }}">{{ $kom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div x-show="komoditasMode === 'baru'">
                                    <input type="text" placeholder="Nama Komoditas Baru"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none"
                                        oninput="document.getElementById('komoditas_hidden').value = this.value" />
                                </div>
                                <input type="hidden" name="komoditas" id="komoditas_hidden"
                                    value="{{ old('komoditas') }}" />
                                @error('komoditas')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tahun --}}
                            <div>
                                <label class="block text-sm font-medium text-heading mb-1">Tahun</label>
                                <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}"
                                    min="2000" max="2099"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none @error('tahun') border-red-400 @enderror" />
                                @error('tahun')
                                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Data Per Bulan --}}
                    <div class="bg-card rounded-xl shadow-card p-6 mb-4">
                        <h6 class="text-heading font-semibold mb-4">Produksi Per Bulan <span
                                class="text-secondary text-xs font-normal">(dalam Ton)</span></h6>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                            @foreach (['januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'] as $bulan)
                                <div>
                                    <label
                                        class="block text-xs font-medium text-secondary mb-1 capitalize">{{ $bulan }}</label>
                                    <input type="number" name="{{ $bulan }}" value="{{ old($bulan, 0) }}"
                                        min="0" step="0.001"
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary focus:outline-none" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.publikasi-data.index') }}"
                            class="px-4 py-2 text-sm text-text border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-5 py-2 text-sm font-semibold bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                            <i class="bx bx-save mr-1"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script>
        function syncKecamatan(select) {
            const option = select.options[select.selectedIndex];
            document.getElementById('kecamatan_hidden').value = option.value;
            document.getElementById('kode_kecamatan_hidden').value = option.dataset.kode ?? '';
        }
    </script>
</body>

</html>
