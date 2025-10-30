@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="fw-bolder mb-3 text-primary"><i class="fas fa-poll-h me-2"></i> Daftar Survei Tersedia</h2>
                <p class="text-secondary">Pilih survei yang belum Anda isi dari daftar di bawah ini.</p>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (!empty($availableSurveys) && $availableSurveys->count() > 0)
            <div class="row mt-4">
                @foreach ($availableSurveys as $survey)
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card h-100 border-start border-5 border-primary shadow-sm survey-item-card">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-dark fw-bold mb-1">{{ $survey->judul }}</h5>
                                <p class="text-secondary small mb-3">{{ Str::limit($survey->deskripsi, 120) }}</p>
                                
                                <div class="mb-3">
                                    <span class="badge bg-label-info me-2"><i class="fas fa-calendar-alt me-1"></i> Mulai: {{ \Carbon\Carbon::parse($survey->tanggal_mulai)->format('d M Y') }}</span>
                                    <span class="badge bg-label-warning"><i class="fas fa-calendar-times me-1"></i> Selesai: {{ \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d M Y') }}</span>
                                </div>
                                
                                {{-- Menggunakan rute survey.show --}}
                                <a href="{{ route('mahasiswa.survey.show', $survey->uuid) }}" class="btn btn-primary btn-sm mt-auto w-100">
                                    <i class="fas fa-pencil-alt me-2"></i> Mulai Isi Survei
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="card mt-5 shadow-sm">
                <div class="card-body p-5 text-center">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h4 class="card-title text-dark">Semua Selesai!</h4>
                    <p class="text-muted mb-0">Tidak ada survei aktif yang tersedia untuk Anda saat ini, atau Anda sudah menyelesaikan semua kewajiban.</p>
                </div>
            </div>
        @endif
    </div>

    <style>
        .survey-item-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .survey-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }
        .bg-label-info { background-color: #e0f7fa !important; color: #00bcd4 !important; }
        .bg-label-warning { background-color: #fff3e0 !important; color: #ff9800 !important; }
    </style>
@endsection