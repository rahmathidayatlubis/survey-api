@extends('layouts.dashboard')

@section('content')
    <div class="survey-form-container">

        {{-- Header Survei --}}
        <div class="survey-header">
            <h1 class="survey-title-main">Evaluasi Dosen Semester Genap 2024</h1>
            <div class="survey-meta">
                <span class="badge-meta badge-primary"><i class="fas fa-layer-group me-2"></i>Kategori: Akademik</span>
                <span class="badge-meta badge-info"><i class="fas fa-users me-2"></i>Target: Mahasiswa TI</span>
                <span class="badge-meta badge-warning"><i class="fas fa-clock me-2"></i>Batas Waktu: 30 Nov 2025</span>
            </div>
        </div>
        
        <hr class="my-4">

        {{-- Progress Bar --}}
        <div class="progress-section">
            <h3 class="progress-title">Progres Anda: <span id="progress-percentage">25%</span></h3>
            <div class="progress" style="height: 15px;">
                {{-- Style width ini akan diubah via JavaScript saat navigasi antar halaman --}}
                <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" id="progress-bar"></div>
            </div>
            <p class="progress-info mt-2 text-muted"><i class="fas fa-info-circle me-1"></i>Anda sedang berada di Bagian 1 dari 4.</p>
        </div>

        {{-- Form Pengisian Survei --}}
        <form action="#" method="POST" class="survey-form">
            @csrf

            {{-- Card Bagian/Section Survei --}}
            <div class="card survey-card">
                <div class="card-header bg-white border-bottom p-4">
                    <h4 class="card-title-main text-primary">
                        <i class="fas fa-bookmark me-2"></i> Bagian 1: Informasi Dosen (Wajib)
                    </h4>
                    <p class="text-muted mb-0">Pilih dosen yang akan Anda evaluasi pada mata kuliah ini.</p>
                </div>
                
                <div class="card-body p-4">
                    
                    {{-- Pertanyaan 1 (Contoh: Dropdown) --}}
                    <div class="form-group mb-4">
                        <label for="dosen_id" class="form-label required-label">1. Pilih Dosen Pengampu Mata Kuliah 'Basis Data':</label>
                        <select class="form-select" id="dosen_id" name="dosen_id" required>
                            <option value="">Pilih salah satu...</option>
                            <option value="1">Dr. Ir. Budi Santoso, M.T.</option>
                            <option value="2">Prof. Siti Hajar, Ph.D.</option>
                            <option value="3">Dr. Rina Wati, S.Kom., M.Cs.</option>
                        </select>
                    </div>
                    
                    {{-- Pertanyaan 2 (Contoh: Essay Singkat) --}}
                    <div class="form-group mb-4">
                        <label for="nim" class="form-label required-label">2. Masukkan NIM Anda (Konfirmasi):</label>
                        <input type="text" class="form-control" id="nim" name="nim" value="{{ Auth::user()->nim }}" required readonly>
                        <small class="form-text text-muted">NIM Anda akan dicatat untuk validasi, tetapi data Anda dijamin anonim.</small>
                    </div>

                </div>
            </div>
            
            {{-- Card Pertanyaan Pilihan Ganda (Skala Likert) --}}
            <div class="card survey-card mt-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h4 class="card-title-main text-primary">
                        <i class="fas fa-chart-line me-2"></i> Bagian 2: Aspek Metode Pengajaran
                    </h4>
                    <p class="text-muted mb-0">Berikan penilaian Anda dari Skala 1 (Sangat Tidak Setuju) hingga 5 (Sangat Setuju).</p>
                </div>
                
                <div class="card-body p-4">
                    
                    {{-- Pertanyaan Skala Likert 1 --}}
                    <div class="question-item mb-4 pb-3 border-bottom">
                        <label class="form-label required-label">3. Dosen menguasai materi perkuliahan dengan baik.</label>
                        <div class="rating-options">
                            <label><input type="radio" name="q_kuasai_materi" value="1" required> 1 (STS)</label>
                            <label><input type="radio" name="q_kuasai_materi" value="2"> 2 (TS)</label>
                            <label><input type="radio" name="q_kuasai_materi" value="3"> 3 (N)</label>
                            <label><input type="radio" name="q_kuasai_materi" value="4"> 4 (S)</label>
                            <label><input type="radio" name="q_kuasai_materi" value="5"> 5 (SS)</label>
                        </div>
                    </div>

                    {{-- Pertanyaan Skala Likert 2 --}}
                    <div class="question-item mb-4 pb-3 border-bottom">
                        <label class="form-label required-label">4. Metode pengajaran yang digunakan dosen mudah dipahami.</label>
                        <div class="rating-options">
                            <label><input type="radio" name="q_metode_ajar" value="1" required> 1 (STS)</label>
                            <label><input type="radio" name="q_metode_ajar" value="2"> 2 (TS)</label>
                            <label><input type="radio" name="q_metode_ajar" value="3"> 3 (N)</label>
                            <label><input type="radio" name="q_metode_ajar" value="4"> 4 (S)</label>
                            <label><input type="radio" name="q_metode_ajar" value="5"> 5 (SS)</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Navigasi/Aksi --}}
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-secondary me-2" disabled><i class="fas fa-arrow-left me-2"></i> Sebelumnya</button>
                <button type="submit" class="btn btn-primary">Lanjut ke Bagian 2 <i class="fas fa-arrow-right ms-2"></i></button>
                {{-- Jika ini halaman terakhir, tombol submit menjadi: --}}
                {{-- <button type="submit" class="btn btn-success"><i class="fas fa-check-circle me-2"></i> Selesaikan Survei</button> --}}
            </div>
        </form>

    </div>

    {{-- Gaya CSS Tambahan (Terintegrasi dengan layout.dashboard) --}}
    <style>
        /* Menggunakan container yang lebih lebar untuk form */
        .survey-form-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .survey-header {
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .survey-title-main {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        
        .survey-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .badge-meta {
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .badge-primary { background: #e0e7ff; color: #4f46e5; }
        .badge-info { background: #ccf1f7; color: #0891b2; }
        .badge-warning { background: #fef3c7; color: #d97706; }

        .progress-section {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }

        .progress-title {
            font-size: 1.2rem;
            color: #1e293b;
            font-weight: 600;
        }

        .progress-bar {
            background-color: var(--primary); /* Menggunakan warna primary dari layout */
            transition: width 0.5s ease;
        }

        .survey-card {
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        }
        
        .survey-card .card-header {
             border-top-left-radius: 12px;
             border-top-right-radius: 12px;
        }

        .card-title-main {
            font-weight: 700;
            font-size: 1.1rem;
        }
        
        .form-label.required-label::after {
            content: ' *';
            color: #e3342f;
            font-weight: 700;
        }

        /* Gaya untuk Pertanyaan Skala Likert */
        .rating-options {
            display: flex;
            gap: 20px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .rating-options label {
            display: flex;
            align-items: center;
            padding: 8px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
        }
        
        .rating-options label:hover {
            border-color: var(--primary);
            background-color: #f1f5f9;
        }

        .rating-options input[type="radio"] {
            margin-right: 8px;
            accent-color: var(--primary);
        }

        .rating-options input[type="radio"]:checked + span,
        .rating-options input[type="radio"]:checked + span:before {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        /* Responsif */
        @media (max-width: 768px) {
            .survey-form-container {
                padding: 0 10px;
            }
            .survey-meta {
                flex-direction: column;
            }
            .rating-options {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
@endsection