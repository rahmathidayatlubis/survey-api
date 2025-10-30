@extends('layouts.dashboard')

@section('content')
    <div class="dashboard-container">
        {{-- ===================== HEADER SECTION ===================== --}}
        <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);">
            <div class="header-content">
                {{-- Bagian judul dan informasi umum survei --}}
                <div>
                    <h2 class="header-title">
                        <i class="fas fa-users header-icon"></i>
                        Daftar Responden
                    </h2>
                    <p class="header-subtitle">{{ $survey->judul }}</p>
                    <div style="margin-top: 10px;">
                        <span style="font-size: 0.9rem;">
                            <i class="fas fa-poll"></i> 
                            {{ $survey->responses->count() }} Total Responden
                        </span>
                    </div>
                </div>

                {{-- Tombol aksi di header (kembali dan export Excel) --}}
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="modern-action-btn" onclick="window.location.href='{{ route('laporan.index') }}'">
                        <i class="fas fa-arrow-left"></i>
                        <span>Kembali</span>
                    </button>
                    <button type="button" class="modern-action-btn2" style="background-color: #10b981;" onclick="window.location.href='{{ route('laporan.excel', $survey->uuid) }}'">
                        <i class="fas fa-file-excel"></i>
                        <span>Export Excel</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ===================== TABEL DATA RESPONDEN ===================== --}}
        <div class="content-grid-full">
            <div class="modern-card">
                {{-- Header kartu tabel --}}
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-list icon-primary"></i>
                        Data Responden Survey
                    </h3>

                    {{-- Kolom pencarian --}}
                    <div class="header-actions">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" placeholder="Cari responden..." class="search-input" id="searchInput">
                        </div>
                    </div>
                </div>

                {{-- Isi tabel --}}
                <div class="modern-card-body">
                    <div class="table-responsive">
                        <table class="modern-data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Nama Responden</th>
                                    <th>Email</th>
                                    <th>Waktu Mengisi</th>
                                    <th>Total Jawaban</th>
                                    <th style="width: 120px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Perulangan data responden --}}
                                @forelse($responses as $index => $response)
                                <tr>
                                    <td>{{ $responses->firstItem() + $index }}.</td>
                                    <td class="nama-mahasiswa">
                                        <div class="profile-meta">
                                            <i class="fas fa-user-circle avatar-icon"></i>
                                            <strong>{{ $response->user->name ?? 'Anonymous' }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $response->user->email ?? '-' }}</td>
                                    <td>
                                        <span style="font-size: 0.85rem;">
                                            <i class="fas fa-clock" style="color: #667eea; margin-right: 5px;"></i>
                                            {{ $response->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="answer-badge">
                                            <i class="fas fa-check-circle"></i>
                                            {{ $response->answers->count() }} Jawaban
                                        </span>
                                    </td>
                                    <td class="action-cell">
                                        {{-- Tombol aksi lihat detail & hapus --}}
                                        <div class="action-buttons">
                                            <button type="button" class="action-icon-btn tooltip-btn" data-tooltip="Lihat Detail" onclick="showDetailModal({{ $response->id }}, '{{ $response->user->name ?? 'Anonymous' }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="action-icon-btn tooltip-btn delete-btn" data-tooltip="Hapus" onclick="confirmDelete({{ $response->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                {{-- Jika belum ada responden --}}
                                <tr>
                                    <td colspan="6" class="text-center" style="padding: 40px;">
                                        <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                                        <p style="color: #64748b; font-size: 1rem;">Belum ada responden untuk survey ini</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Footer tabel dengan informasi pagination --}}
                <div class="modern-card-footer">
                    <span class="pagination-info">
                        Menampilkan {{ $responses->firstItem() ?? 0 }} sampai {{ $responses->lastItem() ?? 0 }} dari {{ $responses->total() ?? 0 }} entri
                    </span>
                    <div class="pagination-controls">
                        {{ $responses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== MODAL DETAIL RESPONDEN ===================== --}}
    <div id="detailModal" class="modal">
        <div class="modal-content-detail">
            <div class="modal-header-detail">
                <h3>
                    <i class="fas fa-user-check"></i>
                    <span id="detailRespondentName">Detail Responden</span>
                </h3>
                {{-- Tombol close modal --}}
                <button type="button" class="modal-close" onclick="closeDetailModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            {{-- Konten utama modal --}}
            <div class="modal-body-detail" id="detailContent">
                {{-- Spinner loading sementara --}}
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    <p>Memuat data...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== STYLING CSS ===================== --}}
    <style>
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
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .header-icon {
            font-size: 1.8rem;
            color: rgba(255,255,255,0.8);
        }

        .header-subtitle {
            font-size: 1rem;
            opacity: 0.9;
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
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .modern-action-btn2 {
            color: #ffffff;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .modern-action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .modern-action-btn2:hover {
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

        .answer-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background-color: #d1fae5;
            color: #065f46;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
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

        .action-icon-btn.delete-btn:hover {
            color: #ef4444;
            background-color: #fee2e2;
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

        /* Modal */
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
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content-detail {
            background-color: #ffffff;
            border-radius: 16px;
            width: 90%;
            max-width: 700px;
            max-height: 80vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(50px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header-detail {
            padding: 25px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header-detail h3 {
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
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body-detail {
            padding: 30px;
            overflow-y: auto;
            flex: 1;
        }

        .loading-spinner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: #667eea;
        }

        .loading-spinner i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .answer-detail-item {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }

        .answer-detail-question {
            font-weight: 700;
            color: #334155;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .answer-detail-response {
            color: #64748b;
            padding: 10px 15px;
            background-color: white;
            border-radius: 8px;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-header {
                padding: 25px;
            }
            
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-title {
                font-size: 1.5rem;
            }
            
            .modern-action-btn {
                width: 100%;
                justify-content: center;
            }

            .modern-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-box {
                max-width: none;
                width: 100%;
            }

            .modern-data-table th, .modern-data-table td {
                padding: 15px 20px;
                font-size: 0.85rem;
            }

            .modal-content-detail {
                width: 95%;
            }
        }
    </style>

    {{-- ===================== SCRIPT JAVASCRIPT ===================== --}}
    <script>
        // 🔍 Fungsi pencarian data responden
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.modern-data-table tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? '' : 'none';
            });
        });

        // 👁️ Tampilkan modal detail responden
        function showDetailModal(responseId, respondentName) {
            const modal = document.getElementById('detailModal');
            const nameEl = document.getElementById('detailRespondentName');
            const contentEl = document.getElementById('detailContent');
            
            nameEl.textContent = 'Detail Jawaban - ' + respondentName;
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Placeholder konten detail (bisa diganti AJAX)
            contentEl.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #64748b;">
                    <i class="fas fa-info-circle" style="font-size: 3rem; margin-bottom: 15px;"></i>
                    <p>Fitur detail jawaban akan segera tersedia</p>
                    <p style="font-size: 0.85rem; margin-top: 10px;">Implementasi memerlukan endpoint API tambahan</p>
                </div>
            `;
        }

        // ❌ Tutup modal detail responden
        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // 🗑️ Konfirmasi penghapusan responden
        function confirmDelete(responseId) {
            if (confirm('Apakah Anda yakin ingin menghapus data responden ini?')) {
                alert('Fungsi delete akan diimplementasikan di controller');
            }
        }

        // Klik di luar modal → menutup modal
        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target == modal) {
                closeDetailModal();
            }
        }

        // Tekan tombol Escape → menutup modal
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetailModal();
            }
        });
    </script>
@endsection