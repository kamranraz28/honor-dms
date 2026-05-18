@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Distributor Invoice History Details"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Distributor Invoice History Details
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> REPORTS</a></li>
        <li class="active"><a href="{{ route('admin.distributorInvoiceHistory') }}">Distributor Invoice History</a></li>
      </ol>
    </section>

<section class="content-header">
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Invoice List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<div class="table-responsive1" style="overflow-x: scroll;overflow-y: scroll; height: 250px;white-space:nowrap; width:100%">
              <table id="example" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Invoice No.</th>
                  
                  <th>View Invoice</th>
                  <th>View Chalan</th>
                  <th>Deposit Slip</th>
                  <th>Status</th>
                  <th>Distributor Name</th>
                  <th>Distributor ID</th>
                  <th>Contact No.</th>
                  <th>Date</th>
                  <th>Total </th>
                  <th>Vat</th>
                  <th>Sub Total</th>
                  <th>Deposite Value</th>
                  <th>Final Order Amount</th>
                  <th>Balance</th>
                  
                 
                </tr>
                </thead>
                <tbody>
@forelse ($invoices as $key=>$element)
  <tr>
    <td>{{$key+1}}</td>
    <td>{{$element['invo_id']}}</td>
<td>
    <a target="_blank" href="{{ route('admin.invoiceDetails',$element['id'])}}"> 
  <button class="btn btn-xs btn-primary"> Invoice Details</button>
    </a>

</td>

<td>
@if ($element['purchase']['id'] != null)
  <a target="_blank" href="{{ route('admin.chalanDetails',$element['purchase']['id'])}}"> 
    <button class="btn btn-xs btn-primary"> Chalan Details</button>
  </a>
@else
  <a target="_blank" href="{{ route('admin.chalanDetails',$element['purchase']['id'])}}"> 
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
  @if ($element['status'] == 0)
    <button  class=" btn btn-danger btn-xs">Pending</button>
  @elseif ($element['status'] == 1)
    <button  class=" btn btn-warning btn-xs">Approved By Sales</button>
  @elseif ($element['status'] == 2)
    <button  class=" btn btn-primary btn-xs">Approved By Accounts</button>
  @elseif ($element['status'] == 3)
    <button  class=" btn btn-success btn-xs">Delevired</button>
  @else
    <button  class=" btn btn-danger btn-xs">Cancel</button>
  @endif 


   
</td>
    <td>
      {{$element['distributor']['distributor']}}
    </td>
    <td>
      {{$element['distributor']['duid']}}
    </td>
    <td>
      {{$element['distributor']['contact']}}
    </td>
    <td>
      {{$element['date']}}
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
      {{ round($element['deposit'] - ($element['purchase']['vat_amount'] + $element['purchase']['total']) , 2)  }}
    </td>

  </tr>
@empty
  {{"NO data Found"}}
@endforelse



                
                
              
                </tbody>
               
              </table>
</div>
            </div>
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </section>
    
 
  </div>
<!-- content part================================ -->


<!--custom delete modal part================================ -->



<!--custom delete modal part================================ -->

@endsection