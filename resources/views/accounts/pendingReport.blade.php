@extends('layouts.master_accounts')

@section('title')
  {{"E-Warranty Ststem :: Pending Order Report"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('accounts.bc.bc')
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
              <h3 class="box-title text-danger">Pending Order Report (All)</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            <!-- form start -->



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


<table id="example" class="ui celled table" width="100%">


<thead>
    <tr>
        <th>Order Number</th>
        <th>LD Name</th>
        <th>ProductModel</th>
        <th>Quantity</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
    @foreach($todaysReport as $index => $orderDetail)
    <tr>
        <td>{{ $orderDetail['orderNumber'] ?? '-' }}</td>
        <td>{{ $orderDetail['customerName'] ?? '-' }}</td>
        <td>{{ $orderDetail['productModel'] ?? '-' }}</td>
        <td>{{ $orderDetail['quantity'] ?? '-' }}</td>
        <td>
            @switch($orderDetail['status'])
                @case(5)
                    {{ 'Delivery Complete' }}
                    @break
                @case(3)
                    {{ 'Waiting to Delivery' }}
                    @break
                @case(2)
                    {{ 'Processing' }}
                    @break
                @case(1)
                    {{ 'Submitted' }}
                    @break
                @default
                    {{ '-' }}
            @endswitch
        </td>


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




@php
  Session::forget(['all_report','distributor_id','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection
