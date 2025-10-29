@extends('survey.template')

@section("content")
<div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <div class="user-info">
                <span>Admin User</span>
                <div class="user-avatar">AU</div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Survey</h3>
                <div class="stat-value">24</div>
                <div class="stat-change">+3 dari bulan lalu</div>
            </div>
            <div class="stat-card">
                <h3>Total Responden</h3>
                <div class="stat-value">1,247</div>
                <div class="stat-change">+156 dari bulan lalu</div>
            </div>
            <div class="stat-card">
                <h3>Survey Aktif</h3>
                <div class="stat-value">8</div>
                <div class="stat-change">2 selesai minggu ini</div>
            </div>
            <div class="stat-card">
                <h3>Tingkat Respon</h3>
                <div class="stat-value">78%</div>
                <div class="stat-change">+5% dari bulan lalu</div>
            </div>
        </div>

        <div class="chart-container">
            <h2>Statistik Responden Bulanan</h2>
            <canvas id="responseChart"></canvas>
        </div>

        <div class="survey-list">
            <h2 style="color: #2d3748; margin-bottom: 20px;">Survey Terbaru</h2>
            <div class="survey-item">
                <div>
                    <div class="survey-title">Kepuasan Pelanggan Q4 2024</div>
                    <div class="survey-meta">352 responden • Dibuat 5 hari lalu</div>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <span class="status-badge status-active">Aktif</span>
                    <button class="btn btn-primary">Lihat Detail</button>
                </div>
            </div>
            <div class="survey-item">
                <div>
                    <div class="survey-title">Evaluasi Produk Baru</div>
                    <div class="survey-meta">189 responden • Dibuat 2 minggu lalu</div>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <span class="status-badge status-active">Aktif</span>
                    <button class="btn btn-primary">Lihat Detail</button>
                </div>
            </div>
            <div class="survey-item">
                <div>
                    <div class="survey-title">Survey Kepuasan Karyawan</div>
                    <div class="survey-meta">127 responden • Dibuat 3 minggu lalu</div>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <span class="status-badge status-closed">Selesai</span>
                    <button class="btn btn-primary">Lihat Detail</button>
                </div>
            </div>
            <div class="survey-item">
                <div>
                    <div class="survey-title">Feedback Website Redesign</div>
                    <div class="survey-meta">298 responden • Dibuat 1 bulan lalu</div>
                </div>
                <div style="display: flex; gap: 10px; align-items: center;">
                    <span class="status-badge status-closed">Selesai</span>
                    <button class="btn btn-primary">Lihat Detail</button>
                </div>
            </div>
        </div>
    </div>
@endsection