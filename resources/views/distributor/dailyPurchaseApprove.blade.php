@extends('layouts.master_distributor')

@section('title')
  {{"Sales Automation Process :: Daily Purchase Approve"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('distributor.bc.bc')
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
              <h3 class="box-title text-danger">Daily Purchase Approve</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('distributor.dailyPurchaseApprove.store') }} " autocomplete="off" enctype="multipart/form-data">

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

                  <!-- <div class="col-md-6">
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
                  </div> -->
                

                </div>
              <!-- /.box-body -->
              <!-- <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div> -->
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
<form class="form-horizontal" method="POST" action="{{ route('distributor.dailyPurchaseApprove.status') }} " autocomplete="off" enctype="multipart/form-data">

<input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">

<table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th><input type="checkbox" name="parentItem" id="checkAll"> All </th>
                  <th>Status</th>
                  <th>Product Name </th>
                  <th>Product Model </th>
                  <th>IMEI 1 </th>
                  <th>IMEI 2 </th>
                  <!-- <th>Quantity </th> -->
                  <th>Created Date </th>
                </tr>
                </thead>
                <tbody>
@foreach ($purchases as $key => $element)
  
  <tr>
    <td>{{$key + 1}} </td>
    <td><input type="checkbox" name="childItem[]" id="childItem" value="{{$element->id}}"> </td>
<td>
  @if ($element->status == true)
       <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#">Active</button>
    @else
      <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#">Inactive</button>
    @endif
</td>
    <td>{{$element->product['name']}} </td>
    <td>{{$element->product['model']}} </td>
    <td> 
      @if ($element->sno == NULL)
        -
      @else
        {{$element->sno}}
      @endif
    </td>
    <td> 
      @if ($element->imei == NULL)
        -
      @else
        {{$element->imei}}
      @endif
    </td>
    <!-- <td>{{--$element->quantity--}} </td> -->
    <td>{{date_format(date_create($element->created_at),"d-M-Y")}}</td>



  </tr>
@endforeach
                </tbody>
               
              </table>

<br> 
              <div class="col-md-12">
                
                <button type="submit" class="btn btn-success" style="width: 100%">Update All</button>
              </div>
</form>




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


 <script type="text/javascript" language="javascript">
    $("#checkAll").click(function () {
       $('input:checkbox').not(this).prop('checked', this.checked);
   });
</script>


@php
  Session::forget(['fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection