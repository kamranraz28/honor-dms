@extends('layouts.master_distributor')

@section('title')
  {{"Sales Automation Process :: Daily Sales Report"}}
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
              <h3 class="box-title text-danger">Daily Sales Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             <form class="form-horizontal1" method="POST" action="{{ route('distributor.dailySalesReport.store') }} " autocomplete="off" enctype="multipart/form-data">

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

           

                  <div class="col-md-6 {{ $errors->has('user_id') ? 'has-error' : '' }}">
                    <label for="user" class="control-label">Retailer</label>
                    <select name="user_id" id="user_id" class="form-control select2" style="width: 100%;">
                      <option value="all">All</option>
                      @foreach ($users as $key => $element)
                        <option value="{{$element['id']}}" {{ $element['id'] == @$ssdata['user_id'] ? ' selected="selected"' : '' }}>{{$element['firstname']}} - {{$element['officeid']}}</option>
                      @endforeach
                    </select>
                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                  </div>

                  <div class="col-md-6 {{ $errors->has('sno') ? 'has-error' : '' }}">
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
<a target="_blank" class="btn btn-sm btn-success pull-right" href="{{route('saleCurrentMonthExcel')}}"> Current Month Sales Download</a>
            <a target="_blank" class="btn btn-sm btn-success pull-right" href="{{route('saleSixMonthExcel')}}"> Last 6 Months Sales Download</a>

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
              <!-- <a href="{{ route('distributor.dailySalesReport.print',[$user_id,$fdate,$todate]) }}" target="_blank" class="btn btn-info btn-xs pull-right">Print Report</a> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<p style="text-align: center;font-size: 12px;font-weight: bold;color: black;">Daily Sales Report From {{$ssdata['fdate']}} to {{$ssdata['todate']}}</p>
 <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>SR </th>
                  <th>Retailer </th>
                  <th>Product Name </th>
                  <th>Product Model </th>
                  <th>IMEI 1 </th>
                  <th>IMEI 2 </th>
                  <th>Created Date </th>
                 {{--  <th>Changing Before Retailer </th> --}}
                  
{{-- @if ($status['dist_return'])
  <th>Action</th>
@endif --}}

                </tr>
                </thead>
                <tbody>
@foreach ($dailySalesReports as $key => $element)
  
  <tr>
    <td>{{$key + 1}} </td>
<td>
@if ($element->sr)
  {{$element->sr['name']}} - {{$element->sr['officeid']}} 
@else
  -
@endif
</td>
    <td>{{$element->retailer['name']}} - {{$element->retailer['officeid']}} </td>
    <td>{{$element->product['name']}} </td>
    <td>{{$element->product['model']}} </td>
    <td>{{$element->sno}} </td>
    <td>{{$element->imei}} </td>
    <td>{{date_format(date_create($element->created_at),"d-M-Y")}}</td>



{{-- 
  <td>
    
    @if (count($element['Salereturn']) > 0)
      <button class="btn btn-xs btn-warning" data-toggle="modal" data-target="#{{'saleReturnUpdateModal1'. $element->id}}">
        Retailer Found
      </button>
    @else
      <button class="btn btn-xs btn-info" data-toggle="modal" data-target="#{{'saleReturnUpdateModal1'. $element->id}}">
        No Retailer
      </button>
    @endif





  </td> --}}



{{-- @if ($status['dist_return'])
  <td>
    
    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'saleReturnUpdateModal'. $element->id}}">
      Return
    </button>

  </td>
@endif --}}



  </tr>


@endforeach
                </tbody>
               
              </table>
<table>
  
  <tbody>
      <tr>
        <td colspan="6">
          {{ $dailySalesReports->links() }}
        </td>
      </tr>
  </tbody>

</table>







            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>
@else
@if (@$ssdata['fdate'])
<table width="100%"> 


    <tbody>
      <tr>
        <td rowspan="14">
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










<!--custom delete modal part================================ -->


@forelse ($dailySalesReports as $key => $element)
  <!-- Modal -->

<!--custom update modal part================================ -->

  <div class="modal fade" id="{{'saleReturnUpdateModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->id}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('distributor.sale.return.update')}}" method="post">
  <p class="text-info">Do You Want To Update This Data ?</p>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="user_id" value="{{ Auth::id() }}">
  <input type="hidden" name="id" value="{{ $element->id }}">


  <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
    <label for="product">Retailer:</label>

      <select name="retailer_id" id="retailer" class="form-control" required="true">
        <!-- <option value="">Select Retailer</option> -->
        @foreach($retailers as $retailer )
          <!-- <option value="{{ $retailer['id'] }}" {{ $element->retailer['id'] == $retailer['id'] ? ' selected="selected"' : '' }}>{{ $retailer['name'] }} - {{ $retailer['officeid'] }} - {{  $element->retailer['id'] }} - {{  $retailer['id'] }} - {{  Auth::id() }}</option> -->

          <option value="{{ $retailer['id'] }}" {{ $element->retailer['id'] == $retailer['id'] ? ' selected="selected"' : '' }}>{{ $retailer['name'] }} - {{ $retailer['officeid'] }} </option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('retailer_id') }}</span>
  </div>





  <div class="form-group">
    <button class="form-control btn btn-success">Update</button>
  </div>
</form>

<!-- body part -->
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>



<!--custom update modal part================================ -->


@if (count($element['Salereturn']) > 0)
  {{-- expr --}}

<!--custom update modal part================================ -->

  <div class="modal fade" id="{{'saleReturnUpdateModal1'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->id}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Retailer Name</th>
        <th>Created Date </th>
        <th>Updated Date</th>
      </tr>
    </thead>
    <tbody>
      
@foreach ($element['Salereturn'] as $key => $Salereturndata)
      <tr>
        <td>{{$key + 1 }}</td>
        <td>{{$Salereturndata['retailer_name']}}</td>
        <td>{{$Salereturndata['created_at']}}</td>
        <td>{{$Salereturndata['updated_at']}}</td>
      </tr>
@endforeach




    </tbody>
  </table>






<!-- body part -->
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>



<!--custom update modal part================================ -->
@endif



@endforeach






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


<!-- content part================================ -->
@endsection