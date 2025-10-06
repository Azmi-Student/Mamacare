<body>
    <!-- NAVBAR -->
    <nav>
        <div class="nav-logo">
            <!-- NAVBAR LOGO -->
            <div class="logo-img">
                <img src="{{ asset('assets/images/logo.svg') }}" alt="logo" class="logo-spin">
            </div>
            <div class="logo-tag">
                <h4>MamaCare</h4>
            </div>
        </div>

        <!-- NAVBAR LIST MENU -->
        <ul>
            <li><a href="">HOME</a></li>
            <li><a href="#tentang">TENTANG</a></li>
            <li><a href="#fitur">FITUR</a></li>
            <li><a href="#layanan">LAYANAN</a></li>
            <li><a href="#artikel">ARTIKEL</a></li>
            <li><a href="#bantuan">BANTUAN</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="#footer">FOOTER</a></li>
        </ul>
        <button onclick="window.location='{{ route('login') }}'" class="btn-login">Masuk</button>
        <button onclick="window.location='{{ route('register') }}'" class="btn-regist">Daftar</button>
    </nav>
</body>
