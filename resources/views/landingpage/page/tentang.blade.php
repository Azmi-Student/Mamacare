<div class="tentang" id="tentang">
    <div class="pola1">
        <img src="{{ asset('images/img-landingpage/pola1.png') }}" alt="pola">
    </div>
    <div class="love-spin1">
        <img src="{{ asset('images/img-landingpage/lovespin1.png') }}" alt="pola" class="lovespin1">
    </div>

    <div class="menu" data-aos="fade-up" data-aos-delay="100">
        <h4>MamaCare</h4>
        <ul>
            <li><a href="#" class="tab-link aktif" onclick="showContent(event, 'about')">Apa itu MamaCare?</a></li>
            <li><a href="#" class="tab-link" onclick="showContent(event, 'latar-belakang')">Latar belakang</a></li>
            <li><a href="#" class="tab-link" onclick="showContent(event, 'tim-dev')">Tim pengembang</a></li>
            <li><a href="#" class="tab-link" onclick="showContent(event, 'kebijakan')">Kebijakan privasi</a></li>
        </ul>
    </div>

    <div class="konten-kanan" data-aos="fade-up" data-aos-delay="300">
        <div id="about" class="konten aktif">
            <p>
                MamaCare adalah aplikasi yang dirancang untuk membantu ibu hamil dalam menjaga kesehatan kandungan ibu secara optimal. Aplikasi ini berfokus pada penyediaan layanan kesehatan berbasis website yang memungkinkan penggunanya untuk memantau perkembangan kesehatan selama kehamilan, mendapatkan informasi nutrisi yang tepat, dan menerima pengingat otomatis untuk kontrol kesehatan serta imunisasi.
            </p>
        </div>
        <div id="latar-belakang" class="konten">
            <p>Latar belakang MamaCare berangkat dari kebutuhan ibu hamil akan informasi yang mudah diakses dan terpercaya untuk menekan angka risiko kehamilan melalui pemantauan rutin yang terdigitalisasi.</p>
        </div>
        <div id="tim-dev" class="konten">
            <p>Tim pengembang terdiri dari profesional di bidang kesehatan, teknologi, dan desain yang berkomitmen menciptakan solusi inklusif bagi ibu dan anak di seluruh Indonesia.</p>
        </div>
        <div id="kebijakan" class="konten">
            <p>Kami menjaga privasi data pengguna dengan menerapkan standar keamanan informasi tingkat tinggi. Data medis Anda bersifat rahasia dan hanya digunakan untuk kepentingan pemantauan kesehatan pribadi.</p>
        </div>
    </div>
</div>

<script>
    function showContent(event, id) {
        event.preventDefault();
        
        // Sembunyikan semua konten
        document.querySelectorAll('.konten').forEach(el => el.classList.remove('aktif'));
        // Hapus class aktif dari semua link menu
        document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('aktif'));

        // Tampilkan konten yang dipilih
        document.getElementById(id).classList.add('aktif');
        // Tambahkan class aktif ke klik yang sedang berlangsung
        event.currentTarget.classList.add('aktif');
    }
</script>