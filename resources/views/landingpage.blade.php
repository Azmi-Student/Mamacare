<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>MamaCare</title>
        <link rel="stylesheet" href="{{ asset('assets/css/reset.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/general.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/navbar.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/hero.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/tentang.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/fitur.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/layanan.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/info.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/artikel.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/bantuan.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/landing-form.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/faq.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/css-landingpage/footer.css') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="{{ asset('assets/images/logo1.svg') }}" type="image/svg+xml">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            @include('landingpage.page.navbar')

            @include('landingpage.page.hero')

            <img class="wave1" src="{{ asset('assets/images/img-landingpage/wave_1.svg') }}" alt="Wave Decoration">

            @include('landingpage.page.tentang')

            <!-- GARIS PINK -->
            <div class="line-bg"><div class="line"></div></div>

            @include('landingpage.page.fitur')

            <img class="wave2" src="{{ asset('assets/images/img-landingpage/wave_2.svg') }}" alt="Wave Decoration">
            
            @include('landingpage.page.layanan')
            
            <!-- GARIS PUTIH -->
            <div class="line2-bg"><div class="line2"></div></div>
            
            @include('landingpage.page.info')

            <img class="wave3" src="{{ asset('assets/images/img-landingpage/wave_3.svg') }}" alt="Wave Decoration">
            
            @include('landingpage.page.artikel')

            <img class="wave4" src="{{ asset('assets/images/img-landingpage/wave_4.svg') }}" alt="Wave Decoration">
            
            @include('landingpage.page.bantuan')
            
            <!-- GARIS PUTIH -->
            <div class="line2-bg"><div class="line2"></div></div>
            
            @include('landingpage.page.landing-form')
            
            <img class="wave5" src="{{ asset('assets/images/img-landingpage/wave_5.svg') }}" alt="Wave Decoration">
            
            @include('landingpage.page.faq')
            
            <img class="wave6" src="{{ asset('assets/images/img-landingpage/wave_6.svg') }}" alt="Wave Decoration">

            @include('landingpage.page.footer')
        </div>
    </body>
</html>