@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-container">
        {{-- Header Section (Modernized) --}}
        <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);">
            <div class="header-content">
                <div>
                    <h2 class="header-title">
                        <i class="fas fa-chart-line header-icon"></i> 
                        Data Survey
                    </h2>
                    <p class="header-subtitle">Kelola informasi dan lihat detail hasil dari setiap survey.</p>
                </div>
                {{-- Tombol ini sekarang menggunakan <button> dan tidak memiliki route() atau href --}}
                <button type="button" class="modern-action-btn">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Survey Baru</span>
                </button>
            </div>
        </div>

        {{-- Main Content - Survey Table --}}
        <div class="content-grid-full">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-poll icon-primary"></i> 
                        Daftar Survey
                    </h3>
                    <div class="header-actions">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" placeholder="Cari survey..." class="search-input">
                        </div>
                        <!-- <a href="#" class="btn-export">
                            <i class="fas fa-file-export"></i>
                            <span>Export</span>
                        </a> -->
                    </div>
                </div>
                <div class="modern-card-body">
                    <div class="table-responsive">
                        <table class="modern-data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Judul Survey</th>
                                    <th>Deskripsi Singkat</th> {{-- KOLOM BARU --}}
                                    <th>Periode</th>
                                    <th style="text-align: center;">Jml. Pertanyaan</th>
                                    <th style="text-align: center;">Jml. Responden</th>
                                    <th>Status</th>
                                    <th style="width: 120px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Pastikan variabel $surveys tersedia dari Controller --}}
                                @forelse ($surveys as $survey)
                                    <tr>
                                        {{-- $loop->iteration untuk nomor urut yang dimulai dari 1 --}}
                                        <td>{{ $loop->iteration }}.</td> 
                                        <td class="nama-mahasiswa">
                                            <div class="profile-meta">
                                                <i class="fas fa-book-open avatar-icon"></i>
                                                {{ $survey->judul }}
                                            </div>
                                        </td>
                                        {{-- KOLOM DESKRIPSI BARU --}}
                                        <td>
                                            <span style="color: #64748b; font-size: 0.9rem;">
                                                {{ Str::limit($survey->deskripsi, 150) }}
                                            </span>
                                        </td>
                                        {{-- Asumsi Anda memiliki Carbon dan Str diakses/terimport dengan benar --}}
                                        <td>{{ \Carbon\Carbon::parse($survey->tanggal_mulai)->format('d M y') }} - {{ \Carbon\Carbon::parse($survey->tanggal_selesai)->format('d M y') }}</td>
                                        <td style="text-align: center;">{{ $survey->questions_count }}</td> 
                                        <td style="text-align: center;">{{ $survey->responses_count }}</td> 
                                        <td>
                                            @if ($survey->is_active)
                                                <span class="modern-badge success">Aktif</span>
                                            @else
                                                <span class="modern-badge warning">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="action-cell">
                                            <div class="action-buttons">
                                                <button type="button" class="action-icon-btn tooltip-btn" data-tooltip="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="action-icon-btn tooltip-btn" data-tooltip="Edit Data">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="action-icon-btn tooltip-btn delete-btn" data-tooltip="Hapus">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 20px;">
                                            <i class="fas fa-info-circle"></i> Tidak ada data survey yang ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- FOOTER KARTU DENGAN PAGINATION --}}
                <div class="modern-card-footer">
                    <span class="pagination-info">
                        Menampilkan {{ $surveys->firstItem() ?? 0 }} sampai {{ $surveys->lastItem() ?? 0 }} dari {{ $surveys->total() ?? 0 }} entri
                    </span>
                    <div class="pagination-controls">
                        {{-- FIX: Menggunakan view pagination default Tailwind --}}
                        {{ $surveys->links('pagination::tailwind') }} 
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    {{-- CSS Styles --}}
    <style>
        /* General Layout - using your existing .dashboard-container and .content-grid-full */
        .content-grid-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Modern Header */
        .modern-header {
            padding: 30px 40px;
            border-radius: 12px;
            margin-bottom: 30px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; /* Allow wrapping on smaller screens */
            gap: 20px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .header-icon {
            font-size: 2rem;
            color: rgba(255,255,255,0.8);
        }

        .header-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            max-width: 600px;
            line-height: 1.5;
        }

        .modern-action-btn {
            background-color: white;
            color: #667eea;
            padding: 12px 25px;
            border: none; /* Make it look like a button */
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 200px; /* Ensure button has decent width */
            justify-content: center;
            cursor: pointer;
        }

        .modern-action-btn:hover {
            background-color: #f0f4f8; /* Light gray on hover */
            color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Modern Card */
        .modern-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden; /* For table rounded corners */
        }

        .modern-card-header {
            padding: 24px 30px;
            border-bottom: 1px solid #edf2f7; /* Lighter border */
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px; /* Spacing for wrap */
        }

        .modern-card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .icon-primary {
            color: #667eea;
            font-size: 1.5rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: flex-end; /* Align actions to the right */
        }

        .search-box {
            position: relative;
            flex-grow: 1; /* Allow search to take more space */
            max-width: 300px; /* Limit search width */
        }

        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px; /* Left padding for icon */
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            outline: none;
        }

        .search-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
        }

        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .btn-export {
            background-color: #e2e8f0;
            color: #4a5568;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            background-color: #cbd5e1;
            color: #2d3748;
        }

        .modern-card-body {
            padding: 0; /* Table will fill the body */
        }

        /* Modern Data Table */
        .modern-data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            color: #4a5568;
        }

        .modern-data-table th, .modern-data-table td {
            padding: 18px 30px;
            text-align: left;
            border-bottom: 1px solid #edf2f7; /* Light horizontal lines */
        }

        .modern-data-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.05em;
        }

        .modern-data-table tbody tr:hover {
            background-color: #f0f4f8; /* Subtle hover effect */
        }
        
        .modern-data-table tbody tr:last-child td {
            border-bottom: none; /* No border for last row */
        }

        .profile-meta {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar-icon {
            font-size: 1.2rem;
            color: #667eea;
        }

        /* Modern Badges */
        .modern-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .modern-badge.success {
            background-color: #d1fae5; /* Light green */
            color: #065f46; /* Darker green text */
        }

        .modern-badge.warning {
            background-color: #fef3c7; /* Light yellow */
            color: #92400e; /* Darker yellow/orange text */
        }

        /* Action Buttons */
        .action-cell {
            text-align: center;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .action-icon-btn {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 1.1rem;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            transition: all 0.2s ease;
            position: relative; /* For tooltip */
        }

        .action-icon-btn:hover {
            color: #667eea;
            background-color: #eff6ff;
        }

        .action-icon-btn.delete-btn:hover {
            color: #ef4444;
            background-color: #fee2e2;
        }

        /* Tooltip */
        .tooltip-btn::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 120%; /* Position above the button */
            left: 50%;
            transform: translateX(-50%);
            background-color: #334155;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            z-index: 10;
        }

        .tooltip-btn:hover::before {
            opacity: 1;
            visibility: visible;
        }

        /* Modern Card Footer (Pagination) */
        .modern-card-footer {
            padding: 20px 30px;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .pagination-info {
            font-size: 0.9rem;
            color: #64748b;
        }
        
        /* Hapus style .pagination-controls karena Tailwind akan mengontrol */
        /* .pagination-controls {
            display: flex;
            gap: 8px;
        } */

        /* Hapus style .pagination-btn dan .active karena Tailwind akan mengontrol */
        /* .pagination-btn { ... } */
        /* .pagination-btn:hover:not(.active) { ... } */
        /* .pagination-btn.active { ... } */


        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modern-header {
                padding: 25px 25px;
            }
            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            .header-title {
                font-size: 1.8rem;
            }
            .header-subtitle {
                font-size: 0.9rem;
            }
            .modern-action-btn {
                width: 100%;
                min-width: unset;
            }

            .modern-card-header {
                flex-direction: column;
                align-items: flex-start;
                padding: 20px 25px;
            }
            .header-actions {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }
            .search-box {
                max-width: none;
                width: 100%;
            }
            .btn-export {
                width: 100%;
                justify-content: center;
            }

            .modern-data-table th, .modern-data-table td {
                padding: 15px 20px;
            }

            .modern-card-footer {
                flex-direction: column;
                padding: 20px 25px;
            }
            /* .pagination-controls {
                flex-wrap: wrap;
                justify-content: center;
            } */
        }

        @media (max-width: 480px) {
            .modern-data-table th, .modern-data-table td {
                font-size: 0.85rem;
                padding: 12px 15px;
            }
            .header-title {
                font-size: 1.5rem;
            }
            .header-icon {
                font-size: 1.3rem;
            }
            .modern-card-title {
                font-size: 1.1rem;
            }
            .icon-primary {
                font-size: 1.2rem;
            }
            .modern-badge {
                font-size: 0.7rem;
                padding: 4px 8px;
            }
            .action-icon-btn {
                font-size: 1rem;
                padding: 4px;
            }
            /* .pagination-btn {
                font-size: 0.8rem;
                padding: 6px 10px;
            } */
        }
    </style>
@endsection
