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



    <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('resources/assets/dms/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
      <!-- bootstrap datepicker -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- Select2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css">
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>






</head>

<body class="hold-transition login-page">
  
  @yield('content')
  


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
    startDate: '1d',
    autoclose: true
});

  
  })
</script>
<script>
$(document).ready(function() {
    var max_fields      = 10;
    var wrapper         = $(".container1"); 
    var add_button      = $(".add_form_field"); 
    
    var x = 1; 
    $(add_button).click(function(e){ 
        e.preventDefault();
        if(x < max_fields){ 
            x++; 
            /*$(wrapper).append('<div class="form-group"><div class="row"><label for="Invoice No." class="col-sm-2 control-label">Product Details</label><div class="col-sm-2"><input type="text" name="product[]" class="form-control"  placeholder="Quantity"></div><div class="col-sm-2"><input type="text" name="quantity[]" class="form-control"  placeholder="Quantity"></div><div class="col-sm-2"><input type="text" name="price[]" class="form-control"  placeholder="Quantity"></div></div><button class="delete">Delete Field &nbsp; <span style="font-size:16px; font-weight:bold;">- </span></button></div>'); //add input box*/
$(wrapper).append('<div class="col-md-8 col-md-offset-2"><div class="row"><div class="col-sm-10"><div class="col-xs-4"><label for="exampleInputEmail1" class="control-label">Product</label><input type="text" name="product[]" class="form-control"  placeholder="Product"></div><div class="col-xs-4"><label for="exampleInputEmail1" class="control-label">Quantity</label><input type="text" name="quantity[]" class="form-control"  placeholder="Quantity"></div><div class="col-xs-4"><label for="exampleInputEmail1" class="control-label">Price</label><input type="text" name="price[]" class="form-control"  placeholder="Price"></div><div class="col-sm-2"></div><button  class="delete btn-warning btn-danger btn-flat col-sm-2">Delete Field &nbsp; <span style="font-size:16px; font-weight:bold;">- </span></button></div></div></div>'); //add input box




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
</script>
<script src="{{asset('resources/assets/dms/plugins/iCheck/icheck.min.js') }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>




 
</body>

</html>
