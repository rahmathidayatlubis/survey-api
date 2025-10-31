@extends('layouts.dashboard')

@section('content')
    {{-- Pesan Sukses --}}
    @if (session('success'))
        <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Pesan Error --}}
    @if (session('error'))
        <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="dashboard-container">
        {{-- Header Section --}}
        <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);">
            <div class="header-content">
                <div>
                    <h2 class="header-title">
                        <i class="fas fa-poll header-icon"></i>
                        Data Survey
                    </h2>
                    <p class="header-subtitle">Kelola informasi data survey dengan mudah.</p>
                </div>
                <a href="{{ route('admin.survey.create') }}" class="modern-action-btn" style="text-decoration:none;">
                    <i class="fas fa-plus-circle"></i>
                    <span>Tambah Survey Baru</span>
                </a>
            </div>
        </div>

        {{-- Main Content - Survey Table --}}
        <div class="content-grid-full">
            <div class="modern-card">
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-list-alt icon-primary"></i>
                        Daftar Survey
                    </h3>
                    
                    {{-- Header Actions: Search --}}
                    <div class="header-actions">
                        <form action="{{ route('admin.survey') }}" method="GET" class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari survey..." 
                                   class="search-input"
                                   value="{{ request('search') }}">
                            @if (request('search'))
                                <button type="button" 
                                        onclick="window.location='{{ route('admin.survey') }}'"
                                        class="clear-search" 
                                        title="Hapus pencarian">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
                
                <div class="modern-card-body">
                    <div class="table-responsive">
                        <table class="modern-data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No.</th>
                                    <th>Judul Survey</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th style="width: 120px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($surveys as $index => $survey)
                                    <tr>
                                        <td>{{ $surveys->firstItem() + $index }}</td>
                                        <td class="survey-title">
                                            <div class="title-meta">
                                                <i class="fas fa-file-alt title-icon"></i>
                                                <strong>{{ $survey->judul }}</strong>
                                            </div>
                                        </td>
                                        <td class="survey-description">
                                            {{ Str::limit($survey->deskripsi, 60) }}
                                        </td>
                                        <td>{{ date('d M Y', strtotime($survey->tanggal_mulai)) }}</td>
                                        <td>{{ date('d M Y', strtotime($survey->tanggal_selesai)) }}</td>
                                        <td>
                                            @if($survey->is_active)
                                                <span class="modern-badge success">Aktif</span>
                                            @else
                                                <span class="modern-badge warning">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="action-cell">
                                            <div class="action-buttons">
                                                {{-- Tombol Show Modal --}}
                                                <button type="button" 
                                                        class="action-icon-btn tooltip-btn" 
                                                        data-tooltip="Lihat Detail"
                                                        onclick="showDetailModal({{ json_encode($survey) }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                {{-- Tombol Edit Modal --}}
                                                <button type="button" 
                                                        class="action-icon-btn tooltip-btn" 
                                                        data-tooltip="Edit Data"
                                                        data-id="{{ $survey->id }}"
                                                        data-judul="{{ $survey->judul }}"
                                                        data-deskripsi="{{ $survey->deskripsi }}"
                                                        data-tanggal-mulai="{{ \Carbon\Carbon::parse($survey->tanggal_mulai)->format('Y-m-d') }}"
                                                        data-tanggal-selesai="{{ \Carbon\Carbon::parse($survey->tanggal_selesai)->format('Y-m-d') }}"
                                                        data-is-active="{{ $survey->is_active }}"
                                                        onclick="showEditModalNew(this)">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                {{-- Tombol Delete --}}
                                                <form action="{{ route('admin.survey.destroy', $survey->id) }}" method="POST"
                                                    style="display:inline;"
                                                    onsubmit="return confirm('Yakin ingin menghapus survey ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-icon-btn tooltip-btn delete-btn"
                                                        data-tooltip="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center" style="padding: 40px;">
                                            <i class="fas fa-inbox" style="font-size: 3rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                                            <p style="color: #94a3b8; margin: 0;">Tidak ada data survey.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modern-card-footer">
                    <span class="pagination-info">
                        Menampilkan {{ $surveys->firstItem() ?? 0 }} sampai {{ $surveys->lastItem() ?? 0 }} dari {{ $surveys->total() }} entri
                    </span>
                    <div class="pagination-controls">
                        {{ $surveys->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Survey --}}
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-info-circle"></i> Detail Survey</h3>
                <button type="button" class="modal-close" onclick="closeDetailModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-row">
                    <span class="detail-label">Judul Survey:</span>
                    <span class="detail-value" id="detail-judul">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Deskripsi:</span>
                    <span class="detail-value" id="detail-deskripsi">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Mulai:</span>
                    <span class="detail-value" id="detail-tanggal-mulai">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tanggal Selesai:</span>
                    <span class="detail-value" id="detail-tanggal-selesai">-</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" id="detail-status">-</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Modal Edit Survey --}}
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-edit"></i> Edit Survey</h3>
                <button type="button" class="modal-close" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit-judul">Judul Survey <span class="required">*</span></label>
                        <input type="text" id="edit-judul" name="judul" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-deskripsi">Deskripsi <span class="required">*</span></label>
                        <textarea id="edit-deskripsi" name="deskripsi" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit-tanggal-mulai">Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" id="edit-tanggal-mulai" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-tanggal-selesai">Tanggal Selesai <span class="required">*</span></label>
                        <input type="date" id="edit-tanggal-selesai" name="tanggal_selesai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-is-active">Status <span class="required">*</span></label>
                        <select id="edit-is-active" name="is_active" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- CSS Styles --}}
    <style>
        /* Previous styles remain the same */
        .pagination-controls nav { display: flex; gap: 8px; }
        .pagination-controls .flex { gap: 8px; }
        .content-grid-full { display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 30px; }
        .modern-header { padding: 30px 40px; border-radius: 12px; margin-bottom: 30px; color: white; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; }
        .header-content { display: flex; align-items: center; justify-content: space-between; width: 100%; }
        .header-title { font-size: 2.2rem; font-weight: 700; margin-bottom: 8px; display: flex; align-items: center; gap: 12px; text-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        .header-icon { font-size: 2rem; color: rgba(255,255,255,0.8); }
        .header-subtitle { font-size: 1rem; opacity: 0.9; max-width: 600px; line-height: 1.5; }
        .modern-action-btn { background-color: white; color: #667eea; padding: 12px 25px; border: none; border-radius: 8px; font-weight: 600; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); min-width: 200px; justify-content: center; cursor: pointer; }
        .modern-action-btn:hover { background-color: #f0f4f8; color: #5a67d8; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15); }
        .modern-card { background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); overflow: hidden; }
        .modern-card-header { padding: 24px 30px; border-bottom: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .modern-card-title { font-size: 1.3rem; font-weight: 700; color: #334155; display: flex; align-items: center; gap: 10px; }
        .icon-primary { color: #667eea; font-size: 1.5rem; }
        .header-actions { display: flex; align-items: center; gap: 12px; flex-wrap: nowrap; }
        .search-box { position: relative; width: 300px; display: flex; align-items: center; }
        .search-input { width: 100%; padding: 10px 40px 10px 40px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.9rem; transition: all 0.3s ease; outline: none; }
        .search-input:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2); }
        .search-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }
        .clear-search { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #94a3b8; cursor: pointer; padding: 4px; font-size: 0.9rem; transition: color 0.2s ease; display: flex; align-items: center; justify-content: center; }
        .clear-search:hover { color: #667eea; }
        .modern-card-body { padding: 0; }
        .modern-data-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; color: #4a5568; }
        .modern-data-table th, .modern-data-table td { padding: 18px 30px; text-align: left; border-bottom: 1px solid #edf2f7; }
        .modern-data-table th { background-color: #f8fafc; font-weight: 600; color: #64748b; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.05em; }
        .modern-data-table tbody tr:hover { background-color: #f0f4f8; }
        .modern-data-table tbody tr:last-child td { border-bottom: none; }
        .survey-title { font-weight: 500; }
        .title-meta { display: flex; align-items: center; gap: 10px; }
        .title-icon { font-size: 1.2rem; color: #667eea; }
        .survey-description { color: #64748b; font-size: 0.85rem; line-height: 1.4; }
        .modern-badge { display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .modern-badge.success { background-color: #d1fae5; color: #065f46; }
        .modern-badge.warning { background-color: #fef3c7; color: #92400e; }
        .action-cell { text-align: center; }
        .action-buttons { display: flex; justify-content: center; gap: 8px; }
        .action-icon-btn { background: none; border: none; color: #94a3b8; font-size: 1.1rem; cursor: pointer; padding: 6px; border-radius: 6px; transition: all 0.2s ease; position: relative; }
        .action-icon-btn:hover { color: #667eea; background-color: #eff6ff; }
        .action-icon-btn.delete-btn:hover { color: #ef4444; background-color: #fee2e2; }
        .tooltip-btn::before { content: attr(data-tooltip); position: absolute; bottom: 120%; left: 50%; transform: translateX(-50%); background-color: #334155; color: white; padding: 5px 10px; border-radius: 6px; font-size: 0.75rem; white-space: nowrap; opacity: 0; visibility: hidden; transition: opacity 0.3s, visibility 0.3s; z-index: 10; }
        .tooltip-btn:hover::before { opacity: 1; visibility: visible; }
        .modern-card-footer { padding: 20px 30px; border-top: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .pagination-info { font-size: 0.9rem; color: #64748b; }
        .pagination-controls { display: flex; gap: 8px; }
        .pagination-controls svg { width: 14px !important; height: 14px !important; vertical-align: middle; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 25px;
        }

        .detail-row {
            padding: 15px 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .detail-value {
            color: #334155;
            font-size: 1rem;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
            font-size: 0.9rem;
        }

        .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            outline: none;
            font-family: inherit;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #edf2f7;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-primary, .btn-secondary {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background-color: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #4a5568;
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modal-content { width: 95%; max-width: none; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>

    {{-- JavaScript --}}
    <script>
        // Show Detail Modal
        function showDetailModal(survey) {
            document.getElementById('detail-judul').textContent = survey.judul;
            document.getElementById('detail-deskripsi').textContent = survey.deskripsi;
            document.getElementById('detail-tanggal-mulai').textContent = formatDate(survey.tanggal_mulai);
            document.getElementById('detail-tanggal-selesai').textContent = formatDate(survey.tanggal_selesai);
            
            const statusBadge = survey.is_active 
                ? '<span class="modern-badge success">Aktif</span>' 
                : '<span class="modern-badge warning">Tidak Aktif</span>';
            document.getElementById('detail-status').innerHTML = statusBadge;
            
            document.getElementById('detailModal').classList.add('show');
        }

        // Close Detail Modal
        function closeDetailModal() {
            document.getElementById('detailModal').classList.remove('show');
        }

        // Show Edit Modal (New Method with data attributes)
        function showEditModalNew(button) {
            const id = button.getAttribute('data-id');
            const judul = button.getAttribute('data-judul');
            const deskripsi = button.getAttribute('data-deskripsi');
            const tanggalMulai = button.getAttribute('data-tanggal-mulai');
            const tanggalSelesai = button.getAttribute('data-tanggal-selesai');
            const isActive = button.getAttribute('data-is-active');
            
            console.log('ID:', id);
            console.log('Judul:', judul);
            console.log('Tanggal Mulai:', tanggalMulai);
            console.log('Tanggal Selesai:', tanggalSelesai);
            console.log('Is Active:', isActive);
            
            document.getElementById('edit-judul').value = judul;
            document.getElementById('edit-deskripsi').value = deskripsi;
            document.getElementById('edit-tanggal-mulai').value = tanggalMulai;
            document.getElementById('edit-tanggal-selesai').value = tanggalSelesai;
            document.getElementById('edit-is-active').value = isActive;
            
            // Set form action
            const editForm = document.getElementById('editForm');
            editForm.action = `/admin/data-survey/${id}`;
            
            document.getElementById('editModal').classList.add('show');
        }

        // Show Edit Modal
        function showEditModal(survey) {
            console.log('Survey data:', survey); // Debug
            
            document.getElementById('edit-judul').value = survey.judul || '';
            document.getElementById('edit-deskripsi').value = survey.deskripsi || '';
            
            // Format tanggal untuk input type="date" (YYYY-MM-DD)
            const tanggalMulai = survey.tanggal_mulai ? survey.tanggal_mulai.split(' ')[0] : '';
            const tanggalSelesai = survey.tanggal_selesai ? survey.tanggal_selesai.split(' ')[0] : '';
            
            document.getElementById('edit-tanggal-mulai').value = tanggalMulai;
            document.getElementById('edit-tanggal-selesai').value = tanggalSelesai;
            
            console.log('Tanggal Mulai:', tanggalMulai); // Debug
            console.log('Tanggal Selesai:', tanggalSelesai); // Debug
            
            document.getElementById('edit-is-active').value = survey.is_active ? '1' : '0';
            
            // Set form action
            const editForm = document.getElementById('editForm');
            editForm.action = `/admin/survey/${survey.id}`;
            
            document.getElementById('editModal').classList.add('show');
        }

        // Close Edit Modal
        function closeEditModal() {
            document.getElementById('editModal').classList.remove('show');
        }

        // Format Date
        function formatDate(dateString) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
            const date = new Date(dateString);
            return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const detailModal = document.getElementById('detailModal');
            const editModal = document.getElementById('editModal');
            
            if (event.target === detailModal) {
                closeDetailModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDetailModal();
                closeEditModal();
            }
        });
    </script>
@endsection