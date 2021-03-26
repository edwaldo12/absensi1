@extends('layouts.index')

@section('content')
    <!-- Header -->
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <button class="btn btn-sm btn-neutral" id="btnAddAdmin" data-toggle="modal"
                            data-target="#addAdminModal">
                            <i class="fa fa-plus"></i>
                            Tambah Admin
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Data Admin</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="user_datatables" style="min-width:100%">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>

                    <!-- Card footer -->
                    <div class="card-footer py-4">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAdminModal" tabindex="-1" role="dialog" aria-labelledby="addAdminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdminModalLabel">Tambah Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAdminForm">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="name" placeholder="Masukkan Nama" name="name" class="form-control">
                            <small id="name_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" placeholder="Masukkan Username" name="username"
                                class="form-control">
                            <small id="username_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" placeholder="Masukkan Password" name="password"
                                class="form-control">
                            <small id="password_error" class="text-danger"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveAdmin">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editAdminModal" tabindex="-1" role="dialog" aria-labelledby="editAdminModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdminModalLabel">Edit Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editAdminForm">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="edit_name" placeholder="Masukkan Nama" name="edit_name"
                                class="form-control">
                            <small id="edit_name_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="edit_username" placeholder="Masukkan Username" name="edit_username"
                                class="form-control">
                            <small id="edit_username_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="edit_password" name="edit_password" class="form-control">
                            <small id="edit_password_error" class="text-danger">Kosongkan password jika tidak ingin merubah
                                password</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning" id="btnUpdateAdmin">Ubah</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('js/admin/user.js') }}"></script>
@endpush
