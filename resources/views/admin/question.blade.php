@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center mb-4">
            <div class="card rounded-0">
                <div class="card-body">
                    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
                        Kelola Pertanyaan: {{ $survey->judul }}
                    </h1>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card rounded-0">
                    <div class="card-body table-responsive p-5">
                        <div class="mb-2">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#createQuestionModal">
                                Tambah Pertanyaan
                            </button>
                        </div>

                        <table id="dataTable" class="table table-hover table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th>Pertanyaan</th>
                                    <th>Tipe</th>
                                    <th>Pilihan Jawaban</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($survey->questions as $question)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $question->pertanyaan }}</td>
                                        <td>
                                            <span
                                                class="badge bg-secondary text-white">{{ ucfirst($question->tipe) }}</span>
                                        </td>
                                        <td class="text-center" style="max-width:200px">
                                            @if (in_array($question->tipe, ['multiple_choice', 'rating']))
                                                @forelse ($question->options as $option)
                                                    <span
                                                        class="badge bg-info text-white">{{ $option->teks_pilihan }}</span>
                                                @empty
                                                    <span class="text-muted small">-</span>
                                                @endforelse
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-warning mr-1" data-toggle="modal"
                                                data-target="#editQuestionModal{{ $question->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="/admin/questions/{{ $question->id }}" method="POST"
                                                class="delete-form d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-danger delete-btn">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Tidak ada pertanyaan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Create --}}
        <div class="modal fade" id="createQuestionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Tambah Pertanyaan Baru</h5>
                        <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <form action="/admin/surveys/{{ $survey->uuid }}/questions" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Pertanyaan</label>
                                <textarea class="form-control" name="pertanyaan" rows="3" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Tipe</label>
                                <select class="form-control" id="create_tipe" name="tipe" required>
                                    <option value="" disabled selected>Pilih...</option>
                                    <option value="multiple_choice">Multiple Choice</option>
                                </select>
                            </div>

                            <div id="create_options_container" style="display:none;">
                                <h6>Pilihan Jawaban:</h6>
                                <div id="create_options_list">
                                    <div class="row mb-2 align-items-center">
                                        <div class="col-md-5"><input type="text" class="form-control"
                                                name="options[0][teks_pilihan]" placeholder="Teks Pilihan"></div>
                                        <div class="col-md-5"><input type="number" class="form-control"
                                                name="options[0][nilai]" placeholder="Nilai (1-5)" min="1"
                                                max="5"></div>
                                        <div class="col-md-2 text-center"><button type="button"
                                                class="btn btn-sm btn-danger" onclick="removeOption(this)">×</button></div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                    onclick="addCreateOption()">+ Tambah Pilihan</button>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Edit --}}
        @foreach ($survey->questions as $question)
            <div class="modal fade" id="editQuestionModal{{ $question->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Edit Pertanyaan</h5>
                            <button type="button" class="close text-white"
                                data-dismiss="modal"><span>&times;</span></button>
                        </div>

                        <form action="/admin/questions/{{ $question->id }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Pertanyaan</label>
                                    <textarea class="form-control" name="pertanyaan" rows="3" required>{{ $question->pertanyaan }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label>Tipe</label>
                                    <select class="form-control edit_tipe" id="edit_tipe_{{ $question->id }}"
                                        name="tipe" required>
                                        <option value="multiple_choice"
                                            {{ $question->tipe == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice
                                        </option>
                                    </select>
                                </div>

                                <div id="edit_options_container_{{ $question->id }}"
                                    style="display: {{ $question->tipe == 'multiple_choice' ? 'block' : 'none' }}">
                                    <h6>Pilihan Jawaban:</h6>
                                    <div id="edit_options_list_{{ $question->id }}">
                                        @foreach ($question->options as $index => $option)
                                            <div class="row mb-2 align-items-center">
                                                <input type="hidden" name="options[{{ $index }}][id]"
                                                    value="{{ $option->id }}">
                                                <div class="col-md-5"><input type="text" class="form-control"
                                                        name="options[{{ $index }}][teks_pilihan]"
                                                        value="{{ $option->teks_pilihan }}"></div>
                                                <div class="col-md-5"><input type="number" class="form-control"
                                                        name="options[{{ $index }}][nilai]"
                                                        value="{{ $option->nilai }}" min="1" max="5">
                                                </div>
                                                <div class="col-md-2 text-center"><button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        onclick="removeOption(this)">×</button></div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                        onclick="addEditOption({{ $question->id }})">+ Tambah Pilihan</button>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary text-white">Perbarui</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ==== Create ====
            const createSelect = document.getElementById('create_tipe');
            const createOptions = document.getElementById('create_options_container');
            createSelect.addEventListener('change', () => {
                createOptions.style.display = (createSelect.value === 'multiple_choice') ? 'block' : 'none';
            });

            let createIndex = 1;
            window.addCreateOption = function() {
                const list = document.getElementById('create_options_list');
                const row = document.createElement('div');
                row.className = 'row mb-2 align-items-center';
                row.innerHTML =
                    `
            <div class="col-md-5"><input type="text" class="form-control" name="options[${createIndex}][teks_pilihan]" placeholder="Teks Pilihan"></div>
            <div class="col-md-5"><input type="number" class="form-control" name="options[${createIndex}][nilai]" placeholder="Nilai (1-5)" min="1" max="5"></div>
            <div class="col-md-2 text-center"><button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this)">×</button></div>`;
                list.appendChild(row);
                createIndex++;
            };

            // ==== Edit ====
            window.addEditOption = function(qid) {
                const list = document.getElementById('edit_options_list_' + qid);
                const index = list.querySelectorAll('.row').length;
                const row = document.createElement('div');
                row.className = 'row mb-2 align-items-center';
                row.innerHTML =
                    `
            <div class="col-md-5"><input type="text" class="form-control" name="options[${index}][teks_pilihan]" placeholder="Teks Pilihan Baru"></div>
            <div class="col-md-5"><input type="number" class="form-control" name="options[${index}][nilai]" placeholder="Nilai (1-5)" min="1" max="5"></div>
            <div class="col-md-2 text-center"><button type="button" class="btn btn-sm btn-danger" onclick="removeOption(this)">×</button></div>`;
                list.appendChild(row);
            };

            // tampilkan/ sembunyikan kontainer opsi di edit modal
            document.querySelectorAll('.edit_tipe').forEach(sel => {
                sel.addEventListener('change', function() {
                    const id = this.id.replace('edit_tipe_', '');
                    document.getElementById('edit_options_container_' + id).style.display =
                        (this.value === 'multiple_choice') ? 'block' : 'none';
                });
            });

            // ==== Hapus baris opsi ====
            window.removeOption = function(btn) {
                btn.closest('.row').remove();
            };

            // ==== Swal delete ====
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Hapus Pertanyaan?',
                        text: 'Data pertanyaan dan semua pilihannya akan dihapus permanen.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6'
                    }).then(result => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
@endpush
