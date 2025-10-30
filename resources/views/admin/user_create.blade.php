@extends('layouts.dashboard')

@section('content')

<div class="dashboard-container">
    {{-- Header Section --}}
    <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4); border-radius: 12px; padding: 25px 35px; margin-bottom: 30px;">
        <div class="header-content" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="header-title" style="color: #fff; font-weight: 700; font-size: 1.8rem; margin-bottom: 5px;">
                    <i class="fas fa-user-plus header-icon" style="margin-right: 8px;"></i>
                    Tambah Mahasiswa Baru
                </h2>
                <p class="header-subtitle" style="color: #e2e8f0; font-size: 0.95rem;">Isi data mahasiswa dengan lengkap dan benar.</p>
            </div>
            <a href="{{ route('admin.user') }}" class="modern-action-btn" style="background-color: #fff; color: #4c51bf; font-weight: 600; border-radius: 8px; padding: 10px 16px; display: inline-flex; align-items: center; text-decoration: none; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>


{{-- Form Section --}}
<div class="modern-card" style="background: #fff; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.06); padding: 30px;">
    <h3 style="font-weight: 700; font-size: 1.2rem; color: #374151; margin-bottom: 25px;">
        <i class="fas fa-id-card" style="color:#667eea; margin-right:8px;"></i>Formulir Mahasiswa
    </h3>

    <form action="{{ route('admin.user.store') }}" method="POST">
        @csrf
        <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label>NIM</label>
                <input type="text" name="nim" class="form-control" placeholder="Masukkan NIM" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" placeholder="Masukkan email aktif" required>
            </div>

            <div class="form-group">
                <label>Program Studi</label>
                <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Teknik Informatika" required>
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    <option value="" disabled selected>Pilih Role</option>
                    <option value="mahasiswa">Mahasiswa</option>
                    <option value="dosen">Dosen</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="Aktif" selected>Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
        </div>

        <div class="form-actions" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
            <button type="submit" class="btn-primary" style="background-color: #667eea; color: #fff; border: none; border-radius: 8px; padding: 12px 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-save"></i> Simpan Data
            </button>
            <a href="{{ route('admin.user') }}" class="btn-secondary" style="background-color: #e2e8f0; color: #475569; border-radius: 8px; padding: 12px 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>


</div>

{{-- Inline Styles for Inputs --}}

<style>
    .form-group label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        outline: none;
    }

    .modern-action-btn:hover {
        background-color: #edf2f7 !important;
        transform: translateY(-2px);
    }

    .btn-primary:hover {
        background-color: #5a67d8 !important;
        transform: translateY(-2px);
    }

    .btn-secondary:hover {
        background-color: #cbd5e1 !important;
        transform: translateY(-2px);
    }
</style>

@endsection
