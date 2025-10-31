@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-container">
        {{-- Welcome Section --}}
        <div class="welcome-section">
            <div class="welcome-content">
                <div>
                    {{-- Menggunakan 'nama' untuk user terautentikasi --}}
                    <h2 class="welcome-title">Selamat Datang, {{ Auth::user()->nama }}! 👋</h2>
                    <p class="welcome-subtitle">Kelola dan pantau semua survey Anda dalam satu dashboard</p>
                </div>
                <div class="welcome-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>{{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>

        {{---
        Stats Cards (Real Data)
        ---}}
        <div class="stats-grid">
            <div class="stat-card stat-primary">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $totalSurveys }}</h3>
                    <p class="stat-label">Total Survey</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> Data Akurat
                    </span>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $totalRespondents }}</h3>
                    <p class="stat-label">Total Responden Unik</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> Data Akurat
                    </span>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $activeSurveys }}</h3>
                    <p class="stat-label">Survey Aktif</p>
                    <span class="stat-change neutral">
                        <i class="fas fa-minus"></i> Sesuai Periode
                    </span>
                </div>
            </div>

            <div class="stat-card stat-info">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $responseRate }}%</h3>
                    <p class="stat-label">Response Rate</p>
                    <span class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> Dari Total Mahasiswa
                    </span>
                </div>
            </div>
        </div>

        {{---
        Charts & Tables Row
        ---}}
        <div class="content-grid">
            {{-- Recent Survey Table --}}
            <div class="card card-table">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-ul"></i>
                        Survey Terbaru (4 Data)
                    </h3>
                    <a href="{{ route('admin.survey') }}" class="btn-link">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Judul Survey</th>
                                    <th>Status</th>
                                    <th>Responden</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentSurveys as $survey)
                                    @php
                                        $statusClass = $survey->is_active ? 'badge-success' : 'badge-warning';
                                        $statusText = $survey->is_active ? 'Aktif' : 'Draft';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="survey-title">
                                                <i class="fas fa-poll survey-icon"></i>
                                                {{ $survey->judul }}
                                            </div>
                                        </td>
                                        <td><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
                                        <td>{{ $survey->responses_count }}</td>
                                        <td>{{ \Carbon\Carbon::parse($survey->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.survey.show', $survey->uuid) }}" class="btn-action btn-view" title="Lihat"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.survey.edit', $survey->uuid) }}" class="btn-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: #64748b;">Tidak ada data survey terbaru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Activity Chart --}}
            <div class="card card-chart">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-area"></i>
                        Aktivitas Survey (7 Hari Terakhir)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{---
        Bottom Row
        ---}}
        <div class="content-grid-2">
            {{-- Top Surveys --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-trophy"></i>
                        Survey Terpopuler (Berdasarkan Responden)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="ranking-list">
                        @forelse ($topSurveys as $index => $survey)
                            <div class="ranking-item">
                                <div class="rank-badge rank-{{ $index + 1 }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="rank-content">
                                    <h4>{{ $survey->judul }}</h4>
                                    <p>{{ $survey->responses_count }} responden</p>
                                </div>
                                <div class="rank-progress">
                                    @php
                                        $maxResponses = $topSurveys->first()->responses_count ?: 1;
                                        $progressWidth = ($survey->responses_count / $maxResponses) * 100;
                                    @endphp
                                    <div class="progress-bar" style="width: {{ $progressWidth }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p style="text-align: center; color: #64748b;">Belum ada survey yang memiliki responden.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="card-body">
                    <div class="quick-actions">
                        <a href="{{ route('admin.survey.create') }}" class="quick-action-btn primary">
                            <i class="fas fa-plus-circle"></i>
                            <span>Buat Survey Baru</span>
                        </a>
                        <button class="quick-action-btn success">
                            <i class="fas fa-file-export"></i>
                            <span>Export Data</span>
                        </button>
                        <a href="{{ route('admin.laporan') }}" class="quick-action-btn info">
                            <i class="fas fa-chart-pie"></i>
                            <span>Lihat Laporan</span>
                        </a>
                        <a href="{{ route('admin.user') }}" class="quick-action-btn warning">
                            <i class="fas fa-users-cog"></i>
                            <span>Kelola User</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS Styles (FULL VERSION) --}}
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

        /* Stats Grid (Initial Desktop Grid) */
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

        .stat-change.neutral {
            color: #64748b;
        }

        /* Content Grid (2:1 Ratio on Desktop) */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr; /* 2/3 untuk tabel, 1/3 untuk chart */
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Content Grid 2 (50/50 Ratio on Desktop) */
        .content-grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Tetapkan 2 kolom */
            gap: 20px;
        }
        
        /* CARD STYLING */
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
            color: #6366f1;
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
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 0.85rem;
            text-decoration: none;
        }

        .btn-view {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-view:hover {
            background: #1e40af;
            color: white;
        }

        .btn-edit {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-edit:hover {
            background: #92400e;
            color: white;
        }

        /* Chart */
        .chart-container {
            position: relative;
            height: 300px;
        }

        /* Ranking List */
        .ranking-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .ranking-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .ranking-item:hover {
            background: #f1f5f9;
            transform: translateX(4px);
        }

        .rank-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: white;
        }

        .rank-1 {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }

        .rank-2 {
            background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);
        }

        .rank-3 {
            background: linear-gradient(135deg, #fb923c 0%, #ea580c 100%);
        }

        .rank-content {
            flex: 1;
        }

        .rank-content h4 {
            margin: 0 0 4px 0;
            font-size: 0.95rem;
            color: #1e293b;
        }

        .rank-content p {
            margin: 0;
            font-size: 0.8rem;
            color: #64748b;
        }

        .rank-progress {
            width: 100px;
            height: 6px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .quick-action-btn {
            padding: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .quick-action-btn i {
            font-size: 1.5rem;
        }

        .quick-action-btn.primary {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }

        .quick-action-btn.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .quick-action-btn.info {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .quick-action-btn.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        /* ======================================= */
        /* RESPONSIVE OVERRIDES (FIXING STACKED LAYOUT) */
        /* ======================================= */

        /* Tablet View (1024px and below) */
        @media (max-width: 1024px) {
            /* Main Content Row Stack */
            .content-grid {
                grid-template-columns: 1fr; /* Ubah dari 2fr 1fr menjadi satu kolom penuh */
            }
        }

        /* Mobile View (768px and below) */
        @media (max-width: 768px) {
            .welcome-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .stats-grid {
                grid-template-columns: 1fr; /* Stack semua stat cards */
            }

            .content-grid-2 {
                grid-template-columns: 1fr; /* Stack bottom cards */
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr); /* Pertahankan 2 kolom jika ruang memungkinkan */
            }
        }
    </style>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Mengambil token CSRF
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';


        async function fetchActivityData() {
            try {
                // Pastikan route() selalu dipanggil dengan sintaks yang benar di Blade
                const response = await fetch("{{ route('admin.dashboard.activity.data') }}", { 
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken 
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                return await response.json();

            } catch (error) {
                console.error('Error fetching activity data:', error);
                // Fallback data dengan label hari dalam Bahasa Indonesia
                return { 
                    labels: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'], 
                    data: [0, 0, 0, 0, 0, 0, 0] 
                }; 
            }
        }

        // Initialize and Update Chart
        async function initChart() {
            const chartData = await fetchActivityData();
            
            const ctx = document.getElementById('activityChart').getContext('2d');
            
            // Hancurkan instance chart lama jika ada (untuk hot reloading)
            if (window.activityChartInstance) {
                window.activityChartInstance.destroy();
            }

            window.activityChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.labels, // Data label dari Controller (Sen, Sel, Rab, dst.)
                    datasets: [{
                        label: 'Total Responden per Hari', 
                        data: chartData.data, // Data responden per hari
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: '#6366f1',
                            borderWidth: 1,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' respons'; 
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b',
                                precision: 0 
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        }
        
        document.addEventListener('DOMContentLoaded', initChart);
    </script>
@endsection