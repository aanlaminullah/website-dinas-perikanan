<nav class="sticky top-0 z-50 glass-header transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo-bolmut.png') }}" alt="Logo Bolmut" class="h-10 w-auto">
                <div class="leading-tight">
                    <h1 class="font-heading font-bold text-fish-blue text-lg">DINAS PERIKANAN</h1>
                    <p class="text-xs text-gray-500 font-medium tracking-wide">KAB. BOLAANG MONGONDOW UTARA</p>
                </div>
            </div>

            {{-- Menu Desktop --}}
            <div class="hidden lg:flex items-center space-x-8 text-sm font-medium text-gray-600">
                <a href="{{ url('/') }}"
                    class="{{ request()->is('/') ? 'text-fish-blue font-semibold' : 'hover:text-fish-blue transition' }}">
                    Beranda
                </a>

                <div class="relative group cursor-pointer">
                    <span class="hover:text-fish-blue flex items-center gap-1 transition">
                        Profil
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>

                <a href="{{ url('publikasi-data') }}"
                    class="{{ Request::is('publikasi-data') ? 'text-fish-blue font-semibold' : 'hover:text-fish-blue' }} transition">
                    Publikasi Data
                </a>
                <a href="#" class="hover:text-fish-blue transition">Berita</a>
                <a href="{{ route('announcements.index') }}"
                    class="{{ Request::is('pengumuman*') ? 'text-fish-blue font-semibold' : 'hover:text-fish-blue' }} transition">
                    Pengumuman
                </a>

                <div class="flex items-center gap-3 ml-4">
                    <button class="p-2 text-gray-400 hover:text-fish-blue transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    <a href="{{ route('login') }}"
                        class="bg-fish-blue text-white px-5 py-2 rounded-lg hover:bg-sky-700 transition shadow-md shadow-blue-200 font-medium">
                        Login Admin
                    </a>
                </div>
            </div>

            {{-- Tombol Hamburger Mobile --}}
            <button id="hamburgerBtn"
                class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-fish-blue hover:bg-gray-100 transition focus:outline-none">
                <svg id="iconHamburger" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg id="iconClose" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Menu Mobile --}}
    <div id="mobileMenu" class="hidden lg:hidden border-t border-gray-100 bg-white">
        <div class="max-w-7xl mx-auto px-4 py-3 space-y-1">
            <a href="{{ url('/') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium {{ request()->is('/') ? 'text-fish-blue bg-blue-50 font-semibold' : 'text-gray-600 hover:text-fish-blue hover:bg-gray-50' }} transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Beranda
            </a>

            <a href="#"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-fish-blue hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Profil
            </a>

            <a href="{{ url('publikasi-data') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium {{ Request::is('publikasi-data') ? 'text-fish-blue bg-blue-50 font-semibold' : 'text-gray-600 hover:text-fish-blue hover:bg-gray-50' }} transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Publikasi Data
            </a>

            <a href="#"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-600 hover:text-fish-blue hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                Berita
            </a>

            <a href="{{ route('announcements.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-medium {{ Request::is('pengumuman*') ? 'text-fish-blue bg-blue-50 font-semibold' : 'text-gray-600 hover:text-fish-blue hover:bg-gray-50' }} transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                Pengumuman
            </a>

            <div class="pt-2 pb-1 border-t border-gray-100 mt-2">
                <a href="{{ route('login') }}"
                    class="flex items-center justify-center gap-2 w-full bg-fish-blue text-white px-5 py-2.5 rounded-lg hover:bg-sky-700 transition font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    Login Admin
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    const hamburgerBtn = document.getElementById('hamburgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const iconHamburger = document.getElementById('iconHamburger');
    const iconClose = document.getElementById('iconClose');

    hamburgerBtn.addEventListener('click', () => {
        const isOpen = !mobileMenu.classList.contains('hidden');
        mobileMenu.classList.toggle('hidden', isOpen);
        iconHamburger.classList.toggle('hidden', !isOpen);
        iconClose.classList.toggle('hidden', isOpen);
    });
</script>
