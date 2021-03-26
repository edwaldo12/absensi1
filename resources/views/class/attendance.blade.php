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
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Informasi Kelas</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-1 text-right mt-1   ">
                                <label for="">Guru</label>
                            </div>
                            <div class="col-11">
                                <input type="text" class="form-control" readonly value="{{ $class->teacher->name }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1 text-right mt-1   ">
                                <label for="">Kategori</label>
                            </div>
                            <div class="col-11">
                                <input type="text" class="form-control" readonly
                                    value="{{ $class->time->schedule->category->name }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1 text-right mt-1   ">
                                <label for="">Waktu Kelas</label>
                            </div>
                            <div class="col-11">
                                <input type="text" class="form-control" readonly
                                    value="{{ $class->start_time }} - {{ $class->end_time }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-1 text-right mt-1   ">
                                <label for="">Status Absensi</label>
                            </div>
                            <div class="col-11">
                                <input id="attendance_status" type="text" class="form-control" readonly value="{{ $class->has_attendance ? "Sudah di absen" : "Belum di absen" }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header border-0">
                        <h3 class="mb-0">Absensi</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <input type="checkbox" id="check-all" @if (!$class->has_attendance) checked @endif> Centang Semua
                            </div>
                        </div>
                        <table class="table table-bordered" id="attendanceTable" style="min-width:100%">
                            <thead>
                                <tr>
                                    <th style="width:1%">Hadir</th>
                                    <th>Siswa</th>
                                    <th>Terakhir Di Ubah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($student_in_class as $student)
                                    <tr>
                                        @if (!$class->has_attendance)
                                        <td>
                                            <input class="check-box" type="checkbox" checked data-id="{{ $student->id }}">
                                        </td>
                                        @else
                                        <td>
                                            <input class="check-box" type="checkbox" {{ $student->is_present == true ? "checked" : "" }} data-id="{{ $student->id }}">
                                        </td>
                                        @endif
                                      <td>{{ $student->name }}</td>
                                      <td>{{ count($student->attendances)>0 ? $student->attendances[0]->updated_at->toDateTimeString() : "-" }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Card footer -->
                    <div class="card-footer py-4">
                        <div class="row">
                            <div class="col-6 text-left">
                                <a class="btn btn-secondary" href="{{ route('class.index') }}">Kembali</a>
                            </div>
                            <div class="col-6 text-right">
                                <button class="btn btn-primary" id="btnSaveAttendance"
                                    data-id="{{ $class_id }}">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ url('js/admin/attendance.js') }}"></script>
@endpush
