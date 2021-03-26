@extends('layouts.index')

@section('content')
    <!-- Header -->
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <button class="btn btn-sm btn-neutral" id="btnAddCategory" data-toggle="modal"
                            data-target="#addCategoryModal">
                            <i class="fa fa-plus"></i>
                            Tambah Kategori
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
                        <h3 class="mb-0">Data Kategori</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="category_datatables" style="min-width:100%">
                            <thead>
                                <tr>
                                    <th style="width:1%">Aksi</th>
                                    <th>Nama Kategori</th>
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

    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addCategoryForm" onkeydown="return event.key != 'Enter';">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" id="name" placeholder="Masukkan Nama" name="name" class="form-control">
                            <small id="name_error" class="text-danger"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSaveCategory">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editCategoryForm" onkeydown="return event.key != 'Enter';">
                        <div class="form-group">
                            <label for="edit_name">Nama</label>
                            <input type="text" id="edit_name" placeholder="Masukkan Nama" name="edit_name"
                                class="form-control">
                            <small id="edit_name_error" class="text-danger"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning" id="btnUpdateCategory">Ubah</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('js/admin/category.js') }}"></script>
@endpush
