<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <title>@yield('title', '"Project"')</title>

@if (@$_SESSION["favicon"] )
 <link rel="shortcut icon" type="image/x-icon" href="{{ asset( 'storage/app/' . $_SESSION['favicon']) }}">
@else
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('resources/assets/dms/dist/img/favicon.ico') }}">
@endif

    <!-- Table with csv,pdf,print -->
    <link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" rel="stylesheet">


  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/select2/dist/css/select2.min.css') }}">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
      
      <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/dist/css/AdminLTE.min.css') }}">
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/dist/css/skins/_all-skins.min.css') }}">
    
    <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/plugins/iCheck/square/blue.css') }}">

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        <!-- Add more -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>



</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">


<!-- top nav part================================ -->
@include('layouts.includes.topnav_sales')
<!-- top nav part================================ -->



<!-- content part================================ -->

@yield('content')

<!-- content part================================ -->















 
<!-- footer part================================ -->

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    
    <!-- <strong>Copyright &copy; 2018 <a href="https://synergyinterface.com">Synergy Interface Ltd</a>.</strong> All rights
    reserved. -->
  </footer>

  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

  <!-- Core plugin JavaScript-->

<!-- jQuery 3 -->
<script src="{{asset('resources/assets/dms/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('resources/assets/dms/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{asset('resources/assets/dms/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- DataTables -->
<script src="{{asset('resources/assets/dms/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('resources/assets/dms/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{asset('resources/assets/dms/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- SlimScroll -->
<script src="{{asset('resources/assets/dms/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{asset('resources/assets/dms/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{asset('resources/assets/dms/dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('resources/assets/dms/dist/js/demo.js') }}"></script>

<script src="{{asset('resources/assets/dms/dist/print/excellentexport.js') }}"></script>
<!-- page script -->

<!-- ======================================================= -->

 <!-- DataTables JavaScript --><!-- Table with csv,pdf,print -->


<!-- <script src="{{asset('resources/assets/required/custom/jquery.dataTables.min.js') }}"></script>
<script src="{{asset('resources/assets/required/custom/dataTables.buttons.min.js') }}"></script>
<script src="{{asset('resources/assets/required/custom/buttons.flash.min.js') }}"></script>
<script src="{{asset('resources/assets/required/custom/jszip.min.js') }}"></script>
<script src="{{asset('resources/assets/required/custom/pdfmake.min.js') }}"></script>
<script src="{{asset('resources/assets/required/custom/vfs_fonts.js') }}"></script>
<script src="{{asset('resources/assets/required/custom/buttons.html5.min.js') }}"></script>
<script src="{{asset('resources/assets/required/custom/buttons.print.min.js') }}"></script>
<script src="{{asset('resources/assets/required/custom/buttons.colVis.min.js') }}"></script>


 <script src="{{asset('resources/assets/dms/dist/print/excellentexport.js') }}"></script> -->
<!-- page script -->
<!-- ======================================================= -->




<!-- ======================================================= -->

 <!-- DataTables JavaScript --><!-- Table with csv,pdf,print -->
     <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>
     <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js"></script>
     <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
     <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
     <script src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
     <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
     <script src="//cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js"></script>
 <script src=" https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
 <script src="{{asset('resources/assets/dms/dist/print/excellentexport.js') }}"></script>
<!-- page script -->
<!-- ======================================================= -->

<!-- Table with csv,pdf,print -->
<script>
      $(document).ready(function() {
        var table = $('#example').DataTable( {
          scrollX: true,
            dom: 'Bfrtip',
                   buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
             {
                extend: 'csvHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 5 ]
                }
            },
            'colvis'
        ],
             "order": [[ 1, "desc" ]],

            "columnDefs": [ {
            "visible": true,
            "targets": -1
        } ]
        } );

        

    } );
  </script>

<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })


  })


</script>

<!-- Page script -->
<script>
  $(function () {

    //Initialize Select2 Elements
    $('.select2').select2()
  

    //Date picker
   /* $('#datepicker').datepicker({
      autoclose: true
    })*/

    $('#datepicker').datepicker({
      format: 'mm/dd/yyyy',
      autoclose: true
    });

    $('#datepicker1').datepicker({
      format: 'mm/dd/yyyy',
      autoclose: true
    });

  
  })
</script>


<script type="text/javascript">
  
  $("#monthpicker").datepicker( {
      format: "mm/yyyy",
      startView: "months", 
      minViewMode: "months"
  });


  $("#monthpicker1").datepicker( {
    format: "mm/yyyy",
      startView: "months", 
      minViewMode: "months"
  });
</script>



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

</body>

</html>
