document.addEventListener("DOMContentLoaded", function () {
    // --- KONFIGURASI & VARIABEL ---
    let currentDoctorId = null;
    const chatBody = document.getElementById("chat-body");
    const chatPanel = document.getElementById("chat-panel");
    const messageInput = document.getElementById("chat-message");
    
    // Ambil ID User yang login dari Meta Tag (PENTING untuk posisi bubble)
    const myId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ============================================================
    // 1. LOGIKA MEMILIH DOKTER (Buka Chat & Load History)
    // ============================================================
    document.querySelectorAll(".doctor-item").forEach(function (doctorItem) {
        doctorItem.addEventListener("click", function () {
            // UI: Hapus kelas active dari dokter sebelumnya
            document.querySelectorAll(".doctor-item").forEach(item => item.classList.remove("active"));
            
            // UI: Tambah kelas active ke dokter yang dipilih
            this.classList.add("active");

            // UI: Update Header Chat
            const doctorName = this.querySelector(".doctor-name").textContent;
            document.getElementById("doctor-name").textContent = "Chat dengan " + doctorName;
            
            // UI: Tampilkan Panel (dengan animasi CSS)
            chatPanel.style.display = "flex";

            // LOGIC: Simpan ID Dokter & Load Pesan
            currentDoctorId = this.getAttribute("data-id");
            loadMessages(currentDoctorId);

            // Fokus ke input agar user bisa langsung ngetik
            // Timeout kecil agar transisi CSS selesai dulu (khusus HP)
            setTimeout(() => {
                messageInput.focus();
            }, 500);
        });
    });

    // ============================================================
    // 2. FUNGSI LOAD MESSAGES (Ambil Riwayat dari Server)
    // ============================================================
    function loadMessages(doctorId) {
        // Tampilkan indikator loading (opsional)
        chatBody.innerHTML = '<div style="text-align:center; padding:20px; color:#999; font-size:12px;">Memuat percakapan...</div>';

        fetch(`/chat/get/${doctorId}`)
            .then(response => response.json())
            .then(data => {
                chatBody.innerHTML = ""; // Bersihkan loading

                if (data.length === 0) {
                    chatBody.innerHTML = '<div style="text-align:center; padding:20px; color:#bbb; font-size:12px;">Belum ada percakapan. Sapa dokter sekarang! 👋</div>';
                    return;
                }

                // Looping data pesan
                data.forEach(chat => {
                    appendMessageToUI(chat.message, chat.sender_id);
                });

                scrollToBottom();
            })
            .catch(error => {
                console.error('Error:', error);
                chatBody.innerHTML = '<div style="text-align:center; color:red;">Gagal memuat pesan.</div>';
            });
    }

    // ============================================================
    // 3. LOGIKA KIRIM PESAN (Send Message)
    // ============================================================
    
    // Fungsi Kirim
    function sendMessage() {
        const message = messageInput.value.trim();

        if (!currentDoctorId) {
            Swal.fire('Ups!', 'Pilih dokter terlebih dahulu', 'warning');
            return;
        }

        if (message) {
            // 1. Tampilkan pesan di UI secara langsung (Optimistic UI) 
            // Agar user merasa aplikasi sangat cepat
            appendMessageToUI(message, myId); // myId = Pesan Saya
            scrollToBottom();
            
            // Kosongkan input
            messageInput.value = "";

            // 2. Kirim ke Database via AJAX
            fetch(`/chat/store/${currentDoctorId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Terkirim:', data);
                // Jika mau update status centang/jam, bisa di sini
            })
            .catch(error => {
                console.error('Error:', error);
                // Opsional: Tampilkan notifikasi gagal
            });
        }
    }

    // Event Listener: Klik Tombol Kirim
    document.getElementById("send-message").addEventListener("click", sendMessage);

    // Event Listener: Tekan ENTER di Input
    messageInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            sendMessage();
        }
    });

    // ============================================================
    // 4. HELPER FUNCTIONS (Fungsi Pembantu)
    // ============================================================

    // Fungsi Render Bubble ke HTML
    function appendMessageToUI(message, senderId) {
        const newMessage = document.createElement("div");
        newMessage.classList.add("chat-message");
        newMessage.textContent = message;

        // LOGIKA KUNCI: 
        // Jika sender_id == myId (User Login) -> Bubble Kanan (Pink)
        // Jika sender_id != myId -> Bubble Kiri (Putih)
        // Pastikan konversi tipe data sama (String vs Int) dengan ==
        if (senderId == myId) {
            newMessage.classList.add("is-me");
        } else {
            newMessage.classList.add("is-doctor");
        }

        chatBody.appendChild(newMessage);
    }

    // Fungsi Auto Scroll ke Bawah
    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // ============================================================
    // 5. TUTUP CHAT PANEL
    // ============================================================
    document.getElementById("close-chat-btn").addEventListener("click", function () {
        chatPanel.style.display = "none";
        document.querySelectorAll(".doctor-item").forEach(item => item.classList.remove("active"));
        currentDoctorId = null; 
    });
});