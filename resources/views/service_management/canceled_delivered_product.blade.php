@extends('layouts.master_service_management')

@section('title')
{{"E-Warranty Ststem :: Receive Product"}}
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



            <!-- ==============one section area ================= -->


            <section class="col-lg-12 connectedSortable">
                <!-- Recent Invoice -->
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title text-danger">Canceled Delivered Products</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                    <form action="{{route('serviceManagement.cancelDeliverdReportStore')}}" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="from_date" class="control-label">From Date</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="fdate" placeholder="YYYY-MM-DD"
                                                value="{{ Session::get('from_date') ?? '' }}" type="text"
                                                class="form-control" id="datepicker1" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="to_date" class="control-label">To Date</label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input name="to_date" placeholder="YYYY-MM-DD"
                                                value="{{ Session::get('to_date') ?? '' }}" type="text"
                                                class="form-control" id="datepicker2" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label for="sno" class="control-label">Search by IMEI</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-search"></i> <!-- You can change the icon if needed -->
                </div>
                <input name="sno" placeholder="Enter IMEI"
                    value="{{ Session::get('sno') ?? '' }}"
                    type="text" class="form-control"
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

                            @php
                                $count = 1;
                            @endphp

                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Replace Id</th>
                                    <th>Memo</th>
                                    <th>Name</th>
                                    <th>Model</th>
                                    <th>Brand</th>
                                    <th>Problem</th>
                                    <th>Service</th>
                                    <th>Category</th>
                                    <th>Send Date</th>
                                    <th>IMEI-1</th>
                                    <th>IMEI-2</th>
                                    <th>Replace IMEI-1</th>
                                    <th>Replace IMEI-2</th>
                                    <th>Distributor Name</th>
                                    <th>Distributor ID</th>
                                    <th>Receive Date</th>
                                    <th>Void</th>
                                    <th>Delivery Date</th>
                                    <th>Remarks</th>
                                    


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receiveReport as $receive)

                                    <tr>
                                        <td>{{ $count++ }}</td>
                                        <td>{{ $receive['id'] }}</td>
                                        <td>
                                            <a href="{{ url('storage/app/d/nokia/' . $receive['memo']) }}" target="_blank">
        <button type="button" class="btn btn-info">
            View
        </button>
    </a>
                                        </td>
                                        <td>{{ $receive['name'] }}</td>
                                        <td>{{ $receive['model'] }}</td>
                                        <td>{{ $receive['brand'] }}</td>
                                        <td>{{ $receive['problem'] }}</td>
                                        <td>{{ $receive['service'] }}</td>
                                        <td>{{ $receive['category'] }}</td>
                                        <td>{{ $receive['send'] }}</td>
                                        <td>{{ $receive['imei1'] }}</td>
                                        <td>{{ $receive['imei2'] }}</td>
                                        <td>{{ $receive['replace1'] }}</td>
                                        <td>{{ $receive['replace2'] }}</td>
                                        <td>{{ $receive['username'] }}</td>
                                        <td>{{ $receive['userid'] }}</td>
                                        <td>{{ $receive['receive_date'] }}</td>
                                        <td>{{ $receive['void'] }}</td>
                                        <td>{{ $receive['delivery_date'] }}</td>
                                        <td>{{ $receive['remarks'] }}</td>
                                        











                                    </tr>
                                @endforeach

                            </tbody>


                        </table>
{{ $replaces->links() }}






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





<!-- content part================================ -->
@endsection
