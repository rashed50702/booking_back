<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Meeting Room | Dashboard </title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/daterangepicker/daterangepicker.css">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{asset('assets')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/bs-stepper/css/bs-stepper.min.css">

    <link rel="stylesheet" href="{{asset('assets')}}/plugins/dropzone/min/dropzone.min.css">

    <link rel="stylesheet" href="{{asset('assets')}}/dist/css/adminlte.min.css?v=3.2.0">
    <link rel="stylesheet" href="{{asset('assets')}}/dist/css/custom.css">
</head>

<body class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__wobble" src="{{asset('assets')}}/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div> -->

        <!-- Navbar -->
        @include('admin.layouts.includes.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('admin.layouts.includes.sidebar')
        <!--/. main sidebar container -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- /.content -->

        @include('admin.layouts.includes.footer')
    </div>
    <script src="{{asset('assets')}}/plugins/jquery/jquery.min.js"></script>

    <script src="{{asset('assets')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="{{asset('assets')}}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('assets')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{asset('assets')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="{{asset('assets')}}/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script src="{{asset('assets')}}/plugins/select2/js/select2.full.min.js"></script>

    <script src="{{asset('assets')}}/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>

    <script src="{{asset('assets')}}/plugins/moment/moment.min.js"></script>
    <script src="{{asset('assets')}}/plugins/inputmask/jquery.inputmask.min.js"></script>

    <script src="{{asset('assets')}}/plugins/daterangepicker/daterangepicker.js"></script>

    <script src="{{asset('assets')}}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>

    <script src="{{asset('assets')}}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

    <script src="{{asset('assets')}}/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>

    <script src="{{asset('assets')}}/plugins/bs-stepper/js/bs-stepper.min.js"></script>

    <script src="{{asset('assets')}}/plugins/dropzone/min/dropzone.min.js"></script>

    <script src="{{asset('assets')}}/dist/js/adminlte.min.js?v=3.2.0"></script>

    @yield('footer-scripts')
</body>

</html>