document.addEventListener("DOMContentLoaded", function () {
    // VARIABEL UTAMA
    let currentPatientId = null; // Ganti nama variabel biar gak bingung
    const chatBody = document.getElementById("chat-body");
    const chatPanel = document.getElementById("chat-panel");
    const messageInput = document.getElementById("chat-message");
    
    // Ambil ID DOKTER yang login
    const myId = document.querySelector('meta[name="user-id"]').getAttribute('content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // 1. LOGIC KLIK PASIEN (LIST)
    document.querySelectorAll(".doctor-item").forEach(function (item) {
        item.addEventListener("click", function () {
            // UI Active State
            document.querySelectorAll(".doctor-item").forEach(i => i.classList.remove("active"));
            this.classList.add("active");

            // UI Buka Chat Panel
            const patientName = this.querySelector(".doctor-name").textContent;
            document.getElementById("patient-name").textContent = "Chat dengan " + patientName;
            chatPanel.style.display = "flex";

            // Simpan ID Pasien & Load Chat
            currentPatientId = this.getAttribute("data-id");
            loadMessages(currentPatientId);

            // Fokus Input
            setTimeout(() => { messageInput.focus(); }, 300);
        });
    });

    // 2. LOAD MESSAGE (Route Dokter)
    function loadMessages(patientId) {
        chatBody.innerHTML = '<div style="text-align:center; padding:20px; color:#999;">Memuat percakapan...</div>';

        // Fetch ke route khusus dokter
        fetch(`/dokter/chat/get/${patientId}`)
            .then(response => response.json())
            .then(data => {
                chatBody.innerHTML = ""; 

                if (data.length === 0) {
                    chatBody.innerHTML = '<div style="text-align:center; padding:20px; color:#bbb;">Belum ada riwayat pesan.</div>';
                    return;
                }

                data.forEach(chat => {
                    appendMessageToUI(chat.message, chat.sender_id);
                });

                scrollToBottom();
            })
            .catch(error => console.error('Error:', error));
    }

    // 3. KIRIM PESAN (Route Dokter)
    function sendMessage() {
        const message = messageInput.value.trim();

        if (!currentPatientId) return;

        if (message) {
            // Tampilkan Langsung
            appendMessageToUI(message, myId);
            scrollToBottom();
            messageInput.value = "";

            // POST ke route khusus dokter
            fetch(`/dokter/chat/store/${currentPatientId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ message: message })
            })
            .then(res => res.json())
            .then(data => console.log('Terkirim', data))
            .catch(err => console.error(err));
        }
    }

    // 4. HELPER FUNCTIONS
    function appendMessageToUI(message, senderId) {
        const newMessage = document.createElement("div");
        newMessage.classList.add("chat-message");
        newMessage.textContent = message;

        // Logic Bubble:
        // Jika sender_id == myId (Saya Dokter) -> Bubble Kanan (.is-me)
        // Jika sender_id != myId (Itu Pasien) -> Bubble Kiri (.is-doctor) *Kita pinjam class is-doctor punya user biar warnanya putih
        if (senderId == myId) {
            newMessage.classList.add("is-me");
        } else {
            newMessage.classList.add("is-doctor"); 
        }

        chatBody.appendChild(newMessage);
    }

    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // EVENT LISTENERS
    document.getElementById("send-message").addEventListener("click", sendMessage);
    messageInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") sendMessage();
    });

    document.getElementById("close-chat-btn").addEventListener("click", function () {
        chatPanel.style.display = "none";
        document.querySelectorAll(".doctor-item").forEach(i => i.classList.remove("active"));
        currentPatientId = null;
    });
});