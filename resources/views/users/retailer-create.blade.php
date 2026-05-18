@extends('layouts.master_admin')

@section('title', 'Sales Automation Process :: Create Retailer')

@section('content')
    <div class="content-wrapper">
        <section class="content">

            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 col-md-12">

                    {{-- ================= PAGE HEADING ================= --}}
                    <div class="box box-warning">
                        <div class="box-header with-border clearfix">
                            <h3 class="box-title text-success pull-left">
                                <i class="fa fa-user-plus"></i> Create New Retailer
                            </h3>

                            <div class="pull-right">
                                <a href="{{ route('admin.retailer') }}" class="btn btn-default btn-sm">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- ================= FORM ================= --}}
                    <div class="box box-primary">
                        <form class="form-horizontal" method="POST" action="{{ route('admin.retailer.store') }}"
                            enctype="multipart/form-data" autocomplete="on">

                            @csrf

                            <div class="box-body" style="padding:30px 50px;">

                                {{-- ================= ALERTS ================= --}}
                                @if(count($errors))
                                    <div class="alert alert-danger alert-dismissible">
                                        <button class="close" data-dismiss="alert">&times;</button>
                                        <strong>Whoops!</strong> There were some problems with your input.
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if(Session::has('success'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button class="close" data-dismiss="alert">&times;</button>
                                        <strong>Success!</strong> {{ Session::get('success') }}
                                    </div>
                                @endif

                                {{-- ================= LOCATION ================= --}}
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Division</label>
                                    <div class="col-sm-7">
                                        <select name="division_id" id="division" class="form-control" required>
                                            <option value="">Select Division</option>
                                            @foreach($divisions as $division)
                                                <option value="{{ $division['id'] }}">{{ $division['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">District</label>
                                    <div class="col-sm-7">
                                        <select name="district_id" id="district" class="form-control" required>
                                            <option value="">Select District</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Upazila</label>
                                    <div class="col-sm-7">
                                        <select name="upazila_id" id="upazila" class="form-control" required>
                                            <option value="">Select Upazila</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Address</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="address" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Market Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="market_name" class="form-control">
                                    </div>
                                </div>

                                <hr>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Full Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="firstname" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Contact Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="contact_name" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Contact No</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="contact" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Email</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="email" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Office ID</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="officeid" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Password</label>
                                    <div class="col-sm-7">
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Confirm Password</label>
                                    <div class="col-sm-7">
                                        <input type="password" name="confirm_password" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Store Type</label>
                                    <div class="col-sm-7">
                                        <select name="store_type" id="store_type" class="form-control" required="required">
                                            <option value="">Select Type</option>
                                            <option value="BP Retail">BP Retail</option>
                                            <option value="RSA Retail">RSA Retail</option>
                                            <option value="SIS Retail">SIS Retail</option>
                                            <option value="GRT Retail">GRT Retail</option>
                                            <option value="BS Retail">BS Retail</option>
                                            <option value="SP Retail">SP Retail</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Photo</label>
                                    <div class="col-sm-7">
                                        <input type="file" name="image" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer" style="padding:20px 50px;">
                                <button type="submit" class="btn btn-success pull-right">
                                    <i class="fa fa-save"></i> Submit
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </section>
    </div>

    {{-- ================= SCRIPTS (UNCHANGED) ================= --}}
    <script type="text/javascript">
        $('#level').on('change', function (e) {
            var level = e.target.value;

            if (level == 100) {
                $('#retailerArea').show();
                $('#hddistrictArea, #hdupazilaArea').hide();
                $('#user_id1').prop("disabled", false);
                $('#district1, #upazila1').prop("disabled", true);
            } else if (level == 300) {
                $('#hddistrictArea').show();
                $('#retailerArea, #hdupazilaArea').hide();
                $('#district1').prop("disabled", false);
                $('#user_id1, #upazila1').prop("disabled", true);
            } else if (level == 10) {
                $('#hdupazilaArea').show();
                $('#retailerArea, #hddistrictArea').hide();
                $('#upazila1').prop("disabled", false);
                $('#user_id1, #district1').prop("disabled", true);
            } else {
                $('#retailerArea, #hddistrictArea, #hdupazilaArea').hide();
                $('#user_id1, #district1, #upazila1').prop("disabled", true);
            }
        });

        $('#division').on('change', function (e) {
            var division_id = e.target.value;
            var route = "{{ route('admin.districtSelectBoxOnDivisionWithAjax') }}/" + division_id;

            $.get(route, function (data) {
                $('#district').empty().append('<option value="">Select District</option>');
                $.each(data, function (i, d) {
                    $('#district').append('<option value="' + d.id + '">' + d.name + '</option>');
                });
            });
        });

        $('#district').on('change', function (e) {
            var district_id = e.target.value;
            var route = "{{ route('admin.upazilaSelectBoxOnDistrictWithAjax') }}/" + district_id;

            $.get(route, function (data) {
                $('#upazila').empty().append('<option value="">Select Upazila</option>');
                $.each(data, function (i, d) {
                    $('#upazila').append('<option value="' + d.id + '">' + d.name + '</option>');
                });
            });
        });
    </script>
@endsection
