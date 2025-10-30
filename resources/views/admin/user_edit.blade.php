@extends('layouts.dashboard')

@section('content')
    {{-- Pesan Error Validation --}}
    @if ($errors->any())
        <div class="alert alert-danger"
            style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong><i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:</strong>
            <ul style="margin: 10px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="dashboard-container">
        {{-- Header Section --}}
        <div class="modern-header"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);">
            <div class="header-content">
                <div>
                    <h2 class="header-title">
                        <i class="fas fa-user-edit header-icon"></i>
                        Edit Data Mahasiswa
                    </h2>
                    <p class="header-subtitle">Perbarui informasi dan data mahasiswa yang sudah terdaftar.</p>
                </div>
                <a href="{{ route('admin.user') }}" class="modern-action-btn" style="text-decoration:none;">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>

        {{-- Main Form Content --}}
        <div class="content-grid-full">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-edit icon-primary"></i>
                        Form Edit Mahasiswa
                    </h3>
                    <span class="badge-info">
                        <i class="fas fa-info-circle"></i>
                        Isi semua field yang wajib diisi
                    </span>
                </div>

                <div class="modern-card-body">
                    <form action="{{ route('admin.user.update', $user->id) }}" method="POST" class="modern-form">
                        @csrf
                        @method('PUT')

                        {{-- Section: Informasi Pribadi --}}
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-user-circle section-icon"></i>
                                <h4 class="section-title">Informasi Pribadi</h4>
                            </div>
                            <div class="section-content">
                                <div class="form-grid">
                                    {{-- Nama Lengkap --}}
                                    <div class="form-group">
                                        <label for="nama" class="form-label">
                                            Nama Lengkap <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-user input-icon"></i>
                                            <input type="text" id="nama" name="nama"
                                                class="form-control @error('nama') is-invalid @enderror"
                                                value="{{ old('nama', $user->nama) }}" placeholder="Masukkan nama lengkap"
                                                required>
                                        </div>
                                        @error('nama')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- NIM --}}
                                    <div class="form-group">
                                        <label for="nim" class="form-label">
                                            NIM <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-id-card input-icon"></i>
                                            <input type="text" id="nim" name="nim"
                                                class="form-control @error('nim') is-invalid @enderror"
                                                value="{{ old('nim', $user->nim) }}" placeholder="Contoh: 2021010001"
                                                required>
                                        </div>
                                        @error('nim')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Email --}}
                                    <div class="form-group">
                                        <label for="email" class="form-label">
                                            Email <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-envelope input-icon"></i>
                                            <input type="email" id="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', $user->email) }}" placeholder="mahasiswa@example.com"
                                                required>
                                        </div>
                                        @error('email')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Tanggal Lahir --}}
                                    {{-- Tanggal Lahir --}}
                                    <div class="form-group">
                                        <label for="tanggal_lahir" class="form-label">
                                            Tanggal Lahir <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-calendar input-icon"></i>
                                            <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                                value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d')) }}"
                                                required>
                                        </div>
                                        @error('tanggal_lahir')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Informasi Akademik --}}
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-graduation-cap section-icon"></i>
                                <h4 class="section-title">Informasi Akademik</h4>
                            </div>
                            <div class="section-content">
                                <div class="form-grid">
                                    {{-- Program Studi --}}
                                    <div class="form-group full-width">
                                        <label for="jurusan" class="form-label">
                                            Program Studi <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-book input-icon"></i>
                                            <select id="jurusan" name="jurusan"
                                                class="form-control @error('jurusan') is-invalid @enderror" required>
                                                <option value="">-- Pilih Program Studi --</option>
                                                <option value="Teknik Informatika"
                                                    {{ old('jurusan', $user->jurusan) == 'Teknik Informatika' ? 'selected' : '' }}>
                                                    Teknik Informatika</option>
                                                <option value="Sistem Informasi"
                                                    {{ old('jurusan', $user->jurusan) == 'Sistem Informasi' ? 'selected' : '' }}>
                                                    Sistem Informasi</option>
                                                <option value="Manajemen Informatika"
                                                    {{ old('jurusan', $user->jurusan) == 'Manajemen Informatika' ? 'selected' : '' }}>
                                                    Manajemen Informatika</option>
                                                <option value="Teknik Komputer"
                                                    {{ old('jurusan', $user->jurusan) == 'Teknik Komputer' ? 'selected' : '' }}>
                                                    Teknik Komputer</option>
                                                <option value="Ilmu Komputer"
                                                    {{ old('jurusan', $user->jurusan) == 'Ilmu Komputer' ? 'selected' : '' }}>
                                                    Ilmu Komputer</option>
                                            </select>
                                        </div>
                                        @error('jurusan')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Role --}}
                                    <div class="form-group">
                                        <label for="role" class="form-label">
                                            Role <span class="required">*</span>
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-user-tag input-icon"></i>
                                            <select id="role" name="role"
                                                class="form-control @error('role') is-invalid @enderror" required>
                                                <option value="">-- Pilih Role --</option>
                                                <option value="mahasiswa"
                                                    {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>
                                                    Mahasiswa</option>
                                                <option value="admin"
                                                    {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                                </option>
                                            </select>
                                        </div>
                                        @error('role')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Keamanan (Optional) --}}
                        <div class="form-section">
                            <div class="section-header">
                                <i class="fas fa-lock section-icon"></i>
                                <h4 class="section-title">Ubah Password (Opsional)</h4>
                            </div>
                            <div class="section-content">
                                <div class="info-box">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Kosongkan jika tidak ingin mengubah password</span>
                                </div>
                                <div class="form-grid">
                                    {{-- Password Baru --}}
                                    <div class="form-group">
                                        <label for="password" class="form-label">
                                            Password Baru
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-key input-icon"></i>
                                            <input type="password" id="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Minimal 8 karakter">
                                        </div>
                                        @error('password')
                                            <span class="error-message">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- Konfirmasi Password --}}
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-label">
                                            Konfirmasi Password
                                        </label>
                                        <div class="input-with-icon">
                                            <i class="fas fa-key input-icon"></i>
                                            <input type="password" id="password_confirmation"
                                                name="password_confirmation" class="form-control"
                                                placeholder="Ulangi password baru">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="form-actions">
                            <a href="{{ route('admin.user') }}" class="btn-cancel">
                                <i class="fas fa-times"></i>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-save"></i>
                                <span>Update Data</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS Styles --}}
    <style>
        /* General Layout */
        .content-grid-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Modern Header */
        .modern-header {
            padding: 30px 40px;
            border-radius: 12px;
            margin-bottom: 30px;
            color: white;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-icon {
            font-size: 2rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .header-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 600px;
            line-height: 1.5;
        }

        .modern-action-btn {
            background-color: white;
            color: #667eea;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .modern-action-btn:hover {
            background-color: #f0f4f8;
            color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Modern Card */
        .modern-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .modern-card-header {
            padding: 24px 30px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .modern-card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-primary {
            color: #667eea;
            font-size: 1.5rem;
        }

        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .modern-card-body {
            padding: 30px;
        }

        /* Form Sections */
        .form-section {
            margin-bottom: 35px;
        }

        .form-section:last-child {
            margin-bottom: 0;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #e2e8f0;
        }

        .section-icon {
            color: #667eea;
            font-size: 1.3rem;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #334155;
            margin: 0;
        }

        .section-content {
            padding-left: 10px;
        }

        /* Form Grid */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .required {
            color: #ef4444;
            font-weight: 700;
        }

        /* Input with Icon */
        .input-with-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #334155;
            transition: all 0.3s ease;
            outline: none;
            background-color: #ffffff;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2394a3b8' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 45px;
        }

        .error-message {
            font-size: 0.85rem;
            color: #ef4444;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .error-message::before {
            content: "⚠";
        }

        /* Info Box */
        .info-box {
            background-color: #f0f9ff;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #1e40af;
        }

        .info-box i {
            font-size: 1.1rem;
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 35px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }

        .btn-cancel,
        .btn-submit {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-cancel {
            background-color: #f1f5f9;
            color: #64748b;
        }

        .btn-cancel:hover {
            background-color: #e2e8f0;
            color: #475569;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .modern-header {
                padding: 25px 25px;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .header-title {
                font-size: 1.8rem;
            }

            .modern-action-btn {
                width: 100%;
                justify-content: center;
            }

            .modern-card-body {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-cancel,
            .btn-submit {
                width: 100%;
                justify-content: center;
            }

            .modern-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .badge-info {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 1.5rem;
            }

            .section-title {
                font-size: 1rem;
            }

            .form-control {
                font-size: 0.9rem;
                padding: 10px 12px 10px 40px;
            }
        }
    </style>
@endsection
