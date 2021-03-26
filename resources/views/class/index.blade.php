@extends('layouts.index')

@section('content')
    <!-- Header -->
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <button class="btn btn-sm btn-neutral" id="btnAddClass" data-toggle="modal"
                            data-target="#addClassModal">
                            <i class="fa fa-plus"></i>
                            Tambah Kelas
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
                        <h3 class="mb-0">Data Kelas</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="class_datatables" style="min-width:100%">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th>Absen</th>
                                    <th>Guru</th>
                                    <th>Jadwal</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Selesai</th>
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

    <div class="modal fade" id="addClassModal" tabindex="-1" role="dialog" aria-labelledby="addClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addClassModalLabel">Tambah Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addClassForm">
                        <div class="form-group">
                            <label for="teacher_id">Guru</label>
                            <select name="teacher_id" id="teacher_id" class="form-control">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <small id="teacher_id_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="schedule_id">Jadwal</label>
                            <select name="schedule_id" id="schedule_id" class="form-control">
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->category->name }} - {{ $schedule->teacher->name }} {{ $schedule->start_time }} {{ $schedule->end_time }}</option>
                                @endforeach
                            </select>
                            <small id="schedule_id_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="time_id">Waktu Jadwal</label>
                            <select name="time_id" id="time_id" class="form-control">
                                @foreach ($times as $time)
                                    <option value="{{ $time->id }}">{{$time->day}}, {{ $time->start_time }} ~ {{ $time->end_time }}</option>
                                @endforeach
                            </select>
                            <small id="time_id_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="start_time">Waktu Mulai</label>
                            <input type="datetime-local" id="start_time" placeholder="Masukkan Tempat Lahir"
                                name="start_time" class="form-control">
                            <small id="start_time_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="end_time">Waktu Selesai</label>
                            <input type="datetime-local" id="end_time" name="end_time" class="form-control">
                            <small id="end_time_error" class="text-danger"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btnSaveClass">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editClassModal" tabindex="-1" role="dialog" aria-labelledby="editClassModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel">Edit Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editClassForm" onkeydown="return event.key != 'Enter';">
                        <div class="form-group">
                            <label for="edit_teacher_id">Guru</label>
                            <select name="edit_teacher_id" id="edit_teacher_id" class="form-control">
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <small id="edit_teacher_id_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_schedule_id">Jadwal</label>
                            <select name="edit_schedule_id" id="edit_schedule_id" class="form-control">
                                @foreach ($schedules as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->category->name }} - {{ $schedule->teacher->name }} {{ $schedule->start_time }} {{ $schedule->end_time }}</option>
                                @endforeach
                            </select>
                            <small id="edit_schedule_id_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_time_id">Waktu Jadwal</label>
                            <select name="edit_time_id" id="edit_time_id" class="form-control">
                                @foreach ($times as $time)
                                    <option value="{{ $time->id }}">{{ $time->start_time }} ~ {{ $time->end_time }}</option>
                                @endforeach
                            </select>
                            <small id="edit_time_id_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_start_time">Waktu Mulai</label>
                            <input type="datetime-local" id="edit_start_time" placeholder="Masukkan Tempat Lahir"
                                name="edit_start_time" class="form-control">
                            <small id="edit_start_time_error" class="text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label for="edit_end_time">Waktu Selesai</label>
                            <input type="datetime-local" id="edit_end_time" name="edit_end_time" class="form-control">
                            <small id="edit_end_time_error" class="text-danger"></small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-warning" id="btnUpdateClass">Ubah</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('js/admin/class.js') }}"></script>
    <script>
        $(function() {
            $('#addClassForm select#teacher_id').select2();
            $('#addClassForm select#schedule_id').select2();
            $('#editClassForm select#edit_teacher_id').select2();
            $('#editClassForm select#edit_schedule_id').select2();
        });
    </script>
@endpush
