@extends('layouts.index')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0"> Tambah Jadwal</h3>
                </div>
                <div class="card-body">
                    <form id="addStudentScheduleForm">
                        <div class="row mb-3">
                            <div class="col-1 text-right mt-2">
                                <label for="">Siswa</label>
                            </div>
                            <div class="col-11">
                                <input type="text" readonly class="form-control" value="{{ $student->name }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1 text-right mt-2">
                                <label for="schedule_id">Jadwal</label>
                            </div>
                            <div class="col-11">
                                <select name="schedule_id" id="schedule_id" class="form-control">
                                    @foreach ($categories as $category)
                                        @foreach ($category->schedules as $schedule)
                                        <option value="{{ $schedule->id }}">{{ $schedule->teacher->name }} - {{ $schedule->category->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1 text-right mt-2">
                                <label for="time_id">Waktu</label>
                            </div>
                            <div class="col-11">
                                <select name="time_id" id="time_id" class="form-control">
                                    @foreach ($times as $time)
                                        <option value="{{ $time->id }}">{{ $time->day }}, {{ $time->start_time }} ~ {{ $time->end_time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Card footer -->
                <div class="card-footer py-4">
                    <div class="row">
                        <div class="col-6 text-left">
                            <a class="btn btn-secondary" href="{{ route('student.index') }}">Kembali</a>
                        </div>
                        <div class="col-6 text-right">
                            <button type="button" class="btn btn-primary" id="btnAddStudentSchedule"
                                data-id="{{ $student_id }}">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <!-- Card header -->
                <div class="card-header border-0">
                    <h3 class="mb-0">Jadwal</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered" style="min-width:100%" id="student_schedules_table">
                        <thead>
                            <tr>
                                <th style="width:1%">Aksi</th>
                                <th>Kategori</th>
                                <th>Guru</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student_schedules as $time)
                            <tr>
                                <td>
                                    <div class='dropdown'>
                                        <button class='btn btn-sm btn-secondary dropdown-toggle' type='button'
                                            id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true'
                                            aria-expanded='false'>
                                            <i class='fa fa-cog'></i>
                                        </button>
                                        <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                            <a class='dropdown-item btn-delete' href='#'
                                                data-id='{{ $time->id }}'>Delete</a>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $time->schedule->category->name }}</td>
                                <td>{{ $time->schedule->teacher->name }}</td>
                                <td>{{ $time->day }}, {{ $time->start_time }} ~ {{ $time->end_time }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/admin/student_schedule.js') }}"></script>
<script>
    $(function() {
        $('#addStudentScheduleForm select#schedule_id').select2();
        $('#addStudentScheduleForm select#time_id').select2();
    });
</script>
@endpush