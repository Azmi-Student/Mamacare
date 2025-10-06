<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Reservasi</title>
    <link rel="stylesheet" href="{{ asset('assets/css/dokter/dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animation.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdown-menu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Menyembunyikan dropdown jika klik di luar area dropdown
        window.onclick = function(event) {
            if (!event.target.matches('.profile-pic')) {
                const dropdown = document.getElementById('dropdown-menu');
                if (dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';
                }
            }
        }

        // Fungsi untuk toggle sidebar (buka/tutup)
        function toggleSidebar() {
            const sidebar = document.querySelector('.left-panel');
            sidebar.classList.toggle('open');
        }

        // Menutup sidebar ketika klik di luar sidebar dan tombol hamburger
        window.addEventListener('click', function(e) {
            const sidebar = document.querySelector('.left-panel');
            const openBtn = document.querySelector('.sidebar-toggle');
            if (!sidebar.contains(e.target) && !openBtn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</head>

<body>
    <div id="splash-screen">
        <img src="{{ asset('assets/images/logo.png') }}" alt="Splash Logo" class="splash-logo" />
    </div>

    <div class="main-wrapper">

        <div class="left-panel">
            <img src="{{ asset('assets/images/logo-mamacare-pink.png') }}" alt="MamaCare Logo" class="logo" />

            <div class="sidebar-item">
                <a href="{{ route('dokter.dashboard') }}" class="sidebar-link">
                    <img src="{{ asset('assets/images/icon-home.png') }}" alt="Home Icon" class="sidebar-icon" />
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </div>

            <div class="sidebar-item active">
                <img src="{{ asset('assets/images/icon-pesan-active.png') }}" alt="Tanya Dokter Icon"
                    class="sidebar-icon" />
                <span class="sidebar-text">Daftar<br>Reservasi</span>
            </div>

            <div class="sidebar-item">
                <a href="{{ route('dokter.pengaturan') }}" class="sidebar-link"><img
                        src="{{ asset('assets/images/icon-pengaturan.png') }}" alt="Pengaturan Icon"
                        class="sidebar-icon" />
                    <span class="sidebar-text">Pengaturan</span> </a>


            </div>
        </div>

        <div class="right-panel">
            <!-- Sidebar Toggle Button (hanya untuk mobile) -->
            <button class="sidebar-toggle" onclick="toggleSidebar()">
                <span></span><span></span><span></span>
            </button>
            <div class="search-bar">
                <div class="search-left">
                    <img src="{{ asset('assets/images/Icon-search.png') }}" alt="Search" class="icon" />
                    <input type="text" placeholder="Cari berdasarkan judul..." />
                </div>
                <div class="search-right">
                    <img src="{{ asset('assets/images/icon-bookmark.png') }}" class="icon" />
                    <img src="{{ asset('assets/images/icon-bell.png') }}" class="icon" />
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>

                    <div class="profile-container">
                        <img src="{{ Auth::check() && Auth::user()->avatar ? Auth::user()->avatar : asset('assets/images/profile-pic.png') }}"
                            alt="Profile" class="profile-pic" onclick="toggleDropdown()" style="cursor: pointer;" />

                        <!-- Dropdown Menu -->
                        <div id="dropdown-menu" class="dropdown-menu">
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        </div>
                    </div>


                </div>
            </div>

            <div class="container mt-5">
                <h2>Daftar Reservasi Pasien</h2>

                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                {{-- ===== DESKTOP: TABEL BIASA ===== --}}
<div class="rsv-desktop table-responsive">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Pasien</th>
        <th>Jenis Periksa</th>
        <th>Jadwal</th>
        <th>Status</th>
        <th>Aksi</th>
        <th>Hasil Checkup</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($reservasis as $index => $reservasi)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $reservasi->nama_pasien }}</td>
        <td>{{ $reservasi->jenis_periksa }}</td>
        <td>{{ $reservasi->jadwal }}</td>
        <td>
          <span class="rsv-badge rsv-badge-{{ Str::slug($reservasi->status,'-') }}">{{ $reservasi->status }}</span>
        </td>
        <td>
          <form action="{{ route('dokter.updateStatus', $reservasi->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('POST')
            <select name="status" class="form-control" onchange="this.form.submit()" {{ $reservasi->status == 'Selesai' ? 'disabled' : '' }}>
              <option value="Belum Diajukan" {{ $reservasi->status == 'Belum Diajukan' ? 'selected' : '' }}>Belum Diajukan</option>
              <option value="Disetujui" {{ $reservasi->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
              <option value="Selesai" {{ $reservasi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
          </form>
          <form action="{{ route('dokter.reservasi.destroy', $reservasi->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</button>
          </form>
        </td>
        <td id="hasil-checkup-{{ $reservasi->id }}">
          @if ($reservasi->status == 'Disetujui' && !$reservasi->hasil_checkup)
            <button class="btn btn-success" onclick="showCheckupPopup({{ $reservasi->id }})">Input Hasil</button>
          @elseif ($reservasi->status == 'Selesai' && $reservasi->hasil_checkup)
            <button class="btn btn-warning" onclick="showCheckupPopup({{ $reservasi->id }})">Edit Hasil</button>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

{{-- ===== MOBILE: KARTU PER-RESERVASI ===== --}}
<div class="rsv-mobile">
  @foreach ($reservasis as $index => $reservasi)
    <div class="rsv-card">
      <div class="rsv-row"><span>No</span><strong>{{ $index + 1 }}</strong></div>
      <div class="rsv-row"><span>Nama Pasien</span><strong>{{ $reservasi->nama_pasien }}</strong></div>
      <div class="rsv-row"><span>Jenis Periksa</span><strong>{{ $reservasi->jenis_periksa }}</strong></div>
      <div class="rsv-row"><span>Jadwal</span><strong>{{ $reservasi->jadwal }}</strong></div>
      <div class="rsv-row"><span>Status</span>
        <strong><span class="rsv-badge rsv-badge-{{ Str::slug($reservasi->status,'-') }}">{{ $reservasi->status }}</span></strong>
      </div>

      <div class="rsv-actions">
        <form action="{{ route('dokter.updateStatus', $reservasi->id) }}" method="POST">
          @csrf
          @method('POST')
          <label class="rsv-label">Ubah Status</label>
          <select name="status" class="form-control" onchange="this.form.submit()" {{ $reservasi->status == 'Selesai' ? 'disabled' : '' }}>
            <option value="Belum Diajukan" {{ $reservasi->status == 'Belum Diajukan' ? 'selected' : '' }}>Belum Diajukan</option>
            <option value="Disetujui" {{ $reservasi->status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="Selesai" {{ $reservasi->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
          </select>
        </form>

        <form action="{{ route('dokter.reservasi.destroy', $reservasi->id) }}" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Yakin ingin menghapus data ini?')">Delete</button>
        </form>

        @if ($reservasi->status == 'Disetujui' && !$reservasi->hasil_checkup)
          <button class="btn btn-success w-100" onclick="showCheckupPopup({{ $reservasi->id }})">Input Hasil</button>
        @elseif ($reservasi->status == 'Selesai' && $reservasi->hasil_checkup)
          <button class="btn btn-warning w-100" onclick="showCheckupPopup({{ $reservasi->id }})">Edit Hasil</button>
        @endif
      </div>
    </div>
  @endforeach
</div>
            </div>








        </div>

        <script src="{{ asset('assets/js/dokter/daftar-reservasi.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>


</html>