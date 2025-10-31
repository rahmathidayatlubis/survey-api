<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Survey</title>
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
                            Portal Login
                        </div>
                    </div>
                    <div class="brand-features">
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Mudah & Cepat</span>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">✓</span>
                            <span>Aman Terpercaya</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="form-section">
                <div class="form-content">
                    <div class="form-header">
                        <h2>Selamat Datang Kembali!</h2>
                        <p>Masukkan kredensial Anda untuk melanjutkan</p>
                    </div>

                    <form action="{{ route('process.login') }}" method="POST" class="login-form">
                        @csrf                        
                        <div class="form-group">
                            <label for="identifier">
                                <span class="label-icon">📧</span>
                                Email / NIM
                            </label>
                            <div class="input-group">
                                <input 
                                    type="text" 
                                    id="identifier" 
                                    name="identifier" 
                                    placeholder="nama@example.com / 2012345678" 
                                    required
                                    autocomplete="username">
                            </div>
                        </div>

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
                                    placeholder="Masukkan password Anda" 
                                    required
                                    autocomplete="current-password">
                            </div>
                        </div>

                        <div class="form-options">
                            <label class="remember-me">
                                <input type="checkbox" name="remember">
                                <span>Ingat saya</span>
                            </label>
                            <a href="/forgot-password" class="forgot-link">Lupa password?</a>
                        </div>

                        <button type="submit" class="btn-login">
                            <span>Login</span>
                            <span class="btn-arrow">→</span>
                        </button>

                        <div class="register-link">
                            Belum punya akun? 
                            <a href="/register">Daftar Sekarang</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>