@extends('layouts.index')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    <button class="btn btn-sm btn-neutral" id="btnAddSchedule" data-toggle="modal"
                        data-target="#addScheduleModal">
                        <i class="fa fa-plus"></i>
                        Tambah Jadwal
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
                    <h3 class="mb-0">Data Jadwal</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" id="schedule_datatables" style="min-width:100%">
                        <thead>
                            <tr>
                                <th>Aksi</th>
                                <th>Guru</th>
                                <th>Kategori</th>
                                {{-- <th>Target Maksimal Buka Kelas</th> --}}
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Card footer -->
                <div class="card-footer py-4">

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addScheduleModal" tabindex="-1" role="dialog" aria-labelledby="addScheduleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleModalLabel">Tambah Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addScheduleForm">
                    <div class="form-group">
                        <label for="category_id">Kategori</label>
                        <select name="category_id" id="category_id" class="form-control">
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="teacher_id">Guru</label>
                        <select name="teacher_id" id="teacher_id" class="form-control"
                            {{ empty($teachers) ? 'disabled' : '' }}>
                            @if (empty($teachers))
                            <option value="">Tidak ada guru pada kategori ini</option>
                            @else
                            @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        {{-- <label for="max_target">Target Maksimal Buka Kelas</label> --}}
                        <input type="hidden" name="max_target" id="max_target" class="form-control"
                            placeholder="Target Maksimal Buka Kelas" value="0">
                        <small id="max_target_error" class="text-danger"></small>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveSchedule">Tambah</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editScheduleModal" tabindex="-1" role="dialog" aria-labelledby="editScheduleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editScheduleModalLabel">Edit Jadwal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editScheduleForm">
                    <div class="form-group">
                        <label for="edit_category_id">Kategori</label>
                        <select name="edit_category_id" id="edit_category_id" class="form-control">
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_teacher_id">Guru</label>
                        <select name="edit_teacher_id" id="edit_teacher_id" class="form-control"
                            {{ empty($teachers) ? 'disabled' : '' }}>
                            @if (empty($teachers))
                            <option value="">Tidak ada guru pada kategori ini</option>
                            @else
                            @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        {{-- <label for="edit_max_target">Target Maksimal Buka Kelas</label> --}}
                        <input type="hidden" name="edit_max_target" id="edit_max_target" class="form-control"
                            placeholder="Target Maksimal Buka Kelas" value="0">
                        <small id="edit_max_target_error" class="text-danger"></small>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" id="btnUpdateSchedule">Edit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addScheduleTimeModal" tabindex="-1" role="dialog"
    aria-labelledby="addScheduleTimeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addScheduleTimeModalLabel">Tambah Waktu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addScheduleTimeForm">
                    <div class="form-group">
                        <label for="day">Hari</label>
                        <select name="day" id="day" class="form-control">
                            <option value="1">Senin</option>
                            <option value="2">Selasa</option>
                            <option value="3">Rabu</option>
                            <option value="4">Kamis</option>
                            <option value="5">Jumat</option>
                            <option value="6">Sabtu</option>
                            <option value="7">Minggu</option>
                        </select>
                        <small id="day_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="start_time">Waktu Mulai</label>
                        <input type="time" id="start_time" placeholder="Masukkan Tempat Lahir" name="start_time"
                            class="form-control">
                        <small id="start_time_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="end_time">Waktu Selesai</label>
                        <input type="time" id="end_time" name="end_time" class="form-control">
                        <small id="end_time_error" class="text-danger"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveScheduleTime">Tambah</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editScheduleTimeModal" tabindex="-1" role="dialog"
    aria-labelledby="editScheduleTimeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editScheduleTimeModalLabel">Tambah Waktu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editScheduleTimeForm">
                    <div class="form-group">
                        <label for="edit_day">Hari</label>
                        <select name="edit_day" id="edit_day" class="form-control">
                            <option value="1">Senin</option>
                            <option value="2">Selasa</option>
                            <option value="3">Rabu</option>
                            <option value="4">Kamis</option>
                            <option value="5">Jumat</option>
                            <option value="6">Sabtu</option>
                            <option value="7">Minggu</option>
                        </select>
                        <small id="edit_day_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="edit_start_time">Waktu Mulai</label>
                        <input type="time" id="edit_start_time" placeholder="Masukkan Tempat Lahir"
                            name="edit_start_time" class="form-control">
                        <small id="edit_start_time_error" class="text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="edit_end_time">Waktu Selesai</label>
                        <input type="time" id="edit_end_time" name="edit_end_time" class="form-control">
                        <small id="edit_end_time_error" class="text-danger"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-warning" id="btnUpdateScheduleTime">Edit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="studentTimeModal" tabindex="-1" role="dialog" aria-labelledby="studentTimeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="studentTimeModalLabel">List Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-10">
                        <select name="student_id" id="student_id" class="form-control">
                        </select>
                    </div>
                    <div class="col-2">
                        <button class="btn btn-primary" id="btnAddStudentTime">
                            <i class="fa fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="card" style="width: 100%;margin:0">
                            <ul class="list-group list-group-flush" id="studentTimeList" style="font-size:13px">
                                <li class="list-group-item" style="padding:0.5rem">
                                    Joseph Alberto 
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/admin/schedule.js') }}"></script>
<script>
    $(function() {
            $('#addScheduleForm select#teacher_id').select2();
            $('#addScheduleForm select#category_id').select2();
            $('#editScheduleForm select#edit_teacher_id').select2();
            $('#editScheduleForm select#edit_category_id').select2();
            $("#studentTimeModal select#student_id").select2();
        });

</script>
@endpush