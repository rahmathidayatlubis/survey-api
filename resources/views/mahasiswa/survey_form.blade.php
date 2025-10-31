@extends('layouts.dashboard')

@section('content')
    <div class="survey-form-container">

        {{-- Header Survei Dinamis --}}
        <div class="survey-header">
            <h1 class="survey-title-main">{{ $survey->judul }}</h1>
            <p class="text-muted">{{ $survey->deskripsi }}</p>
            <div class="survey-meta mt-3">
                <span class="badge-meta badge-warning"><i class="fas fa-clock me-2"></i>Batas Waktu: {{ \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d M Y') }}</span>
            </div>
        </div>

        <hr class="my-4">
        
        {{-- Progress Bar --}}
        <div class="progress-section">
            <h3 class="progress-title">Total Pertanyaan: <span id="total-questions">{{ $totalQuestions }}</span></h3>
            <p class="progress-info mt-2 text-muted"><i class="fas fa-info-circle me-1"></i> Harap isi semua kolom bertanda *.</p>
        </div>

        {{-- Form Pengisian Survei --}}
        <form action="{{ route('mahasiswa.survey.submit', $survey->uuid) }}" method="POST" class="survey-form">
            @csrf
            <input type="hidden" name="survey_id" value="{{ $survey->id }}"> 
            
            {{-- Bagian 1: Data Diri (Read-only) --}}
            <div class="card survey-card mb-4">
                <div class="card-header bg-light border-bottom p-4">
                    <h4 class="card-title-main text-dark">
                        <i class="fas fa-user-circle me-2"></i> Informasi Pengisi
                    </h4>
                </div>
                
                <div class="card-body p-4">
                    {{-- NIM Otomatis --}}
                    <div class="form-group mb-0">
                        <label for="nim" class="form-label">NIM Anda (Otomatis):</label>
                        <input type="text" class="form-control" id="nim" value="{{ Auth::user()->nim }}" readonly>
                        <small class="form-text text-muted">Data ini akan digunakan untuk validasi, tetapi hasil survei Anda akan tetap anonim.</small>
                    </div>
                </div>
            </div>

            {{-- Bagian 2: Pertanyaan Survei Dinamis dari Database --}}
            <div class="card survey-card">
                <div class="card-header bg-white border-bottom p-4">
                    <h4 class="card-title-main text-primary">
                        <i class="fas fa-list-ol me-2"></i> Pertanyaan Survei
                    </h4>
                    <p class="text-muted mb-0">Jawablah setiap pertanyaan berdasarkan kondisi sebenarnya.</p>
                </div>
                
                <div class="card-body p-4">
                    @foreach ($questions as $index => $question)
                        <div class="question-item mb-4 pb-3 border-bottom">
                            <label class="form-label required-label">{{ $index + 1 }}. {{ $question->pertanyaan }}</label>
                            
                            {{-- Tipe Pertanyaan: Multiple Choice / Rating --}}
                            @if ($question->tipe == 'multiple_choice' || $question->tipe == 'rating')
                                <div class="rating-options">
                                    @foreach ($question->options as $option)
                                        <label class="rating-label">
                                            <input type="radio" 
                                                   name="answers[{{ $question->id }}]" 
                                                   value="{{ $option->id }}" 
                                                   required> 
                                            {{ $option->nilai }} ({{ $option->teks_pilihan }})
                                        </label>
                                    @endforeach
                                </div>
                            
                            {{-- Tipe Pertanyaan: Text/Essay --}}
                            @elseif ($question->tipe == 'text')
                                <textarea class="form-control" 
                                          name="answers[{{ $question->id }}][text]" 
                                          rows="3" 
                                          placeholder="Tuliskan jawaban atau masukan Anda di sini..."
                                          required></textarea>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-check-circle me-2"></i> Selesaikan dan Kirim Survei</button>
            </div>
        </form>

    </div>

    <style>
        /* ... (CSS Anda untuk styling, tidak diubah) ... */
        .survey-form-container { max-width: 1000px; margin: 0 auto; }
        .survey-header { background-color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .survey-title-main { font-size: 1.8rem; font-weight: 700; color: #1e293b; margin-bottom: 10px; }
        .survey-meta { display: flex; gap: 10px; flex-wrap: wrap; }
        .badge-meta { padding: 5px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .progress-section { background: white; padding: 20px 25px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .progress-title { font-size: 1.2rem; color: #1e293b; font-weight: 600; }
        .survey-card { border: 1px solid #ddd; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .survey-card .card-header { border-top-left-radius: 12px; border-top-right-radius: 12px; }
        .card-title-main { font-weight: 700; font-size: 1.1rem; }
        .form-label.required-label::after { content: ' *'; color: #e3342f; font-weight: 700; }
        .rating-options { display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; }
        .rating-label { display: flex; align-items: center; padding: 8px 15px; border: 1px solid #cbd5e1; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; font-weight: 500; background-color: #f8f9fa; }
        .rating-label:hover { border-color: #4f46e5; background-color: #eef2ff; }
        .rating-options input[type="radio"] { margin-right: 8px; accent-color: #4f46e5; }
        .rating-options input[type="radio"]:checked + .rating-label { background-color: #4f46e5; color: white; border-color: #4f46e5; }
        @media (max-width: 768px) { .survey-form-container { padding: 0 10px; } .survey-meta { flex-direction: column; } .rating-options { flex-direction: column; gap: 8px; } }
    </style>
@endsection
