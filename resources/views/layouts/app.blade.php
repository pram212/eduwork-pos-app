<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('adminLTE/plugins/fontawesome-free/css/all.min.css')}}">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('adminLTE/dist/css/adminlte.min.css')}}">
  <!-- Start build your styles -->
  @yield('css')

</head>
<body class="hold-transition sidebar-mini dark-mode">
    <div class="wrapper" id="app">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{asset('adminLTE/dist/img/AdminLTELogo.png')}}" alt="AdminLTELogo" height="60" width="60">
        </div>
        <!-- Navbar -->
        @include('layouts.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts.sidebar')
        <!-- /.sidebar -->

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('content-title')</h1>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">@yield('breadcrumb')</a></li>
                                <li class="breadcrumb-item active">@yield('sub-breadcrumb')</li>
                            </ol>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                            <div class="card-header bg-dark">
                                <h5 class="m-0">@yield('card-header')</h5>
                            </div>
                            <div class="card-body">
                                @yield('content')
                            </div>
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline bg-light rounded-pill px-2 pb-1">
                <a href="https://wa.me/6282117694669" target="_blank"><img src="{{asset('img/wa-icon.png')}}" alt="Whatsapp Icon"> Developer</a>
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; 2021 <a href="#">Pramono</a>.</strong> All rights reserved.
        </footer>
        <!-- /.main Footer -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{asset('adminLTE/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('adminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('adminLTE/dist/js/adminlte.min.js')}}"></script>
    <!-- Start Build Your Scripts -->
    @stack('script')

</body>
</html>
