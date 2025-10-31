<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Survey</title>
    <link rel="icon" type="image/png" href="{{ asset('img/image.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* Gaya dasar untuk error, sesuaikan dengan CSS Anda */
        .error-message {
            color: #e3342f; /* Warna merah Laravel default */
            font-size: 0.85em;
            margin-top: 5px;
        }
        .form-row {
            display: flex;
            gap: 20px; /* Jarak antar kolom, sesuaikan jika perlu */
        }
        .form-row > .form-group {
            flex: 1; /* Agar kolom membagi ruang secara merata */
        }
    </style>
</head>
<body>
    <div class="background-decoration">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-container">
            <div class="brand-section">
                <div class="brand-content">
                    <div class="logo-container">
                        <img src="{{ asset('img/image.png') }}" alt="Logo Sistem Survey" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="logo-placeholder">SS</div>
                    </div>
                    <div class="brand-text">
                        <h1>Sistem Survey</h1>
                        <p class="brand-subtitle">Sistem Survey Pendidikan</p>
                        <div class="brand-tagline">
                            <span class="dot"></span>
                            Portal Registrasi
                        </div>
                    </div>
                    <div class="brand-features">
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Gratis & Mudah</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Data Aman</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="form-content">
                    <div class="form-header">
                        <h2>Buat Akun Baru</h2>
                        <p>Lengkapi data diri Anda untuk mendaftar</p>
                    </div>

                    {{-- Menambahkan penanganan pesan sukses dari controller --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert" style="color: green; margin-bottom: 15px;">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Ubah action ke route POST register Anda --}}
                    <form action="{{ route('register.post') }}" method="POST" class="login-form register-form">
                        @csrf
                        
                        <div class="form-group">
                            <label for="nim">
                                <span class="label-icon">🆔</span>
                                Nomor Induk Mahasiswa (NIM)
                            </label>
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    id="nim" 
                                    name="nim" 
                                    placeholder="Masukkan NIM Anda" 
                                    required
                                    value="{{ old('nim') }}"
                                    autocomplete="off">
                            </div>
                            @error('nim')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama">
                                <span class="label-icon">👤</span>
                                Nama Lengkap
                            </label>
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    id="nama" 
                                    name="nama" {{-- Ubah dari 'name' menjadi 'nama' --}}
                                    placeholder="Masukkan nama lengkap" 
                                    required
                                    value="{{ old('nama') }}"
                                    autocomplete="name">
                            </div>
                            @error('nama')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">
                                <span class="label-icon">📧</span>
                                Email Address
                            </label>
                            <div class="input-group">
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    placeholder="nama@example.com" 
                                    required
                                    value="{{ old('email') }}"
                                    autocomplete="email">
                            </div>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="tanggal_lahir">
                                    <span class="label-icon">📅</span>
                                    Tanggal Lahir
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="date" 
                                        id="tanggal_lahir" 
                                        name="tanggal_lahir" 
                                        required
                                        value="{{ old('tanggal_lahir') }}"
                                        autocomplete="bday">
                                </div>
                                @error('tanggal_lahir')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="jurusan">
                                    <span class="label-icon">📚</span>
                                    Jurusan (Opsional)
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="text" 
                                        id="jurusan" 
                                        name="jurusan" 
                                        placeholder="Contoh: Teknik Informatika" 
                                        value="{{ old('jurusan') }}"
                                        autocomplete="off">
                                </div>
                                @error('jurusan')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="password">
                                    <span class="label-icon">🔒</span>
                                    Password
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="password" 
                                        id="password" 
                                        name="password" 
                                        placeholder="Min. 8 karakter" 
                                        required
                                        autocomplete="new-password">
                                </div>
                                @error('password')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">
                                    <span class="label-icon">🔒</span>
                                    Konfirmasi Password
                                </label>
                                <div class="input-group">
                                    <input 
                                        type="password" 
                                        id="password_confirmation" 
                                        name="password_confirmation" 
                                        placeholder="Ulangi password" 
                                        required
                                        autocomplete="new-password">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" name="terms" required>
                                <span>Saya setuju dengan <a href="/terms" class="link-inline">Syarat & Ketentuan</a> dan <a href="/privacy" class="link-inline">Kebijakan Privasi</a></span>
                            </label>
                            @error('terms')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn-login">
                            <span>Daftar Sekarang</span>
                            <span class="btn-arrow">→</span>
                        </button>

                        <div class="register-link">
                            Sudah punya akun? 
                            {{-- Ganti '/' dengan route login yang sesuai --}}
                            <a href="{{ route('login') }}">Login di sini</a> 
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>