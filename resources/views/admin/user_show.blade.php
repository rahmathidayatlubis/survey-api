@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-container">
        {{-- Header Section --}}
        <div class="modern-header"
            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);">
            <div class="header-content">
                <div>
                    <h2 class="header-title">
                        <i class="fas fa-user-circle header-icon"></i>
                        Detail Mahasiswa
                    </h2>
                    <p class="header-subtitle">Informasi lengkap dan riwayat akademik mahasiswa.</p>
                </div>
                <a href="{{ route('admin.user') }}" class="modern-action-btn" style="text-decoration:none;">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Daftar</span>
                </a>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="content-wrapper">
            {{-- Profile Card (Left Side) --}}
            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar-wrapper">
                        <div class="avatar-circle">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="status-indicator active"></div>
                    </div>
                    <h3 class="profile-name">{{ $user->nama }}</h3>
                    <p class="profile-role">{{ ucfirst($user->role) }}</p>
                    <span class="modern-badge success">
                        <i class="fas fa-check-circle"></i>
                        Status Aktif
                    </span>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <i class="fas fa-graduation-cap stat-icon"></i>
                        <div class="stat-content">
                            <span class="stat-label">Program Studi</span>
                            <span class="stat-value">{{ $user->jurusan }}</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-id-card stat-icon"></i>
                        <div class="stat-content">
                            <span class="stat-label">NIM</span>
                            <span class="stat-value">{{ $user->nim }}</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-calendar-alt stat-icon"></i>
                        <div class="stat-content">
                            <span class="stat-label">Terdaftar Sejak</span>
                            <span class="stat-value">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('admin.user.edit', $user->id) }}" class="action-btn edit-btn">
                        <i class="fas fa-edit"></i>
                        <span>Edit Data</span>
                    </a>
                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete-btn">
                            <i class="fas fa-trash-alt"></i>
                            <span>Hapus Data</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Detail Information Cards (Right Side) --}}
            <div class="details-section">
                {{-- Personal Information Card --}}
                <div class="detail-card">
                    <div class="card-header">
                        <div class="card-title-wrapper">
                            <i class="fas fa-user-circle card-icon"></i>
                            <h4 class="card-title">Informasi Pribadi</h4>
                        </div>
                        <span class="card-badge">Personal Data</span>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user"></i>
                                    <span>Nama Lengkap</span>
                                </div>
                                <div class="info-value">{{ $user->nama }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-id-badge"></i>
                                    <span>NIM</span>
                                </div>
                                <div class="info-value">{{ $user->nim }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-envelope"></i>
                                    <span>Email</span>
                                </div>
                                <div class="info-value email-value">{{ $user->email }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-birthday-cake"></i>
                                    <span>Tanggal Lahir</span>
                                </div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-calendar-check"></i>
                                    <span>Usia</span>
                                </div>
                                <div class="info-value">{{ \Carbon\Carbon::parse($user->tanggal_lahir)->age }} Tahun</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Academic Information Card --}}
                <div class="detail-card">
                    <div class="card-header">
                        <div class="card-title-wrapper">
                            <i class="fas fa-graduation-cap card-icon"></i>
                            <h4 class="card-title">Informasi Akademik</h4>
                        </div>
                        <span class="card-badge academic">Academic Data</span>
                    </div>
                    <div class="card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-book"></i>
                                    <span>Program Studi</span>
                                </div>
                                <div class="info-value">{{ $user->jurusan }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-user-tag"></i>
                                    <span>Role/Hak Akses</span>
                                </div>
                                <div class="info-value">
                                    <span class="role-badge {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-clock"></i>
                                    <span>Waktu Pendaftaran</span>
                                </div>
                                <div class="info-value">{{ $user->created_at->format('d F Y, H:i') }} WIB</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Terakhir Diupdate</span>
                                </div>
                                <div class="info-value">{{ $user->updated_at->format('d F Y, H:i') }} WIB</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Additional Info Card --}}
                <div class="detail-card highlight-card">
                    <div class="card-header">
                        <div class="card-title-wrapper">
                            <i class="fas fa-info-circle card-icon"></i>
                            <h4 class="card-title">Informasi Tambahan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="highlight-info">
                            <div class="highlight-item">
                                <i class="fas fa-shield-alt"></i>
                                <div class="highlight-content">
                                    <h5>Status Akun</h5>
                                    <p>Akun mahasiswa ini dalam status <strong>aktif</strong> dan dapat mengakses sistem.</p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-database"></i>
                                <div class="highlight-content">
                                    <h5>ID Database</h5>
                                    <p>ID unik dalam database: <strong>#{{ $user->id }}</strong></p>
                                </div>
                            </div>
                            <div class="highlight-item">
                                <i class="fas fa-history"></i>
                                <div class="highlight-content">
                                    <h5>Riwayat Aktivitas</h5>
                                    <p>Data terakhir dimodifikasi {{ $user->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS Styles --}}
    <style>
        /* General Layout */
        .content-wrapper {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 30px;
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

        /* Profile Card (Left Side) */
        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .avatar-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 20px;
        }

        .avatar-circle {
            width: 120px;
            height: 120px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            color: #667eea;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .status-indicator {
            position: absolute;
            bottom: 8px;
            right: 8px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .status-indicator.active {
            background-color: #10b981;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-role {
            font-size: 0.95rem;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .modern-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .modern-badge.success {
            background-color: rgba(255, 255, 255, 0.25);
            color: white;
        }

        /* Profile Stats */
        .profile-stats {
            padding: 25px 30px;
            border-bottom: 1px solid #edf2f7;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .stat-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 500;
        }

        .stat-value {
            font-size: 1rem;
            color: #334155;
            font-weight: 700;
        }

        /* Profile Actions */
        .profile-actions {
            padding: 25px 30px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-btn {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        .edit-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .delete-btn {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .delete-btn:hover {
            background-color: #dc2626;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }

        /* Detail Cards (Right Side) */
        .details-section {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .detail-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-icon {
            color: #667eea;
            font-size: 1.4rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #334155;
            margin: 0;
        }

        .card-badge {
            background-color: #eff6ff;
            color: #1e40af;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card-badge.academic {
            background-color: #f0fdf4;
            color: #166534;
        }

        .card-body {
            padding: 25px;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .info-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .info-label i {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .info-value {
            font-size: 1.05rem;
            color: #1e293b;
            font-weight: 600;
            padding: 12px 15px;
            background-color: #f8fafc;
            border-radius: 8px;
            border-left: 3px solid #667eea;
        }

        .email-value {
            word-break: break-all;
        }

        .role-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .role-badge.mahasiswa {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .role-badge.admin {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Highlight Card */
        .highlight-card {
            border: 2px solid #e0e7ff;
            background: linear-gradient(135deg, #fafbff 0%, #f5f7ff 100%);
        }

        .highlight-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .highlight-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 18px;
            background: white;
            border-radius: 10px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .highlight-item i {
            font-size: 1.5rem;
            color: #667eea;
            margin-top: 2px;
        }

        .highlight-content h5 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #334155;
            margin: 0 0 5px 0;
        }

        .highlight-content p {
            font-size: 0.9rem;
            color: #64748b;
            margin: 0;
            line-height: 1.6;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }

            .profile-card {
                position: static;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

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

            .avatar-circle {
                width: 100px;
                height: 100px;
                font-size: 3rem;
            }

            .profile-name {
                font-size: 1.3rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-badge {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 1.5rem;
            }

            .card-title {
                font-size: 1rem;
            }

            .profile-actions {
                padding: 20px 20px;
            }

            .card-body {
                padding: 20px;
            }

            .info-value {
                font-size: 0.95rem;
            }
        }
    </style>
@endsection