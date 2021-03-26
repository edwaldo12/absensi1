@extends('layouts.index')

@section('content')
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Dashboard</h6>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
          <!-- Card body -->
          <a href="{{ url('student') }}">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Siswa</h5>
                  <span class="h2 font-weight-bold mb-0">{{ number_format($student) }}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                    <i class="fa fa-user"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $student_growth }}%</span>
                <span class="text-nowrap" style="font-size:12px">Semenjak bulan lalu</span>
              </p>
            </div>
          </a>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
          <!-- Card body -->
          <a href="{{ url('teacher') }}">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Guru</h5>
                  <span class="h2 font-weight-bold mb-0">{{ number_format($teacher)}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                    <i class="fa fa-user"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $teacher_growth }}%</span>
                <span class="text-nowrap" style="font-size:12px">Semenjak bulan lalu</span>
              </p>
            </div>
          </a>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
          <!-- Card body -->
          <a href="{{ url('schedule') }}">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Jadwal</h5>
                  <span class="h2 font-weight-bold mb-0">{{ number_format($schedule)}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-orange text-white rounded-circle shadow">
                    <i class="fa fa-clock"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $schedule_growth }}%</span>
                <span class="text-nowrap" style="font-size:12px">Semenjak bulan lalu</span>
              </p>
            </div>
          </a>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
          <!-- Card body -->
          <a href="{{ url('class') }}">
            <div class="card-body">
              <div class="row">
                <div class="col">
                  <h5 class="card-title text-uppercase text-muted mb-0">Kelas</h5>
                  <span class="h2 font-weight-bold mb-0">{{ number_format($class)}}</span>
                </div>
                <div class="col-auto">
                  <div class="icon icon-shape bg-red text-white rounded-circle shadow">
                    <i class="fa fa-clipboard"></i>
                  </div>
                </div>
              </div>
              <p class="mt-3 mb-0 text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $class_growth }}%</span>
                <span class="text-nowrap" style="font-size:12px">Semenjak bulan lalu</span>
              </p>
            </div>
          </a>
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
          <h3 class="mb-0">Jadwal Hari Ini</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered" id="today_table" style="min-width:100%">
            <thead>
              <tr>
                <th style="width:1%">Aksi</th>
                <th>Guru</th>
                <th>Kategori</th>
                <th>Target Maksimal Buka Kelas</th>
                <th>Waktu</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($today_schedules as $today)
              <tr>
                <td>
                  <div class='dropdown'>
                    <button class='btn btn-sm btn-secondary dropdown-toggle' type='button' id='dropdownMenuButton'
                      data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                      <i class='fa fa-cog'></i>
                    </button>
                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                      <a href="#" class='btn-open-class dropdown-item' data-schedule-id="{{ $today->schedule->id }}"
                        data-time-id="{{ $today->id }}">Buka Kelas</a>
                    </div>
                  </div>
                </td>
                <td>{{ $today->schedule->teacher->name }}</td>
                <td>{{ $today->schedule->category->name }}</td>
                <td>{{ $today->schedule->max_target }}</td>
                <td>{{ $today->day }}, {{ $today->start_time }} ~ {{ $today->end_time }}</td>
              </tr>
              @endforeach
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
            <select name="schedule_id" id="schedule_id" class="form-control" disabled>
              @foreach ($schedules as $schedule)
              <option value="{{ $schedule->id }}">{{ $schedule->category->name }} - {{ $schedule->teacher->name }}
                {{ $schedule->start_time }} {{ $schedule->end_time }}</option>
              @endforeach
            </select>
            <small id="schedule_id_error" class="text-danger"></small>
          </div>
          <div class="form-group">
            <label for="time_id">Waktu Jadwal</label>
            <select name="time_id" id="time_id" class="form-control" disabled>
              @foreach ($times as $time)
              <option value="{{ $time->id }}">{{$time->day}}, {{ $time->start_time }} ~ {{ $time->end_time }}</option>
              @endforeach
            </select>
            <small id="time_id_error" class="text-danger"></small>
          </div>
          <div class="form-group">
            <label for="start_time">Waktu Mulai</label>
            <input type="datetime-local" id="start_time" placeholder="Masukkan Tempat Lahir" name="start_time"
              class="form-control">
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
@endsection

@push('scripts')
<script src="{{ url('js/admin/dashboard.js') }}"></script>
<script>
  $(function(){
      $('#addClassForm select#teacher_id').select2();
      $('#addClassForm select#schedule_id').select2();
      $('#addClassForm select#time_id').select2();
  })
</script>
@endpush