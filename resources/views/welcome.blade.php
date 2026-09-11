<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MamaCare</title>
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/reset.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/general.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/tentang.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/fitur.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/layanan.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/info.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/artikel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/bantuan.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/landing-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/faq.css') }}">
    <link rel="stylesheet" href="{{ asset('css/css-landingpage/footer.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('images/img-landingpage/logo.png') }}" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        @include('landingpage.page.navbar')

        @include('landingpage.page.hero')

        <img class="wave1" src="{{ asset('images/img-landingpage/wave_1.png') }}" alt="Wave Decoration">

        @include('landingpage.page.tentang')

        <!-- GARIS PINK -->
        <div class="line-bg">
            <div class="line"></div>
        </div>

        @include('landingpage.page.fitur')

        <img class="wave2" src="{{ asset('images/img-landingpage/wave_2.png') }}" alt="Wave Decoration">

        @include('landingpage.page.layanan')

        <!-- GARIS PUTIH -->
        <div class="line2-bg">
            <div class="line2"></div>
        </div>

        @include('landingpage.page.info')

        <img class="wave3" src="{{ asset('images/img-landingpage/wave_3.png') }}" alt="Wave Decoration">

        @include('landingpage.page.artikel')

        <img class="wave4" src="{{ asset('images/img-landingpage/wave_4.png') }}" alt="Wave Decoration">

        @include('landingpage.page.bantuan')

        <!-- GARIS PUTIH -->
        <div class="line2-bg">
            <div class="line2"></div>
        </div>

        @include('landingpage.page.landing-form')

        <img class="wave5" src="{{ asset('images/img-landingpage/wave_5.png') }}" alt="Wave Decoration">

        @include('landingpage.page.faq')

        <img class="wave6" src="{{ asset('images/img-landingpage/wave_6.png') }}" alt="Wave Decoration">

        @include('landingpage.page.footer')
    </div>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800, // durasi animasi 0.8 detik
            once: true,    // animasi hanya berjalan sekali saat di-scroll
            offset: 100    // jarak sebelum animasi mulai
        });
    </script>
</body>
<script>
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('.container nav ul');

    menuToggle.addEventListener('click', () => {
        menuToggle.classList.toggle('is-active');
        navMenu.classList.toggle('active');
    });
</script>

</html>
