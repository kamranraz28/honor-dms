@extends('layouts.master_admin')

@section('title')
  {{"E-Warranty Ststem :: Daily Sales Report"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('admin.bc.bc')
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
              <h3 class="box-title text-danger">Daily Report Searching Result According To Posting Date</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('admin.dailySalesReport.store') }} " autocomplete="off" enctype="multipart/form-data">

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
               <br><br><br>

                <div class="form-group {{ $errors->has('level') ? 'has-error' : '' }}">
                  
                  <div class="col-sm-6">
                    <label for="Level" class="control-label">User Level</label>
                    <select name="level" id="level" class="form-control" style="width: 100%;" required="required">
                      <option selected="selected">Select Level</option>
                      <option value="400">Accounts </option>
                      <option value="300">Inventory </option>
                      <option value="200">OPD Hospital </option>
                      <option value="500">Admin </option>
                    </select>
                    <span class="text-danger">{{ $errors->first('level') }}</span>
                  </div>

                  <div class="col-sm-6">
                    <label for="Level" class="control-label">User</label>
                    <select name="user_id" id="user_id" class="form-control" style="width: 100%;" required="required">
                      <option selected="selected">Select User</option>
                    </select>
                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                  </div>
                </div>




                <div class="form-group">
                  

                  <div class="col-sm-6">
                    <label for="Level" class="control-label">From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="fdate" placeholder="YYYY-MM-DD" value="{{@$retVal = ($ssdata['fdate']) ? $ssdata['fdate'] : ""  }}" type="text" class="form-control pull-right" id="datepicker3"  required="required" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-sm-6">
                    <label for="Level" class="control-label">To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="YYYY-MM-DD" value="{{@$retVal = ($ssdata['todate']) ? $ssdata['todate'] : ""  }}" type="text" class="form-control pull-right" id="datepicker4"  required="required" autocomplete="off">
                    </div>
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

@if (count($dailySalesReports) != 0)

@php
  $fdate = date_format(date_create($ssdata['fdate']),"Y-m-d");
  $todate = date_format(date_create($ssdata['todate']),"Y-m-d");
  $user_id = $ssdata['user_id'];
@endphp


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
              <!-- <h3 class="box-title">Recent Order Invoice </h3> -->
              <a href="{{ route('admin.dailySalesReport.print',[$user_id,$fdate,$todate]) }}" target="_blank" class="btn btn-info btn-xs pull-right">Print Report</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p style="text-align: center;font-size: 12px;font-weight: bold;color: black;">User Daily Report From {{$ssdata['fdate']}} to {{$ssdata['todate']}}</p>

<table id="example" width="100%">
         

    <thead>
      <!-- <tr>
        <th colspan="5" style="border: none">
          <caption style="text-align: center;font-size: 12px;font-weight: bold;color: black;">Daybook Report From {{$ssdata['fdate']}} to {{$ssdata['todate']}}</caption>
        </th>
      </tr> -->

  
      <tr>
        <th> User Name </th>
        <th> User Type </th>
        <th> Post. Date </th>
        <th> Vch. Date </th>
        <th> Voucher No </th>
        <th> Amount </th>
      </tr>

    </thead>
    <tbody>

@foreach ($dailySalesReports as $key => $dailySalesReport)
<tr>
          <td> {{$dailySalesReport['username']}} </td>
          <td> 
@if ($dailySalesReport['level'] == "500")
  Admin
@elseif($dailySalesReport['level'] == "400")
  Accounts
@elseif($dailySalesReport['level'] == "300")
  Inventory
@else
  OPD Hospital
@endif

          </td>
          <td> {{$dailySalesReport['date']}} </td>
          <td> {{$dailySalesReport['vdate']}} </td>
          <td> {{$dailySalesReport['vno']}} </td>

          <td>
            @isset ($dailySalesReport['amount'])
              {{number_format($dailySalesReport['amount'],2)}}
            @endisset
          </td>
</tr>

@endforeach




    </tbody>
  </table>







            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>
@endif
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
  Session::forget(['user_id','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection