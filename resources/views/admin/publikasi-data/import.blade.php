<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Import Data - Dinas Perikanan Bolmut</title>
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

<body class="bg-body text-text" x-data="{ sidebarOpen: false, fileName: '', dragging: false }">
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
                    <h5 class="text-heading font-semibold text-base">Import Data Excel</h5>
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

                {{-- Error import --}}
                @if (session('import_errors'))
                    <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4">
                        <p class="text-sm font-semibold text-red-600 mb-2 flex items-center gap-2">
                            <i class="bx bx-error-circle text-lg"></i> Terdapat kesalahan pada file:
                        </p>
                        <ul class="list-disc list-inside text-xs text-red-500 space-y-1">
                            @foreach (session('import_errors') as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Form Upload --}}
                    <div class="lg:col-span-2 bg-card rounded-xl shadow-card p-6">
                        <h6 class="text-heading font-semibold mb-1">Upload File</h6>
                        <p class="text-secondary text-sm mb-5">Format yang didukung: <strong>.xlsx, .xls, .csv</strong>.
                            Maksimal 5MB.</p>

                        <form action="{{ route('admin.publikasi-data.import.process') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            {{-- Drag & Drop Area --}}
                            <div class="border-2 border-dashed rounded-xl p-8 text-center transition cursor-pointer"
                                :class="dragging ? 'border-primary bg-primary-light' :
                                    'border-gray-200 hover:border-primary hover:bg-gray-50'"
                                @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                                @drop.prevent="
                                    dragging = false;
                                    const f = $event.dataTransfer.files[0];
                                    if (f) {
                                        fileName = f.name;
                                        $refs.fileInput.files = $event.dataTransfer.files;
                                    }
                                "
                                @click="$refs.fileInput.click()">
                                <i class="bx bx-cloud-upload text-5xl mb-3"
                                    :class="dragging ? 'text-primary' : 'text-gray-300'"></i>
                                <p class="text-sm text-heading font-medium"
                                    x-text="fileName || 'Klik atau drag & drop file di sini'"></p>
                                <p class="text-xs text-secondary mt-1" x-show="!fileName">xlsx, xls, csv — maks. 5MB</p>
                            </div>

                            <input type="file" name="file" accept=".xlsx,.xls,.csv" x-ref="fileInput"
                                class="hidden" @change="fileName = $event.target.files[0]?.name || ''" />

                            @error('file')
                                <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                            @enderror

                            <div class="flex items-center justify-end gap-3 mt-5">
                                <a href="{{ route('admin.publikasi-data.index') }}"
                                    class="px-4 py-2 text-sm text-text border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                                    Batal
                                </a>
                                <button type="submit"
                                    class="px-5 py-2 text-sm font-semibold bg-primary text-white rounded-lg hover:bg-primary/90 transition flex items-center gap-2">
                                    <i class="bx bx-upload"></i> Import Sekarang
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Panduan --}}
                    <div class="bg-card rounded-xl shadow-card p-6 h-fit">
                        <h6 class="text-heading font-semibold mb-3">Panduan Import</h6>
                        <ol class="text-sm text-text space-y-3 list-decimal list-inside">
                            <li>Download template Excel di bawah.</li>
                            <li>Isi data sesuai kolom yang tersedia. Jangan mengubah nama kolom header.</li>
                            <li>Simpan file dalam format <strong>.xlsx</strong> atau <strong>.csv</strong>.</li>
                            <li>Upload file melalui form di samping.</li>
                        </ol>

                        <div class="mt-5 pt-4 border-t border-gray-100">
                            <p class="text-xs text-secondary font-medium mb-2 uppercase tracking-wide">Kolom yang
                                diperlukan:</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach (['kode_kecamatan', 'kecamatan', 'komoditas', 'tahun', 'januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 'agustus', 'september', 'oktober', 'november', 'desember'] as $col)
                                    <span
                                        class="text-xs bg-primary-light text-primary px-2 py-0.5 rounded font-mono">{{ $col }}</span>
                                @endforeach
                            </div>
                        </div>

                        <a href="{{ route('admin.publikasi-data.template') }}"
                            class="mt-5 flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-semibold border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition">
                            <i class="bx bx-download text-lg"></i> Download Template
                        </a>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>

</html>
