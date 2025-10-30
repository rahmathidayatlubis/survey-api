@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-container">

        {{-- Welcome Section --}}
        <div class="welcome-section">
            <div class="welcome-content">
                <div>
                    <h2 class="welcome-title">Riwayat Partisipasi 📋</h2>
                    <p class="welcome-subtitle">Daftar semua survey yang telah Anda selesaikan.</p>
                </div>
                <div class="welcome-badge">
                    <i class="fas fa-history"></i>
                    <span>{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>

        {{-- Stats Cards (Ringkasan Riwayat) --}}
        <div class="stats-grid">
            
            {{-- Total Survey Selesai --}}
            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    {{-- Dinamis --}}
                    <h3 class="stat-number">{{ $totalSurveysCompleted }}</h3>
                    <p class="stat-label">Total Survey Selesai</p>
                    <span class="stat-change positive">
                        <i class="fas fa-trophy"></i> Kontribusi Luar Biasa!
                    </span>
                </div>
            </div>

            {{-- Survey Bulan Ini --}}
            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    {{-- Dinamis --}}
                    <h3 class="stat-number">{{ $surveysThisMonth }}</h3>
                    <p class="stat-label">Survey Bulan Ini</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </span>
                </div>
            </div>

            {{-- Poin Kontribusi Total --}}
            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-content">
                    {{-- Dinamis --}}
                    <h3 class="stat-number">{{ $totalPoints }}</h3>
                    <p class="stat-label">Total Poin Terkumpul (Nilai Rating)</p>
                    <span class="stat-change positive">
                        <i class="fas fa-medal"></i> Terus Tingkatkan!
                    </span>
                </div>
            </div>
             
            {{-- Kategori Terbanyak (Placeholder karena tidak ada kolom kategori di DB) --}}
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">Akademik</h3>
                    <p class="stat-label">Kategori (Default)</p>
                    <span class="stat-change neutral">
                        <i class="fas fa-chart-pie"></i> Tambahkan kolom 'kategori' di tabel 'surveys'
                    </span>
                </div>
            </div>
        </div>

        {{-- Main Content - Riwayat Table --}}
        <div class="content-grid-full">
            <div class="card card-table">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-check"></i>
                        Daftar Survey Selesai
                    </h3>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="btn-link">
                        Kembali ke Dashboard <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="card-filters">
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" placeholder="Cari judul survey..." class="search-input">
                    </div>
                    <select class="filter-select">
                        <option selected>Semua Tahun</option>
                        {{-- Logika filter bisa ditambahkan di JavaScript atau di Controller --}}
                    </select>
                    <select class="filter-select">
                        <option selected>Semua Kategori</option>
                        {{-- Logika filter bisa ditambahkan di JavaScript atau di Controller --}}
                    </select>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Judul Survey</th>
                                    <th>Tanggal Selesai</th>
                                    <!-- <th style="width: 150px; text-align: center;">Aksi</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($history as $index => $response)
                                <tr>
                                    <td>{{ $index + 1 }}.</td>
                                    <td>
                                        <div class="survey-title">
                                            <i class="fas fa-poll survey-icon" style="color:#667eea;"></i>
                                            {{ $response->survey->judul }}
                                        </div>
                                    </td>
                                    <td>{{ $response->submitted_at->translatedFormat('d M Y, H:i') }}</td>
                                    <!-- <td>
                                        <div class="action-buttons">
                                            <button type="button" class="btn-action btn-view" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn-action btn-download" title="Unduh Bukti">
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        </div>
                                    </td> -->
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="fas fa-info-circle me-2"></i> Belum ada riwayat partisipasi survey.
                                        Silakan isi survey di halaman <a href="{{ route('mahasiswa.survey') }}">Data Survey</a>.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Pagination (Placeholder) --}}
                <div class="card-footer">
                    <span class="pagination-info">Menampilkan {{ $history->count() }} entri.</span>
                    <div class="pagination-controls">
                        <button type="button" class="pagination-btn disabled">
                            <i class="fas fa-angle-left"></i> Previous
                        </button>
                        <button type="button" class="pagination-btn active">1</button>
                        <button type="button" class="pagination-btn disabled">
                            Next <i class="fas fa-angle-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS Section --}}
    <style>
        /* ... (CSS Anda yang sudah ada di sini) ... */
        .dashboard-container { max-width: 1400px; margin: 0 auto; }
        .welcome-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 16px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); }
        .welcome-content { display: flex; justify-content: space-between; align-items: center; color: white; }
        .welcome-title { font-size: 1.8rem; font-weight: 700; margin: 0 0 8px 0; }
        .welcome-subtitle { margin: 0; opacity: 0.9; font-size: 1rem; }
        .welcome-badge { background: rgba(255, 255, 255, 0.2); padding: 12px 24px; border-radius: 50px; backdrop-filter: blur(10px); display: flex; align-items: center; gap: 10px; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; }
        .welcome-badge i { font-size: 1.1rem; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 12px; padding: 24px; display: flex; gap: 20px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); transition: all 0.3s ease; border: 1px solid #e2e8f0; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1); }
        .stat-icon { width: 60px; height: 60px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; }
        .stat-primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-success .stat-icon { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
        .stat-warning .stat-icon { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
        .stat-info .stat-icon { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
        .stat-content { flex: 1; }
        .stat-number { font-size: 2rem; font-weight: 700; margin: 0 0 5px 0; color: #1e293b; }
        .stat-label { font-size: 0.9rem; color: #64748b; margin: 0 0 8px 0; }
        .stat-change { font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
        .stat-change.positive { color: #10b981; }
        .stat-change.negative { color: #e3342f; }
        .stat-change.neutral { color: #64748b; }
        .content-grid-full { display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 30px; }
        .card { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0; overflow: hidden; }
        .card-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .card-title { font-size: 1.1rem; font-weight: 700; margin: 0; color: #1e293b; display: flex; align-items: center; gap: 10px; }
        .card-title i { color: #6366f1; }
        .btn-link { color: #6366f1; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn-link:hover { gap: 10px; color: #4f46e5; }
        .card-filters { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 12px; flex-wrap: wrap; align-items: center; }
        .search-box { position: relative; flex: 1; min-width: 250px; }
        .search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }
        .search-input { width: 100%; padding: 10px 12px 10px 38px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; transition: all 0.2s; }
        .search-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
        .filter-select { padding: 10px 16px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem; background: white; cursor: pointer; transition: all 0.2s; }
        .filter-select:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }
        .card-body { padding: 24px; }
        .table-responsive { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead th { text-align: left; padding: 12px; font-size: 0.85rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #e2e8f0; }
        .data-table tbody td { padding: 16px 12px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        .data-table tbody tr:hover { background: #f8fafc; }
        .survey-title { display: flex; align-items: center; gap: 10px; font-weight: 600; color: #1e293b; }
        .survey-icon { font-size: 1rem; }
        .badge { padding: 5px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-primary { background: #e0e7ff; color: #4338ca; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-info { background: #cffafe; color: #0e7490; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .action-buttons { display: flex; gap: 8px; justify-content: center; }
        .btn-action { width: 36px; height: 36px; border: none; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; font-size: 0.9rem; }
        .btn-view { background: #dbeafe; color: #1e40af; }
        .btn-view:hover { background: #bfdbfe; transform: translateY(-2px); }
        .btn-download { background: #ccf1f7; color: #0891b2; }
        .btn-download:hover { background: #a5f3fc; transform: translateY(-2px); }
        .card-footer { padding: 20px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; }
        .pagination-info { font-size: 0.9rem; color: #64748b; }
        .pagination-controls { display: flex; gap: 8px; }
        .pagination-btn { padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 8px; background: white; color: #64748b; font-size: 0.9rem; font-weight: 600; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px; }
        .pagination-btn:hover:not(.disabled):not(.active) { background: #f8fafc; border-color: #cbd5e1; }
        .pagination-btn.active { background: #6366f1; color: white; border-color: #6366f1; }
        .pagination-btn.disabled { opacity: 0.5; cursor: not-allowed; }
        @media (max-width: 1024px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .welcome-content { flex-direction: column; gap: 20px; text-align: center; } .stats-grid { grid-template-columns: 1fr; } .card-filters { flex-direction: column; align-items: stretch; } .search-box { min-width: 100%; } .card-footer { flex-direction: column; gap: 16px; } .pagination-controls { width: 100%; justify-content: center; } .data-table th, .data-table td { min-width: 100px; } }
    </style>
@endsection
