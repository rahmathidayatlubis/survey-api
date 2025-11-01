@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-4 text-gray-800 font-weight-bold">Analisis Survei Mahasiswa</h1>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Survei Aktif</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalSurveiAktif }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Responden</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalResponden }}</div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Rata-rata Nilai Keseluruhan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($rataKeseluruhan, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Jumlah Responden per Survei</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="myBarChart1"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Rata-rata Nilai per Pertanyaan</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="myBarChart2"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Charts -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">Rata-rata Nilai per Survei</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="myBarChart3"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-danger">Rata-rata Nilai per Jurusan</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="myBarChart4"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <script>
        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily =
            'Nunito, -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function(n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Bar Chart 1: Jumlah Responden per Survei
        var ctx1 = document.getElementById("myBarChart1");
        var myBarChart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: @json($responPerSurvei->pluck('judul')),
                datasets: [{
                    label: "Jumlah Responden",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: @json($responPerSurvei->pluck('total_responden')),
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                },
            }
        });

        // Bar Chart 2: Rata-rata Nilai per Pertanyaan
        var ctx2 = document.getElementById("myBarChart2");
        var myBarChart2 = new Chart(ctx2, {
            type: 'horizontalBar',
            data: {
                labels: @json($rataPerPertanyaan->pluck('pertanyaan')),
                datasets: [{
                    label: "Rata-rata Nilai",
                    backgroundColor: "#1cc88a",
                    hoverBackgroundColor: "#17a673",
                    borderColor: "#1cc88a",
                    data: @json($rataPerPertanyaan->pluck('rata_nilai')),
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                    yAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        }
                    }]
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                }
            }
        });

        // Bar Chart 3: Rata-rata Nilai per Survei
        var ctx3 = document.getElementById("myBarChart3");
        var myBarChart3 = new Chart(ctx3, {
            type: 'bar',
            data: {
                labels: @json($rataPerSurvei->pluck('judul')),
                datasets: [{
                    label: "Rata-rata Nilai",
                    backgroundColor: "#f6c23e",
                    hoverBackgroundColor: "#dda15e",
                    borderColor: "#f6c23e",
                    data: @json($rataPerSurvei->pluck('rata_survey')),
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 5,
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                },
            }
        });

        // Bar Chart 4: Rata-rata Nilai per Jurusan
        var ctx4 = document.getElementById("myBarChart4");
        var myBarChart4 = new Chart(ctx4, {
            type: 'bar',
            data: {
                labels: @json($rataPerJurusan->pluck('jurusan')),
                datasets: [{
                    label: "Rata-rata Nilai",
                    backgroundColor: "#e74a3b",
                    hoverBackgroundColor: "#c0392b",
                    borderColor: "#e74a3b",
                    data: @json($rataPerJurusan->pluck('rata_nilai')),
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 5,
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                        }
                    }
                },
            }
        });
    </script>
@endpush
