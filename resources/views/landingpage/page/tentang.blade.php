<body> 
    <div class="tentang" id="tentang">
        <div class="pola1">
            <img src="{{ asset('assets/images/img-landingpage/pola1.svg') }}" alt="pola">
        </div>
        <div class="love-spin1">
            <img src="{{ asset('assets/images/img-landingpage/lovespin1.svg') }}" alt="pola" class="lovespin1">
        </div>

        <div class="menu">
            <h4>MamaCare</h4>
            <ul>
                <li><a href="" onclick="showContent(event, 'about')">Apa itu MamaCare?</a></li>
                <li><a href="" onclick="showContent(event, 'latar-belakang')">Latar belakang</a></li>
                <li><a href="" onclick="showContent(event, 'tim-dev')">Tim pengembang</a></li>
                <li><a href="" onclick="showContent(event, 'kebijakan')">Kebijakan privasi</a></li>
            </ul>
        </div>
        <div class="konten-kanan">
            <div id="about" class="konten aktif">
                <p>
                    MamaCare adalah aplikasi yang dirancang untuk membantu ibu hamil dalam menjaga kesehatan kandungan ibu secara optimal. Aplikasi ini berfokus pada penyediaan layanan kesehatan berbasis website yang memungkinkan penggunanya untuk memantau perkembangan kesehatan selama kehamilan, mendapatkan informasi nutrisi yang tepat, dan menerima pengingat otomatis untuk kontrol kesehatan serta imunisasi.
                </p>
            </div>
            <div id="latar-belakang" class="konten">
                <p>Latar belakang MamaCare berangkat dari kebutuhan ibu hamil akan informasi yang mudah diakses...</p>
            </div>
            <div id="tim-dev" class="konten">
                <p>Tim pengembang terdiri dari profesional di bidang kesehatan, teknologi, dan desain...</p>
            </div>
            <div id="kebijakan" class="konten">
                <p>Kami menjaga privasi data pengguna dengan menerapkan standar keamanan informasi...</p>
            </div>
        </div>
    </div>

    <script>
        function showContent(event, id) {
            event.preventDefault();

            document.querySelectorAll('.konten').forEach(el => el.classList.remove('aktif'));

            document.getElementById(id).classList.add('aktif');
        }
    </script>
</body>
