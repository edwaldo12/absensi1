@extends('layouts.index')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <button class="btn btn-sm btn-neutral" id="btnAddTeacher" data-toggle="modal"
                        data-target="#addTeacherModal">
                        <i class="fa fa-plus"></i>
                        Tambah Guru
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
                    <h3 class="mb-0">Data Guru</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="teacher_datatables" style="min-width:100%">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Usia</th>
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

<div class="modal fade" id="addTeacherModal" tabindex="-1" role="dialog" aria-labelledby="addTeacherModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Tambah Guru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addTeacherForm">
                    <div class="form-group">
                        <label for="name">Nama</label>
                        <input type="text" id="name" placeholder="Masukkan Nama" name="name" class="form-control">
                        <small id="name_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="M">Laki-Laki</option>
                            <option value="F">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="place_of_birth">Tempat Lahir</label>
                        <input type="text" id="place_of_birth" placeholder="Masukkan Tempat Lahir" name="place_of_birth"
                            class="form-control">
                        <small id="place_of_birth_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="date_of_birth">Tanggal Lahir</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control">
                        <small id="date_of_birth_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telepon</label>
                        <input type="text" id="phone" placeholder="Masukkan Telepon" name="phone" class="form-control">
                        <small id="phone_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <input type="text" id="address" placeholder="Masukkan Alamat" name="address"
                            class="form-control">
                        <small id="address_error" class="text-danger"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveTeacher">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editTeacherModal" tabindex="-1" role="dialog" aria-labelledby="editTeacherModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTeacherModalLabel">Edit Guru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editTeacherForm">
                    <div class="form-group">
                        <label for="edit_name">Nama</label>
                        <input type="text" id="edit_name" placeholder="Masukkan Nama" name="edit_name" class="form-control">
                        <small id="edit_name_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="edit_gender">Jenis Kelamin</label>
                        <select name="edit_gender" id="edit_gender" class="form-control">
                            <option value="M">Laki-Laki</option>
                            <option value="F">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_place_of_birth">Tempat Lahir</label>
                        <input type="text" id="edit_place_of_birth" placeholder="Masukkan Tempat Lahir" name="edit_place_of_birth"
                            class="form-control">
                        <small id="edit_place_of_birth_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="edit_date_of_birth">Tanggal Lahir</label>
                        <input type="date" id="edit_date_of_birth" name="edit_date_of_birth" class="form-control">
                        <small id="edit_date_of_birth_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Telepon</label>
                        <input type="text" id="edit_phone" placeholder="Masukkan Telepon" name="edit_phone" class="form-control">
                        <small id="edit_phone_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="edit_address">Alamat</label>
                        <input type="text" id="edit_address" placeholder="Masukkan Alamat" name="edit_address"
                            class="form-control">
                        <small id="edit_address_error" class="text-danger"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" id="btnUpdateTeacher">Ubah</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="teacherCategoryModal" tabindex="-1" role="dialog" aria-labelledby="teacherCategoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header pb-2">
                <h5 class="modal-title" id="teacherCategoryModalLabel">Kategori Guru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-2">
                <div class="mb-3" id="list-category">
                    <div class="badge badge-primary" data-id="test">Machine Learning <i class="fa fa-times delete-category" style="margin-left:2.5px"></i></div>
                </div>
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select name="category_id" id="category_id" class="form-control">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small id="category_id_error" class="text-danger"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnAddCategory">Tambah</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="teacherScheduleModal" tabindex="-1" role="dialog" aria-labelledby="teacherScheduleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teacherScheduleModalLabel">Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table" id="teacherScheduleTable">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/admin/teacher.js') }}"></script>

<script>
    $(function() {
        $('#addCategoryForm select#category_id').select2();
    });
</script>
@endpush