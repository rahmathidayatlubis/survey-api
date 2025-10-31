<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Survey - {{ $survey->judul }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            font-size: 24px;
            color: #667eea;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            color: #666;
        }

        .info-section {
            background-color: #f8fafc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            color: #334155;
        }

        .info-value {
            color: #64748b;
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 15px;
        }

        .stat-box {
            flex: 1;
            background-color: #eff6ff;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #667eea;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
        }

        .question-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .question-title {
            background-color: #667eea;
            color: white;
            padding: 12px 15px;
            border-radius: 8px 8px 0 0;
            font-weight: bold;
            font-size: 13px;
        }

        .question-content {
            border: 2px solid #667eea;
            border-top: none;
            border-radius: 0 0 8px 8px;
            padding: 15px;
        }

        .option-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 10px;
            background-color: #f8fafc;
            border-radius: 6px;
        }

        .option-text {
            flex: 1;
            font-weight: 600;
            color: #334155;
        }

        .option-stats {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .option-count {
            font-weight: bold;
            color: #667eea;
            font-size: 13px;
        }

        .option-percentage {
            background-color: #667eea;
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }

        .progress-bar-container {
            width: 100%;
            height: 20px;
            background-color: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 8px;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }

        .text-answer {
            background-color: #f8fafc;
            padding: 10px 12px;
            border-left: 4px solid #667eea;
            margin-bottom: 8px;
            border-radius: 4px;
            color: #334155;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }

        .page-break {
            page-break-after: always;
        }

        /* Table styles for cleaner layout */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>LAPORAN SURVEY</h1>
        <p>{{ $survey->judul }}</p>
    </div>

    {{-- Info Section --}}
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Deskripsi:</span>
            <span class="info-value">{{ $survey->deskripsi ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Periode:</span>
            <span class="info-value">
                {{ $survey->tanggal_mulai->format('d F Y') }} - {{ $survey->tanggal_selesai->format('d F Y') }}
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span class="info-value">{{ now()->format('d F Y, H:i') }} WIB</span>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="stats-container">
        <div class="stat-box">
            <div class="stat-value">{{ $survey->responses->count() }}</div>
            <div class="stat-label">Total Responden</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $survey->questions->count() }}</div>
            <div class="stat-label">Total Pertanyaan</div>
        </div>
        <div class="stat-box">
            <div class="stat-value">{{ $survey->responses->count() > 0 ? '100%' : '0%' }}</div>
            <div class="stat-label">Tingkat Partisipasi</div>
        </div>
    </div>

    {{-- Questions and Answers --}}
    @foreach($statistics as $index => $stat)
        <div class="question-section">
            <div class="question-title">
                Pertanyaan {{ $index + 1 }}: {{ $stat['question']->pertanyaan }}
                <span style="float: right; background-color: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: 4px;">
                    {{ $stat['total_responses'] }} Jawaban
                </span>
            </div>
            <div class="question-content">
                @if($stat['question']->tipe === 'pilihan_ganda' || $stat['question']->tipe === 'checkbox')
                    {{-- Multiple Choice Answers --}}
                    @foreach($stat['options_stats'] as $optStat)
                        <div class="option-row">
                            <span class="option-text">{{ $optStat['option']->teks_opsi }}</span>
                            <div class="option-stats">
                                <span class="option-count">{{ $optStat['count'] }} responden</span>
                                <span class="option-percentage">{{ $optStat['percentage'] }}%</span>
                            </div>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: {{ $optStat['percentage'] }}%;">
                                @if($optStat['percentage'] > 15)
                                    {{ $optStat['percentage'] }}%
                                @endif
                            </div>
                        </div>
                    @endforeach
                
                @elseif($stat['question']->tipe === 'teks' || $stat['question']->tipe === 'teks_panjang')
                    {{-- Text Answers --}}
                    @if(isset($stat['text_answers']) && $stat['text_answers']->count() > 0)
                        <p style="font-weight: bold; margin-bottom: 10px; color: #334155;">
                            Jawaban Responden ({{ $stat['text_answers']->count() }}):
                        </p>
                        @foreach($stat['text_answers'] as $textIndex => $answer)
                            <div class="text-answer">
                                <strong>{{ $textIndex + 1 }}.</strong> {{ $answer }}
                            </div>
                        @endforeach
                    @else
                        <p style="text-align: center; color: #94a3b8; padding: 20px;">
                            Belum ada jawaban untuk pertanyaan ini
                        </p>
                    @endif
                @endif
            </div>
        </div>

        {{-- Page break after every 3 questions --}}
        @if(($index + 1) % 3 == 0 && !$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    {{-- Footer --}}
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Survey</p>
        <p>&copy; {{ now()->year }} - Semua hak dilindungi</p>
    </div>
</body>
</html>