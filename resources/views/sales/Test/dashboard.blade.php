@extends('layouts.master_sales1')

@section('title')
  {{"DMS :: Dashboard"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Dashboard
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('sales.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="{{ route('sales.dashboard') }}">Dashboard</a></li>
      </ol>
    </section>







    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{$newOrder}}</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
          </div>
        </div>

        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>{{$totalDistribuitors}}</h3>

              <p>Distributor Registrations</p>
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-4 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>{{$completeOrder}}</h3>

              <p>Orders Completed</p>
            </div>
            <div class="icon">
              <i class="ion ion-pie-graph"></i>
            </div>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <div class="row">
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Recent Order Invoice </h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">


<div class="table-responsive1" style="overflow-x: scroll;overflow-y: scroll; height: 250px;white-space:nowrap; width:100%">


                            <table id="example" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Invoice No.</th>
                  <th>Date</th>
                  <th>Status</th>
                  
                  
                  <th>View Invoice</th>
                  <th>View Chalan</th>
                  <th>View Deposit Slip</th>
                  <th>Region.</th>
                  <th>Territory.</th>
                  <th>Distributor Name</th>
                  <th>Distributor ID</th>
                  
                  <th>Total </th>
                  <th>Vat</th>
                  <th>Sub Total</th>
                  <th>Deposite Value</th>
                  <th>Final Order Value</th>
                  <th>Remarks</th>
                   
                   
                 
                </tr>
                </thead>
                <tbody>
@forelse ($invoices as $element)
  <tr>
    <td>{{$element['invo_id']}}</td>
    <td>
      {{$element['date']}}
    </td>
<td>
  @if ($element['status'] == 0)
    <button  class=" btn btn-danger btn-xs" data-toggle="modal" data-target="#{{'pendingStatusChangeModal'. $element['id']}}">Pending</button>
  @elseif ($element['status'] == 1)
    <button  class=" btn btn-warning btn-xs" data-toggle="modal" data-target="#{{'pendingStatusChangeModal'. $element['id']}}">Approved By Sales</button>
  @elseif ($element['status'] == 2)
    <button  class=" btn btn-primary btn-xs">Approved By Accounts</button>
  @elseif ($element['status'] == 3)
    <button  class=" btn btn-success btn-xs">Delivered</button>
  @else
    <button  class="btn btn-danger btn-xs">Cancel</button>
  @endif 


   
</td>
<td>

    <a target="_blank" href="{{ route('sales.invoiceDetails',$element['id'])}}"> 
  <button class="btn btn-xs btn-primary"> Invoice Details</button>
    </a>

</td>

<td>
@if ($element['purchase']['id'] != null)
  <a target="_blank" href="{{ route('sales.chalanDetails',$element['purchase']['id'])}}"> 
    <button class="btn btn-xs btn-primary"> Chalan Details</button>
  </a>
@else
  <a target="_blank" href="{{ route('sales.chalanDetails',$element['purchase']['id'])}}"> 
    <button class="btn btn-xs btn-primary" disabled="disabled"> Chalan Details</button>
  </a>
@endif


</td>


        
                <td> 

@if ($element['bslip'])
<a target="_blank" href="{{ asset( 'storage/app/' . $element['bslip']) }}">
  <img width="30px" height="20px" src="{{ asset( 'storage/app/' . $element['bslip']) }}"> 
</a>
@else
  No Image File
@endif
        </td>


    <td>
      {{$element['region']['region']}}
    </td>
    <td>
      {{$element['territory']['territory']}}
    </td>

    <td>
      {{$element['distributor']['distributor']}}
    </td>
    <td>
      {{$element['distributor']['duid']}}
    </td>
    
    <td>
      {{$element['total']}}
    </td>
    <td>
      {{$element['vat_amount']}}
    </td>
    <td>
      {{$element['vat_amount'] + $element['total']}}
    </td>
    <td>
      {{$element['deposit']}}
    </td>
        <td>
     {{$element['purchase']['vat_amount'] + $element['purchase']['total']}}
</td>

<td>
  @if ($element['remarks'] != NULL)
    <p style="color: red; font-weight: bolder;">{{$element['remarks']}}</p>
  @elseif ($element['remarks1'] != NULL)
    <p style="color: red; font-weight: bolder;">{{$element['remarks1']}}</p>
  @else
    <p style="color: green; font-weight: bolder;">Not Applicable</p>
  @endif


</td>


  </tr>
@empty
  {{"NO data Found"}}
@endforelse



                
                
              
                </tbody>
               
              </table>
</div>





            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

      
        </section>
        <!-- /.Left col -->
        <!-- right col (We are only adding the ID to make the widgets sortable)-->
        
        <!-- right col -->
      </div>
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
 
  </div>
<!-- /.content-wrapper -->





<!-- content part================================ -->
@endsection