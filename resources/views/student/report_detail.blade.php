@extends('layouts.index')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
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
                    <div class="row">
                        <div class="col-6">
                            <h3 class="mb-0 d-inline">Laporan Detail Absensi - {{ $student->name }}</h3>
                        </div>
                        <div class="col-6 text-right">
                            <button class="btn btn-sm btn-danger" id="btnStudentReport">
                                <i class="fa fa-file-pdf"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="card-content">
                    <table class="table table-bordered" id="student_datatables" style="min-width:100%">
                        <thead>
                            <tr>
                                <th>Guru</th>
                                <th>Jadwal</th>
                                <th>Waktu Mulai</th>
                                <th>Waktu Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($details) > 0)
                            @foreach ($details as $detail)
                            <tr>
                                <td>{{ $detail->class->teacher->name }}</td>
                                <td>
                                    {{ $detail->class->time->schedule->category->name }} - {{ $detail->class->time->schedule->teacher->name }}
                                    <br>
                                    {{ $detail->class->time->start_time }} ~ {{ $detail->class->time->end_time }}
                                </td>
                                <td>{{ $detail->class->start_time }}</td>
                                <td>{{ $detail->class->end_time }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="text-center">Belum ada absensi...</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Card footer -->
                <div class="card-footer py-4">
                    <a href="{{ url('student/report/'.$student->id) }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/admin/student_report_detail.js') }}"></script>
@endpush