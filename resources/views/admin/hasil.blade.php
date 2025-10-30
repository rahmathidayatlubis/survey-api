@extends('layouts.dashboard')

@section('content')
<div class="dashboard-container">
    {{-- Header --}}
    <div class="modern-header">
        <div class="header-content">
            <div>
                <h2 class="header-title">
                    <i class="fas fa-chart-pie header-icon"></i>
                    Data Hasil Survei
                </h2>
                <p class="header-subtitle">Visualisasi hasil survei mahasiswa berdasarkan data real-time</p>
            </div>
            <div class="header-actions">
                <select id="surveySelect" class="modern-select">
                    @foreach($surveys as $s)
                        <option value="{{ $s->id }}">{{ $s->judul }}</option>
                    @endforeach
                </select>
                <button id="refreshBtn" class="modern-action-btn">
                    <i class="fas fa-sync-alt"></i> 
                    <span>Refresh Data</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="stats-grid">
        <div class="stat-card card-blue">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h4 class="stat-label">Total Responden</h4>
                <div id="cardRespondents" class="stat-value">-</div>
                <p class="stat-description">Jumlah mahasiswa yang mengisi survey</p>
            </div>
        </div>
        
        <div class="stat-card card-purple">
            <div class="stat-icon">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-content">
                <h4 class="stat-label">Rata-Rata Skor</h4>
                <div id="cardAvg" class="stat-value">-</div>
                <p class="stat-description">Rata-rata keseluruhan skor (1 - 5)</p>
            </div>
        </div>
        
        <div class="stat-card card-green">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h4 class="stat-label">Indeks Kepuasan (IKM)</h4>
                <div id="cardIKM" class="stat-value">-</div>
                <p class="stat-description">(Rata-rata / 5) × 100%</p>
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="charts-grid">
        {{-- Bar Chart --}}
        <div class="chart-card chart-large">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar"></i>
                    Rata-Rata Nilai per Pertanyaan
                </h3>
            </div>
            <div class="card-body">
                <canvas id="barChart"></canvas>
            </div>
        </div>

        {{-- Pie Chart --}}
        <div class="chart-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie"></i>
                    Distribusi Jawaban
                </h3>
                <select id="questionSelect" class="chart-select"></select>
            </div>
            <div class="card-body">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Summary Table --}}
    <div class="table-section">
        <div class="table-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list"></i>
                    Ringkasan Pertanyaan
                </h3>
            </div>
            <div class="card-body">
                <div class="table-wrapper">
                    <table class="data-table" id="summaryTable">
                        <thead>
                            <tr>
                                <th style="width: 60px;">No.</th>
                                <th>Pertanyaan</th>
                                <th style="width: 150px;">Rata-rata Skor</th>
                                <th style="width: 150px;">Jumlah Respon</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Diisi oleh JavaScript --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const barCtx = document.getElementById('barChart').getContext('2d');
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    let barChart = null;
    let pieChart = null;

    const surveySelect = document.getElementById('surveySelect');
    const questionSelect = document.getElementById('questionSelect');
    const refreshBtn = document.getElementById('refreshBtn');

    function formatNumber(n, decimals = 2) {
        return Number(n).toFixed(decimals);
    }

    async function fetchData(surveyId) {
        const url = `{{ url('/admin/data-hasil/data') }}?survey_id=${surveyId}`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const data = await res.json();
        return data;
    }

    function renderCards(data) {
        document.getElementById('cardRespondents').innerText = data.total_respondents ?? 0;
        document.getElementById('cardAvg').innerText = (data.overall_average ? formatNumber(data.overall_average, 2) : '-') + ' / 5';
        document.getElementById('cardIKM').innerText = (data.ikm ? formatNumber(data.ikm, 2) : '-') + ' %';
    }

    function truncateLabel(label, maxLength = 40) {
        if (label.length <= maxLength) return label;
        return label.substring(0, maxLength) + '...';
    }

    function renderBarChart(labels, values) {
        if (barChart) barChart.destroy();
        
        // Truncate labels untuk tampilan yang lebih rapi
        const truncatedLabels = labels.map(label => truncateLabel(label, 35));
        
        barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: truncatedLabels,
                datasets: [{
                    label: 'Rata-rata skor (1–5)',
                    data: values,
                    backgroundColor: 'rgba(102, 126, 234, 0.85)',
                    borderRadius: 8,
                    barThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                // Tampilkan full label di tooltip
                                return labels[context[0].dataIndex];
                            },
                            label: function(context) {
                                return 'Rata-rata: ' + context.parsed.y.toFixed(2);
                            }
                        },
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 13,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        displayColors: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 5,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            font: {
                                size: 10
                            },
                            autoSkip: false,
                            padding: 5
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function renderPieChart(labels, values) {
        if (pieChart) pieChart.destroy();
        pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Distribusi',
                    data: values,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(168, 85, 247, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    function fillQuestionSelect(questions, selectedQuestionId = null) {
        questionSelect.innerHTML = '';
        questions.forEach((q, idx) => {
            const opt = document.createElement('option');
            opt.value = q.id;
            // Truncate pertanyaan yang terlalu panjang untuk dropdown
            const questionText = q.pertanyaan.length > 80 
                ? q.pertanyaan.substring(0, 77) + '...' 
                : q.pertanyaan;
            opt.text = `${idx + 1}. ${questionText}`;
            questionSelect.appendChild(opt);
        });
        if (selectedQuestionId) questionSelect.value = selectedQuestionId;
    }

    function fillSummaryTable(summary) {
        const tbody = document.querySelector('#summaryTable tbody');
        tbody.innerHTML = '';
        summary.forEach((row, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="text-center">${idx + 1}.</td>
                <td>${row.pertanyaan}</td>
                <td class="text-center">${Number(row.avg).toFixed(2)}</td>
                <td class="text-center">${row.responses_count}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    async function loadAndRender(surveyId) {
        const data = await fetchData(surveyId);

        renderCards(data);

        // Store original labels and use short_label for display
        const originalLabels = data.question_summary.map(q => q.pertanyaan);
        const labels = data.question_summary.map(q => {
            // Buat label singkat dari pertanyaan
            const text = q.short_label || q.pertanyaan;
            return text.length > 50 ? text.substring(0, 50) + '...' : text;
        });
        const values = data.question_summary.map(q => q.avg ? Number(q.avg).toFixed(2) : 0);
        renderBarChart(labels, values);

        fillQuestionSelect(data.questions, data.questions.length ? data.questions[0].id : null);
        fillSummaryTable(data.question_summary);

        if (data.questions.length) {
            const firstQ = data.questions[0].id;
            updatePieForQuestion(firstQ, data);
        }
    }

    async function updatePieForQuestion(questionId, cachedData = null) {
        let distData;
        if (cachedData && cachedData.options_by_question && cachedData.options_by_question[questionId]) {
            distData = cachedData.options_by_question[questionId];
        } else {
            const url = `{{ url('/admin/data-hasil/data') }}?survey_id=${surveySelect.value}&question_id=${questionId}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const d = await res.json();
            distData = d.question_options || [];
        }

        const labels = distData.map(o => o.teks_pilihan);
        const values = distData.map(o => o.count);
        renderPieChart(labels, values);
    }

    // Event listeners
    surveySelect.addEventListener('change', () => loadAndRender(surveySelect.value));
    refreshBtn.addEventListener('click', () => loadAndRender(surveySelect.value));
    questionSelect.addEventListener('change', () => updatePieForQuestion(questionSelect.value));

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
        const firstSurveyId = surveySelect.value;
        if (firstSurveyId) {
            loadAndRender(firstSurveyId);
        }
    });
</script>

<style>
    /* Global Container */
    .dashboard-container {
        padding: 0;
        max-width: 100%;
    }

    /* Modern Header */
    .modern-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 32px;
        margin-bottom: 32px;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .header-title {
        color: white;
        font-size: 1.875rem;
        font-weight: 700;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-icon {
        font-size: 2rem;
    }

    .header-subtitle {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        font-size: 1rem;
    }

    .header-actions {
        display: flex;
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Modern Select */
    .modern-select {
        background: white;
        color: #334155;
        padding: 12px 40px 12px 16px;
        border-radius: 10px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        font-size: 0.95rem;
        font-weight: 500;
        min-width: 300px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 20px;
    }

    .modern-select:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-color: rgba(102, 126, 234, 0.3);
        transform: translateY(-1px);
    }

    .modern-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
    }

    .modern-select option {
        padding: 10px;
        background: white;
        color: #334155;
    }

    /* Action Button */
    .modern-action-btn {
        background: white;
        color: #667eea;
        padding: 12px 20px;
        border-radius: 10px;
        border: none;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .modern-action-btn:hover {
        background: #667eea;
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        transform: translateY(-2px);
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        display: flex;
        gap: 20px;
        align-items: flex-start;
        transition: all 0.3s ease;
        border-left: 4px solid;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .card-blue {
        border-left-color: #3b82f6;
    }

    .card-purple {
        border-left-color: #667eea;
    }

    .card-green {
        border-left-color: #10b981;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .card-blue .stat-icon {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .card-purple .stat-icon {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }

    .card-green .stat-icon {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .stat-description {
        font-size: 0.875rem;
        color: #94a3b8;
        margin: 0;
    }

    /* Charts Grid */
    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 1200px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .chart-large .card-body {
        height: 450px;
        padding: 24px;
    }

    .chart-card:not(.chart-large) .card-body {
        height: 380px;
        padding: 24px;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: #667eea;
    }

    .chart-select {
        background: white;
        color: #334155;
        padding: 10px 36px 10px 14px;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        font-size: 0.875rem;
        font-weight: 500;
        min-width: 280px;
        max-width: 100%;
        cursor: pointer;
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23667eea' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 18px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .chart-select:hover {
        border-color: #667eea;
        box-shadow: 0 2px 6px rgba(102, 126, 234, 0.15);
        transform: translateY(-1px);
    }

    .chart-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
    }

    .chart-select option {
        padding: 10px;
        background: white;
        color: #334155;
        font-size: 0.875rem;
    }

    .card-body {
        position: relative;
    }

    /* Table Section */
    .table-section {
        margin-bottom: 32px;
    }

    .table-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
        padding: 0 24px 24px 24px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9375rem;
    }

    .data-table thead {
        background: #f8fafc;
    }

    .data-table th {
        padding: 16px 20px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        font-size: 0.8125rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }

    .data-table td {
        padding: 16px 20px;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
    }

    .data-table tbody tr {
        transition: background-color 0.2s ease;
    }

    .data-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .text-center {
        text-align: center !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .modern-header {
            padding: 24px;
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
            flex-direction: column;
        }

        .modern-select,
        .modern-action-btn {
            width: 100%;
            min-width: auto;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            flex-direction: column;
            text-align: center;
        }

        .stat-icon {
            margin: 0 auto;
        }

        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .chart-select {
            width: 100%;
            min-width: auto;
        }

        .data-table {
            font-size: 0.875rem;
        }

        .data-table th,
        .data-table td {
            padding: 12px 16px;
        }
    }
</style>
@endsection