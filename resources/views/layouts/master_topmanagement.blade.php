<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  
  <title>@yield('title', '"Sales Automation Process"')</title>



    <!-- Table with csv,pdf,print -->
    <link href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.3.1/css/buttons.dataTables.min.css" rel="stylesheet">
@if (@$_SESSION["favicon"] )
 <link rel="shortcut icon" type="image/x-icon" href="{{ asset( 'storage/app/' . $_SESSION['favicon']) }}">
@else
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('resources/assets/dms/dist/img/favicon.ico') }}">
@endif

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
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script> -->

<script src="{{asset('resources/assets/dms/bower_components/jquery/dist/jquery.min.js') }}"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<style>
    /* small helpers */
    #example { display: none; }

    /* =========================
       SINGLE GLOBAL LOADER
       ========================= */
    #loading {
      position: fixed;
      inset: 0;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(15, 23, 42, 0.85);
      z-index: 9999;
      backdrop-filter: blur(3px);
    }

    .loader-content {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
    }

    #loading-image {
      width: 72px;
      filter: drop-shadow(0 8px 16px rgba(0,0,0,0.35));
    }

    .loader-spinner {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      border: 4px solid rgba(148, 163, 184, 0.35);
      border-top-color: #3b82f6;
      animation: spin 1s linear infinite, glow 1.8s ease-in-out infinite;
    }

    .loader-text {
      color: #e5e7eb;
      font-size: 14px;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      font-weight: 600;
      text-shadow: 0 2px 8px rgba(0,0,0,0.35);
    }

    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes glow {
      0% { box-shadow: 0 0 0 0 rgba(59,130,246,0.8); }
      70% { box-shadow: 0 0 0 12px rgba(59,130,246,0); }
      100% { box-shadow: 0 0 0 0 rgba(59,130,246,0); }
    }

    /* THEME TWEAKS (modern look) */
    body { background: #f3f4f6 !important; font-family: 'Source Sans Pro', sans-serif !important; }
    * { transition: all 0.22s ease; }

    .main-header { background: linear-gradient(135deg,#0f172a,#1e293b) !important; box-shadow: 0 6px 30px rgba(0,0,0,0.25); }
    .main-header .navbar { background: transparent !important; border: none !important; }

    .main-header .logo img { height: 52px; object-fit: contain; filter: drop-shadow(0 5px 12px rgba(0,0,0,0.5)); }
    .main-header .logo img:hover { transform: translateY(-2px) scale(1.04); }

    .main-sidebar { background: linear-gradient(180deg,#0f172a,#111827 50%,#1f2937) !important; border-right: 1px solid rgba(255,255,255,0.06); box-shadow: 4px 0 20px rgba(0,0,0,0.22); }
    .sidebar-menu>li>a { color: #e5e7eb !important; padding: 12px 16px; margin: 6px 12px; border-radius: 10px; display:flex; align-items:center; gap:10px; }

    .sidebar-menu>li>a:hover, .sidebar-menu>li.active>a { background: linear-gradient(135deg,#2563eb,#1d4ed8); box-shadow: 0 8px 20px rgba(37,99,235,0.25); color:#fff !important; transform: translateY(-1px); }

    table.dataTable { background: #fff !important; border-radius: 12px; overflow: hidden; box-shadow: 0 12px 30px rgba(0,0,0,0.06); }
    .dataTables_wrapper .dataTables_filter input { border-radius: 999px; padding: 9px 12px; border: 1px solid #cbd5e1; }

    .dataTables_wrapper .dt-buttons button { border-radius: 999px !important; padding: 8px 16px !important; background: #2563eb !important; border: none !important; color: white !important; box-shadow: 0 4px 12px rgba(37,99,235,0.25); }
    .dataTables_wrapper .dt-buttons button:hover { background: #1d4ed8 !important; }

    .form-control { border-radius: 10px !important; border: 1px solid #cbd5e1 !important; box-shadow: none !important; }
    .form-control:focus { border-color: #3b82f6 !important; box-shadow: 0 0 0 3px rgba(59,130,246,0.12) !important; }

    .btn { border-radius: 10px !important; padding: 7px 14px; }
    .btn-info { background: linear-gradient(135deg,#0ea5e9,#0284c7) !important; border: none !important; box-shadow: 0 6px 18px rgba(2,132,199,0.2); }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">


<!-- top nav part================================ -->
@include('layouts.includes.topnav_topmanagement')
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
<!-- Table with csv,pdf,print -->
<script>
      $(document).ready(function() {
        var table = $('#example').DataTable( {
         
          scrollY: '300',
          scrollX: true,
          fixedColumns: true,
          fixedHeader: true,
          //pageLength: 100,
          "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]],

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
            "order": [[ 0, "asc" ]],

            "columnDefs": [ {
            "visible": true,
            "targets": -1
        } ]


        } );

        

    } );
  </script>
<script>
      $(document).ready(function() {
        var table = $('#example5').DataTable( {
         
          //scrollY: '300',
          scrollX: true,
          fixedColumns: true,
          fixedHeader: true,
          //pageLength: 100,
          "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]],

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
            //"order": [[ 0, "desc" ]],

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
    $('#example3').DataTable({
      "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]],
    })
  })


</script>
<!-- Page script -->
<script>
  $(function () {

    //Initialize Select2 Elements
    $('.select2').select2();
    $('.select3').select2();
  
    $('#datepicker').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

    $('#datepicker1').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

    $('#datepicker2').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

    $('#datepicker3').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

    $('#datepicker4').datepicker({
      format: 'yyyy-mm-dd',
      autoclose: true
    });

  
  })
</script>
<!-- <script>
$(document).ready(function() {
    var max_fields      = 10;
    var wrapper         = $(".container1"); 
    var add_button      = $(".add_form_field"); 
    
    var x = 1; 
    $(add_button).click(function(e){ 
        e.preventDefault();
        if(x < max_fields){ 
            x++; 
$(wrapper).append('<div class="row " style="padding:0px 50px 10px 250px "><div class="col-xs-3"><select name="select_products[]" class="form-control"><option selected="selected">Select Product</option><option value=" Product 1">Product 1</option><option value="Product 2">Product 2</option><option value="Product 3">Product 3</option><option Value="Product 4">Product 4</option><option value="Product 5">Product 5</option></select></div><div class="col-xs-3"><input type="text" name="quantity[]" class="form-control"  placeholder="Quantity"></div><div class="col-xs-3"><input type="text" name="price[]" class="form-control"  placeholder="Price"></div><button  <button  class="delete btn btn-danger btn-round col-sm-3">Delete Field &nbsp; <span style="font-size:16px; font-weight:bold;">- </span></button></div>'); //add input box





        }
    else
    {
    alert('You Reached the limits')
    }
    });
    
    $(wrapper).on("click",".delete", function(e){ 
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});

</script> -->

<!-- <script type="text/javascript">
  $("#monthpicker").datepicker( {
    format: "mm-yyyy",
</script> -->


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
