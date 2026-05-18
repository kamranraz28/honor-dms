@extends('layouts.master_topmanagement')

@section('title')
  {{"E-Warranty Ststem :: Daily Replace Report"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('topmanagement.bc.bc')
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
              <h3 class="box-title text-danger">Repalce Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal1" method="POST" action="{{ route('topmanagement.dailyReplaceReport.store') }} " autocomplete="off" enctype="multipart/form-data">

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

        <div class="col-md-6">
          <div class="form-group">
            
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="model" style="visibility: hidden;">IMEI Or Serial No:</label>
            <button type="submit" class="btn btn-success form-control">Search...</button>
          </div>
        </div>
                
                </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <!-- <button type="submit" class="btn btn-success pull-right">Submit</button> -->
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

@if (count($dailyReplaceReports) != 0)

@php
  //$fdate = date_format(date_create($ssdata['fdate']),"Y-m-d");
  //$todate = date_format(date_create($ssdata['todate']),"Y-m-d");
  //$user_id = $ssdata['user_id'];
@endphp


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
              
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p style="text-align: center;font-size: 12px;font-weight: bold;color: black;">
  Replace Daily Report By {{$ssdata['fdate']}} To {{$ssdata['todate']}}
</p>

<table id="example5" width="100%">
         

    <thead>


  
      <tr>
        <th> Replace IMEI </th>
        <th> Replace SL.NO </th>
        <th> Replace Date </th>

        <th> Retailer Name </th>
        <th> Retailer Code </th>

        <th> Product </th>
        <th> Model </th>

        <th> IMEI 1 </th>
        <th> IMEI 2 </th>
        <th> Warranty Period </th>
        <th> Sale Date </th>
        <th> Warranty Start Date </th>
        <th> Warranty End Date </th>
       
      </tr>

    </thead>
    <tbody>

@foreach ($dailyReplaceReports as $key => $dailyReplaceReport)
<tr>
         
          <td> {{$dailyReplaceReport['imei']}} </td>
          <td> {{$dailyReplaceReport['sno']}} </td>
          <td> {{$dailyReplaceReport['rplsdate']}} </td>
          <td> {{$dailyReplaceReport['smsdetail']['user']['firstname']}}</td>
          <td> {{$dailyReplaceReport['smsdetail']['user']['officeid']}}</td>

          <td> {{$dailyReplaceReport['smsdetail']['product']['name']}} </td>
          <td> {{$dailyReplaceReport['smsdetail']['product']['model']}} </td>
          
          <td> {{$dailyReplaceReport['smsdetail']['sno']}}</td>
          <td> {{$dailyReplaceReport['smsdetail']['imei']}}</td>
          <td> {{$dailyReplaceReport['smsdetail']['wperiod']}} Days</td>
          <td> {{$dailyReplaceReport['smsdetail']['saledate']}} </td>
          <td> {{$dailyReplaceReport['smsdetail']['sdate']}} </td>
          <td> {{$dailyReplaceReport['smsdetail']['edate']}} </td>
          

</tr>

@endforeach




    </tbody>
  </table>







            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>
@else
@if (@$dataCount > 0)
<table width="100%"> 


    <tbody>
      <tr>
        <td rowspan="9">
          <p class="text-center text-danger">NO Data Found</p>
          
        </td>
      </tr>
    </tbody>

</table>
@endif

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
  Session::forget(['fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection