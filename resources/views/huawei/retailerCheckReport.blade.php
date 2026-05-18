@extends('layouts.master_huawei')

@section('title')
  {{"E-Warranty Ststem :: Retailer Mapping Report"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('huawei.bc.bc')
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
              <h3 class="box-title text-danger">Retailer Mapping Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('huawei.retailerCheckReport.store') }} " autocomplete="off" enctype="multipart/form-data">

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


                <div class="col-md-8">
                  <label class="control-label" for="distributor">Distributor :</label>
                  <select name="distributor_id" id="distributor" class="form-control select2" required="required">
                    <option value="All">All</option>
                    @foreach($distributors as $key=>$distributor )
                      <option value="{{ $distributor['id'] }}" {{ Session::get('distributor_id') == $distributor['id'] ? ' selected="selected"' : '' }}>{{ $distributor['firstname'] }} - {{ $distributor['officeid'] }}</option>
                    @endforeach
                  </select> 
                  <span class="text-danger">{{ $errors->first('distributor_id') }}</span>
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


<table id="example" width="100%">
         

    <thead>
      <tr>
        <th> # </th>
        <th> Distributor </th>
        <th> Distributor Code </th>
        <th> Retailer </th>
        <th> Retailer Code </th>
        <th> Date </th>
      </tr>

    </thead>
    <tbody>

@foreach ($retailerCheckReports as $key => $element)

@foreach ($element['retailer'] as $rkey => $retailervalue)
  <tr>
      <td> {{$rkey + 1}} </td>
      <td> {{$element['firstname']}} </td>
      <td> {{$element['officeid']}} </td>

      <td> {{$retailervalue['name']}} </td>
      <td> {{$retailervalue['officeid']}} </td>
      <td> {{$retailervalue['date']}} </td>
            
  </tr>
@endforeach


@endforeach





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