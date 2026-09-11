<div class="hero">
    <div class="text" data-aos="fade-right">
        <div class="hero-logo">
            <img src="{{ asset('images/img-landingpage/logo.png') }}" alt="logo" class="logo-spin">
            <div class="logo-tag">
                <h4>MamaCare</h4>
            </div>
        </div>

        <h2>Inovasi digital untuk asisten perawatan kesehatan fase kehamilan ibu hingga pasca persalinan</h2>
        
        <div class="hero-buttons">
            <button onclick="window.location='{{ route('login') }}'" class="btn-get-started">Get Started</button>
            <button onclick="window.location='{{ route('login') }}'" class="btn-login">Masuk</button>
            <button onclick="window.location='{{ route('register') }}'" class="btn-regist">Daftar</button>
        </div>
    </div>
    
    <div class="hero-image" data-aos="fade-left">
        <img class="dokter" src="{{ asset('images/img-landingpage/icon_docter.png') }}" alt="doctor-mascot">
    </div>
</div>