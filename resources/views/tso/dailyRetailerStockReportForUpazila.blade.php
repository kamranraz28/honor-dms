@extends('layouts.master_tso')

@section('title')
  {{"Sales Automation Process :: Retailer Stock Report"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('tso.bc.bc')
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
              <h3 class="box-title text-danger">Retailer Stock Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal1" method="POST" action="{{ route('tso.dailyRetailerStockReportForUpazila.store') }} " autocomplete="off" enctype="multipart/form-data">

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

           
                  
                  <div class="col-md-6 {{ $errors->has('upazila_id') ? 'has-error' : '' }}">
                    <label for="upazila" class="control-label">Distributor</label>
                    <select name="upazila_id" id="upazila_id" class="form-control select2" style="width: 100%;" required="required">
                      {{-- <option value="">Select Distributor</option> --}}
                      @foreach ($upazilas as $key => $upazila)
                        <option value="{{$upazila['upazila_id']}}">{{$upazila['name']."-".$upazila['bn_name']}}</option>
                      @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('upazila_id') }}</span>
                  </div>

                  <div class="col-md-6 {{ $errors->has('user_id') ? 'has-error' : '' }}">
                    <label for="user" class="control-label">Retailer</label>
                    <select name="user_id" id="user_id" class="form-control select2" style="width: 100%;">
                      <!-- <option value="">Select Retailer</option> -->
                      
                    </select>
                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                  </div>

                  

                  <div class="col-md-6">
                    <label for="Level" class="control-label">From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="fdate" placeholder="YYYY-MM-DD" value="{{@$retVal = ($ssdata['fdate']) ? $ssdata['fdate'] : ""  }}" type="text" class="form-control pull-right" id="datepicker3"  required="required" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="Level" class="control-label">To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="YYYY-MM-DD" value="{{@$retVal = ($ssdata['todate']) ? $ssdata['todate'] : ""  }}" type="text" class="form-control pull-right" id="datepicker4"  required="required" autocomplete="off">
                    </div>
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

@php
  @$fdate = date_format(date_create($ssdata['fdate']),"Y-m-d");
  @$todate = date_format(date_create($ssdata['todate']),"Y-m-d");
  //$user_id = $ssdata['user_id'];
@endphp


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p style="text-align: center;font-size: 12px;font-weight: bold;color: black;">Retailer Stock Report  {{@$ssdata['fdate']}} to {{@$ssdata['todate']}}</p>

<table id="example" width="100%">
         

    <thead>
      <tr>
        <th> # </th>
        <th> Retailer </th>
        <th> Product </th>
        <th> Stock In </th>
        <th> Stock Out </th>
        <th> Stock </th>
      </tr>

    </thead>
    <tbody>

@foreach ($dailyRetailerStockReports as $key => $element)
<tr>
          <td> {{$key + 1}} </td>
          <td> {{$element['retailer']}} </td>
          <td> {{$element['product']}} - {{$element['model']}}</td>
          <td> {{$element['stockin']}} </td>
          <td> {{$element['stockout']}} </td>
          <td> {{$element['stock']}} </td>
          
</tr>

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


<script type="text/javascript">
  

$(document).ready(function() {
  
  $('#upazila_id').on('change', function(e){
    var upazila_id = e.target.value;

console.log(upazila_id);
    var route = "{{route('admin.distributorSelectBoxOnRetailerWithAjax')}}/"+upazila_id;
    $.get(route, function(data) {
      console.log(data);
      $('#user_id').empty();
      $.each(data, function(index,data){
        $('#user_id').append('<option value="' + data.retailer_id + '">' + data.name + " ( " + data.officeid +" ) " +  '</option>');

      });
    });


  });


});

</script>

@php
  Session::forget(['brand_id','upazila_id','sno','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection