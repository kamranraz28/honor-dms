@extends('layouts.master_accounts')

@section('title', 'Sales Automation Process :: Distributor Delivery Report')

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
                    <h3 class="box-title text-danger">Distributor Delivery Report</h3>
                </div>

                <div class="box-header">
                <form action="{{route('accounts.distributorDeliveryReportStore')}}" method="post"
                        style="max-width: 400px; margin: 40px 0px;">
                        @csrf
                        <label class="control-label" for="distributor">Distributor :</label>
                        <select name="distributor_id" id="distributor" class="form-control select2" required="required">
                            <option value="All">All</option>
                            @foreach($distributors as $key=>$distributor)
                            <option value="{{ $distributor['id'] }}" {{ Session::get('distributor_id')==$distributor['id'] ? ' selected="selected"' : '' }}>
                                {{ $distributor['firstname'] }} - {{ $distributor['officeid'] }}</option>
                            @endforeach
                        </select>
                        <span class="text-danger">{{ $errors->first('distributor_id') }}</span>
                        <br>

                        <br>

                        <div class="row">
                                <div class="col-md-12">
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
                                <div class="col-md-12">
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
                        
                        <div class="form-group">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>
                    <div class="text-right col-lg-12">
                            <a target="_blank" class="btn btn-sm btn-success" href="{{ route('distributorDeliveryExcel') }}">Download Excel</a> <br>
                            
                        </div>
                </div>

                <div class="box-header">
                    <table id="example" class="ui celled table" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Order ID</th>
                            <th>LD Name</th>
                            <th>Product Model</th>
                            <th>Date</th>
                            <th>Qty</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (empty($orderDetails))
    -
@else
    @foreach($orderDetails as $index => $orderDetail)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $orderDetail['orderNumber'] ?? '-' }}</td>
            <td>{{ $orderDetail['customerName'] ?? '-' }}</td>
            <td>{{ $orderDetail['productModel'] ?? '-' }}</td>
            <td>{{ $orderDetail['deliveryDate'] ?? '-' }}</td>
            <td>{{ $orderDetail['quantity'] ?? '-' }}</td>
            <td>{{ $orderDetail['price'] ?? '-' }}</td>
        </tr>
    @endforeach
@endif
                    </tbody>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </section>
    </section>

</div>

@endsection