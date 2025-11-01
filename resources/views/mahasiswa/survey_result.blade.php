@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                <i class="fas fa-poll text-primary"></i> Hasil Survey Kamu
            </h1>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @forelse ($responses as $response)
            <div class="card shadow mb-4 border-left-primary rounded-0">
                <div
                    class="card-header py-3 bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="m-0 font-weight-bold">{{ $response->survey->judul }}</h6>
                        <small class="text-light">Dijawab pada:
                            {{ $response->created_at->translatedFormat('d F Y, H:i') }}</small>
                    </div>
                    <span class="badge badge-light text-primary font-weight-bold">
                        {{ strtoupper($response->survey->kategori ?? 'Survey Mahasiswa') }}
                    </span>
                </div>

                <div class="card-body bg-light">
                    @foreach ($response->answers as $answer)
                        <div class="mb-3 p-3 border-left-info bg-white shadow-sm rounded-sm">
                            <div class="d-flex justify-content-between align-items-start">
                                <strong class="text-dark">
                                    <i class="fas fa-question-circle text-info mr-1"></i>
                                    {{ $answer->question->pertanyaan ?? '-' }}
                                </strong>
                            </div>
                            <div class="mt-2">
                                <span class="text-primary font-weight-bold">
                                    {{ $answer->option->teks_pilihan ?? ($answer->jawaban_teks ?? '-') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center mt-5">
                <i class="fas fa-inbox fa-3x text-gray-400 mb-3"></i>
                <p class="text-muted">Belum ada survey yang kamu isi.</p>
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-primary btn-sm rounded-pill">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                </a>
            </div>
        @endforelse
    </div>
@endsection
