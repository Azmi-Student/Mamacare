<body>
    <!-- FAQ -->
    <div class="faq" id="faq">
        <!-- HEADER FAQ -->
        <div class="header fade-in-up">
            <h2>Pertanyaan Terkait Aplikasi MamaCare</h2>
            <p>Temukan jawaban dari pertanyaan yang sering muncul</p>
        </div>

        <div class="faq-container fade-in-up">
            <div class="faq-item">
                <div class="faq-question">
                    Siapa yang dapat menggunakan aplikasi MamaCare?
                    <img src="{{ asset('assets/images/img-landingpage/chevron.svg') }}" alt="">
                </div>
                <div class="faq-answer">Aplikasi MamaCare dapat digunakan oleh semua wanita yang sedang merencanakan kehamilan, ibu hamil, ibu menyusui, serta orang tua yang ingin memantau tumbuh kembang dan imunisasi anak mereka.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Bagaimana cara menggunakan aplikasi MamaCare?
                    <img src="{{ asset('assets/images/img-landingpage/chevron.svg') }}" alt="">
                </div>
                <div class="faq-answer">Setelah melakukan pendaftaran, pengguna dapat mengisi data kehamilan atau anak, lalu mulai menggunakan fitur-fitur seperti kalender kehamilan, pengingat kontrol, informasi nutrisi, dan notifikasi imunisasi secara otomatis.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Apakah saya akan mendapatkan pengingat untuk jadwal kontrol kehamilan dan imunisasi anak saya?
                    <img src="{{ asset('assets/images/img-landingpage/chevron.svg') }}" alt="">
                </div>
                <div class="faq-answer">Ya, MamaCare menyediakan pengingat otomatis untuk jadwal kontrol kehamilan, pemeriksaan kandungan, serta jadwal imunisasi anak berdasarkan usia dan data yang telah Anda masukkan.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Apakah informasi kesehatan saya aman di MamaCare?
                    <img src="{{ asset('assets/images/img-landingpage/chevron.svg') }}" alt="">
                </div>
                <div class="faq-answer">Ya. Data pengguna dienkripsi dan disimpan dengan protokol keamanan terbaik. MamaCare berkomitmen menjaga kerahasiaan dan privasi informasi pribadi serta kesehatan pengguna.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Bagaimana cara menghubungi tim dukungan jika saya mengalami masalah dengan aplikasi?
                    <img src="{{ asset('assets/images/img-landingpage/chevron.svg') }}" alt="">
                </div>
                <div class="faq-answer">Anda dapat menghubungi tim dukungan melalui menu "Bantuan" di dalam aplikasi, atau langsung kirim email ke mamacareplus05@gmail.com. Tim kami siap membantu Anda setiap hari kerja.</div>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const faqItems = document.querySelectorAll(".faq-item");

        faqItems.forEach(item => {
            const question = item.querySelector(".faq-question");
            question.addEventListener("click", () => {
                item.classList.toggle("active");
            });
        });
    });
</script>
