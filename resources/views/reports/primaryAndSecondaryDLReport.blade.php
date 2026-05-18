@extends('layouts.master_admin')

@section('title')
  {{"E-Warranty System :: Daily Stock Report"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
              <h3 class="box-title text-danger">Daily Purchase Or Sales Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">


            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('admin.dailyPurchaseSaleReport1.store') }} " autocomplete="off" enctype="multipart/form-data">

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
               <br>

                <div class="col-md-8">
                  <label class="control-label" for="distributor">Distributor (Leave Blank for All)</label>
                  <input type="text"
           id="distributor_search"
           class="form-control"
           placeholder="Type to Search distributor..."
           list="distributor_list"
           autocomplete="off">

    <datalist id="distributor_list"></datalist>

    <input type="hidden" name="distributor_id" id="distributor_id">
                  <span class="text-danger">{{ $errors->first('distributor_id') }}</span>
                </div>

                  <div class="col-md-8">
                    <label for="Level" class="control-label">From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="fdate" placeholder="YYYY-MM-DD" value="{{@$retVal = (Session::get('fdate')) ? $ssdata['fdate'] : ""  }}" type="text" class="form-control pull-right" id="datepicker3" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-md-8">
                    <label for="Level" class="control-label">To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="YYYY-MM-DD" value="{{@$retVal = (Session::get('todate')) ? $ssdata['todate'] : ""  }}" type="text" class="form-control pull-right" id="datepicker4" autocomplete="off">
                    </div>
                  </div>



<div class="col-md-8">

                 <label class="control-label">Sales Type</label>
                  <div class="col-sm-123">
                    <label class="radio-inline">
                      <input type="radio" name="type" value="Purchase" @if (Session::get('type') == "Purchase" ) checked @endif>Primary
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="type" value="Sale" @if (Session::get('type') == "Sale" ) checked @endif>Secondary
                    </label>
                  </div>

                </div>

                </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="action-btn action-sync" ><span class="btn-icon">
                                    <i class="fa fa-download"></i>
                                </span>

                                <span class="btn-text">
                                    Download Report
                                </span>

                                <span class="action-chip">
                                    Submit
                                </span></button>
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

   <div class="text-left col-lg-6">
        <a target="_blank" class="action-btn action-sync" href="{{ route('currentMonthPrimaryExcel') }}"><span class="btn-text">Primary Sales Export (Current Month)</span></a> <br>
        <a target="_blank" class="action-btn action-sync" style="margin-top: 12px" href="{{ route('lastSixmonthPrimaryExcel') }}"><span class="btn-text">Primary Sales Export (Last Six Months)</span></a> <br>
        <a target="_blank" class="action-btn action-sync" style="margin-top: 12px" href="{{ route('admin.stock.pexcel') }}"><span class="btn-text">Primary Sales Export (All)</span></a>
    </div>

    <div class="text-right col-lg-6">
      <a target="_blank" class="action-btn action-sync" href="{{ route('currentMonthExcel') }}"> <span class="btn-text">Secondary Sales Export (Current Month)</span> </a> <br>

        <a target="_blank" class="action-btn action-sync" style="margin-top: 12px" href="{{ route('lastSixmonthExcel') }}"><span class="btn-text">Secondary Sales Export (Last Six Months)</span></a> <br>
        <a target="_blank" class="action-btn action-sync" style="margin-top: 12px" href="{{ route('admin.stock.sexcel') }}"><span class="btn-text">Secondary Sales Export (All)</span></a>
    </div>




{{-- <center><div id="loader"><img src="{{ asset('resources/assets/dms/dist/img/loading.gif') }}"></div></center> --}}
            </div>
            <!-- /.box-header -->

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
  Session::forget(['type','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection
