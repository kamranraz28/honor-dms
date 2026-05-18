@extends('layouts.master_admin')

@section('title')
    {{"Sales Automation Process :: User List"}}
@endsection


@section('content')

    <!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        </section>

        <!-- Main content -->
        <section class="content-header">

            <div class="row">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">User List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example" class="ui celled table" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Level</th>
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Alternative Email</th>
                                    <th>Office ID</th>
                                    <th>Contact Name</th>
                                    <th>Contact No.</th>
                                    <th>Division</th>
                                    <th>District</th>
                                    <th>Upazila</th>
                                    <th>Address</th>
                                    <th>Password Status</th>
                                    <th>Status</th>
                                    <th>Photo</th>
                                    <th>NID Image</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $element)
                                    <tr>


                                        <td>
                                            <a href="{{ route('admin.singleuser', [$element->id]) }}" target="_blank"
                                                class="btn btn-xs btn-info">
                                                <i class="fa fa-info" aria-hidden="true" style="width: 10px"></i>
                                            </a>
                                        </td>



                                        <td>
                                            @if ($element->level == 500)
                                                <button class="btn btn-danger btn-xs">Admin</button>
                                            @elseif($element->level == 400)
                                                <button class="btn btn-info btn-xs">Top Management</button>
                                            @elseif($element->level == 300)
                                                <button class="btn btn-info btn-xs">Mid Management</button>
                                            @elseif($element->level == 200)
                                                <button class="btn btn-info btn-xs">Retailer</button>
                                            @elseif($element->level == 100)
                                                <button class="btn btn-info btn-xs">Distributor</button>
                                            @elseif($element->level == 10)
                                                <button class="btn btn-info btn-xs">TSO/TSM</button>
                                            @elseif($element->level == 1000)
                                                <button class="btn btn-info btn-xs">Huawei</button>
                                            @elseif($element->level == 5)
                                                <button class="btn btn-info btn-xs">Servie Center</button>
                                            @elseif($element->level == 6)
                                                <button class="btn btn-info btn-xs">Warehouse</button>
                                            @elseif($element->level == 7)
                                                <button class="btn btn-info btn-xs">Accounts</button>
                                            @else
                                                <button class="btn btn-warning btn-xs">SR</button>
                                            @endif

                                        </td>


                                        <td>@if (!empty($element['dis_cat']))
                                            {{ $element['dis_cat'] }}
                                        @else
                                                -
                                            @endif
                                        </td>


                                        <td>{{$element->firstname}} {{$element->lastname}}</td>
                                        <td>{{$element->email}}</td>
                                        <td>

                                            @if ($element['alemail'])
                                                {{$element['alemail']}}
                                            @else
                                                No Alternative Email
                                            @endif
                                        </td>

                                        <td>{{$element->officeid}}</td>
                                        <td>{{$element->contact_name}}</td>
                                        <td>{{$element->contact}}</td>


                                        <td>
                                            @if (!empty($element['division']['name']))
                                                {{ $element['division']['name'] }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if (!empty($element['district']['name']))
                                                {{ $element['district']['name'] }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if (!empty($element['upazila']['name']))
                                                {{ $element['upazila']['name'] }}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>@if (!empty($element['address']))
                                            {{ $element['address'] }}
                                        @else
                                                -
                                            @endif
                                        </td>





                                        <td>
                                            <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                data-target="#{{'userPasswordChangeModal' . $element->id}}">
                                                Change Password
                                            </button>
                                        </td>

                                        <td>
                                            @if ($element->level == 500)
                                                <button class="btn btn-xs btn-primary" disabled>Active</button>
                                            @else
                                                @if ($element->active == true)
                                                    <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                        data-target="#retailerStatusModal{{ $element->id }}">
                                                        Active
                                                    </button>
                                                @else
                                                    <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                        data-target="#retailerStatusModal{{ $element->id }}">
                                                        Inactive
                                                    </button>
                                                @endif
                                            @endif

                                        </td>


                                        <td>

                                            @if ($element->photo)
                                                <a target="_blank" href="{{ asset('storage/app/d/nokia/' . $element->photo) }}">
                                                    View Image
                                                </a>
                                            @else
                                                No Image File
                                            @endif
                                        </td>

                                        <td>

                                            @if ($element['nidimage'])
                                                <a target="_blank"
                                                    href="{{ asset('storage/app/d/nokia/' . $element['nidimage']) }}">
                                                    View Image
                                                </a>
                                            @else
                                                No Image File
                                            @endif
                                        </td>



                                    </tr>
                                @endforeach



                            </tbody>

                        </table>

                        <table>

                            <tbody>
                                <tr>
                                    <td colspan="9">
                                        {{ $users->links() }}
                                    </td>
                                </tr>
                            </tbody>

                        </table>



                    </div>
                    <div class="clear"></div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </section>


    </div>
    <!-- /.content-wrapper -->
    <!-- content part================================ -->





    <!--custom update modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'userPasswordChangeModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element->firstname}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->

                        <form action="{{route('admin.user.updatePassword')}}" method="post" autocomplete="on"
                            enctype="multipart/form-data">
                            <h3 class="text-info">Do You Want To Update Password ?</h3>
                            <br>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="put">
                            <input type="hidden" name="id" value="{{ $element->id }}">






                            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Password</label>

                                <div class="col-sm-10">
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Enter Password" value="{{ old('password') }}" required="required">
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                </div>

                            </div>

                            <br><br>


                            <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Confirm Password</label>

                                <div class="col-sm-10">
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                        placeholder="Enter Confirm Password" value="{{ old('confirm_password') }}"
                                        required="required">
                                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                </div>

                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="form-control btn btn-warning">Update Password</button>
                                </div>
                            </div>







                        </form>

                        <!-- body part -->
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    @empty
        {{'Data not found'}}
    @endforelse
    <!--custom update modal part================================ -->


    @forelse ($users as $key => $element)
        <div class="modal fade" id="retailerStatusModal{{ $element->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">{{ $element->email }}</h5>
                        <button class="close" type="button" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        @if ($element->active == true)
                            <form action="{{ route('admin.user.changeActiveStatus') }}" method="POST">
                                @csrf
                                <p class="text-info">Do You Want To Inactive This User ?</p>

                                <input type="hidden" name="id" value="{{ $element->id }}">
                                <input type="hidden" name="active" value="{{ $element->active }}">

                                <div class="form-group">
                                    <button class="form-control btn btn-danger">Inactive</button>
                                </div>
                            </form>
                        @else
                            <form action="{{ route('admin.user.changeActiveStatus') }}" method="POST">
                                @csrf
                                <p class="text-info">Do You Want To Active This User ?</p>

                                <input type="hidden" name="id" value="{{ $element->id }}">
                                <input type="hidden" name="active" value="{{ $element->active }}">

                                <div class="form-group">
                                    <button class="form-control btn btn-primary">Active</button>
                                </div>
                            </form>
                        @endif

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <p>Data not found</p>
    @endforelse



    <!-- // jquery area ========= -->
    <!-- // jquery area ========= -->
    <script type="text/javascript">

        $('#level').on('change', function (e) {
            var level = e.target.value;
            console.log(level);
            if (level == 100) {
                $('#retailerArea').css({ 'display': 'block' });
                $('#hddistrictArea').css({ 'display': 'none' });
                $('#hdupazilaArea').css({ 'display': 'none' });

                $('#user_id1').prop("disabled", false);
                $('#district1').prop("disabled", true);
                $('#upazila1').prop("disabled", true);


            } else if (level == 300) {
                $('#hddistrictArea').css({ 'display': 'block' });
                $('#retailerArea').css({ 'display': 'none' });
                $('#hdupazilaArea').css({ 'display': 'none' });

                $('#user_id1').prop("disabled", true);
                $('#district1').prop("disabled", false);
                $('#upazila1').prop("disabled", true);

            } else if (level == 10) {
                $('#hdupazilaArea').css({ 'display': 'block' });
                $('#hddistrictArea').css({ 'display': 'none' });
                $('#retailerArea').css({ 'display': 'none' });

                $('#user_id1').prop("disabled", true);
                $('#district1').prop("disabled", true);
                $('#upazila1').prop("disabled", false);

            } else {
                $('#retailerArea').css({ 'display': 'none' });
                $('#hddistrictArea').css({ 'display': 'none' });
                $('#hdupazilaArea').css({ 'display': 'none' });

                $('#user_id1').prop("disabled", true);
                $('#district1').prop("disabled", true);
                $('#upazila1').prop("disabled", true);

            }
        });




        $('#division').on('change', function (e) {
            var division_id = e.target.value;
            console.log(division_id);
            var route = "{{route('admin.districtSelectBoxOnDivisionWithAjax')}}/" + division_id;
            $.get(route, function (data) {
                //console.log(data);
                $('#district').empty();
                $('#district').append('<option value="' + '">Select District</option>');
                $.each(data, function (index, data) {
                    $('#district').append('<option value="' + data.id + '">' + data.name + '</option>');
                });
            });
        });

        $('#district').on('change', function (e) {
            var district_id = e.target.value;
            //console.log(district_id);
            var route = "{{route('admin.upazilaSelectBoxOnDistrictWithAjax')}}/" + district_id;
            $.get(route, function (data) {
                //console.log(data);
                $('#upazila').empty();

                $.each(data, function (index, data) {
                    $('#upazila').append('<option value="' + data.id + '">' + data.name + '</option>');
                });
            });
        });


    </script>

    <!-- // jquery area ========= -->


@endsection
