@extends('layouts.master_accounts')

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


                            <table id="example" class="ui celled table" width="100%">


                                <thead>
                                    <tr>
                                        <th>Product Model</th>
                                        <th>Quantity</th>
                                        <th>Value (Qty * Price)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($todaysReport as $report)

                                        <tr>
                                            <td>{{ $report['model'] }}</td>
                                            <td>{{ $report['quantity'] }}</td>
                                            <td>{{ number_format($report['value'], 2) }}</td>
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

@endsection
