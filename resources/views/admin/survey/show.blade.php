@extends('layouts.dashboard')

@section('content')

<div class="dashboard-container">
    {{-- Header Section --}}
    <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4); border-radius: 12px; padding: 25px 35px; margin-bottom: 30px;">
        <div class="header-content" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="header-title" style="color: #fff; font-weight: 700; font-size: 1.8rem; margin-bottom: 5px;">
                    <i class="fas fa-poll header-icon" style="margin-right: 8px;"></i>
                    Detail Survey: {{ $survey->judul }}
                </h2>
                <p class="header-subtitle" style="color: #e2e8f0; font-size: 0.95rem;">Ringkasan informasi, pertanyaan, dan opsi jawaban terdaftar.</p>
            </div>
            {{-- Tombol Kembali dan Edit --}}
            <div style="display: flex; gap: 10px;">
                <a href="{{ route('admin.survey') }}" class="modern-action-btn" style="background-color: #fff; color: #4c51bf; font-weight: 600; border-radius: 8px; padding: 10px 16px; display: inline-flex; align-items: center; text-decoration: none; transition: all 0.3s ease;">
                    <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Ringkasan dan Status --}}
    <div class="content-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        {{-- Card Status --}}
        <div class="modern-card" style="padding: 25px; text-align: center; border-left: 5px solid {{ $survey->is_active ? '#10b981' : '#f59e0b' }};">
            <h4 style="font-size: 1rem; color: #64748b; margin-bottom: 10px;">Status Survey</h4>
            <span class="modern-badge {{ $survey->is_active ? 'success' : 'warning' }}" style="font-size: 0.9rem;">
                {{ $survey->is_active ? 'AKTIF' : 'TIDAK AKTIF' }}
            </span>
        </div>

        {{-- Card Pertanyaan --}}
        <div class="modern-card" style="padding: 25px; text-align: center; border-left: 5px solid #667eea;">
            <h4 style="font-size: 1rem; color: #64748b; margin-bottom: 5px;">Total Pertanyaan</h4>
            <p style="font-size: 2.2rem; font-weight: 700; color: #374151;">{{ $survey->questions_count }}</p>
        </div>

        {{-- Card Responden --}}
        <div class="modern-card" style="padding: 25px; text-align: center; border-left: 5px solid #ef4444;">
            <h4 style="font-size: 1rem; color: #64748b; margin-bottom: 5px;">Total Responden</h4>
            <p style="font-size: 2.2rem; font-weight: 700; color: #374151;">{{ $survey->responses_count }}</p>
        </div>
    </div>
    
    {{-- Blok Rincian Survey --}}
    <div class="modern-card" style="padding: 30px; margin-bottom: 30px;">
        <h3 style="font-weight: 700; font-size: 1.2rem; color: #374151; margin-bottom: 25px;">
            <i class="fas fa-clipboard-list" style="color:#667eea; margin-right:8px;"></i>Rincian Survey
        </h3>

        <div class="detail-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="detail-group">
                <label style="font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">Deskripsi:</label>
                <p style="color: #374151;">{{ $survey->deskripsi }}</p>
            </div>
            <div class="detail-group">
                <label style="font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">UUID:</label>
                <p style="color: #374151; font-family: monospace; font-size: 0.9rem;">{{ $survey->uuid }}</p>
            </div>
            <div class="detail-group">
                <label style="font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">Periode Survey:</label>
                <p style="color: #374151;">
                    {{ \Carbon\Carbon::parse($survey->tanggal_mulai)->format('d F Y') }}
                    sampai
                    {{ \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d F Y') }}
                </p>
            </div>
            <div class="detail-group">
                <label style="font-weight: 600; color: #64748b; display: block; margin-bottom: 4px;">Dibuat Pada:</label>
                <p style="color: #374151;">{{ \Carbon\Carbon::parse($survey->created_at)->format('d F Y H:i') }}</p>
            </div>
        </div>
    </div>
    
    {{-- BLOK BARU: DAFTAR PERTANYAAN & OPSI --}}
    <div class="modern-card" style="padding: 30px;">
        <h3 style="font-weight: 700; font-size: 1.2rem; color: #374151; margin-bottom: 25px;">
            <i class="fas fa-list-ul" style="color:#f59e0b; margin-right:8px;"></i>Daftar Pertanyaan Terdaftar
        </h3>

        @forelse ($survey->questions->sortBy('urutan') as $question)
            <div class="question-detail-block" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; background-color: #fcfcfc;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="font-weight: 600; color: #374151; font-size: 1rem;">
                        <span style="color: #667eea;">Q{{ $question->urutan }}.</span> {{ $question->pertanyaan }}
                    </h4>
                    <span class="modern-badge" style="background-color: #e2e8f0; color: #4a5568; text-transform: capitalize;">
                        {{ str_replace('_', ' ', $question->tipe) }}
                    </span>
                </div>
                
                <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 15px;">
                    *Jawaban {{ $question->tipe == 'text' ? 'berupa teks bebas.' : 'memiliki opsi berbobot.' }}
                </p>

                @if ($question->tipe == 'multiple_choice' || $question->tipe == 'rating')
                    <div style="padding: 10px; border-top: 1px solid #edf2f7;">
                        <h5 style="font-size: 0.95rem; font-weight: 600; color: #4a5568; margin-bottom: 8px;">Opsi Jawaban:</h5>
                        <ul style="list-style-type: none; padding-left: 0;">
                            @foreach ($question->options->sortByDesc('nilai') as $option)
                                <li style="padding: 5px 0; border-bottom: 1px dotted #edf2f7; display: flex; justify-content: space-between; font-size: 0.9rem;">
                                    <span style="color: #374151;">{{ $option->teks_pilihan }}</span>
                                    <span style="font-weight: 600; color: #10b981;">(Bobot: {{ $option->nilai }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @empty
            <div style="padding: 20px; text-align: center; color: #ef4444; border: 1px dashed #fee2e2; border-radius: 8px;">
                <i class="fas fa-exclamation-triangle"></i> Survey ini belum memiliki pertanyaan terdaftar.
            </div>
        @endforelse
    </div>
    
</div>

<style>
    /* Styling tambahan untuk show view */
    .modern-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .modern-card:hover {
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }
    .modern-badge {
        display: inline-block;
        padding: 6px 15px; border-radius: 20px; font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
    }
    .modern-badge.success {
        background-color: #d1fae5; color: #065f46;
    }
    .modern-badge.warning {
        background-color: #fef3c7; color: #92400e;
    }
    .modern-action-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr !important;
        }
        .content-grid {
            grid-template-columns: 1fr !important;
        }
        /* Perbaikan tata letak header agar tombol Edit/Kembali tetap rapi di mobile */
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

@endsection