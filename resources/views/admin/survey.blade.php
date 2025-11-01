@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center mb-4">
            <div class="card rounded-0">
                <div class="card-body">
                    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Daftar Survey</h1>
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
                                data-target="#createModal">Tambah
                                Survey</button>
                        </div>

                        <table id="dataTable" class="table table-hover table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Periode</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($surveys as $survey)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $survey->judul }}</td>
                                        <td>{{ $survey->tanggal_mulai->toDateString() }} s.d.
                                            {{ $survey->tanggal_selesai->toDateString() }}
                                        </td>
                                        <td class="text-center">
                                            @if ($survey->is_active == 1)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center d-flex justify-content-center">
                                            {{-- Lihat Detail --}}
                                            <a href="/admin/surveys/{{ $survey->uuid }}/edit"
                                                class="btn mr-1 btn-sm btn-info" data-tooltip="Tambah Pertanyaan">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Edit --}}
                                            <button type="button" class="btn mr-1 btn-sm btn-warning" data-toggle="modal"
                                                data-target="#updateModal{{ $survey->uuid }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- Hapus --}}
                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-tooltip="Hapus" data-uuid="{{ $survey->uuid }}"
                                                data-judul="{{ $survey->judul }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Update --}}
                                    <div class="modal fade" id="updateModal{{ $survey->uuid }}" data-backdrop="static"
                                        data-keyboard="false" tabindex="-1"
                                        aria-labelledby="updateModal{{ $survey->uuid }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <form action="/admin/surveys/{{ $survey->uuid }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="updateModal{{ $survey->uuid }}">Edit
                                                            Survey: {{ $survey->judul }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="p-0 p-md-4">
                                                            <div class="form-group">
                                                                <label for="judul">Judul Survey</label>
                                                                <input type="text" class="form-control rounded-0"
                                                                    id="judul" name="judul" required
                                                                    value="{{ old('judul', $survey->judul) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="deskripsi">Deskripsi</label>
                                                                <textarea name="deskripsi" class="form-control rounded-0" id="deskripsi" required>{{ old('deskripsi', $survey->deskripsi) }}</textarea>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="tanggal_mulai">Tanggal Mulai</label>
                                                                        <input type="date" class="form-control rounded-0"
                                                                            name="tanggal_mulai" id="tanggal_mulai"
                                                                            aria-describedby="tanggal_mulailHelp"
                                                                            autocomplete="off" required
                                                                            value="{{ old('tanggal_mulai', $survey->tanggal_mulai->format('Y-m-d')) }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label for="tanggal_selesai">Tanggal Selesai</label>
                                                                        <input type="date" class="form-control rounded-0"
                                                                            name="tanggal_selesai" id="tanggal_selesai"
                                                                            aria-describedby="tanggal_selesailHelp"
                                                                            autocomplete="off" required
                                                                            value="{{ old('tanggal_selesai', $survey->tanggal_selesai->format('Y-m-d')) }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="is_active">Status</label>
                                                                <select class="form-control" id="is_active"
                                                                    name="is_active" required>
                                                                    <option value="" disabled>-- Pilih
                                                                        Status --</option>
                                                                    <option value="1"
                                                                        {{ old('is_active', $survey->is_active) ? 'selected' : '' }}>
                                                                        Aktif</option>
                                                                    <option value="0"
                                                                        {{ old('is_active', $survey->is_active) ? '' : 'selected' }}>
                                                                        Tidak Aktif</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Tambah</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal Create --}}
        <div class="modal fade" id="createModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            aria-labelledby="createModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <form action="/admin/surveys" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="createModal">Tambah Survey Baru</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="p-0 p-md-4">
                                <div class="form-group">
                                    <label for="judul">Judul Survey</label>
                                    <input type="text" class="form-control rounded-0" id="judul" name="judul"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control rounded-0" id="deskripsi" required></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_mulai">Tanggal Mulai</label>
                                            <input type="date" class="form-control rounded-0" name="tanggal_mulai"
                                                id="tanggal_mulai" aria-describedby="tanggal_mulailHelp"
                                                autocomplete="off" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tanggal_selesai">Tanggal Selesai</label>
                                            <input type="date" class="form-control rounded-0" name="tanggal_selesai"
                                                id="tanggal_selesai" aria-describedby="tanggal_selesailHelp"
                                                autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="is_active">Status</label>
                                    <select class="form-control" id="is_active" name="is_active" required>
                                        <option value="" disabled selected>-- Pilih Status --</option>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        const swal = $('.swal').data('swal');

        if (swal) {
            Swal.fire({
                title: 'Berhasil',
                text: swal,
                showConfirmButton: false,
                icon: 'success',
                timer: 1600
            })
        }

        // Handle delete with SweetAlert
        $(document).on('click', '.delete-btn', function() {
            let uuid = $(this).data('uuid');
            let judul = $(this).data('judul');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus survey "${judul}". Tindakan ini tidak bisa dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/surveys/${uuid}`,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Dihapus!',
                                text: 'Survey berhasil dihapus.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "{{ url('/admin/surveys') }}";
                                }
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal menghapus survey.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            // Tidak redirect saat error
                        }
                    });
                }
            });
        });
    </script>
@endpush
