@extends('layouts.master_warehouse')

@section('title')
  {{"E-Warranty Ststem :: Order Report"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('warehouse.bc.bc')
    <!-- bc part================================ -->

    <!-- Main content -->
    <section class="content">


<!-- Main row -->
<div class="row">
  <!-- Left col -->
  


<!-- ==============one section area ================= -->


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title text-danger">Order Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('warehouse.orderReport.store') }} " autocomplete="off" enctype="multipart/form-data">

    @if(count($errors))
      <div class="alert alert-danger alert-dismissible">
        <strong>Whoops!</strong> There were some problems with your input.
        <br/>
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(Session::has('success'))
      

      <div class="alert alert-success alert-dismissible fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> {{Session::get('success')}}
      </div>

    @endif

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
{{-- for for displaying success and errror message --}}
                <div class="box-body">
               <br><br>


                <div class="" style="text-align:left">
                  <h4>Search by Order Number:</h4>
                  <input type="number" class="form-control" name="distributor_id" value="{{ old('distributor_id') }}">

                  
                </div>
                


<div class="col-md-8">
                 
<br>
                </div>


                </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
            
            </form>


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>

<!-- ==============one section area ================= -->

<!-- ==============one section area ================= -->


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">

            </div>
            <!-- /.box-header -->
            <div class="box-body">


<table id="example" class="ui celled table" width="100%">
         

    <thead>
      <tr>
        <th  style="text-align:center">#</th>
        <th  style="text-align:center">Order Number</th>
        <th  style="text-align:center">Distributor Id</th>
        <th  style="text-align:center"> Distributor Name</th>
        <th  style="text-align:center">Product Name</th>
        <th  style="text-align:center">Product Model</th>
        <th  style="text-align:center">IMEI1</th>
        <th  style="text-align:center">IMEI2</th>
        <th  style="text-align:center">Date</th>
      </tr>

    </thead>
    <tbody style="text-align:center">

    @if (isset($orderReports) && !empty($orderReports))
    @foreach ($orderReports as $key => $element)
    <tr  style="text-align:center">
        <td> {{$key + 1}} </td>
        <td> {{$element['orderNumber']}} </td>
        <td>{{$element['userId']}}</td>
        <td>{{$element['name']}}</td>
        <td>{{$element['productName']}}</td>
        <td>{{$element['productModel']}}</td>
        <td>{{$element['sno']}}</td>
        <td>{{$element['imei']}}</td>
        <td>{{$element['date']}}</td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="8">No data available</td>
    </tr>
@endif



        
    </tbody>
  </table>







            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>

<!-- ==============one section area ================= -->









</div>
<!-- /.row (main row) -->
















    </section>
    <!-- /.content -->
 
  </div>
<!-- /.content-wrapper -->


<!-- <script type="text/javascript">
  

$(document).ready(function() {
  
  $('#level').on('change', function(e){
    var level = e.target.value;


    var route = "{{--route('ajax.GetUsersOnLevelChange')--}}/"+level;
    $.get(route, function(data) {
      //console.log(data);
      $('#user_id').empty();
      $('#user_id').append('<option value="">Select User</option>');
      $.each(data, function(index,data){
        $('#user_id').append('<option value="' + data.id + '">' + data.firstname + " "+ data.lastname + " ( " +data.email +" ) " +  '</option>');
      });
    });


  });


});

</script> -->

@php
  Session::forget(['distributor_id']);
@endphp
<!-- content part================================ -->
@endsection