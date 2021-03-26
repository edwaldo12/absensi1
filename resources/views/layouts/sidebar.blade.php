<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
        <!-- Brand -->
        <div class="sidenav-header  align-items-center">
            <a class="navbar-brand" href="javascript:void(0)">
                <h2 style="text-transform:capitalize;">{{ env('APP_NAME', 'Attendance') }}</h2>
            </a>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="ni ni-tv-2 text-primary"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('class.index') }}">
                            <i class="fa fa-clipboard text-danger"></i>
                            <span class="nav-link-text">Kelas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('schedule.index') }}">
                            <i class="fa fa-clock text-orange"></i>
                            <span class="nav-link-text">Jadwal</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('category.index') }}">
                            <i class="fa fa-tags text-blue"></i>
                            <span class="nav-link-text">Kategori</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teacher.index') }}">
                            <i class="ni ni-single-02 text-green"></i>
                            <span class="nav-link-text">Guru</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.index') }}">
                            <i class="ni ni-single-02 text-yellow"></i>
                            <span class="nav-link-text">Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.index') }}">
                            <i class="ni ni-single-02 text-blue"></i>
                            <span class="nav-link-text">Admin</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
</nav>
