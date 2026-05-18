@extends('layouts.master_topmanagement')

@section('title')
  {{"E-Warranty Ststem :: Daily Stock Report"}}
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
              <h3 class="box-title text-danger">Daily Stock Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('topmanagement.dailyRetailerStockReport.store') }} " autocomplete="off" enctype="multipart/form-data">

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
                  <label class="control-label" for="retailer">Retailer :</label>
                  <select name="retailer_id" id="retailer" class="form-control select2" required="required">
                    <option value="All">All</option>
                    @foreach($retailers as $key=>$retailer )
                      <option value="{{ $retailer['id'] }}" {{ Session::get('retailer_id') == $retailer['id'] ? ' selected="selected"' : '' }}>{{ $retailer['firstname'] }} - {{ $retailer['officeid'] }}</option>
                    @endforeach
                  </select> 
                  <span class="text-danger">{{ $errors->first('retailer_id') }}</span>
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
                 
<br>
                 
                  <div class="col-sm-123">
                    <div class="input-group">
                      <label class="checkbox-inline">
                        <input type="checkbox" <?php echo $retVal = (Session::get('all_report')) ? 'checked' : ''?>  value="all_report" name="all_report">
                        All
                      </label>
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
  Session::forget(['all_report','retailer_id','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection