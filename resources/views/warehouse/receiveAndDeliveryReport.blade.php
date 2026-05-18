@extends('layouts.master_warehouse')

@section('title', 'Sales Automation Process :: Receive And Delivery Report')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
    @include('warehouse.bc.bc')
    <!-- bc part================================ -->

    <!-- Main content -->
    <section class="content">
        <section class="col-lg-12 connectedSortable">
            <!-- Recent Invoice -->

            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title text-danger">Receive and Delivery IMEI Report</h3>
                </div>

                <div class="box-header">
                    <div class="row">
                        <div class="col-lg-6">
                            <a target="_blank" class="btn btn-sm btn-success" href="{{ route('currentMonthReceiveReport') }}">Received IMEI (Current Month)</a>
                        </div>
                        <div class="col-lg-6 text-left">
                            <a target="_blank" class="btn btn-sm btn-success" href="{{ route('currentMonthDeliveryReport') }}">Delivered IMEI (Current Month)</a>
                        </div>
                    </div>
                </div>
                <br>

                <br>
                

                <div class="box-header">
                
                    <h4>Download Received and Delivered IMEI by selecting Date Range:</h4>
                
                    <div class="row">
                        <div class="col-lg-6">
                            <form action="{{route('allReceiveReport')}}" method="post" style="max-width: 400px; margin: 40px 0px;">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="fdate" class="control-label">From Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input name="fdate" placeholder="YYYY-MM-DD"
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
                                                    type="text" class="form-control pull-right" id="datepicker4"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Download Received IMEI</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-6">
                            <form action="{{route('allDeliveryReport')}}" method="post" style="max-width: 400px; margin: 40px 0px;">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="fdate" class="control-label">From Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input name="fdate" placeholder="YYYY-MM-DD"
                                                    type="text" class="form-control pull-right" id="datepicker5"
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
                                                    type="text" class="form-control pull-right" id="datepicker6"
                                                    autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Download Delivered IMEI</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </section>
    </section>

</div>

@endsection
