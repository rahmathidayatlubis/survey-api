@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center mb-4">
            <div class="card rounded-0">
                <div class="card-body">
                    <h1 class="h3 mb-0 text-gray-800 font-weight-bold">Daftar Mahasiswa</h1>
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
                                data-target="#createModal">Tambah Mahasiswa</button>
                        </div>

                        <table id="dataTable" class="table table-hover table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">NIM</th>
                                    <th scope="col">Program Studi</th>
                                    <th scope="col">Registrasi Akun</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $user->nama }}</td>
                                        <td>{{ $user->nim }}</td>
                                        <td>{{ $user->jurusan }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td class="text-center d-flex justify-content-center">
                                            {{-- Edit --}}
                                            <button type="button" class="btn mr-1 btn-sm btn-warning" data-toggle="modal"
                                                data-target="#updateModal{{ $user->uuid }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            {{-- Hapus --}}
                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-tooltip="Hapus" data-uuid="{{ $user->uuid }}"
                                                data-nama="{{ $user->nama }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Update --}}
                                    <div class="modal fade" id="updateModal{{ $user->uuid }}" data-backdrop="static"
                                        data-keyboard="false" tabindex="-1"
                                        aria-labelledby="updateModal{{ $user->uuid }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <form action="/admin/students/{{ $user->uuid }}" method="post">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="updateModal{{ $user->uuid }}">Edit
                                                            Data: {{ $user->nama }}</h5>
                                                        <button type="button" class="close text-white" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="p-0 p-md-4">
                                                            <div class="form-group">
                                                                <label for="nim">NIM</label>
                                                                <input type="text" class="form-control rounded-0"
                                                                    id="nim" name="nim" required
                                                                    value="{{ old('nim', $user->nim) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="nama">Nama</label>
                                                                <input type="text" class="form-control rounded-0"
                                                                    id="nama" name="nama" required
                                                                    value="{{ old('nama', $user->nama) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="email">Email</label>
                                                                <input type="email" class="form-control rounded-0"
                                                                    id="email" name="email" required
                                                                    value="{{ old('email', $user->email) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                                                <input type="date" class="form-control rounded-0"
                                                                    name="tanggal_lahir" id="tanggal_lahir"
                                                                    aria-describedby="tanggal_lahirlHelp" autocomplete="off"
                                                                    required
                                                                    value="{{ old('tanggal_lahir', $user->tanggal_lahir->format('Y-m-d')) }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="jurusan">Program Studi</label>
                                                                <input type="text" class="form-control rounded-0"
                                                                    id="jurusan" name="jurusan" required
                                                                    value="{{ old('jurusan', $user->jurusan) }}">
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan
                                                            Perubahan</button>
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
                <form action="/admin/students" method="post">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="createModal">Tambah Mahasiswa Baru</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="p-0 p-md-4">
                                <div class="form-group">
                                    <label for="nim">NIM</label>
                                    <input type="text" class="form-control rounded-0" id="nim" name="nim"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama</label>
                                    <input type="text" class="form-control rounded-0" id="nama" name="nama"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control rounded-0" id="email" name="email"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" class="form-control rounded-0" name="tanggal_lahir"
                                        id="tanggal_lahir" aria-describedby="tanggal_lahirlHelp" autocomplete="off"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="jurusan">Program Studi</label>
                                    <input type="text" class="form-control rounded-0" id="jurusan" name="jurusan"
                                        required>
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
        // Handle delete with SweetAlert
        $(document).on('click', '.delete-btn', function() {
            let uuid = $(this).data('uuid');
            let nama = $(this).data('nama');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Anda akan menghapus mahasiswa "${nama}". Tindakan ini tidak bisa dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/admin/students/${uuid}`,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Dihapus!',
                                text: 'Mahasiswa berhasil dihapus.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href =
                                        "{{ url('/admin/students') }}";
                                }
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal menghapus mahasiswa.',
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
