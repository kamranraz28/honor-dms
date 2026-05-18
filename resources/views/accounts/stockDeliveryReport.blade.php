@extends('layouts.master_accounts')

@section('title', 'Sales Automation Process :: Stock Delivery Report')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
    @include('accounts.bc.bc')
    <!-- bc part================================ -->

    <!-- Main content -->
    <section class="content">
        <section class="col-lg-12 connectedSortable">
            <!-- Recent Invoice -->

            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title text-danger">Stock Delivery Report</h3>
                </div>

                <div class="box-body">
                        <form action="{{route('accounts.stockDeliveryReportStore')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fdate" class="control-label">From Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="fdate" placeholder="YYYY-MM-DD"
                                                value="{{ @$retVal = (Session::get('fdate')) ? $ssdata['fdate'] : "" }}"
                                                type="text" class="form-control pull-right" id="datepicker3"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="todate" class="control-label">To Date</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="todate" placeholder="YYYY-MM-DD"
                                                value="{{ @$retVal = (Session::get('todate')) ? $ssdata['todate'] : "" }}"
                                                type="text" class="form-control pull-right" id="datepicker4"
                                                autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>



                <div class="box-header">
                    <table id="example" class="ui celled table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Product Name</th>
                                <th>Color</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($stockDeliveryReport['productModel'] as $key => $productModel)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $stockDeliveryReport['productDetailsWithCounts'][$productModel]['name'] }}</td>
                                <td>{{ $stockDeliveryReport['productDetailsWithCounts'][$productModel]['color'] }}</td>
                                <td>{{ $stockDeliveryReport['productDetailsWithCounts'][$productModel]['count'] ?? 0 }}</td>
                            </tr>
                        @endforeach



                        </tbody>
                    </table>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </section>
    </section>

</div>
@php
  Session::forget(['stockDeliveryReport','fdate', 'todate']);
@endphp
@endsection