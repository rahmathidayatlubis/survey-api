@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-container">

        {{-- ===========================
            🔹 HEADER SECTION
            Berisi informasi utama survei: judul, deskripsi, tanggal, jumlah responden,
            serta tombol aksi untuk kembali & download laporan PDF.
        ============================ --}}
        <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);">
            <div class="header-content">
                
                {{-- Informasi utama tentang survei --}}
                <div>
                    <h2 class="header-title">
                        <i class="fas fa-chart-pie header-icon"></i>
                        {{ $survey->judul }}
                    </h2>
                    <p class="header-subtitle">{{ $survey->deskripsi }}</p>

                    {{-- Menampilkan tanggal pelaksanaan dan jumlah responden --}}
                    <div style="margin-top: 10px; display: flex; gap: 20px; flex-wrap: wrap;">
                        <span style="font-size: 0.9rem;">
                            <i class="fas fa-calendar-alt"></i> 
                            {{ $survey->tanggal_mulai->format('d M Y') }} - {{ $survey->tanggal_selesai->format('d M Y') }}
                        </span>
                        <span style="font-size: 0.9rem;">
                            <i class="fas fa-users"></i> 
                            {{ $survey->responses->count() }} Responden
                        </span>
                    </div>
                </div>

                {{-- Tombol aksi: kembali ke daftar & download laporan PDF --}}
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="modern-action-btn" onclick="window.location.href='{{ route('admin.laporan.index') }}'">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </button>
                    <button type="button" class="modern-action-btn2" style="background-color: #ef4444;" onclick="window.location.href='{{ route('admin.laporan.pdf', $survey->uuid) }}'">
                        <i class="fas fa-file-pdf"></i>
                        <span>Download PDF</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===========================
            🔹 STATISTICS SUMMARY
            Menampilkan data ringkas survei dalam bentuk 3 kartu:
            - Total responden
            - Total pertanyaan
            - Tingkat partisipasi
        ============================ --}}
        <div class="stats-grid">
            {{-- Kartu total responden --}}
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #dbeafe;">
                    <i class="fas fa-users" style="color: #1e40af;"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $survey->responses->count() }}</div>
                    <div class="stat-label">Total Responden</div>
                </div>
            </div>
            
            {{-- Kartu total pertanyaan --}}
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #fef3c7;">
                    <i class="fas fa-question-circle" style="color: #92400e;"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $survey->questions->count() }}</div>
                    <div class="stat-label">Total Pertanyaan</div>
                </div>
            </div>
            
            {{-- Kartu tingkat partisipasi (dummy 100%) --}}
            <div class="stat-card">
                <div class="stat-icon" style="background-color: #d1fae5;">
                    <i class="fas fa-percentage" style="color: #065f46;"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-value">{{ $survey->responses->count() > 0 ? '100%' : '0%' }}</div>
                    <div class="stat-label">Tingkat Partisipasi</div>
                </div>
            </div>
        </div>

        {{-- ===========================
            🔹 DETAILED QUESTION STATISTICS
            Menampilkan setiap pertanyaan beserta hasil statistiknya:
            - Pilihan ganda / checkbox ditampilkan dalam bentuk batang (progress bar)
            - Pertanyaan teks ditampilkan dalam daftar jawaban
        ============================ --}}
        @foreach($statistics as $stat)
        <div class="content-grid-full">
            <div class="modern-card">
                {{-- Header tiap pertanyaan --}}
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-poll icon-primary"></i>
                        {{ $stat['question']->pertanyaan }}
                    </h3>
                </div>

                {{-- Isi statistik pertanyaan --}}
                <div class="modern-card-body" style="padding: 30px;">

                    {{-- Jika tipe pertanyaan adalah pilihan ganda / checkbox --}}
                    @if($stat['question']->tipe === 'pilihan_ganda' || $stat['question']->tipe === 'checkbox')
                        <div class="chart-container">
                            {{-- Loop setiap opsi jawaban dan tampilkan jumlah serta persentase --}}
                            @foreach($stat['options_stats'] as $optStat)
                            <div class="option-bar">
                                <div class="option-info">
                                    <span class="option-text">{{ $optStat['option']->teks_opsi }}</span>
                                    <span class="option-count">{{ $optStat['count'] }} ({{ $optStat['percentage'] }}%)</span>
                                </div>
                                {{-- Progress bar visualisasi jawaban --}}
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $optStat['percentage'] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    {{-- Jika tipe pertanyaan adalah teks / teks panjang --}}
                    @elseif($stat['question']->tipe === 'teks' || $stat['question']->tipe === 'teks_panjang')
                        <div class="text-answers">
                            {{-- Loop semua jawaban teks --}}
                            @forelse($stat['text_answers'] as $answer)
                            <div class="answer-item">
                                <i class="fas fa-comment-alt"></i>
                                <span>{{ $answer }}</span>
                            </div>
                            @empty
                            {{-- Jika belum ada jawaban --}}
                            <p style="color: #94a3b8; text-align: center;">Belum ada jawaban</p>
                            @endforelse
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===========================
        🔹 STYLING CSS
        Semua elemen tampilan (header, card, statistik, progress bar)
        menggunakan gaya modern dan responsif.
    ============================ --}}
    <style>
        /* Grid wrapper utama setiap section */
        .content-grid-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        /* ===== HEADER ===== */
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
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title {
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 700px;
        }

        /* Tombol aksi di header */
        .modern-action-btn, .modern-action-btn2 {
            border: none;
            border-radius: 8px;
            padding: 12px 25px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .modern-action-btn {
            background-color: white;
            color: #667eea;
        }

        .modern-action-btn2 {
            color: #ffffff;
        }

        .modern-action-btn:hover, .modern-action-btn2:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* ===== STATISTICS CARDS ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
        }

        /* ===== QUESTION CARDS ===== */
        .modern-card {
            background: white;
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
        }

        .modern-card-title {
            font-size: 1.4rem; /* ubah ukuran sesuai keinginan, misal 0.95rem */
            font-weight: 600;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modern-badge.success {
            background-color: #d1fae5;
            color: #065f46;
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 600;
        }

        /* ===== PROGRESS BAR ===== */
        .option-bar {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .progress-bar {
            width: 100%;
            height: 30px;
            background-color: #f1f5f9;
            border-radius: 8px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            transition: width 0.5s ease;
        }

        /* ===== TEXT ANSWERS ===== */
        .answer-item {
            background-color: #f8fafc;
            padding: 15px 20px;
            border-left: 4px solid #667eea;
            border-radius: 8px;
            display: flex;
            gap: 12px;
        }

        .answer-item span {
            color: #334155;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header-content { flex-direction: column; align-items: flex-start; }
            .header-title { font-size: 1.5rem; }
            .stats-grid { grid-template-columns: 1fr; }
            .modern-action-btn { width: 100%; justify-content: center; }
        }
    </style>
@endsection
