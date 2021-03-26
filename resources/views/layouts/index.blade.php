<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 4.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Favicon -->
    <link rel="icon" href="{{ url('template') }}/img/brand/favicon.png" type="image/png">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <!-- Icons -->
    <link rel="stylesheet" href="{{ url('template') }}/vendor/nucleo/css/nucleo.css" type="text/css">
    <link rel="stylesheet" href="{{ url('template') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css"
        type="text/css">
    <!-- Argon CSS -->
    <link rel="stylesheet" href="{{ url('template') }}/css/argon.css?v=1.2.0" type="text/css">
    <!-- Jquery JS -->
    <script src="{{ url('template') }}/vendor/jquery/dist/jquery.min.js"></script>
    <!-- Data Tables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ url('vanillatoast/vanillatoasts.css') }}">
    <script src="{{ url('vanillatoast/vanillatoasts.js') }}"></script>
    <!-- My CSS -->
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <!-- Select2 -->
    <link href="{{ url('select2/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ url('select2/select2.min.js') }}"></script>

</head>

<body>
    {{-- Sidebar --}}
    @include('layouts.sidebar')
    <!-- Main content -->
    <div class="main-content" id="panel">
        {{-- Top Nav --}}
        @include('layouts.topnav')

        @yield('content')

        <div class="container-fluid">
            
        </div>
    </div>
    <!-- Argon Scripts -->
    <!-- Core -->
    <script src="{{ url('template') }}/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('template') }}/vendor/js-cookie/js.cookie.js"></script>
    <script src="{{ url('template') }}/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="{{ url('template') }}/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <!-- Argon JS -->
    <script src="{{ url('template') }}/js/argon.js?v=1.2.0"></script>
    <!-- Data Tables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js"></script>
    @stack('scripts')
</body>

</html>