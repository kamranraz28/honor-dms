@extends('layouts.master_tso')

@section('title')
  {{"Sales Automation Process ::  Sales Report"}}
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
              <h3 class="box-title text-danger">LD Sales Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('tso.dailySalesReports.store') }} " autocomplete="off" enctype="multipart/form-data">

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
                      
                      <option value="All">All</option>
                      @foreach ($upazilas as $key => $upazila)
                        <option value="{{$upazila['upazila_id']}}">{{$upazila['name']."-".$upazila['bn_name']}}</option>
                      @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('upazila_id') }}</span>
                  </div>

                
                  <div class="col-md-6">
                    <label for="Level" class="control-label">From Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="fdate" placeholder="YYYY-MM-DD" value="{{@$retVal = (Session::get('fdate')) ? $ssdata['fdate'] : ""  }}" type="text" class="form-control pull-right" id="datepicker3" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-md-6">
                    <label for="Level" class="control-label">To Date</label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="YYYY-MM-DD" value="{{@$retVal = (Session::get('todate')) ? $ssdata['todate'] : ""  }}" type="text" class="form-control pull-right" id="datepicker4" autocomplete="off">
                    </div>
                  </div>
                


<div class="col-md-6">
                 
<br>
<br>
                
           <!--<div class="col-sm-123">
                    <div class="input-group">
                      <label class="checkbox-inline">
                        <input type="checkbox"{{-- <?php echo $retVal = (Session::get('all_report')) ? 'checked' : ''?> --}} value="all_report" name="all_report">
                        All
                      </label>
                    </div>
                  </div>--!>
 


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
@if ($ssdata['count'] == 1)
  {{-- expr --}}


<table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  {{-- <th>#</th> --}}
                  <th>LD Name</th>
                   <th>LD ID</th>
                   <th>Retailer</th>
                   <th>Retailer ID</th>
                    <th>Brand</th>
                  <th>Product Name </th>
                  <th>Product Model </th>
                  <th>IMEI 1 </th>
                  <th>IMEI 2</th>
                  <!-- <th>Quantity </th> -->
                  <th>Created Date </th>
                </tr>
                </thead>
                <tbody>
@foreach ($sales as $pur)
    <tr>
   {{--  <td>{{$pur + 1}} </td> --}}
    <td>{{$pur['firstname']}} </td>
    <td>{{$pur['officeid']}} </td>
    <td>{{$pur['rname']}}</td>
    <td>{{$pur['rid']}} </td>
    <td>{{$pur['brand']}} </td>
    <td>{{$pur['name']}} </td>
    <td>{{$pur['model']}} </td>
    <td>{{ $pur['sno'] }}</td>
    <td>{{ $pur['imei'] }}</td>
    <td>{{ $pur['created_at'] }}</td>



  </tr>
@endforeach
                </tbody>
               
              </table>
<table>
  
  <tbody>
      <tr>
        <td colspan="6">
         {{--  {{ $purchases->links() }} --}}
        </td>
      </tr>
  </tbody>

</table>

@endif





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
  Session::forget(['all_report','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection