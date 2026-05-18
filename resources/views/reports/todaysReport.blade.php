@extends('layouts.master_admin')

@section('title')
  {{"E-Warranty Ststem :: Today's Order Report"}}
@endsection


@section('content')


<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">


<!-- Main row -->
<div class="row">
  <!-- Left col -->

  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
                <div class="box-header">
              <h3 class="box-title text-danger">Today's Order Report</h3>
            </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="row">
                            <div class="col-lg-6">

                                <form action="{{ route('admin.todaysOrderStore') }}"
                                      method="POST"
                                      style="max-width: 420px;">

                                    @csrf

                                    {{-- From Date --}}
                                    <div class="form-group">
                                        <label for="fdate" class="control-label">From Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="fdate"
                                                   placeholder="YYYY-MM-DD"
                                                   type="text"
                                                   class="form-control pull-right"
                                                   id="datepicker3"
                                                   autocomplete="off" required>
                                        </div>
                                    </div>

                                    {{-- To Date --}}
                                    <div class="form-group">
                                        <label for="todate" class="control-label">To Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="todate"
                                                   placeholder="YYYY-MM-DD"
                                                   type="text"
                                                   class="form-control pull-right"
                                                   id="datepicker4"
                                                   autocomplete="off" required>
                                        </div>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="admin-action-row">
                                        <button type="submit" class="action-btn action-sync">
                                            <span class="btn-icon">
                                                <i class="fa fa-cloud-download"></i>
                                            </span>
                                            <span class="btn-text">
                                                Show Report
                                            </span>
                                            <span class="action-chip">
                                                Submit
                                            </span>
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>
                        <br>
                        <br>
                        <br>

<table id="example" class="ui celled table" width="100%">


<thead>
    <tr>
        <th>Order Number</th>
        <th>LD Name</th>
        <th>ProductModel</th>
        <th>Quantity</th>
        <th>Price</th>
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
        <td>{{ $orderDetail['price'] ?? '-' }}</td>
        <td>
            @switch($orderDetail['status'])
                @case(5)
                    {{ 'Delivery Complete' }}
                    @break
                @case(3)
                    {{ 'Waiting to Delivery' }}
                    @break
                @case(2)
                    {{ 'Waiting to Add IMEI' }}
                    @break
                @case(1)
                    {{ 'Waiting for Accounts Approval' }}
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
