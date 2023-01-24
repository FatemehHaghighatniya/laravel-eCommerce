<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Vax.ir @yield('title')</title>

    <!-- Custom fonts for this template-->
{{--    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">--}}

    <!-- Custom styles for this template-->
    <link href="{{asset('/css/admin.css')}}" rel="stylesheet">
    <link href="/dist/mds.bs.datetimepicker.style.css" rel="stylesheet"/>


</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
@include('admin.sections.sidebar')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
  @include('admin.sections.topbar')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">
@yield('content')

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        @include('admin.sections.footer')
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
@include('admin.sections.scroll_top')

<!-- Logout Modal-->

<!-- Bootstrap core JavaScript-->
{{--<script src="vendor/jquery/jquery.min.js"></script>--}}
{{--<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>--}}

{{--<!-- Core plugin JavaScript-->--}}
{{--<script src="vendor/jquery-easing/jquery.easing.min.js"></script>--}}

{{--<!-- Custom scripts for all pages-->--}}
{{--<script src="js/sb-admin-2.min.js"></script>--}}

<!-- Page level plugins -->
{{--<script--}}
{{--    src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"--}}
{{--    integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8"--}}
{{--    crossorigin="anonymous"></script>--}}
<script src="{{asset('/js/admin.js')}}"></script>
<script src="/dist/mds.bs.datetimepicker.js"></script>



<!-- Page level custom scripts -->

{{--<script src="js/demo/chart-area-demo.js"></script>--}}
{{--<script src="js/demo/chart-pie-demo.js"></script>--}}
@include('sweet::alert')
@yield('script')
</body>

</html>
