@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-container">
        {{-- ===========================
             🔹 HEADER SECTION
             Bagian ini menampilkan judul halaman, deskripsi singkat, dan tombol kembali
        ============================ --}}
        <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);">
            <div class="header-content">
                <div>
                    <h2 class="header-title">
                        <i class="fas fa-chart-bar header-icon"></i>
                        Laporan Survey
                    </h2>
                    <p class="header-subtitle">Kelola dan analisis hasil survey dengan mudah.</p>
                </div>
                {{-- Tombol kembali ke halaman daftar survei --}}
                <button type="button" class="modern-action-btn" onclick="window.location.href='{{ route('admin.survey') }}'">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali ke Survey</span>
                </button>
            </div>
        </div>

        {{-- ===========================
             🔹 TABEL LAPORAN SURVEY
             Menampilkan daftar seluruh survei yang sudah dibuat
        ============================ --}}
        <div class="content-grid-full">
            <div class="modern-card">
                {{-- === HEADER TABEL === --}}
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-file-alt icon-primary"></i>
                        Daftar Laporan Survey
                    </h3>

                    {{-- Fitur pencarian dan tombol export --}}
                    <div class="header-actions">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" placeholder="Cari survey..." class="search-input" id="searchInput">
                        </div>

                        {{-- Tombol export excel (belum aktif) --}}
                        <!-- <a href="#" class="btn-export" onclick="event.preventDefault(); alert('Export akan segera tersedia');">
                            <i class="fas fa-file-excel"></i>
                            <span>Export Excel</span>
                        </a> -->
                    </div>
                </div>

                {{-- === ISI TABEL === --}}
                <div class="modern-card-body">
                    <div class="table-responsive">
                        <table class="modern-data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Judul Survey</th>
                                    <th>Periode</th>
                                    <th>Total Responden</th>
                                    <th>Status</th>
                                    <th style="width: 180px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop setiap survei --}}
                                @forelse($surveys ?? [] as $index => $survey)
                                <tr>
                                    {{-- Nomor urut --}}
                                    <td>{{ $index + 1 }}.</td>

                                    {{-- Judul survey --}}
                                    <td class="nama-mahasiswa">
                                        <div class="profile-meta">
                                            <i class="fas fa-poll avatar-icon"></i>
                                            <strong>{{ $survey->judul }}</strong>
                                        </div>
                                    </td>

                                    {{-- Periode mulai dan selesai --}}
                                    <td>
                                        <div style="font-size: 0.85rem;">
                                            <div><i class="fas fa-calendar-alt" style="color: #667eea; margin-right: 5px;"></i>{{ $survey->tanggal_mulai->format('d M Y') }}</div>
                                            <div style="margin-top: 3px;"><i class="fas fa-calendar-check" style="color: #667eea; margin-right: 5px;"></i>{{ $survey->tanggal_selesai->format('d M Y') }}</div>
                                        </div>
                                    </td>

                                    {{-- Jumlah responden --}}
                                    <td>
                                        <span class="response-count">
                                            <i class="fas fa-users"></i>
                                            {{ $survey->responses->count() }} Responden
                                        </span>
                                    </td>

                                    {{-- Status aktif/selesai/nonaktif --}}
                                    <td>
                                        @if($survey->is_active && $survey->tanggal_selesai >= now())
                                            <span class="modern-badge success">Aktif</span>
                                        @elseif($survey->tanggal_selesai < now())
                                            <span class="modern-badge danger">Selesai</span>
                                        @else
                                            <span class="modern-badge warning">Nonaktif</span>
                                        @endif
                                    </td>

                                    {{-- Aksi tombol detail, responden, dan export PDF --}}
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            {{-- Detail laporan lengkap --}}
                                            <button type="button" class="action-icon-btn tooltip-btn" data-tooltip="Detail Lengkap" onclick="window.location.href='{{ route('admin.laporan.show', $survey->uuid) }}'">
                                                <i class="fas fa-chart-pie"></i>
                                            </button>

                                            {{-- Lihat daftar responden --}}
                                            <button type="button" class="action-icon-btn tooltip-btn" data-tooltip="Responden" onclick="window.location.href='{{ route('admin.laporan.responses', $survey->uuid) }}'">
                                                <i class="fas fa-users"></i>
                                            </button>

                                            {{-- Export laporan ke PDF --}}
                                            <button type="button" class="action-icon-btn tooltip-btn" data-tooltip="Download PDF" onclick="window.location.href='{{ route('admin.laporan.pdf', $survey->uuid) }}'">
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Jika belum ada survei sama sekali --}}
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center" style="padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                                        <p style="color: #64748b; font-size: 1rem;">Belum ada data laporan survey</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- === FOOTER TABEL (INFO PAGINASI) === --}}
                <div class="modern-card-footer">
                    <span class="pagination-info">
                        Menampilkan {{ $surveys->firstItem() ?? 0 }} sampai {{ $surveys->lastItem() ?? 0 }} dari {{ $surveys->total() ?? 0 }} entri
                    </span>
                    <div class="pagination-controls">
                        {{ $surveys->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===========================
         🔹 STYLING KHUSUS HALAMAN LAPORAN
         Semua gaya tampilan disusun modern & responsif
    ============================ --}}

    {{-- CSS Styles --}}
    <style>
        /* General Layout */
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
            flex-wrap: wrap;
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
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            min-width: 200px;
            justify-content: center;
            cursor: pointer;
        }

        .modern-action-btn:hover {
            background-color: #f0f4f8;
            color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        /* Modern Card */
        .modern-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .modern-card-header {
            padding: 24px 30px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
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
            justify-content: flex-end;
        }

        .search-box {
            position: relative;
            flex-grow: 1;
            max-width: 300px;
        }

        .search-input {
            width: 100%;
            padding: 10px 15px 10px 40px;
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
            background-color: #10b981;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-export:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .modern-card-body {
            padding: 0;
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
            border-bottom: 1px solid #edf2f7;
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
            background-color: #f0f4f8;
        }
        
        .modern-data-table tbody tr:last-child td {
            border-bottom: none;
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

        .response-count {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background-color: #eff6ff;
            color: #1e40af;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
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
            background-color: #d1fae5;
            color: #065f46;
        }

        .modern-badge.warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .modern-badge.danger {
            background-color: #fee2e2;
            color: #991b1b;
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
            position: relative;
        }

        .action-icon-btn:hover {
            color: #667eea;
            background-color: #eff6ff;
        }

        /* Tooltip */
        .tooltip-btn::before {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 120%;
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

        /* Modern Card Footer */
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

        .pagination-controls {
            display: flex;
            gap: 8px;
        }

        .text-center {
            text-align: center;
        }

        /* Quick Preview Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to { 
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-content-preview {
            background-color: #ffffff;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
            overflow: hidden;
        }

        .modal-header-preview {
            padding: 25px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-preview h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body-preview {
            padding: 30px;
        }

        .preview-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .preview-stat-item {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            border: 2px solid #e2e8f0;
        }

        .preview-stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .preview-stat-content {
            flex: 1;
        }

        .preview-stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 3px;
        }

        .preview-stat-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 600;
        }

        .preview-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn-preview-action {
            width: 100%;
            padding: 14px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            background-color: #667eea;
            color: white;
        }

        .btn-preview-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        /* Responsive */
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
                font-size: 0.85rem;
            }

            .modern-card-footer {
                flex-direction: column;
                padding: 20px 25px;
            }

            .modal-content-preview {
                width: 95%;
            }

            .preview-stats {
                grid-template-columns: 1fr;
            }

            .modal-body-preview {
                padding: 20px;
            }
        }
    </style>

    {{-- ===========================
         🔹 SCRIPT PENCARIAN CEPAT
         Fungsi ini memungkinkan pengguna mencari survei langsung di tabel
    ============================ --}}
    <script>
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.modern-data-table tbody tr');
            
            // Filter baris tabel berdasarkan input pencarian
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });
    </script>
@endsection