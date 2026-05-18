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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
      
      <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<!-- <script src="{{asset('resources/assets/required/charts/loader.js') }}"></script> -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">


<!-- top nav part================================ -->
@include('layouts.includes.topnav_huawei')
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<!-- DataTables -->
{{-- <script src="{{asset('resources/assets/dms/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.20/js/dataTables.bootstrap.min.js"></script>
<!-- date-range-picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- SlimScroll -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>
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
