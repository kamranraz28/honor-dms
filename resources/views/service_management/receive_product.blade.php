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
                        <h3 class="box-title text-danger">Pending Products</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <!-- form start -->
                        <form action="{{route('serviceManagement.receiveReportStore')}}" method="post">
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
                    value="{{ Session::get('from_date') ?? '' }}"
                    type="text" class="form-control" id="datepicker1"
                    autocomplete="off">
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
                    value="{{ Session::get('to_date') ?? '' }}"
                    type="text" class="form-control" id="datepicker2"
                    autocomplete="off">
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
                @if(Session::has('success'))


                    <div class="alert alert-success alert-dismissible fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success!</strong> {{Session::get('success')}}
                    </div>

                @endif

                <!-- Recent Invoice -->
                <div class="box box-warning">
                    <div class="box-header">
<a href="{{ route('serviceManagement.receiveProductExcel', ['export' => 'excel']) }}" class="btn btn-success">
    Download all in Excel
</a>

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
                                    <th>Action</th>
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
                                        <td>
                                            <button type="button" class="btn btn-md btn-info" data-toggle="modal"
                                                data-target="#myModal_{{$receive['id']}}">Receive</button>
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
                                        



                                        <div id="myModal_{{$receive['id']}}" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close"
                                                            data-dismiss="modal">&times;</button>
                                                        <h2 class="modal-title" style="text-align: center; color: blue">
                                                            Receive Product</h2>
                                                    </div>
                                                    <form method="post"
                                                        action="{{route('serviceManagement.receiveConfirm', $receive['id'])}}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Category</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['category'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Brand</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['brand'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Product Name</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['name'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Model</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['model'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Customer Name</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['customername'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Customer Number</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['number'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Service Type</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['service'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Problem</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['problem'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>IMEI-1</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['imei1'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>IMEI-2</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['imei2'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Distributor ID</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['userid'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Distributor Name</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['username'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Send Date</label>
                                                                        <input type="text" class="form-control"
                                                                            value="{{ $receive['send'] }}" readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Receive Date</label>
                                                                        <input type="date" class="form-control"
                                                                            name="receive_date" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Remarks</label>
                                                                        <textarea class="form-control"
                                                                            name="remarks"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <button type="button" class="btn btn-info btn-block"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-block">Confirm</button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </div>




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
