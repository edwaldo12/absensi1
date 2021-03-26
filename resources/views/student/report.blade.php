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
                            <h3 class="mb-0 d-inline">Laporan Absensi - {{ $student->name }}</h3>
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
                                <th>Kategori</th>
                                <th>Jumlah Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($reports) > 0)
                            @foreach ($reports as $report)
                            <tr>
                                <td>{{ $report->category_name }}</td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="{{ url('student/report/detail/'.$report->student_id.'/'.$report->category_id) }}">
                                        {{ $report->attendance_count }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="2" class="text-center">Belum ada absensi...</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <!-- Card footer -->
                <div class="card-footer py-4">
                    <a href="{{ route('student.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/admin/student_report.js') }}"></script>
@endpush