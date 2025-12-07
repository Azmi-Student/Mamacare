<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    
    <title>Chat Pasien - Dokter Area</title>

    <link rel="stylesheet" href="{{ asset('assets/css/animation.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/tanya-dokter.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>

<body>
    <div id="splash-screen">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Splash Logo" class="splash-logo" />
    </div>

    <div class="main-wrapper">

        <div class="left-panel">
            <img src="{{ asset('assets/images/logo-mamacare-pink.png') }}" alt="MamaCare Logo" class="logo" />

            <a href="{{ route('dokter.dashboard') }}" class="sidebar-link">
                <div class="sidebar-item">
                    <img src="{{ asset('assets/images/icon-home.png') }}" alt="Dashboard Icon" class="sidebar-icon" />
                    <span class="sidebar-text">Dashboard</span>
                </div>
            </a>

            <a href="{{ route('dokter.daftar-reservasi') }}" class="sidebar-link">
                <div class="sidebar-item">
                    <img src="{{ asset('assets/images/icon-pesan.png') }}" alt="Reservasi Icon" class="sidebar-icon" />
                    <span class="sidebar-text">Daftar Reservasi</span>
                </div>
            </a>

            <div class="sidebar-item active">
                <img src="{{ asset('assets/images/icon-pesan-active.png') }}" alt="Chat Icon" class="sidebar-icon" />
                <span class="sidebar-text">Chat Pasien</span>
            </div>

            <a href="{{ route('dokter.pengaturan') }}" class="sidebar-link">
                <div class="sidebar-item">
                    <img src="{{ asset('assets/images/icon-pengaturan.png') }}" alt="Pengaturan Icon" class="sidebar-icon" />
                    <span class="sidebar-text">Pengaturan</span>
                </div>
            </a>
        </div>

        <div class="right-panel">
            <div class="tanya-dokter-box">
                <div class="tanya-dokter-header">
                    <h2 class="tanya-dokter-title">Daftar Pasien</h2>
                    <button class="btn-kembali" onclick="window.location.href='{{ route('dokter.dashboard') }}'">Kembali</button>
                </div>
                <div class="tanya-dokter-line"></div>

                <div class="doctor-list">
                    @forelse ($pasiens as $pasien)
                        <div class="doctor-item" data-id="{{ $pasien->id }}">
                            <img src="{{ $pasien->avatar ?? asset('assets/images/profile-pic.png') }}" 
                                 alt="Pasien {{ $pasien->name }}"
                                 class="doctor-avatar" />
                            
                            <div class="doctor-info">
                                <p class="doctor-name">{{ $pasien->name }}</p>
                                <p class="doctor-description">Riwayat Chat</p>
                            </div>
                            
                            <span class="doctor-time">Pasien</span>
                        </div>
                    @empty
                        <div style="text-align: center; color: #999; margin-top: 50px;">
                            Belum ada pasien yang memulai chat.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="chat-panel" id="chat-panel">
                <div class="chat-header">
                    <p id="patient-name">Chat dengan [Nama Pasien]</p>
                    <button class="close-chat-btn" id="close-chat-btn">X</button>
                </div>
                <div class="chat-body" id="chat-body">
                    </div>
                <div class="chat-input">
                    <input type="text" id="chat-message" placeholder="Ketik balasan...">
                    <button id="send-message">Kirim</button>
                </div>
            </div>

        </div>
    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/dokter/chat-dokter.js') }}"></script>

</html>