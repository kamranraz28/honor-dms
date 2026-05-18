@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Factory Stock"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Factory Stock
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> REPORTS</a></li>
        <li class="active"><a href="{{ route('admin.dateWiseInvoice') }}">Date Wise Invoice</a></li>
      </ol>
    </section>

 
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Search Date Wise Invoice</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('admin.dateWiseInvoice.store') }}" autocomplete="on" enctype="multipart/form-data">
<div class="box-body">
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
</div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
{{-- for for displaying success and errror message --}}
                <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="fdate" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker"  required="required" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker1"  required="required" autocomplete="off">
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
        </div>
      </div>
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Stock List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th colspan="15"> Date: {{$from_date}} to {{$to_date}}</th>
                </tr>
                <tr>
                  <th>Invoice No.</th>
                  <th>Date</th>
                  <th>View Invoice</th>
                  <th>View Chalan</th>
                  <th>Invoice Status</th>
                  <th>Distributor Name</th>
                  <th>Distributor ID</th>
                  <th>Bank Name</th>
                  <th>Deposit Method</th>
                  
                  <th>Total </th>
                  <th>Vat</th>
                  <th>Sub Total</th>
                  <th>Deposite Value</th>
                  <th>Final Order Amount</th>
                  <th>Balance</th>
                  
                 
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
      {{$element['bank']['bank']}}
    </td>

    <td>
      {{$element['deposit_method']}}
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
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        </section>
 
 
  </div>
<!-- content part================================ -->
@endsection