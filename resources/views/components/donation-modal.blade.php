{{-- ===================================================================== --}}
{{-- 1. MODAL DONASI (Input Nominal)                                       --}}
{{-- ===================================================================== --}}
<div id="modal-donasi" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white w-full max-w-md rounded-2xl border-2 border-[#FF3EA5] shadow-[8px_8px_0px_0px_#FF3EA5] p-6 m-4 transform scale-100 transition-transform">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-black text-[#FF3EA5] uppercase tracking-wide">💖 Dukung MamaCare</h3>
            <button onclick="closeModalDonasi()" class="text-gray-400 hover:text-[#FF3EA5] transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Chips --}}
        <p class="text-sm font-bold text-gray-500 mb-2">Pilih Nominal:</p>
        <div class="grid grid-cols-2 gap-3 mb-4">
            <button type="button" onclick="pilihNominal(5000, this)" class="nominal-chip p-3 rounded-xl border-2 border-gray-200 text-gray-500 font-bold hover:border-[#FF3EA5] hover:text-[#FF3EA5] hover:bg-pink-50 transition-all">Rp 5.000</button>
            <button type="button" onclick="pilihNominal(10000, this)" class="nominal-chip p-3 rounded-xl border-2 border-gray-200 text-gray-500 font-bold hover:border-[#FF3EA5] hover:text-[#FF3EA5] hover:bg-pink-50 transition-all">Rp 10.000</button>
            <button type="button" onclick="pilihNominal(25000, this)" class="nominal-chip p-3 rounded-xl border-2 border-gray-200 text-gray-500 font-bold hover:border-[#FF3EA5] hover:text-[#FF3EA5] hover:bg-pink-50 transition-all">Rp 25.000</button>
            <button type="button" onclick="pilihNominal(50000, this)" class="nominal-chip p-3 rounded-xl border-2 border-gray-200 text-gray-500 font-bold hover:border-[#FF3EA5] hover:text-[#FF3EA5] hover:bg-pink-50 transition-all">Rp 50.000</button>
        </div>

        {{-- Input Manual --}}
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-600 mb-2">Atau Ketik Manual</label>
            <div class="relative group">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold group-focus-within:text-[#FF3EA5]">Rp</span>
                <input type="number" id="input-nominal" placeholder="0" min="1000" class="w-full pl-12 pr-4 py-3 rounded-xl border-2 border-gray-200 focus:border-[#FF3EA5] focus:ring-0 outline-none font-bold text-lg text-gray-700 transition-colors" oninput="resetChips()">
            </div>
        </div>

        {{-- Tombol Bayar --}}
        <button id="btn-process-payment" class="w-full py-3 rounded-xl bg-[#FF3EA5] text-white font-black text-lg shadow-[4px_4px_0px_0px_#ff90c8] hover:shadow-none hover:translate-y-1 transition-all flex justify-center items-center gap-2">
            <span>LANJUT BAYAR</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
        </button>
    </div>
</div>

{{-- ===================================================================== --}}
{{-- 2. MODAL SUKSES (Muncul Otomatis setelah Bayar)                       --}}
{{-- ===================================================================== --}}
<div id="modal-sukses" class="fixed inset-0 z-[10000] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm transition-all duration-300">
    <div class="bg-white w-full max-w-sm rounded-2xl border-2 border-[#10B981] shadow-[8px_8px_0px_0px_#10B981] p-8 m-4 text-center transform scale-100 transition-transform">
        
        {{-- Ikon Ceklis Animasi --}}
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-[#10B981]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h3 class="text-2xl font-black text-gray-800 mb-2 uppercase tracking-tight">Terima Kasih!</h3>
        <p class="text-gray-500 font-medium mb-8">Donasi Mama sangat berarti untuk pengembangan aplikasi ini. Sehat selalu ya, Ma! 💖</p>

        <button onclick="tutupSemuaModal()" class="w-full py-3 rounded-xl bg-[#10B981] text-white font-black text-lg shadow-[4px_4px_0px_0px_#059669] hover:shadow-none hover:translate-y-1 transition-all">
            TUTUP
        </button>
    </div>
</div>

{{-- Script Midtrans --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    // --- VARIABEL GLOBAL UNTUK POLLING ---
    let pollingInterval = null;

    // --- HELPERS UI ---
    function pilihNominal(amount, element) {
        document.getElementById('input-nominal').value = amount;
        resetChips();
        element.classList.remove('text-gray-500', 'border-gray-200');
        element.classList.add('bg-[#FF3EA5]', 'text-white', 'border-[#FF3EA5]');
    }

    function resetChips() {
        document.querySelectorAll('.nominal-chip').forEach(el => {
            el.classList.remove('bg-[#FF3EA5]', 'text-white', 'border-[#FF3EA5]');
            el.classList.add('text-gray-500', 'border-gray-200');
        });
    }

    // Fungsi Buka Tutup Modal
    const modalDonasi = document.getElementById('modal-donasi');
    const modalSukses = document.getElementById('modal-sukses');

    function openModalDonasi() {
        if(modalDonasi) modalDonasi.classList.remove('hidden');
    }

    function closeModalDonasi() {
        if(modalDonasi) modalDonasi.classList.add('hidden');
    }

    function openModalSukses() {
        closeModalDonasi(); // Tutup yang donasi dulu
        if(modalSukses) modalSukses.classList.remove('hidden');
    }

    function tutupSemuaModal() {
        if(modalSukses) modalSukses.classList.add('hidden');
        if(modalDonasi) modalDonasi.classList.add('hidden');
        // Stop polling kalau user tutup semua
        stopPolling();
    }

    // --- MAIN LOGIC ---
    document.addEventListener("DOMContentLoaded", function() {
        const btnSidebar = document.getElementById('btn-donasi');
        const btnProcess = document.getElementById('btn-process-payment');
        const inputNominal = document.getElementById('input-nominal');

        if (btnSidebar) {
            // Event Buka Modal
            btnSidebar.onclick = function(e) {
                e.preventDefault();
                openModalDonasi();
            };

            // Event Klik di luar modal untuk menutup
            window.onclick = function(e) {
                if(e.target === modalDonasi) closeModalDonasi();
                if(e.target === modalSukses) tutupSemuaModal();
            }

            // PROSES BAYAR
            btnProcess.onclick = function() {
                const nominal = inputNominal.value;
                if (!nominal || nominal < 1000) {
                    alert("Minimal donasi Rp 1.000 ya, Ma! 😊");
                    return;
                }

                const originalText = btnProcess.innerHTML;
                btnProcess.innerHTML = '<span class="animate-pulse">Memproses...</span>';
                btnProcess.disabled = true;

                // Minta Token & Order ID dari Laravel
                fetch("{{ route('donasi.pay') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ amount: nominal })
                })
                .then(response => response.json())
                .then(data => {
                    btnProcess.innerHTML = originalText;
                    btnProcess.disabled = false;

                    if(data.error) { alert("Error: " + data.error); return; }

                    // Ambil Order ID untuk dicek nanti
                    const currentOrderId = data.order_id;

                    // --- MUNCULKAN SNAP MIDTRANS ---
                    snap.pay(data.snap_token, {
                        // 1. Sukses Manual (Klik tombol finish)
                        onSuccess: function(result) {
                            stopPolling();
                            openModalSukses(); 
                        },
                        // 2. Pending (QRIS Muncul) -> MULAI CEK OTOMATIS
                        onPending: function(result) {
                            // Jalankan polling buat ngecek status di background
                            startPolling(currentOrderId);
                        },
                        // 3. Error
                        onError: function(result) {
                            stopPolling();
                            alert("Pembayaran gagal.");
                        },
                        // 4. Ditutup
                        onClose: function(){
                            stopPolling();
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    btnProcess.innerHTML = originalText;
                    btnProcess.disabled = false;
                    alert("Gagal koneksi server.");
                });
            };
        }
    });

    // --- FUNGSI POLLING (CEK STATUS SETIAP 5 DETIK) ---
    function startPolling(orderId) {
        // Hapus interval lama biar gak numpuk
        if (pollingInterval) clearInterval(pollingInterval);

        console.log("Mulai memantau status pembayaran...");

        pollingInterval = setInterval(() => {
            fetch(`/donasi/check-status/${orderId}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Status Polling:", data.status);
                    
                    // Jika status LUNAS (Settlement) atau CAPTURE (Kartu Kredit)
                    if (data.status === 'settlement' || data.status === 'capture') {
                        stopPolling(); // Berhenti cek

                        // Coba tutup popup Midtrans secara paksa (opsional)
                        try { snap.hide(); } catch(e) {} 

                        // Munculkan modal sukses
                        openModalSukses();
                    }
                })
                .catch(err => console.error("Gagal polling:", err));
        }, 5000); // Cek setiap 5000ms (5 detik)
    }

    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
            console.log("Polling berhenti.");
        }
    }
</script>