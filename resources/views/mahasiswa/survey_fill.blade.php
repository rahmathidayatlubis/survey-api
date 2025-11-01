@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center mb-4">
            <div class="card rounded-0">
                <div class="card-body">
                    <h1 class="h4 mb-0 text-gray-800 font-weight-bold">
                        Isi Survey: {{ $survey->judul }}
                    </h1>
                    <p class="text-muted small mb-0">{{ $survey->deskripsi }}</p>
                </div>
            </div>
        </div>

        <div class="card rounded-0">
            <div class="card-body p-4">
                <form action="{{ route('mahasiswa.survey.submit', $survey->uuid) }}" method="POST">
                    @csrf

                    @foreach ($survey->questions as $q)
                        <div class="mb-4">
                            <h6>{{ $loop->iteration }}. {{ $q->pertanyaan }}</h6>

                            @if ($q->tipe === 'multiple_choice')
                                @foreach ($q->options as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="answers[{{ $q->id }}]"
                                            value="{{ $option->id }}" id="opt{{ $option->id }}" required>
                                        <label class="form-check-label" for="opt{{ $option->id }}">
                                            {{ $option->teks_pilihan }}
                                        </label>
                                    </div>
                                @endforeach
                            @elseif ($q->tipe === 'text')
                                <textarea name="answers[{{ $q->id }}]" class="form-control" rows="2"
                                    placeholder="Tulis jawaban kamu di sini..." required></textarea>
                            @elseif ($q->tipe === 'rating')
                                <select name="answers[{{ $q->id }}]" class="form-control" required>
                                    <option value="">Pilih rating</option>
                                    @foreach ($q->options as $opt)
                                        <option value="{{ $opt->id }}">{{ $opt->teks_pilihan }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    @endforeach

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Kirim Jawaban
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
