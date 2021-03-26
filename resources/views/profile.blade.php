@extends('layouts.index')

@section('content')

<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <h1 class="text-white">Profile</h1>
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
                    <h3 class="mb-0">Informasi akun</h3>
                </div>
                <form id="accountForm">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-2 text-right mt-2">
                                <label for="">Nama</label>
                            </div>
                            <div class="col-10">
                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Masukkan Nama..." value="{{ Auth::user()->name }}">
                                <small id="name_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-2 text-right mt-2">
                                <label for="">Username</label>
                            </div>
                            <div class="col-10">
                                <input type="text" id="username" name="username" class="form-control"
                                    placeholder="Masukkan Username..." value="{{ Auth::user()->username }}">
                                <small id="username_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer py-4 text-right">
                        <button type="button" class="btn btn-primary" id="btnSaveAccount">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Ganti Password</h3>
                </div>
                <form id="changePasswordForm">
                    @csrf
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-2 text-right mt-2">
                                <label for="">Password Lama</label>
                            </div>
                            <div class="col-10">
                                <input type="password" id="current_password" name="current_password" class="form-control"
                                    placeholder="Masukkan Password...">
                                <small id="current_password_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-2 text-right mt-2">
                                <label for="">Password Baru</label>
                            </div>
                            <div class="col-10">
                                <input type="password" id="new_password" name="new_password" class="form-control"
                                    placeholder="Masukkan Password Baru...">
                                <small id="new_password_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-2 text-right mt-2">
                                <label for="">Konfirmasi Password Baru</label>
                            </div>
                            <div class="col-10">
                                <input type="password" id="confirmation_new_password" name="confirmation_new_password" class="form-control"
                                    placeholder="Masukkan Password Baru...">
                                <small id="confirmation_new_password_error" class="text-danger"></small>
                            </div>
                        </div>
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer py-4 text-right">
                        <button type="button" class="btn btn-primary" id="btnChangePassword">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ url('js/admin/profile.js') }}"></script>
@endpush

@endsection