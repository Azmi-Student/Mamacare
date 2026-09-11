<div class="flex flex-col h-full bg-white">
    <style>
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>    {{-- 1. LOGO AREA --}}
    <div class="sidebar-logo-container h-20 flex items-center justify-center lg:justify-start lg:px-6 shrink-0 mt-2">
        @php
            $dashboardRoute = 'dashboard';
            if (auth()->user()->role === 'admin') {
                $dashboardRoute = 'admin.dashboard';
            } elseif (auth()->user()->role === 'dokter') {
                $dashboardRoute = 'dokter.dashboard';
            }
        @endphp

        <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3 group">
            <div
                class="w-10 h-10 bg-[#FF3EA5] rounded-lg border-2 border-[#FF3EA5] flex items-center justify-center shadow-[3px_3px_0px_0px_#ff90c8] group-hover:translate-x-1 group-hover:shadow-none transition-all">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Icon"
                    class="w-6 h-6 object-contain brightness-0 invert">
            </div>
            <span
                class="sidebar-text text-2xl font-black text-[#FF3EA5] hidden lg:block whitespace-nowrap tracking-tighter uppercase drop-shadow-sm">
                MamaCare
            </span>
        </a>
    </div>

    {{-- 2. NAVIGATION LINKS --}}
    <nav class="flex-1 overflow-y-auto py-4 px-4 space-y-3 no-scrollbar">

        {{-- LINK: DASHBOARD (Dynamic based on Role) --}}
        @php
            $isDashboardActive =
                request()->routeIs('dashboard') ||
                request()->routeIs('admin.dashboard') ||
                request()->routeIs('dokter.dashboard');
        @endphp

        <a href="{{ route($dashboardRoute) }}"
            class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group
            {{ $isDashboardActive
                ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1'
                : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">

            <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isDashboardActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>

            <span
                class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isDashboardActive ? 'text-white' : 'text-[#FF3EA5]' }}">
                Dashboard
            </span>
        </a>

        {{-- === MENU KHUSUS MAMA === --}}
        @if (auth()->user()->role === 'mama')
            @php
                $isLayananDokterActive =
                    request()->routeIs('mama.tanya-dokter*') || request()->routeIs('mama.reservasi*');
            @endphp

            {{-- Dropdown Group --}}
            <div x-data="{ open: {{ $isLayananDokterActive ? 'true' : 'false' }} }" class="space-y-1">

                {{-- Tombol Induk Dropdown --}}
                <button @click="open = !open"
                    class="sidebar-icon-container w-full flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group 
            {{ $isLayananDokterActive
                ? 'bg-pink-50 border-[#FF3EA5] text-[#FF3EA5]'
                : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5]' }}">

                    <svg class="w-6 h-6 shrink-0 stroke-[2.5px]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>

                    <span
                        class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm flex-1 text-left">Layanan
                        Dokter</span>

                    <svg class="sidebar-dropdown-icon w-4 h-4 transition-transform duration-200 hidden lg:block"
                        :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- Sub-Menu Area --}}
                <div x-show="open" x-transition.origin.top class="lg:ps-4 space-y-2 mt-2 overflow-hidden">

                    {{-- SUBMENU: CHAT DOKTER --}}
                    @php $isChatActive = request()->routeIs('mama.tanya-dokter*'); @endphp
                    <a href="{{ route('mama.tanya-dokter') }}"
                        class="sidebar-icon-container flex items-center p-2.5 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group
                {{ $isChatActive
                    ? 'bg-[#FF3EA5] border-[#FF3EA5] text-white shadow-[3px_3px_0px_0px_#ff90c8]'
                    : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5]' }}">
                        <svg class="w-5 h-5 stroke-[2.5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="sidebar-text ms-3 hidden lg:block font-bold uppercase tracking-wide text-[11px]">Chat
                            Dokter</span>
                    </a>

                    {{-- SUBMENU: RESERVASI --}}
                    @php $isResActive = request()->routeIs('mama.reservasi*'); @endphp
                    <a href="{{ Route::has('mama.reservasi') ? route('mama.reservasi') : '#' }}"
                        class="sidebar-icon-container flex items-center p-2.5 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group
                {{ $isResActive
                    ? 'bg-[#FF3EA5] border-[#FF3EA5] text-white shadow-[3px_3px_0px_0px_#ff90c8]'
                    : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5]' }}">
                        <svg class="w-5 h-5 stroke-[2.5px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span
                            class="sidebar-text ms-3 hidden lg:block font-bold uppercase tracking-wide text-[11px]">Reservasi</span>
                    </a>
                </div>
            </div>

            {{-- LINK: REKAP DATA (Di luar dropdown agar tidak bentrok) --}}
            @php $isRekapActive = request()->routeIs('mama.rekap-data*'); @endphp
            <a href="{{ Route::has('mama.rekap-data') ? route('mama.rekap-data') : '#' }}"
                class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group mt-3
        {{ $isRekapActive
            ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1'
            : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">
                <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isRekapActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span
                    class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isRekapActive ? 'text-white' : 'text-[#FF3EA5]' }}">Rekap
                    Data</span>
            </a>

            {{-- LINK: PANDUAN NUTRISI (MAMA) --}}
            @php $isNutrisiActive = request()->routeIs('mama.nutrition-guide'); @endphp
            <a href="{{ route('mama.nutrition-guide') }}"
                class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group mt-3
        {{ $isNutrisiActive
            ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1'
            : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">
                <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isNutrisiActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span
                    class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isNutrisiActive ? 'text-white' : 'text-[#FF3EA5]' }}">
                    Nutrisi
                </span>
            </a>
        @endif

        {{-- === MENU KHUSUS DOKTER === --}}
        @if (auth()->user()->role === 'dokter')
            {{-- 1. DAFTAR PASIEN --}}
            @php
                $isReservasiActive = request()->routeIs('dokter.reservasi.*');
            @endphp
            <a href="{{ route('dokter.reservasi.index') }}"
                class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group
                {{ $isReservasiActive
                    ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1'
                    : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">

                <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isReservasiActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>

                <span
                    class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isReservasiActive ? 'text-white' : 'text-[#FF3EA5]' }}">
                    Daftar Pasien
                </span>
            </a>

            {{-- 2. CHAT PASIEN --}}
            @php
                $isChatActive = request()->routeIs('dokter.chat.*');
            @endphp
            <a href="{{ Route::has('dokter.chat.index') ? route('dokter.chat.index') : '#' }}"
                class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group
                {{ $isChatActive
                    ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1'
                    : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">

                <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isChatActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>

                <span
                    class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isChatActive ? 'text-white' : 'text-[#FF3EA5]' }}">
                    Chat Pasien
                </span>
            </a>

            {{-- 3. [BARU] KELOLA ARTIKEL --}}
            @php
                $isKelolaArtikelActive = request()->routeIs('dokter.kelola-artikel.*');
            @endphp
            <a href="{{ route('dokter.kelola-artikel.index') }}"
                class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group
                {{ $isKelolaArtikelActive
                    ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1'
                    : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">

                {{-- Ikon Pencil Square (Edit) --}}
                <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isKelolaArtikelActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>

                <span
                    class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isKelolaArtikelActive ? 'text-white' : 'text-[#FF3EA5]' }}">
                    Kelola Artikel
                </span>
            </a>

            {{-- 4. [BARU] KELOLA NUTRISI --}}
            @php
                $isKelolaNutrisiActive = request()->routeIs('dokter.kelola-nutrisi.*');
            @endphp
            <a href="{{ route('dokter.kelola-nutrisi.index') }}"
                class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group mt-3
                {{ $isKelolaNutrisiActive
                    ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1'
                    : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">

                {{-- Ikon Apple (Nutrisi) --}}
                <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isKelolaNutrisiActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>

                <span
                    class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isKelolaNutrisiActive ? 'text-white' : 'text-[#FF3EA5]' }}">
                    Kelola Nutrisi
                </span>
            </a>
        @endif

        {{-- LINK: KOMUNITAS (SHARED) --}}
        @php $isKomunitasActive = request()->routeIs('komunitas.*'); @endphp
        <a href="{{ route('komunitas.index') }}"
            class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group mt-3
            {{ $isKomunitasActive ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1' : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">

            <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isKomunitasActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
            </svg>
            <span
                class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isKomunitasActive ? 'text-white' : 'text-[#FF3EA5]' }}">Komunitas</span>
        </a>

        {{-- LINK: ARTIKEL (PUBLIK) --}}
        {{-- Tetap ada di bawah biar semua role bisa baca artikel --}}
        @php $isArtikelActive = request()->routeIs('artikel.index') || request()->routeIs('artikel.show'); @endphp
        <a href="{{ route('artikel.index') }}"
            class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group
            {{ $isArtikelActive ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1' : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">

            <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ $isArtikelActive ? 'text-white' : 'text-[#FF3EA5]' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
            <span
                class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ $isArtikelActive ? 'text-white' : 'text-[#FF3EA5]' }}">Artikel</span>
        </a>

        {{-- LINK: SETTINGS (Semua Role) --}}
        <a href="{{ route('profile.edit') }}"
            class="sidebar-icon-container flex items-center p-3 rounded-xl border-2 transition-all duration-200 justify-center lg:justify-start group
            {{ request()->routeIs('profile.edit')
                ? 'bg-[#FF3EA5] border-[#FF3EA5] shadow-[4px_4px_0px_0px_#ff90c8] -translate-y-1'
                : 'bg-white border-transparent text-[#FF3EA5] hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1' }}">

            <svg class="w-6 h-6 shrink-0 stroke-[2.5px] {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-[#FF3EA5]' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            <span
                class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm {{ request()->routeIs('profile.edit') ? 'text-white' : 'text-[#FF3EA5]' }}">
                Settings
            </span>
        </a>

        {{-- =========================================== --}}
        {{-- TOMBOL BERI DONASI (BARU)                   --}}
        {{-- =========================================== --}}
        <button id="btn-donasi"
            class="sidebar-icon-container w-full flex items-center p-3 rounded-xl border-2 border-transparent bg-white text-[#FF3EA5] shadow-none hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1 transition-all duration-200 justify-center lg:justify-start group mt-4">

            {{-- Ikon Love/Heart --}}
            <svg class="w-6 h-6 shrink-0 stroke-[2.5px] text-[#FF3EA5] fill-none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>

            <span class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm">
                Beri Donasi
            </span>
        </button>
    </nav>

    {{-- 3. LOGOUT BUTTON AREA --}}
    <div class="p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="sidebar-icon-container w-full flex items-center p-3 rounded-xl border-2 border-transparent text-[#FF3EA5] hover:bg-pink-50 hover:border-[#FF3EA5] hover:shadow-[4px_4px_0px_0px_#FF3EA5] hover:-translate-y-1 transition-all duration-200 justify-center lg:justify-start group">
                <svg class="w-6 h-6 shrink-0 stroke-[2.5px] text-[#FF3EA5]" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="sidebar-text ms-3 hidden lg:block font-black uppercase tracking-wide text-sm text-[#FF3EA5]">
                    Keluar
                </span>
            </button>
        </form>
    </div>
</div>
