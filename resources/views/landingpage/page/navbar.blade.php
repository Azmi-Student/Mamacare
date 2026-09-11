<nav>
    <div class="nav-logo">
        <div class="logo-img">
            <img src="{{ asset('images/img-landingpage/logo.png') }}" alt="logo" class="logo-spin">
        </div>
        <div class="logo-tag">
            <h4>MamaCare</h4>
        </div>
    </div>

    <div class="menu-toggle" id="mobile-menu">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <ul class="nav-menu">
        <li><a href="">HOME</a></li>
        <li><a href="#tentang">TENTANG</a></li>
        <li><a href="#fitur">FITUR</a></li>
        <li><a href="#layanan">LAYANAN</a></li>
        <li><a href="#artikel">ARTIKEL</a></li>
        <li><a href="#bantuan">BANTUAN</a></li>
        <li><a href="#faq">FAQ</a></li>
        <li class="nav-btn-mobile">
            <button onclick="window.location='{{ route('login') }}'" class="btn-login">Masuk</button>
            <button onclick="window.location='{{ route('register') }}'" class="btn-regist">Daftar</button>
        </li>
    </ul>
</nav>
