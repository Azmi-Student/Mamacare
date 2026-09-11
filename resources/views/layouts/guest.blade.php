<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mamacare')</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    {{-- Tailwind + Config --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: { primary: '#FF3EA5' }
                },
            },
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Style Input Autofill --}}
    <style>

        /* 1. Mengatur teks saat preview & setelah dipilih (Autofill) */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active {
        /* Memaksa warna teks tetap Pink Primary */
        -webkit-text-fill-color: #FF3EA5 !important; 
        /* Memaksa background tetap putih (menghapus warna kuning browser) */
        -webkit-box-shadow: 0 0 0px 1000px white inset !important;
        /* Memastikan font-weight mengikuti desain Mama */
        font-weight: 700 !important;
    }

    /* 2. Mengatur font saat kursor menyorot pilihan autofill (Preview State) */
    input:autofill {
        color: #FF3EA5 !important;
    }

    /* 3. Tambahan khusus untuk beberapa browser agar transisinya halus */
    input::-webkit-contacts-auto-fill-button {
        background-color: #FF3EA5;
    }
    </style>
</head>
<body class="bg-white text-gray-800 font-sans antialiased overflow-x-hidden">

    {{-- LOADING SCREEN --}}
    @include('components.loading-screen')

    {{-- TEMPAT KONTEN --}}
    <div class="min-h-screen flex flex-col lg:flex-row overflow-hidden">
        @yield('content')
    </div>


</body>
</html>