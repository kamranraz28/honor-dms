@extends('layouts.master_accounts')

@section('title', 'Sales Automation Process :: VAT Report')

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
                    <h3 class="box-title text-danger">VAT Report</h3>
                </div>
                <div class="box-header">

                    <div class="box-body">
                        <form action="{{route('accounts.vatReportStore')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
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
                                <div class="col-md-8">
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
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="box-footer">
                                            <button type="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="text-right col-lg-12">
        <a target="_blank" class="btn btn-sm btn-success" href="{{ route('accounts.vatDownload') }}">Download All</a> <br> <br>
        <a target="_blank" class="btn btn-sm btn-success" href="{{ route('accounts.currentMonthVatDownload') }}">Vat Report <?php $currentMonth = date('F\'y'); echo $currentMonth; ?> Download</a> <br>
    </div>
                    </div>


                    <table id="example" class="ui celled table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>CompanyCode</th>
                                <th>BranchCode</th>
                                <th>InvoiceNo</th>
                                <th>customerCode</th>
                                <th>IssueDate </th>
                                <th>IssueTime</th>
                                <th>DeliveryDate</th>
                                <th>DeliveryTime</th>
                                <th>Place</th>
                                <th>Car</th>
                                <th>Remarks</th>
                                <th>Challantype</th>
                                <th>DistChannel</th>
                                <th>ErrorMessage</th>
                                <th>ProductCode</th>
                                <th>ProductName</th>
                                <th>ProductModel</th>
                                <th>IssueQty</th>
                                <th>UnitTP</th>
                                <th>TotalWithoutSD</th>
                                <th>TotalSD</th>
                                <th>TotalWithoutVAT</th>
                                <th>TotalVAT</th>
                                <th>TotalWithVAT</th>
                                <th>NetAmount</th>
                                <th>Discount</th>
                                <th>ErrorMessage</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orderDetails as $orderDetail)
                            <tr>
                                <td>SXN01</td>
                                <td>0002</td>
                                <td>{{ $orderDetail['postingID'] . date('dmY', strtotime($orderDetail['invoiceDate']))
                                    ?? '-'}}</td>
                                <td>{{ $orderDetail['customerCode'] ?? '-' }}</td>
                                <td>{{ $orderDetail['issueDate'] ?? '-'}}</td>
                                <td>{{ $orderDetail['issueTime'] ?? '-'}}</td>
                                <td>{{ $orderDetail['deliveryDate'] ?? '-'}}</td>
                                <td>{{ $orderDetail['deliveryTime'] ?? '-'}}</td>
                                <td>{{ $orderDetail['address'] ?? '-' }}</td>
                                <td>{{ $orderDetail['deliveryInfo'] ?? '-' }}</td>
                                <td></td>
                                <td>{{ $orderDetail['chalan_type'] ?? '-' }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $orderDetail['productCode'] ?? '-' }}</td>
                                <td>{{ $orderDetail['productName'] ?? '-' }}</td>
                                <td>{{ $orderDetail['productModel'] ?? '-' }}</td>
                                <td>{{ $orderDetail['quantity'] ?? '-'}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
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
  Session::forget(['fdate','todate']);
@endphp

@endsection