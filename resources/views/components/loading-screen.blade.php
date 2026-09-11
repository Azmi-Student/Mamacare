<style>
    /* --- STYLE LOADING SCREEN --- */
    #loading-screen {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background-color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;

        /* HALUS: Fade In & Fade Out lebih cepat (0.6 detik) */
        opacity: 1;
        visibility: visible;
        transition: opacity 0.6s ease-in-out, visibility 0.6s ease-in-out;
    }

    /* Class untuk sembunyikan (Fade Out) */
    .loader-hidden {
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none;
    }

    /* Animasi Logo Muter */
    .spin-continuous {
        animation: spin 1.5s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    /* Progress Bar Fill */
    #progress-bar-fill {
        width: 0%;
        transition: width 0.2s ease-out;
        /* Bikin gerakan bar dari JS lebih halus */
    }
</style>

{{-- LOADING SCREEN --}}
<div id="loading-screen">
    {{-- Logo Muter --}}
    <img src="{{ asset('images/logo-icon.png') }}" alt="Loading..."
        class="w-16 h-16 object-contain spin-continuous mb-8">

    {{-- Progress Bar Container --}}
    <div class="w-64 h-2 bg-gray-100 rounded-full overflow-hidden relative shadow-inner">
        <div id="progress-bar-fill" class="h-full bg-[#FF3EA5] rounded-full"></div>
    </div>

    <p class="mt-4 text-xs font-semibold text-gray-400 tracking-[0.2em] animate-pulse uppercase">Memuat</p>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const loader = document.getElementById('loading-screen');
        const progressFill = document.getElementById('progress-bar-fill');

        let progress = 0;
        let isPageLoaded = false;
        let simulationInterval;

        function runProgressSimulation(startProgress = 0) {
            clearInterval(simulationInterval);
            progress = startProgress;
            progressFill.style.width = progress + '%';

            // Mulai Fade In (Muncul) secara halus
            loader.classList.remove('loader-hidden');

            simulationInterval = setInterval(() => {
                if (!isPageLoaded) {
                    // Simpan progress agar bisa dilanjutkan di halaman selanjutnya
                    sessionStorage.setItem('loader_progress', progress);
                    
                    // Jalan pelan ke 90%
                    if (progress < 90) {
                        progress += Math.random() * 1.5;
                    }
                } else {
                    // Geber ke 100%
                    progress += 7;
                }

                if (progress >= 100) {
                    progress = 100;
                    clearInterval(simulationInterval);
                    sessionStorage.removeItem('loader_progress');

                    // Setelah bar 100%, tunggu sebentar baru Fade Out
                    setTimeout(() => {
                        loader.classList.add('loader-hidden');
                    }, 300);
                }

                progressFill.style.width = progress + '%';
            }, 40);
        }

        // A. Inisialisasi awal saat buka web
        let isFirstVisit = false;
        try {
            if (!document.referrer || new URL(document.referrer).origin !== window.location.origin) {
                isFirstVisit = true;
            }
        } catch(e) {
            isFirstVisit = true;
        }
        
        let isReload = false;
        try {
            if (window.performance && window.performance.getEntriesByType) {
                const navEntries = window.performance.getEntriesByType("navigation");
                if (navEntries.length > 0 && navEntries[0].type === "reload") {
                    isReload = true;
                }
            } else if (window.performance && window.performance.navigation) {
                if (window.performance.navigation.type === 1) {
                    isReload = true;
                }
            }
        } catch(e) {}

        if (sessionStorage.getItem('trigger_loader') === 'true' || isFirstVisit || isReload) {
            // Ambil progress dari halaman sebelumnya kalau ada (agar nyambung)
            let savedProgress = parseFloat(sessionStorage.getItem('loader_progress')) || 0;
            
            // Kalau ini halaman baru dari klik link, dia nyambung. Kalau reload/first visit murni, mulai dari 0.
            if (isFirstVisit || isReload) {
                savedProgress = 0;
            }
            
            runProgressSimulation(savedProgress);
            sessionStorage.removeItem('trigger_loader');
        } else {
            // Sembunyikan langsung tanpa animasi jika dari form submit/refresh
            loader.style.transition = 'none';
            loader.classList.add('loader-hidden');
            progressFill.style.width = '100%';
            
            setTimeout(() => {
                loader.style.transition = 'opacity 0.6s ease-in-out, visibility 0.6s ease-in-out';
            }, 50);
            
            isPageLoaded = true;
        }

        window.addEventListener('load', function() {
            isPageLoaded = true;
        });

        // B. Deteksi Klik pada Link (Hanya Navigasi Utama)
        document.addEventListener('click', function(e) {
            // Kita hanya memicu loader jika yang diklik adalah link navigasi utama (berada di dalam <nav> atau logo)
            const target = e.target.closest('nav a, .sidebar-logo-container a');

            if (target) {
                const href = target.getAttribute('href');
                const targetAttr = target.getAttribute('target');

                if (
                    e.ctrlKey || e.metaKey || e.shiftKey || e.button !== 0 || // Mencegah trigger jika buka di tab baru
                    targetAttr === '_blank' ||
                    href === '#' ||
                    (href && href.startsWith('javascript')) ||
                    (href && href.startsWith('#'))
                ) {
                    return;
                }

                // Reset status dan mulai animasi fade in
                isPageLoaded = false;
                sessionStorage.setItem('trigger_loader', 'true');
                runProgressSimulation();

                // Safety timeout
                setTimeout(() => {
                    isPageLoaded = true;
                }, 6000);
            }
        });

        // C. Deteksi Form Submit (Untuk Login, Register, Update Profile, dll)
        document.addEventListener('submit', function(e) {
            // Abaikan jika form disubmit menggunakan AJAX/preventDefault, atau form memiliki class 'no-loader'
            if (e.defaultPrevented || (e.target && e.target.classList.contains('no-loader'))) return;
            
            isPageLoaded = false;
            sessionStorage.setItem('trigger_loader', 'true');
            runProgressSimulation();
            
            // Safety timeout
            setTimeout(() => {
                isPageLoaded = true;
            }, 6000);
        });



        // D. Fix Browser Back
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                loader.classList.add('loader-hidden');
            }
        });
    });
</script>
