@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center mb-4">
            <div class="card rounded-0">
                <div class="card-body">
                    <h1 class="h4 mb-0 text-gray-800 font-weight-bold">Dashboard Mahasiswa</h1>
                    <p class="text-muted small mb-0">Daftar survey yang belum kamu isi</p>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse ($surveys as $survey)
                <div class="col-md-4 mb-4">
                    <div class="card shadow h-100 border-left-primary">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bold">{{ $survey->judul }}</h5>
                                <p class="text-muted small">{{ Str::limit($survey->deskripsi, 100) }}</p>
                            </div>
                            <a href="{{ route('mahasiswa.survey.fill', $survey->uuid) }}"
                                class="btn btn-primary btn-block mt-3">
                                <i class="fas fa-edit"></i> Isi Survey
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center mt-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <p class="lead text-muted">Semua survey sudah kamu isi. 🎉</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
