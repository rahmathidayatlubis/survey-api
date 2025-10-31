@extends('layouts.dashboard')

@section('content')

<div class="dashboard-container">
    {{-- Header Section --}}
    <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4); border-radius: 12px; padding: 25px 35px; margin-bottom: 30px;">
        <div class="header-content" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="header-title" style="color: #fff; font-weight: 700; font-size: 1.8rem; margin-bottom: 5px;">
                    <i class="fas fa-file-alt header-icon" style="margin-right: 8px;"></i>
                    Buat Survey Baru
                </h2>
                <p class="header-subtitle" style="color: #e2e8f0; font-size: 0.95rem;">Tentukan detail dasar, pertanyaan, dan opsi jawaban survey.</p>
            </div>
            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.survey') }}" class="modern-action-btn" style="background-color: #fff; color: #4c51bf; font-weight: 600; border-radius: 8px; padding: 10px 16px; display: inline-flex; align-items: center; text-decoration: none; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>


    <form action="{{ route('admin.survey.store') }}" method="POST" id="surveyForm">
        @csrf
        
        {{-- CARD 1: INFORMASI DASAR SURVEY (TIDAK BERUBAH) --}}
        <div class="modern-card" style="margin-bottom: 30px; background: #fff; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.06); padding: 30px;">
            <h3 style="font-weight: 700; font-size: 1.2rem; color: #374151; margin-bottom: 25px;">
                <i class="fas fa-poll-h" style="color:#667eea; margin-right:8px;"></i>Informasi Dasar Survey
            </h3>

            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Judul Survey</label>
                    <input type="text" name="judul" class="form-control" placeholder="Contoh: Evaluasi Kepuasan Pelayanan Akademik" value="{{ old('judul') }}" required>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Deskripsi Lengkap</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan tujuan dan ruang lingkup survey ini secara detail." required>{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" required>
                </div>

                <div class="form-group">
                    <label>Status (Aktif/Nonaktif)</label>
                    <select name="is_active" class="form-control" required>
                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif (Dapat Diisi)</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Tidak Aktif (Draft)</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- CARD 2: PERTANYAAN SURVEY DINAMIS --}}
        <div class="modern-card" style="margin-bottom: 30px; background: #fff; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.06); padding: 30px;">
            <h3 style="font-weight: 700; font-size: 1.2rem; color: #374151; margin-bottom: 25px;">
                <i class="fas fa-question-circle" style="color:#667eea; margin-right:8px;"></i>Daftar Pertanyaan & Opsi Jawaban
            </h3>
            
            <div id="questions-container">
                {{-- Question templates will be inserted here --}}
            </div>

            <div style="margin-top: 25px;">
                <button type="button" id="add-question-btn" class="btn-secondary" style="background-color: #e2e8f0; color: #475569; border-radius: 8px; padding: 12px 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; border: none; transition: all 0.3s ease;">
                    <i class="fas fa-plus-square"></i> Tambah Pertanyaan Baru
                </button>
            </div>
        </div>


        {{-- TOMBOL SUBMIT --}}
        <div class="form-actions" style="margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px;">
            <button type="submit" class="btn-primary" style="background-color: #667eea; color: #fff; border: none; border-radius: 8px; padding: 12px 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-save"></i> Simpan Survey & Pertanyaan
            </button>
            <a href="{{ route('admin.survey') }}" class="btn-secondary" style="background-color: #e2e8f0; color: #475569; border-radius: 8px; padding: 12px 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>


</div>

{{-- Inline Styles for Inputs (TIDAK BERUBAH) --}}
<style>
    .form-group label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
        display: block; 
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        box-sizing: border-box; 
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
        outline: none;
    }

    /* Kustomisasi untuk struktur pertanyaan */
    .question-block {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        background-color: #f7f9fc;
    }

    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px dashed #cbd5e1;
    }

    .delete-q-btn {
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        font-size: 1.2rem;
        transition: color 0.2s;
    }

    .option-row {
        display: grid;
        grid-template-columns: 2fr 1fr auto; /* Teks, Nilai, Tombol Hapus */
        gap: 10px;
        margin-bottom: 8px;
    }

    .add-o-btn {
        background-color: #667eea;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 10px;
    }
    
    .remove-option-btn {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 0 8px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .remove-option-btn:hover {
        background: #ef4444;
        color: white;
        border-color: #ef4444;
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
    
    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr !important;
        }
        .option-row {
             grid-template-columns: 1fr 1fr auto;
        }
    }
</style>

