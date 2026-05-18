<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <title>@yield('title', '"Sales Automation Process"')</title>
  
  <link href="{{ asset('resources/assets/required/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('resources/assets/required/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

  <link href="{{ asset('resources/assets/required/vendor/datatables/dataTables.bootstrap4.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('resources/assets/required/css/sb-admin.css') }}" rel="stylesheet" type="text/css">



</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->



<!-- top nav part================================ -->
@include('layouts.includes.topnav_user')
<!-- top nav part================================ -->



<!-- content part================================ -->

@yield('content')

<!-- content part================================ -->


 
<!-- footer part================================ -->

    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © Your Website {{date('Y')}}</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>

<!-- modal part================================ -->

    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
          </div>
        </div>
      </div>
    </div>

<!-- modal part================================ -->


      <!-- Bootstrap core JavaScript-->
      <script src="{{asset('resources/assets/required/vendor/jquery/jquery.min.js') }}"></script>
      <script src="{{asset('resources/assets/required/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{asset('resources/assets/required/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

      <script src="{{asset('resources/assets/required/vendor/chart.js/Chart.min.js') }}"></script>
      <script src="{{asset('resources/assets/required/vendor/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('resources/assets/required/vendor/datatables/dataTables.bootstrap4.js') }}"></script>

      <script src="{{asset('resources/assets/required/js/sb-admin.min.js') }}"></script>
      <script src="{{asset('resources/assets/required/js/sb-admin-datatables.min.js') }}"></script>
      <script src="{{asset('resources/assets/required/js/sb-admin-charts.min.js') }}"></script>
      <!-- Core plugin JavaScript-->


  </div>


<!-- code for side nav--------------------- -->

<script>
    /** add active class and stay opened when selected */
    var url = window.location;

    // for sidebar menu entirely but not cover treeview
    $('ul.sidebar-menu a').filter(function () {
        return this.href == url;
    }).parent().addClass('active');

    // for treeview
    $('ul.treeview-menu a').filter(function () {
        return this.href == url;
    }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
</script>

<!-- code for side nav--------------------- -->


<!-- footer part================================ -->

</body>

</html>
