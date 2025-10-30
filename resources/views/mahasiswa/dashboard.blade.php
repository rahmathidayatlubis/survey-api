@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-container">
        {{-- Welcome Section --}}
        <div class="welcome-section">
            <div class="welcome-content">
                <div>
                    {{-- Asumsi: Auth::user()->nama ada, sesuai dengan skema registrasi --}}
                    <h2 class="welcome-title">Halo, {{ Auth::user()->nama }}! 👋</h2> 
                    <p class="welcome-subtitle">Ayo, berikan kontribusi Anda dalam meningkatkan kualitas pendidikan melalui survey.</p>
                </div>
                <div class="welcome-badge">
                    <i class="fas fa-graduation-cap"></i>
                    <span>{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>

        {{-- Stats Cards (Metrik Mahasiswa) --}}
        <div class="stats-grid">
            
            {{-- Total Survey Tersedia --}}
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    {{-- Ganti dengan data dinamis: Total survey yang ditujukan ke mahasiswa ini --}}
                    <h3 class="stat-number">15</h3> 
                    <p class="stat-label">Total Survey Tersedia</p>
                    <span class="stat-change neutral">
                        <i class="fas fa-info-circle"></i> Sesuai Jurusan Anda ({{ Auth::user()->jurusan ?? 'Belum Diisi' }})
                    </span>
                </div>
            </div>

            {{-- Survey Selesai Diisi --}}
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    {{-- Ganti dengan data dinamis: Jumlah survey yang sudah diisi mahasiswa ini --}}
                    <h3 class="stat-number">12</h3> 
                    <p class="stat-label">Survey Sudah Diisi</p>
                    <span class="stat-change positive">
                        <i class="fas fa-trophy"></i> Hampir Selesai!
                    </span>
                </div>
            </div>

            {{-- Survey Belum Diisi / Wajib --}}
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    {{-- Ganti dengan data dinamis: Jumlah survey yang BELUM diisi mahasiswa ini --}}
                    <h3 class="stat-number">3</h3> 
                    <p class="stat-label">Survey Menunggu</p>
                    <span class="stat-change negative" style="color:#e3342f;">
                        <i class="fas fa-exclamation-triangle"></i> Segera diisi
                    </span>
                </div>
            </div>

            {{-- Poin Kontribusi (Opsional untuk motivasi) --}}
            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">550</h3> 
                    <p class="stat-label">Poin Kontribusi</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> Tunjukkan Partisipasi Anda
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Content: Daftar Survey --}}
        <div class="content-grid-full">
            
            {{-- Daftar Survey yang Harus Diisi --}}
            <div class="card card-table">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-check"></i>
                        Survey yang Perlu Anda Isi
                    </h3>
                    <a href="#" class="btn-link">Lihat Riwayat <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Judul Survey</th>
                                    <th>Target</th>
                                    <th>Batas Waktu</th>
                                    <th>Status Anda</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Contoh 1: Belum Diisi --}}
                                <tr>
                                    <td>
                                        <div class="survey-title">
                                            <i class="fas fa-poll survey-icon" style="color:#f59e0b;"></i>
                                            Evaluasi Dosen Semester Genap 2024
                                        </div>
                                    </td>
                                    <td>Teknik Informatika</td>
                                    <td>30 Nov 2025</td>
                                    <td><span class="badge badge-warning">Belum Diisi</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="#" class="btn-action btn-fill" title="Isi Survey">
                                                <i class="fas fa-pen"></i> Isi Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                
                                {{-- Contoh 2: Sudah Diisi --}}
                                <tr>
                                    <td>
                                        <div class="survey-title">
                                            <i class="fas fa-poll survey-icon" style="color:#10b981;"></i>
                                            Kepuasan Fasilitas Laboratorium
                                        </div>
                                    </td>
                                    <td>Semua Mahasiswa</td>
                                    <td>15 Okt 2025</td>
                                    <td><span class="badge badge-success">Sudah Diisi</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-filled" disabled title="Sudah Selesai">
                                                <i class="fas fa-check"></i> Selesai
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                {{-- Contoh 3: Belum Diisi (Tenggat dekat) --}}
                                <tr>
                                    <td>
                                        <div class="survey-title">
                                            <i class="fas fa-poll survey-icon" style="color:#e3342f;"></i>
                                            Feedback Website Kampus V2
                                        </div>
                                    </td>
                                    <td>Teknik Informatika</td>
                                    <td>**20 Nov 2025**</td>
                                    <td><span class="badge badge-danger">Belum Diisi</span></td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="#" class="btn-action btn-fill" title="Isi Survey">
                                                <i class="fas fa-pen"></i> Isi Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- CSS Section --}}
    {{-- Ini adalah semua style CSS yang Anda berikan, dengan sedikit penyesuaian untuk Mahasiswa --}}
    <style>
        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Welcome Section */
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .welcome-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .welcome-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 0 8px 0;
        }

        .welcome-subtitle {
            margin: 0;
            opacity: 0.9;
            font-size: 1rem;
        }

        .welcome-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 24px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        
        /* Ganti icon welcome badge */
        .welcome-badge i {
            font-size: 1.1rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            display: flex;
            gap: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-primary .stat-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .stat-success .stat-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .stat-warning .stat-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .stat-info .stat-icon {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 5px 0;
            color: #1e293b;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #64748b;
            margin: 0 0 8px 0;
        }

        .stat-change {
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .stat-change.positive {
            color: #10b981;
        }

        /* Tambahkan warna negatif/danger untuk survey wajib yang belum diisi */
        .stat-change.negative { 
            color: #e3342f; 
        }

        .stat-change.neutral {
            color: #64748b;
        }

        /* Content Grid Mahasiswa (Full Width) */
        .content-grid-full { 
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title i {
            color: #6366f1;
        }

        .btn-link {
            color: #6366f1;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-link:hover {
            gap: 10px;
            color: #4f46e5;
        }

        .card-body {
            padding: 24px;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            text-align: left;
            padding: 12px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
        }

        .data-table tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }

        .survey-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #1e293b;
        }

        .survey-icon {
            font-size: 1rem;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-action {
            /* Resetting default admin button size */
            width: auto; 
            height: auto;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 0.85rem;
            padding: 8px 16px; /* Padding baru */
            font-weight: 600;
        }
        
        /* Style untuk tombol "Isi Sekarang" (Belum Diisi) */
        .btn-fill {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .btn-fill:hover {
             background: linear-gradient(135deg, #059669 0%, #047857 100%);
        }

        /* Style untuk tombol "Selesai" (Sudah Diisi) */
        .btn-filled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }


        /* Responsive */
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .welcome-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .content-grid-full {
                grid-template-columns: 1fr;
            }
            
            .data-table th, .data-table td {
                /* Membuat kolom tabel dapat bergulir jika terlalu kecil */
                min-width: 100px; 
            }
        }
    </style>
    
    {{-- Karena dashboard mahasiswa ini lebih sederhana, kita tidak perlu Chart.js --}}
@endsection