{{-- SCRIPT JAVASCRIPT DINAMIS (FIXED) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const questionsContainer = document.getElementById('questions-container');
    const addQuestionBtn = document.getElementById('add-question-btn');
    let questionCounter = 0; 
    
    // --- 1. Utility Functions ---

    // Function untuk membuat HTML Opsi Jawaban (FIXED)
    const createOptionTemplate = (qIndex, oIndex) => {
        return `
            <div class="option-row" data-option-index="${oIndex}">
                <input type="text" name="questions[${qIndex}][options][${oIndex}][teks_pilihan]" class="form-control" placeholder="Teks Jawaban (Cth: Sangat Baik)" required>
                <input type="number" name="questions[${qIndex}][options][${oIndex}][nilai]" class="form-control" placeholder="Nilai (Bobot)" required min="1">
                <button type="button" class="remove-option-btn" title="Hapus Opsi">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    };
    
    // Function untuk update nomor urut dan name attributes
    const updateQuestionNumbers = () => {
        const questionBlocks = questionsContainer.querySelectorAll('.question-block');
        questionBlocks.forEach((block, index) => {
            const qIndex = index;
            const oldQIndex = parseInt(block.dataset.questionIndex);

            // Update judul dan hidden urutan
            block.querySelector('h4').textContent = `Pertanyaan #${index + 1}`;
            block.querySelector(`input[name$="[urutan]"]`).value = index + 1;
            
            // Update dataset index untuk referensi di listeners
            block.dataset.questionIndex = qIndex; 

            // Re-index nama input pertanyaan dan opsinya
            block.querySelectorAll('[name^="questions"]').forEach(input => {
                let currentName = input.name;
                // Mengganti angka index lama dengan index baru
                let newName = currentName.replace(`questions[${oldQIndex}]`, `questions[${qIndex}]`);
                input.name = newName;
            });

            // Re-index opsi jawaban
            const optionRows = block.querySelectorAll('.option-row');
            optionRows.forEach((row, optIndex) => {
                row.dataset.optionIndex = optIndex;
                row.querySelectorAll('input').forEach(input => {
                    input.name = input.name.replace(/\[options\]\[\d+\]/, `[options][${optIndex}]`);
                });
            });
        });
    };

    // --- 2. Listener Initialization ---

    // Function untuk menginisialisasi semua listener di dalam blok pertanyaan
    const initializeQuestionListeners = (questionBlock) => {
        const qIndex = questionBlock.dataset.questionIndex;

        // 1. Hapus Pertanyaan
        const deleteBtn = questionBlock.querySelector('.delete-q-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                questionBlock.remove();
                updateQuestionNumbers();
            });
        }

        // 2. Tipe Pertanyaan (Menampilkan/Menyembunyikan Opsi)
        const typeSelect = questionBlock.querySelector('.question-type-select');
        if (typeSelect) {
            typeSelect.addEventListener('change', function(e) {
                const optionsArea = questionBlock.querySelector('.options-area');
                if (e.target.value === 'multiple_choice' || e.target.value === 'rating') {
                    optionsArea.style.display = 'block';
                } else {
                    optionsArea.style.display = 'none';
                }
            });
        }

        // 3. Tambah Opsi Jawaban (FIXED!)
        const addOptionBtn = questionBlock.querySelector('.add-option-btn');
        if (addOptionBtn) {
            addOptionBtn.addEventListener('click', function() {
                const optionList = questionBlock.querySelector('.option-list');
                const currentQIndex = questionBlock.dataset.questionIndex;
                const optionCount = optionList.querySelectorAll('.option-row').length;
                
                // Buat elemen baru
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = createOptionTemplate(currentQIndex, optionCount);
                const newOptionRow = tempDiv.firstElementChild;
                
                // Tambahkan ke list
                optionList.appendChild(newOptionRow);
                
                // Tambahkan listener Hapus untuk opsi baru
                const removeBtn = newOptionRow.querySelector('.remove-option-btn');
                if (removeBtn) {
                    removeBtn.addEventListener('click', function() {
                        newOptionRow.remove();
                        updateQuestionNumbers(); // Update index setelah hapus
                    });
                }
            });
        }
        
        // 4. Hapus Opsi yang sudah ada
        questionBlock.querySelectorAll('.remove-option-btn').forEach(btn => {
            // Hapus listener lama untuk menghindari duplikasi
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            newBtn.addEventListener('click', function() {
                this.closest('.option-row').remove();
                updateQuestionNumbers(); // Update index setelah hapus
            });
        });
    };

    // --- 3. Question Creation ---

    // Function untuk membuat template Pertanyaan
    const createQuestionTemplate = () => {
        const qIndex = questionCounter;
        questionCounter++;
        
        const questionHtml = document.createElement('div');
        questionHtml.className = 'question-block';
        questionHtml.dataset.questionIndex = qIndex;
        
        questionHtml.innerHTML = `
            <div class="question-header">
                <h4 style="font-weight: 600; color: #5a67d8;">Pertanyaan #${qIndex + 1}</h4>
                <button type="button" class="delete-q-btn" title="Hapus Pertanyaan"><i class="fas fa-trash-alt"></i></button>
            </div>
            
            <div class="form-group">
                <label>Isi Pertanyaan</label>
                <input type="text" name="questions[${qIndex}][pertanyaan]" class="form-control" placeholder="Masukkan pertanyaan survey" required>
            </div>
            
            <div class="form-group" style="margin-top: 15px;">
                <label>Tipe Pertanyaan</label>
                <select name="questions[${qIndex}][tipe]" class="form-control question-type-select" required>
                    <option value="multiple_choice" selected>Pilihan Ganda (Berbobot)</option>
                    <option value="rating">Rating (Angka)</option>
                    <option value="text">Input Teks Bebas</option>
                </select>
            </div>
            
            <div class="options-area" style="margin-top: 20px;">
                <h5 style="font-weight: 600; color: #334155; margin-bottom: 10px;">Opsi Jawaban & Bobot Nilai</h5>
                <div class="option-list">
                    ${createOptionTemplate(qIndex, 0)}
                </div>
                <button type="button" class="add-option-btn add-o-btn">
                    <i class="fas fa-plus"></i> Tambah Opsi Jawaban
                </button>
            </div>

            <input type="hidden" name="questions[${qIndex}][urutan]" value="${qIndex + 1}">
        `;
        
        questionsContainer.appendChild(questionHtml);
        initializeQuestionListeners(questionHtml);
        updateQuestionNumbers();
    };

    // --- 4. Main Execution ---
    
    // Inisialisasi Pertanyaan Awal saat load (jika container kosong)
    if (questionsContainer.children.length === 0) {
        createQuestionTemplate();
    }

    // Tambah Pertanyaan saat tombol diklik
    addQuestionBtn.addEventListener('click', createQuestionTemplate);
});
</script>

@endsection