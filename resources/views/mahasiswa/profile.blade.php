@extends('layouts.dashboard')

@section('content')
    <div class="container-fluid">

        <div class="d-sm-flex align-items-center mb-4">
            <div class="card rounded-0">
                <div class="card-body">
                    <h1 class="h4 mb-0 text-gray-800 font-weight-bold">Profil Mahasiswa</h1>
                </div>
            </div>
        </div>

        <div class="card rounded-0">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <i class="fas fa-user-circle fa-7x text-secondary mb-3"></i>
                        <h5 class="font-weight-bold mb-1">{{ Auth::user()->nama }}</h5>
                        <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editProfileModal">
                            <i class="fas fa-edit"></i> Edit Profil
                        </button>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="35%">NIM</th>
                                <td>{{ Auth::user()->nim }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>{{ Auth::user()->tanggal_lahir->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th>Program Studi</th>
                                <td>{{ Auth::user()->jurusan }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td><span class="badge badge-info">{{ Auth::user()->role }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Edit Profil --}}
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('mahasiswa.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Edit Profil</h5>
                            <button type="button" class="close text-white"
                                data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" name="nama" class="form-control" value="{{ Auth::user()->nama }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}"
                                    required>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    value="{{ Auth::user()->tanggal_lahir->format('Y-m-d') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Program Studi</label>
                                <input type="text" name="jurusan" class="form-control"
                                    value="{{ Auth::user()->jurusan }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
