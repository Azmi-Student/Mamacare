<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mamacare Dashboard')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="shortcut icon" href="{{ asset('images/logo-icon.png') }}" type="image/x-icon">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    {{-- Tailwind + Config --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '#FF3EA5',
                    }
                },
            },
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Custom Style --}}
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }



        /* Mengunci gaya teks agar tetap Pink saat Autofill Browser */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-text-fill-color: #FF3EA5 !important;
            /* Warna Pink Primary */
            -webkit-box-shadow: 0 0 0px 1000px white inset !important;
            /* Background tetap Putih */
            transition: background-color 5000s ease-in-out 0s;
            font-family: inherit !important;
        }

        /* Menangani state preview saat memilih data */
        input:autofill {
            color: #FF3EA5 !important;
        }

        /* --- STYLE SIDEBAR COLLAPSED --- */
        .sidebar-collapsed .sidebar-text {
            display: none !important;
        }
        .sidebar-collapsed .sidebar-icon-container {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .sidebar-collapsed .sidebar-logo-container {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .sidebar-collapsed .sidebar-dropdown-icon {
            display: none !important;
        }
    </style>
</head>

<body class="bg-[#FFEFF8] text-gray-800 font-sans antialiased">

    @include('components.loading-screen')


    <div x-data="{ sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true' }" 
         x-init="$watch('sidebarCollapsed', val => localStorage.setItem('sidebarCollapsed', val))"
         class="flex h-screen overflow-hidden">
        {{-- SIDEBAR --}}
        <div :class="sidebarCollapsed ? 'w-20 sidebar-collapsed' : 'w-64'"
            class="hidden lg:flex flex-col bg-white border-r-2 border-[#FF3EA5] h-full transition-all duration-300 z-40 relative group/sidebar">
            
            {{-- Toggle Button --}}
            <button @click="sidebarCollapsed = !sidebarCollapsed" 
                class="absolute -right-3 top-10 w-6 h-6 bg-white border-2 border-[#FF3EA5] rounded-full flex items-center justify-center text-[#FF3EA5] z-50 hover:bg-[#FF3EA5] hover:text-white transition-colors cursor-pointer shadow-sm">
                <svg class="w-3 h-3 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M15 19l-7-7 7-7"></path></svg>
            </button>

            @include('components.sidebar')
        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden relative">

            {{-- 
                HEADER MOBILE
                Logika: Tampil HANYA JIKA halaman anak TIDAK me-request untuk menyembunyikannya.
            --}}
            @if (!View::hasSection('hideMobileHeader'))
                <header
                    class="lg:hidden mx-4 mt-4 rounded-xl bg-white border-2 border-[#FF3EA5] shadow-[3px_3px_0px_0px_#ff90c8] h-14 flex items-center justify-between px-4 shrink-0 z-30 relative">
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/logo-icon.png') }}" alt="Mamacare Logo"
                            class="block w-7 h-7 object-contain">
                        <span class="font-black text-lg text-[#FF3EA5] tracking-tight pl-1 uppercase">MamaCare</span>
                    </div>
                    <div class="flex items-center gap-3">
                        @include('components.header')
                    </div>
                </header>
            @endif

            {{-- MAIN AREA --}}
            {{-- 
               Kita juga bisa atur padding top dinamis. 
               Jika header di-hide (halaman chat), padding top (pt-6) kita hilangkan biar full screen.
               Jika header ada, pakai pt-6.
            --}}
            <main
                class="flex-1 overflow-x-hidden overflow-y-auto no-scrollbar p-4 pb-24 md:p-6 md:pb-24 lg:pb-6 
                {{ View::hasSection('hideMobileHeader') ? 'pt-0' : 'pt-6' }}">
                <div class="container mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- MOBILE NAVBAR --}}
    @if (!View::hasSection('hideMobileNav'))
        <div class="lg:hidden">
            @include('components.navbar-mobile')
        </div>
    @endif



    <script>
        // Global configuration untuk fetch agar selalu bawa token CSRF
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
    @include('components.donation-modal')
</body>

</html>
