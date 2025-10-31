@extends('layouts.dashboard')

@section('content')

<div class="dashboard-container">
    {{-- Header Section --}}
    <div class="modern-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4); border-radius: 12px; padding: 25px 35px; margin-bottom: 30px;">
        <div class="header-content" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="header-title" style="color: #fff; font-weight: 700; font-size: 1.8rem; margin-bottom: 5px;">
                    <i class="fas fa-edit header-icon" style="margin-right: 8px;"></i>
                    Edit Survey: {{ $survey->judul }}
                </h2>
                <p class="header-subtitle" style="color: #e2e8f0; font-size: 0.95rem;">Perbarui detail survey, pertanyaan, dan opsi jawaban.</p>
            </div>
            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.survey') }}" class="modern-action-btn" style="background-color: #fff; color: #4c51bf; font-weight: 600; border-radius: 8px; padding: 10px 16px; display: inline-flex; align-items: center; text-decoration: none; transition: all 0.3s ease;">
                <i class="fas fa-arrow-left" style="margin-right: 6px;"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>


    <form action="{{ route('admin.survey.update', $survey->uuid) }}" method="POST" id="surveyForm">
        @csrf
        @method('PUT')

        {{-- CARD 1: INFORMASI DASAR SURVEY --}}
        <div class="modern-card" style="margin-bottom: 30px; background: #fff; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.06); padding: 30px;">
            <h3 style="font-weight: 700; font-size: 1.2rem; color: #374151; margin-bottom: 25px;">
                <i class="fas fa-poll-h" style="color:#667eea; margin-right:8px;"></i>Perbarui Informasi Survey
            </h3>

            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">
                
                {{-- Judul Survey --}}
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Judul Survey</label>
                    <input type="text" name="judul" class="form-control" value="{{ old('judul', $survey->judul) }}" required>
                </div>

                {{-- Deskripsi Survey --}}
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Deskripsi Lengkap</label>
                    <textarea name="deskripsi" class="form-control" rows="3" required>{{ old('deskripsi', $survey->deskripsi) }}</textarea>
                </div>

                {{-- Tanggal Mulai --}}
                <div class="form-group">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $survey->tanggal_mulai) }}" required>
                </div>

                {{-- Tanggal Selesai --}}
                <div class="form-group">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $survey->tanggal_selesai) }}" required>
                </div>

                {{-- Status Aktif --}}
                <div class="form-group">
                    <label>Status (Aktif/Nonaktif)</label>
                    <select name="is_active" class="form-control" required>
                        <option value="1" {{ old('is_active', $survey->is_active) == 1 ? 'selected' : '' }}>Aktif (Dapat Diisi)</option>
                        <option value="0" {{ old('is_active', $survey->is_active) == 0 ? 'selected' : '' }}>Tidak Aktif (Draft)</option>
                    </select>
                </div>

            </div>
        </div>

        {{-- CARD 2: PERTANYAAN SURVEY DINAMIS (Data Terisi Otomatis) --}}
        <div class="modern-card" style="margin-bottom: 30px; background: #fff; border-radius: 12px; box-shadow: 0 10px 20px rgba(0,0,0,0.06); padding: 30px;">
            <h3 style="font-weight: 700; font-size: 1.2rem; color: #374151; margin-bottom: 25px;">
                <i class="fas fa-question-circle" style="color:#667eea; margin-right:8px;"></i>Daftar Pertanyaan & Opsi Jawaban
            </h3>
            
            <div id="questions-container">
                {{-- LOOP UNTUK MENGISI PERTANYAAN YANG SUDAH ADA --}}
                @foreach ($survey->questions as $qIndex => $question)
                    <div class="question-block" data-question-index="{{ $qIndex }}" data-db-id="{{ $question->id }}">
                        <div class="question-header">
                            <h4 style="font-weight: 600; color: #5a67d8;">Pertanyaan #{{ $qIndex + 1 }}</h4>
                            <button type="button" class="delete-q-btn" title="Hapus Pertanyaan"><i class="fas fa-trash-alt"></i></button>
                        </div>
                        
                        {{-- Hidden ID Pertanyaan (PENTING UNTUK UPDATE) --}}
                        <input type="hidden" name="questions[{{ $qIndex }}][id]" value="{{ $question->id }}">
                        
                        <div class="form-group">
                            <label>Isi Pertanyaan</label>
                            <input type="text" name="questions[{{ $qIndex }}][pertanyaan]" class="form-control" value="{{ old('questions.'.$qIndex.'.pertanyaan', $question->pertanyaan) }}" required>
                        </div>
                        
                        <div class="form-group" style="margin-top: 15px;">
                            <label>Tipe Pertanyaan</label>
                            <select name="questions[{{ $qIndex }}][tipe]" class="form-control question-type-select" required>
                                <option value="multiple_choice" {{ old('questions.'.$qIndex.'.tipe', $question->tipe) == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda (Berbobot)</option>
                                <option value="rating" {{ old('questions.'.$qIndex.'.tipe', $question->tipe) == 'rating' ? 'selected' : '' }}>Rating (Angka)</option>
                                <option value="text" {{ old('questions.'.$qIndex.'.tipe', $question->tipe) == 'text' ? 'selected' : '' }}>Input Teks Bebas</option>
                            </select>
                        </div>
                        
                        {{-- Opsi Jawaban (Hanya terlihat jika tipe Multiple Choice/Rating) --}}
                        <div class="options-area" style="margin-top: 20px; display: {{ in_array($question->tipe, ['multiple_choice', 'rating']) ? 'block' : 'none' }};">
                            <h5 style="font-weight: 600; color: #334155; margin-bottom: 10px;">Opsi Jawaban & Bobot Nilai</h5>
                            <div class="option-list">
                                {{-- LOOP UNTUK MENGISI OPSI JAWABAN YANG SUDAH ADA --}}
                                @foreach ($question->options as $oIndex => $option)
                                    <div class="option-row" data-option-index="{{ $oIndex }}" data-db-id="{{ $option->id }}">
                                        {{-- Hidden ID Opsi (PENTING UNTUK UPDATE) --}}
                                        <input type="hidden" name="questions[{{ $qIndex }}][options][{{ $oIndex }}][id]" value="{{ $option->id }}">

                                        <input type="text" name="questions[{{ $qIndex }}][options][{{ $oIndex }}][teks_pilihan]" class="form-control" placeholder="Teks Jawaban" value="{{ old('questions.'.$qIndex.'.options.'.$oIndex.'.teks_pilihan', $option->teks_pilihan) }}" required>
                                        <input type="number" name="questions[{{ $qIndex }}][options][{{ $oIndex }}][nilai]" class="form-control" placeholder="Nilai (Bobot)" value="{{ old('questions.'.$qIndex.'.options.'.$oIndex.'.nilai', $option->nilai) }}" required min="1">
                                        <button type="button" class="remove-option-btn" title="Hapus Opsi">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="add-option-btn add-o-btn">
                                <i class="fas fa-plus"></i> Tambah Opsi Jawaban
                            </button>
                        </div>

                        {{-- Urutan (Hidden Field) --}}
                        <input type="hidden" name="questions[{{ $qIndex }}][urutan]" value="{{ old('questions.'.$qIndex.'.urutan', $question->urutan) }}">
                    </div>
                @endforeach
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
                <i class="fas fa-sync-alt"></i> Update Survey & Pertanyaan
            </button>
            <a href="{{ route('admin.survey') }}" class="btn-secondary" style="background-color: #e2e8f0; color: #475569; border-radius: 8px; padding: 12px 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.3s ease;">
                <i class="fas fa-times"></i> Batal
            </a>
        </div>
    </form>
</div>

{{-- Inline Styles and JavaScript (FIXED) --}}
<style>
    /* ... (CSS Anda yang sudah ada, tidak perlu diulang) ... */
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
    /* ... (rest of CSS) ... */

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

{{-- SCRIPT JAVASCRIPT DINAMIS UNTUK EDIT --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questionsContainer = document.getElementById('questions-container');
        const addQuestionBtn = document.getElementById('add-question-btn');
        
        // Counter untuk pertanyaan BARU (mulai dari jumlah pertanyaan yang sudah ada)
        let questionCounter = questionsContainer.querySelectorAll('.question-block').length;
        
        // --- 1. Utility Functions ---

        // Function untuk membuat HTML Opsi Jawaban BARU
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
        
        // Function untuk update nomor urut dan re-index name attributes
        const updateQuestionNumbers = () => {
            const questionBlocks = questionsContainer.querySelectorAll('.question-block');
            questionBlocks.forEach((block, newIndex) => {
                const oldIndex = parseInt(block.dataset.questionIndex);

                // Update judul pertanyaan
                const headerTitle = block.querySelector('h4');
                if (headerTitle) {
                    headerTitle.textContent = `Pertanyaan #${newIndex + 1}`;
                }

                // Update dataset index
                block.dataset.questionIndex = newIndex;

                // Update hidden urutan field
                const urutanField = block.querySelector('input[name$="[urutan]"]');
                if (urutanField) {
                    urutanField.value = newIndex + 1;
                    urutanField.name = `questions[${newIndex}][urutan]`;
                }

                // Update semua input dalam pertanyaan ini
                block.querySelectorAll('input, select, textarea').forEach(input => {
                    if (input.name && input.name.startsWith('questions[')) {
                        // Ganti index pertanyaan lama dengan yang baru
                        input.name = input.name.replace(`questions[${oldIndex}]`, `questions[${newIndex}]`);
                    }
                });

                // Re-index opsi jawaban
                const optionRows = block.querySelectorAll('.option-row');
                optionRows.forEach((row, optIndex) => {
                    row.dataset.optionIndex = optIndex;
                    
                    row.querySelectorAll('input').forEach(input => {
                        if (input.name && input.name.includes('[options]')) {
                            // Ganti index opsi dengan yang baru
                            input.name = input.name.replace(/\[options\]\[\d+\]/, `[options][${optIndex}]`);
                        }
                    });
                });
            });
        };
        
        // --- 2. Event Listener Functions ---

        // Function untuk handle penghapusan pertanyaan
        const handleDeleteQuestion = (questionBlock) => {
            return function() {
                if (confirm('Yakin ingin menghapus pertanyaan ini?')) {
                    questionBlock.remove();
                    updateQuestionNumbers();
                }
            };
        };

        // Function untuk handle perubahan tipe pertanyaan
        const handleQuestionTypeChange = (questionBlock) => {
            return function(e) {
                const optionsArea = questionBlock.querySelector('.options-area');
                if (optionsArea) {
                    if (e.target.value === 'multiple_choice' || e.target.value === 'rating') {
                        optionsArea.style.display = 'block';
                    } else {
                        optionsArea.style.display = 'none';
                    }
                }
            };
        };

        // Function untuk handle penambahan opsi
        const handleAddOption = (questionBlock) => {
            return function() {
                const optionList = questionBlock.querySelector('.option-list');
                if (!optionList) return;

                const currentQIndex = questionBlock.dataset.questionIndex;
                const optionCount = optionList.querySelectorAll('.option-row').length;
                
                // Buat elemen baru
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = createOptionTemplate(currentQIndex, optionCount);
                const newOptionRow = tempDiv.firstElementChild;
                
                // Tambahkan ke list
                if (newOptionRow) {
                    optionList.appendChild(newOptionRow);
                    
                    // Tambahkan listener untuk tombol hapus
                    const removeBtn = newOptionRow.querySelector('.remove-option-btn');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function() {
                            if (confirm('Yakin ingin menghapus opsi ini?')) {
                                newOptionRow.remove();
                                updateQuestionNumbers();
                            }
                        });
                    }
                }
            };
        };

        // Function untuk handle penghapusan opsi
        const handleRemoveOption = (optionRow) => {
            return function() {
                if (confirm('Yakin ingin menghapus opsi ini?')) {
                    optionRow.remove();
                    updateQuestionNumbers();
                }
            };
        };
        
        // --- 3. Initialize Listeners for Question Block ---

        const initializeQuestionListeners = (questionBlock) => {
            // 1. Tombol Hapus Pertanyaan
            const deleteBtn = questionBlock.querySelector('.delete-q-btn');
            if (deleteBtn) {
                // Remove existing listeners
                const newDeleteBtn = deleteBtn.cloneNode(true);
                deleteBtn.parentNode.replaceChild(newDeleteBtn, deleteBtn);
                newDeleteBtn.addEventListener('click', handleDeleteQuestion(questionBlock));
            }

            // 2. Select Tipe Pertanyaan
            const typeSelect = questionBlock.querySelector('.question-type-select');
            if (typeSelect) {
                // Remove existing listeners
                const newTypeSelect = typeSelect.cloneNode(true);
                typeSelect.parentNode.replaceChild(newTypeSelect, typeSelect);
                newTypeSelect.addEventListener('change', handleQuestionTypeChange(questionBlock));
            }

            // 3. Tombol Tambah Opsi
            const addOptionBtn = questionBlock.querySelector('.add-option-btn');
            if (addOptionBtn) {
                // Remove existing listeners
                const newAddBtn = addOptionBtn.cloneNode(true);
                addOptionBtn.parentNode.replaceChild(newAddBtn, addOptionBtn);
                newAddBtn.addEventListener('click', handleAddOption(questionBlock));
            }
            
            // 4. Tombol Hapus Opsi (untuk semua opsi yang ada)
            const removeOptionBtns = questionBlock.querySelectorAll('.remove-option-btn');
            removeOptionBtns.forEach(btn => {
                const optionRow = btn.closest('.option-row');
                if (optionRow) {
                    // Remove existing listeners
                    const newBtn = btn.cloneNode(true);
                    btn.parentNode.replaceChild(newBtn, btn);
                    newBtn.addEventListener('click', handleRemoveOption(optionRow));
                }
            });
        };
        
        // --- 4. Create New Question Template ---

        const createQuestionTemplate = () => {
            const qIndex = questionCounter;
            questionCounter++;
            
            const questionBlock = document.createElement('div');
            questionBlock.className = 'question-block';
            questionBlock.dataset.questionIndex = qIndex;
            
            // PENTING: Tidak ada Blade syntax di sini, murni HTML
            questionBlock.innerHTML = `
                <div class="question-header">
                    <h4 style="font-weight: 600; color: #5a67d8;">Pertanyaan #${qIndex + 1}</h4>
                    <button type="button" class="delete-q-btn" title="Hapus Pertanyaan">
                        <i class="fas fa-trash-alt"></i>
                    </button>
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
                
                <div class="options-area" style="margin-top: 20px; display: block;">
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
            
            questionsContainer.appendChild(questionBlock);
            initializeQuestionListeners(questionBlock);
            updateQuestionNumbers();
        };

        // --- 5. Main Initialization ---
        
        // Inisialisasi listener untuk semua pertanyaan yang sudah ada (dari database)
        const existingQuestions = questionsContainer.querySelectorAll('.question-block');
        existingQuestions.forEach(block => {
            initializeQuestionListeners(block);
        });

        // Event listener untuk tombol "Tambah Pertanyaan Baru"
        if (addQuestionBtn) {
            addQuestionBtn.addEventListener('click', createQuestionTemplate);
        }
        
        // Update numbering saat pertama kali load
        updateQuestionNumbers();
        
        console.log('Survey Edit Form initialized with', existingQuestions.length, 'existing questions');
    });
</script>

@endsection