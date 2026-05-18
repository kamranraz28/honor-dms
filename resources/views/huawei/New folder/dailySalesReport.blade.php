@extends('layouts.master_huawei')

@section('title')
  {{"E-Warranty Ststem :: Daily Sales Report"}}
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
              <h3 class="box-title text-danger">Daily Sales Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('huawei.dailySalesReport.store') }} " autocomplete="off" enctype="multipart/form-data">

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

           
                  
                  <div class="col-md-4 {{ $errors->has('brand_id') ? 'has-error' : '' }}">
                    <label for="brand" class="control-label">Brand</label>
                    <select name="brand_id" id="brand_id" class="form-control select2" style="width: 100%;" required="required">
                      <option value="all">All</option>
                      @foreach ($brands as $key => $element)
                        <option value="{{$element['id']}}" {{ $element['id'] == @$ssdata['brand_id'] ? ' selected="selected"' : '' }}>{{$element['name']}}</option>
                      @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('brand_id') }}</span>
                  </div>

                  <div class="col-md-4 {{ $errors->has('user_id') ? 'has-error' : '' }}">
                    <label for="user" class="control-label">Retailer</label>
                    <select name="user_id" id="user_id" class="form-control select2" style="width: 100%;">
                      <option value="">Select Retailer</option>
                      @foreach ($users as $key => $element)
                        <option value="{{$element['id']}}" {{ $element['id'] == @$ssdata['user_id'] ? ' selected="selected"' : '' }}>{{$element['firstname']}} - {{$element['officeid']}}</option>
                      @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                  </div>

                  <div class="col-md-4 {{ $errors->has('sno') ? 'has-error' : '' }}">
                    <label for="sno">Serial No:</label>
                    <input type="text" id="sno" name="sno" class="form-control" placeholder="Enter Serial No" value="{{@$retVal = ($ssdata['sno']) ? $ssdata['sno'] : ""  }}">
                    <span class="text-danger">{{ $errors->first('sno') }}</span>
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
              <!-- <a href="" target="_blank" class="btn btn-info btn-xs pull-right">Print Report</a> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p style="text-align: center;font-size: 12px;font-weight: bold;color: black;">Daily Sales Report From {{@$ssdata['fdate']}} to {{@$ssdata['todate']}}</p>

<table id="example">
         

    <thead>
      <tr>
        <th> # </th>
        <th> Brand </th>
        <th> Product </th>
        <th> Model </th>
        <th> IMEI </th>
        <th> Serial No </th>
        <th> Warranty Period </th>
        <th> Sale Date </th>
        <th> Warranty S.Date </th>
        <th> Warranty E.Date </th>
        <th> Retailer Code </th>
        <th> Retailer Name </th>
        <th> Customer Mobile </th>
        <th> Created At </th>
      </tr>

    </thead>
    <tbody>

@foreach ($dailySalesReports as $key => $element)
<tr>
          <td> {{$key + 1}} </td>
          <td> {{$element->brand['name']}} </td>
          <td> {{$element->product['name']}} </td>
          <td> {{$element->product['model']}} </td>
          <td> {{$element->imei}} </td>
          <td> {{$element->sno}} </td>
          <td> {{$element->wperiod}} Days</td>
          <td> {{$element->saledate}} </td>
          <td> {{$element->sdate}} </td>
          <td> {{$element->edate}} </td>
          <td> {{$element->user['officeid']}} </td>
          <td> {{$element->user['firstname']}} </td>
          <td> {{$element->mobile}} </td>
          <td> {{$element->createdAt}} </td>
          
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
  Session::forget(['brand_id','user_id','sno','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection