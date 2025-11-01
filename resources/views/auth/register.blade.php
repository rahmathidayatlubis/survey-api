<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Survey</title>
    <link rel="icon" type="image/png" href="{{ asset('img/image.png') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <div class="background-decoration">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-container">
            <!-- Left Side - Branding -->
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

            <!-- Right Side - Register Form -->
            <div class="form-section">
                <div class="form-content">
                    <div class="form-header">
                        <h2>Buat Akun Baru</h2>
                        <p>Lengkapi data diri Anda untuk mendaftar</p>
                    </div>

                    <form action="#" method="POST" class="login-form register-form">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">
                                <span class="label-icon">👤</span>
                                Nama Lengkap
                            </label>
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    placeholder="Masukkan nama lengkap" 
                                    required
                                    autocomplete="name">
                            </div>
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
                                    autocomplete="email">
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
                        </div>

                        <button type="submit" class="btn-login">
                            <span>Daftar Sekarang</span>
                            <span class="btn-arrow">→</span>
                        </button>

                        <div class="register-link">
                            Sudah punya akun? 
                            <a href="/">Login di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>