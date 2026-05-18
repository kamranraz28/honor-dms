@extends('layouts.master_accounts')

@section('title')
  {{"DMS :: Order List"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Order List
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('accounts.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> SALES</a></li>
        <li class="active"><a href="{{ route('accounts.orderList') }}">Order List</a></li>
      </ol>
    </section>

  
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Order List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
{{-- for for displaying success and errror message --}}
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

{{-- for for displaying success and errror message --}}

<div class="table-responsive1" style="overflow-x: scroll;overflow-y: scroll; height: 250px;white-space:nowrap; width:100%">


                            <table id="example" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Invoice No.</th>
                  <th>Date</th>
                  <th>Status</th>

                  <th>Chalan Status</th>


                  
                  
                  <th>View Invoice</th>
                  <th>View Chalan</th>
                  <th>Chalan Invoice</th>
                  <th>View Deposit Slip</th>

                  <th>Bank Name</th>
                  <th>Deposit Method</th>

                  <th>Region.</th>
                  <th>Territory.</th>
                  <th>Distributor Name</th>
                  <th>Distributor ID</th>
                  
                  <th>Total </th>
                  <th>Vat</th>
                  <th>Sub Total</th>
                  <th>Deposite Amount</th>
                  <th>Final Order Amount</th>
                  <th>Sales Remarks</th>

                  <th>Accounts Remarks</th>
                  
                   
                   
                 
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
    <button  class=" btn btn-danger btn-xs">Pending</button>
  @elseif ($element['status'] == 1)
    <button  class=" btn btn-warning btn-xs"  data-toggle="modal" data-target="#{{'pendingStatusChangeModal'. $element['id']}}">Approved By Sales</button>
  @elseif ($element['status'] == 2)
    <button  class=" btn btn-primary btn-xs" data-toggle="modal" data-target="#{{'pendingStatusChangeModal'. $element['id']}}">Approved By Accounts</button>
  @elseif ($element['status'] == 3)
    <button  class=" btn btn-success btn-xs">Delevired</button>
  @else
    <button  class=" btn btn-danger btn-xs">Cancel</button>
  @endif 


   
</td>

<td>
  @if ($element['purchase']['status'] == 2)
    <button  class=" btn btn-danger btn-xs" data-toggle="modal" data-target="#{{'inputChalanModal'. $element['id']}}">Accept Chalan</button>
  @elseif($element['purchase']['status'] == 1)
    <button disabled="disabled"  class=" btn btn-default btn-xs">Accept Chalan</button>
  @else
    <button disabled="disabled"  class=" btn btn-default btn-xs">Accept Chalan</button>
  @endif
</td>



<td>

    <a target="_blank" href="{{ route('accounts.invoiceDetails',$element['id'])}}"> 
  <button class="btn btn-xs btn-primary"> Invoice Details</button>
    </a>

</td>

<td>
@if ($element['purchase']['id'] != null)
  <a target="_blank" href="{{ route('accounts.chalanDetails',$element['purchase']['id'])}}"> 
    <button class="btn btn-xs btn-primary"> Chalan Details</button>
  </a>
@else
  <a target="_blank" href="{{ route('accounts.chalanDetails',$element['purchase']['id'])}}"> 
    <button class="btn btn-xs btn-primary" disabled="disabled"> Chalan Details</button>
  </a>
@endif


</td>
<td>
@if ($element['purchase']['id'] != null)
  <a target="_blank" href="{{ route('accounts.chalanDetailsinvoice',$element['purchase']['id'])}}"> 
    <button class="btn btn-xs btn-primary"> Chalan Invoice</button>
  </a>
@else
  <a target="_blank" href="{{ route('accounts.chalanDetailsinvoice',$element['purchase']['id'])}}"> 
    <button class="btn btn-xs btn-primary" disabled="disabled"> Chalan Invoice</button>
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

@if ($element['bank']['bank'] != NULL)
{{$element['bank']['bank']}}

@else
  <p style="color: green; font-weight: bolder;">-</p>
@endif
        </td>
  <td> 

@if ($element['deposit_method'] != NULL)
  {{$element['deposit_method']}}

@else
  <p style="color: green; font-weight: bolder;">-</p>
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
 {{$element['remarks']}}
@elseif ($element['remarks1'] != NULL)
  {{$element['remarks1']}}
@else
  <p style="color: green; font-weight: bolder;">-</p>
@endif


</td>

    <td>

@if ($element['remarks2'] != NULL)
  {{$element['remarks2']}}
@else
  <p style="color: green; font-weight: bolder;">-</p>
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
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </section>


 
  </div>
<!-- content part================================ -->

<!--custom delete modal part================================ -->


@forelse ($invoices as $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'pendingStatusChangeModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['invo_id']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->



@if ($element['status'] == 1)
  <form action="{{route('accounts.orderList.pendingStatusChange')}}" method="post">
    <h3 class="text-info">Do You Want To Approved By Accounts This Data ?</h3>
     <br>
      <input type="hidden" name="_token" value="{{ csrf_token() }}">





      <div class="form-group {{ $errors->has('bankname') ? 'has-error' : '' }}">
        <div class="col-sm-121">
          <label class="control-label">Bank Name</label>
          <select name="bank_name" class="form-control">
            <option value="">Select Bank Name</option>
@foreach ($banks as $bank)
  <option value="{{$bank['id']}}">{{$bank['bank']}} </option>
@endforeach
          </select>
          <span class="text-danger">{{ $errors->first('bankname') }}</span>
        </div>
      </div>


      <div class="form-group {{ $errors->has('bankname') ? 'has-error' : '' }}">
        <div class="col-sm-121">
          <label class="control-label">Deposit Method</label>
          <select name="deposit_method" class="form-control">
            <option value="">Select Method</option>
            <option value="Cheque">Cheque</option>
            <option value="Cash">Cash</option>
            <option value="Others">Others</option>
          </select>
          <span class="text-danger">{{ $errors->first('bankname') }}</span>
        </div>
      </div>



      <div class="form-group {{ $errors->has('remarks2') ? 'has-error' : '' }}">
        <div class="col-sm-121">
          <label class="control-label">Remarks</label>
          <input type="text" id="remarks2" name="remarks2" class="form-control" placeholder="Enter Remarks" value="{{ old('remarks2') }}">
          <span class="text-danger">{{ $errors->first('remarks2') }}</span>
        </div>
      </div>



      <div class="form-group">
        <input name="id" type="hidden" value="{{$element['id']}}">
        <input name="status" type="hidden" value="{{$element['status']}}">
        <button class="form-control btn btn-primary">Approved By Accounts</button>
      </div>
  </form>

  <!-- <hr>
  <form action="{{route('accounts.orderList.pendingStatusChange')}}" method="post">
    <h3 class="text-info">Do You Want To Cancel This Data ?</h3>
     <br>
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="form-group">
        <input name="id" type="hidden" value="{{$element['id']}}">
        <input name="status" type="hidden" value="4">
        <button class="form-control btn btn-danger">Cancel</button>
      </div>
  </form> -->
@elseif ($element['status'] == 2)
  
  <form action="{{route('accounts.orderList.pendingStatusChange')}}" method="post">
      <h3 class="text-info">Do You Want To Approved By Sales This Data ?</h3>
      <br>
      <input type="hidden" name="_token" value="{{ csrf_token() }}">

      <div class="form-group">
        <input name="id" type="hidden" value="{{$element['id']}}">
        <input name="status" type="hidden" value="{{$element['status']}}">
        <button class="form-control btn btn-info">Approved By Sales</button>
      </div>

  </form>

  <!-- <hr>
  <form action="{{route('accounts.orderList.pendingStatusChange')}}" method="post">
    <h3 class="text-info">Do You Want To Cancel This Data ?</h3>
     <br>
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <div class="form-group">
        <input name="id" type="hidden" value="{{$element['id']}}">
        <input name="status" type="hidden" value="4">
        <button class="form-control btn btn-danger">Cancel</button>
      </div>
  </form> -->
@else
  <form action="{{route('accounts.orderList.pendingStatusChange')}}" method="post">
    <h3 class="text-info">Do You Want To Pending This Data ?</h3>
    <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="form-group">
      <input name="id" type="hidden" value="{{$element['id']}}">
      <input name="status" type="hidden" value="{{$element['status']}}">
      <button class="form-control btn btn-danger">Pending</button>
    </div>

  </form>

  <hr>
  <form action="{{route('accounts.orderList.pendingStatusChange')}}" method="post">
    <h3 class="text-info">Do You Want To Approved This Data ?</h3>
     <br>
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <!-- <input name="_method" type="hidden" value="post"> -->
      <div class="form-group">
        <input name="id" type="hidden" value="{{$element['id']}}">
        <input name="status" type="hidden" value="{{$element['status']}}">
        <button class="form-control btn btn-warning">Approved</button>
      </div>
  </form>
@endif
  
   

    

  

<!-- body part -->
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
@empty
  {{'Data not found'}}
@endforelse
<!--custom delete modal part================================ -->


<!--custom inputChalanModal modal part================================ -->

@foreach ($invoices as $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'inputChalanModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Order Invoice No: {{$element['invo_id']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->


<form action="{{route('accounts.orderList.orderListInputChalan')}}" method="post">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['purchase']['id'] }}">
  <input type="hidden" name="invoice_id" value="{{ $element['id']}}">




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

<script type="text/javascript">
  $(document).ready(function() {

    $('#datepic{{$element['id']}}').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
    });

  });
</script>

@endforeach
<!--custom inputChalanModal modal part================================ -->










@endsection