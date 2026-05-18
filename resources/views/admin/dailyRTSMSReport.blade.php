@extends('layouts.master_admin')

@section('title')
  {{"E-Warranty Ststem :: Daily Return Report"}}
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
              <h3 class="box-title text-danger">Retailer Wise Promotion</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal1" method="POST" action="{{ route('admin.dailyRTSMSReport.store') }} " autocomplete="off" enctype="multipart/form-data">

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
<p style="text-align: center;font-size: 12px;font-weight: bold;color: black;">
  Promotion Report By {{@$ssdata['fdate']}} To {{@$ssdata['todate']}}
</p>

              <table id="example" class="ui celled table" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Retailer </th>

                  <th>Retailer ID</th>

                  <th>Mobile </th>
                  <th>Promotion </th>
                  <th>Gift </th>
                  <th>Color </th>
                  <th>Created Date </th>
                 
                  <!-- <th>Updated Date </th> -->
                </tr>
                </thead>
                <tbody>
@foreach ($preturns as $key => $element)
  
  <tr>
    <td>{{$key + 1}} </td>

    <td>{{$element->user['firstname']}} </td>
    <td>{{$element->user['officeid']}} </td>
    <td>{{$element->phoneno}} </td>
    <td>{{$element->promort['name']}} </td>
    <td>{{$element->promortdetail['details']}} </td>
    <td>{{$element->color}} </td>

    <td>{{$element->created_at}}</td>
    <!-- <td>{{date_format(date_create($element->updated_at),"d-M-Y")}}</td> -->

    <!-- <td>
    
      @if ($element->status == 1)
        <button class="btn btn-xs btn-primary"> Active</button> 
     @else
        <button class="btn btn-xs btn-danger"> Inactive</button>
      @endif
    </td> -->

  </tr>


@endforeach
                </tbody>
               
              </table>
  







            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


<!-- ************************************************* -->



  </section>

<!-- ==============one section area ================= -->








</div>
<!-- /.row (main row) -->



    </section>
    <!-- /.content -->
 
  </div>
<!-- /.content-wrapper -->



@php
  //Session::forget(['fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection