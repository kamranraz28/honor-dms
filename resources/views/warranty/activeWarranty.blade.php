@extends('layouts.master_admin')

@section('title')
    {{"Sales Automation Process :: activewarranty OR Company"}}
@endsection

@section('content')

<div class="content-wrapper">

    <!-- Page header -->
    <section class="content-header">
        <h3 class="box-title">Active Warranty</h3>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title text-primary">Search Warranty Information</h3>
                    </div>

                    <form class="form-horizontal" method="POST"
                        action="{{ route('admin.activewarranty.store') }}"
                        autocomplete="on" enctype="multipart/form-data">

                        @csrf

                        <div class="box-body">

                            {{-- ERROR BLOCK --}}
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Whoops!</strong> Please fix the following:
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- SUCCESS BLOCK --}}
                            @if(Session::has('success'))
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>Success!</strong> {{ Session::get('success') }}
                                </div>
                            @endif


                            <!-- Retailer Field -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Retailer</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                        <input type="text" id="retailer_search"
                                               class="form-control"
                                               placeholder="Type to search retailer..."
                                               list="retailer_list" autocomplete="off" required>

                                        <datalist id="retailer_list"></datalist>
                                        <input type="hidden" name="retailer_id" id="retailer_id" required>
                                    </div>
                                </div>
                            </div>


                            <!-- IMEI -->
                            <div class="form-group {{ $errors->has('sno') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">IMEI 1</label>
                                <div class="col-sm-10">
                                    <input type="text" id="sno" name="sno" class="form-control"
                                           placeholder="Enter Serial No" value="{{ old('sno') }}">
                                    <span class="help-block">{{ $errors->first('sno') }}</span>
                                </div>
                            </div>

                            <!-- Mobile -->
                            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Mobile No</label>
                                <div class="col-sm-10">
                                    <input type="text" id="mobile" name="mobile" class="form-control"
                                           placeholder="Enter Mobile No" value="{{ old('mobile') }}">
                                    <span class="help-block">{{ $errors->first('mobile') }}</span>
                                </div>
                            </div>

                            <!-- From Date -->
                            <div class="form-group">
                                <label class="col-sm-2 control-label">From Date</label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input name="fdate" type="text" id="datepicker3"
                                               class="form-control"
                                               placeholder="YYYY-MM-DD" autocomplete="off"
                                               required value="{{ old('fdate') }}">
                                    </div>
                                    <span class="help-block">{{ $errors->first('fdate') }}</span>
                                </div>
                            </div>

                        </div> <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right">
                                Submit
                            </button>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    </section>

</div>

@endsection
