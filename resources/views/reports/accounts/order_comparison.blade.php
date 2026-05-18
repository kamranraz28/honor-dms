@extends('layouts.master_accounts')

@section('title')
    {{ 'E-Warranty Ststem :: Dashboard' }}
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
            <div class="row new-box">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h1 class="orader">
                                    {{ __('Order Comparison') }}
                                </h1>
                            </div>
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <form id="myForm" action="{{ route('account.comparison') }}" method="GET"
                            style="max-width: 400px; margin: 40px 0px;">

                            <div class="form-group">
                                <label for="fdate" class="control-label">From Date:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="fdate" placeholder="YYYY-MM-DD" value="{{ $fdate ? $fdate : '' }}"
                                        type="text" class="form-control pull-right" id="datepicker1" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="todate" class="control-label">To Date:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="todate" placeholder="YYYY-MM-DD" value="{{ $todate ? $todate : '' }}"
                                        type="text" class="form-control pull-right" id="datepicker2" autocomplete="off">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Order Number</th>
                                            <th>Details Quantity</th>
                                            <th>Approved Quantity</th>
                                            <th>Difference</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($report as $row)
                                            <tr
                                                style="background-color: {{ $row['status'] == 'Mismatch' ? '#f8d7da' : '#d4edda' }}">
                                                <td>{{ $row['order_id'] }}</td>
                                                <td>{{ $row['details_qty'] }}</td>
                                                <td>{{ $row['posting_qty'] }}</td>
                                                <td>{{ $row['difference'] }}</td>
                                                <td>{{ $row['status'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection


