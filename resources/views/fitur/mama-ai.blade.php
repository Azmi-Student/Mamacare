<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tanya Mama.AI</title>

    <link rel="stylesheet" href="{{ asset('assets/css/animation.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />


    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Custom Style dikit buat AI */
        .ai-header-gradient {
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            /* Warna agak beda (Ungu/Pink) biar kerasa AI */
        }

        .ai-avatar {
            background: #fff;
            padding: 2px;
            box-shadow: 0 0 10px rgba(161, 140, 209, 0.5);
        }

        /* Karena ini AI, chat panel kita bikin default muncul/full width */
        .tanya-dokter-box {
            display: flex;
            flex-direction: row;
            overflow: hidden;
            padding: 0;
            background: #f9f9f9;
        }

        /* Sidebar Kiri (Riwayat Chat) */
        .ai-sidebar {
            width: 300px;
            background: #fff;
            border-right: 1px solid #eee;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .new-chat-btn {
            background: linear-gradient(135deg, #a18cd1, #fbc2eb);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .new-chat-btn:hover {
            transform: scale(1.02);
        }

        /* Area Chat Utama */
        .ai-chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 85vh;
            /* Tinggi fix */
            position: relative;
        }

        .ai-chat-body {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .ai-chat-input-area {
            padding: 20px;
            background: white;
            border-top: 1px solid #eee;
            display: flex;
            gap: 10px;
        }

        /* Override display chat panel bawaan css lama */
        .chat-panel {
            display: none;
            /* Kita gak pake floating panel, tapi full page */
        }

        /* --- TAMBAHAN RESPONSIVE --- */
        @media screen and (max-width: 768px) {

            /* 1. Sembunyikan Sidebar Menu Utama (Kiri) agar fokus ke Chat */
            .left-panel {
                display: none;
            }

            /* 2. Reset Layout Wrapper */
            .main-wrapper {
                flex-direction: column;
                height: 100vh;
            }

            .right-panel {
                width: 100%;
                height: 100%;
                padding: 0;
                /* Hapus padding bawaan container */
            }

            /* 3. Atur Ulang Box Chat AI */
            .tanya-dokter-box {
                flex-direction: column;
                /* Stack ke bawah */
                height: 100vh;
                /* Full layar HP */
                border-radius: 0;
                /* Hilangkan rounded corner biar full */
            }

            /* 4. Sembunyikan Sidebar Riwayat Chat (Bisa dibikin tombol toggle nanti) */
            .ai-sidebar {
                display: none;
            }

            /* 5. Maksimalkan Area Chat */
            .ai-chat-area {
                width: 100%;
                height: 100%;
            }

            /* 6. Sesuaikan Header & Padding */
            .chat-header {
                padding: 15px;
            }

            .ai-avatar {
                width: 30px;
                height: 30px;
            }

            .ai-chat-body {
                padding: 15px;
                /* Kurangi padding biar chat bubble lebih lega */
            }

            /* 7. Input Area fix di bawah */
            .ai-chat-input-area {
                padding: 10px;
                position: sticky;
                bottom: 0;
            }

            /* 8. Ukuran Font Mobile */
            .chat-message {
                font-size: 14px;
                max-width: 85%;
                /* Biar bubble tidak terlalu lebar */
            }
        }

        /* Style Tombol Kembali */
        .btn-kembali {
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            /* Warna teks putih agar kontras dengan header ungu */
            text-decoration: none;
            margin-right: 15px;
            /* Jarak antara tombol dan avatar */
            padding: 8px 12px;
            border-radius: 20px;
            transition: background 0.2s;
            background: rgba(255, 255, 255, 0.1);
            /* Sedikit transparan */
        }

        .btn-kembali:hover {
            background: rgba(255, 255, 255, 0.2);
            /* Efek saat mouse lewat */
        }

        /* Opsional: Di HP, tulisan "Kembali" bisa dihilangkan biar hemat tempat (cuma ikon panah) */
        @media screen and (max-width: 768px) {
            .text-kembali {
                display: none;
            }

            .btn-kembali {
                padding: 8px;
                /* Kecilin padding di HP */
            }
        }
    </style>
</head>

<body>
    <div id="splash-screen">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Splash Logo" class="splash-logo" />
    </div>

    <div class="main-wrapper">

        <div class="left-panel" id="app-sidebar">
            <img src="{{ asset('assets/images/logo-mamacare-pink.png') }}" alt="MamaCare Logo" class="logo" />

            <div class="sidebar-item active">
                <img src="{{ asset('assets/images/icon-home-active.png') }}" alt="Home Icon" class="sidebar-icon" />
                <span class="sidebar-text">Home</span>

            </div>

            <a href="{{ url('/tanya-dokter') }}" class="sidebar-link">
                <div class="sidebar-item">
                    <img src="{{ asset('assets/images/icon-pesan.png') }}" alt="Tanya Dokter Icon"
                        class="sidebar-icon" />
                    <span class="sidebar-text">Tanya Dokter</span>
                </div>
            </a>


            <a href="{{ url('/pengaturan') }}" class="sidebar-link">
                <div class="sidebar-item">
                    <img src="{{ asset('assets/images/icon-pengaturan.png') }}" alt="Pengaturan Icon"
                        class="sidebar-icon" />
                    <span class="sidebar-text">Pengaturan</span>
                </div>
            </a>


            <img src="{{ asset('assets/images/logo-donasi.png') }}" alt="MamaCare Logo" class="logo" />
            <!-- Tombol Donasi -->
            <button class="donasi-btn">Berikan Donasi</button>

        </div>

        <div class="right-panel">

            <div class="tanya-dokter-box">

                <div class="ai-sidebar">
                    <button class="new-chat-btn" onclick="location.reload()">
                        <span>+</span> Topik Baru
                    </button>

                    <p style="color:#999; font-size:12px; margin-bottom:10px;">RIWAYAT PERCAKAPAN</p>

                    <div
                        style="padding:10px; background:#f5f5f5; border-radius:8px; margin-bottom:5px; font-size:13px; cursor:pointer;">
                        Makanan sehat trimester 1...
                    </div>
                    <div
                        style="padding:10px; border-radius:8px; margin-bottom:5px; font-size:13px; cursor:pointer; color:#666;">
                        Cara mengatasi mual pagi...
                    </div>
                </div>

                <div class="ai-chat-area">
                    <div class="chat-header ai-header-gradient" style="border-radius: 0;">
                        <div style="display:flex; align-items:center; gap:10px;">

                            <a href="{{ url('/') }}" class="btn-kembali">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                <span class="text-kembali">Kembali</span>
                            </a>
                            <img src="{{ asset('assets/images/fitur-ai.png') }}" ...>

                            <div>
                                <p style="margin:0; font-weight:600; color:white;">Mama.AI Assistant</p>
                                <span style="font-size:11px; opacity:0.9; color:white;">Selalu ada 24/7 untuk
                                    Bunda</span>
                            </div>

                        </div>
                    </div>

                    <div class="ai-chat-body" id="chat-body">
                        <div class="chat-message is-doctor" style="max-width:80%;">
                            Halo Bunda {{ Auth::user()->name }}! 👋 <br>
                            Saya Mama.AI, asisten pintar bunda. Ada yang ingin ditanyakan seputar kehamilan atau
                            kesehatan bunda hari ini?
                        </div>
                    </div>

                    <div class="ai-chat-input-area">
                        <input type="text" id="chat-message" placeholder="Tanya sesuatu ke Mama.AI..."
                            style="flex:1; padding:12px 20px; border-radius:30px; border:1px solid #ddd; outline:none;">
                        <button id="send-message"
                            style="background: linear-gradient(135deg, #a18cd1, #fbc2eb); color:white; border:none; padding:10px 25px; border-radius:30px; cursor:pointer; font-weight:600;">
                            Kirim
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const chatBody = document.getElementById('chat-body');
        const input = document.getElementById('chat-message');
        const sendBtn = document.getElementById('send-message');
        
        // Ambil CSRF Token dari meta tag (PENTING BUAT LARAVEL)
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function appendMessage(text, isMe) {
            const div = document.createElement('div');
            div.className = `chat-message ${isMe ? 'is-me' : 'is-doctor'}`;
            
            if (isMe) {
                div.style.background = 'linear-gradient(135deg, #a18cd1, #fbc2eb)';
                div.style.color = 'white';
                div.style.alignSelf = 'flex-end';
            } else {
                div.style.background = 'white';
                div.style.color = '#333';
                div.style.alignSelf = 'flex-start';
                // Biar format list/paragraf dari AI rapi (convert \n jadi <br>)
                div.style.whiteSpace = 'pre-line'; 
            }

            div.innerHTML = text;
            chatBody.appendChild(div);
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        async function sendMessageToAI() {
            const text = input.value;
            if (!text) return;

            // 1. Tampilkan pesan user
            appendMessage(text, true);
            input.value = '';

            // 2. Tampilkan Loading (Mama.AI sedang mengetik...)
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'chat-message is-doctor';
            loadingDiv.style.background = 'transparent';
            loadingDiv.style.color = '#999';
            loadingDiv.style.fontStyle = 'italic';
            loadingDiv.innerText = 'Mama.AI sedang berpikir...';
            chatBody.appendChild(loadingDiv);
            chatBody.scrollTop = chatBody.scrollHeight;

            try {
                // 3. Kirim ke Laravel Backend
                const response = await fetch("{{ route('chat.ai') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message: text })
                });

                const data = await response.json();

                // 4. Hapus loading, tampilkan jawaban AI
                chatBody.removeChild(loadingDiv);
                
                if (data.reply) {
                    appendMessage(data.reply, false);
                } else {
                    appendMessage("Maaf Bunda, saya tidak mengerti.", false);
                }

            } catch (error) {
                chatBody.removeChild(loadingDiv);
                appendMessage("Maaf Bunda, ada gangguan koneksi internet.", false);
                console.error('Error:', error);
            }
        }

        sendBtn.addEventListener('click', sendMessageToAI);

        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessageToAI();
        });
    </script>
</body>

</html>
