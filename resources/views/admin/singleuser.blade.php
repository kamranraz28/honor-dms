@extends('layouts.master_admin')

@section('title')
    {{"Sales Automation Process :: Add User"}}
@endsection


@section('content')

    <!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- bc part================================ -->
        @include('admin.bc.bc')
        <!-- bc part================================ -->
        </section>




        <!-- Main content -->
        <section class="content-header">

            <div class="row">

                <div class="box-body">
                    @if(count($errors))
                        <div class="alert alert-danger alert-dismissible">
                            <strong>Whoops!</strong> There were some problems with your input.
                            <br />
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(Session::has('success'))


                        <div class="alert alert-success alert-dismissible fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <strong>Success!</strong> {{Session::get('success')}}
                        </div>

                    @endif
                </div>


                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title">User List</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example" class="display" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                    <th>Action</th>
                                    <th>Map Distributor</th>
                                    <th>Map District</th>
                                    <th>Map Retailer</th>
                                    <th>Map SR</th>
                                    <th>Name</th>
                                    <th>Office ID</th>
                                    <th>Email</th>

                                    <th>Alternative Email</th>
                                    <th>Contact Name</th>
                                    <th>Contact No.</th>

                                    <th>Division</th>
                                    <th>District</th>
                                    <th>Upazila</th>


                                    <th>Level</th>
                                    <th>Password Status</th>
                                    <th>Status</th>
                                    <th>Return</th>

                                    <th>Photo</th>
                                    <th>NID Image</th>


                                </tr>

                            </thead>



                            <tbody>
                                @foreach ($users as $element)
                                    <tr>



                                        <td>

                                            @if ($element->level == 500)
                                                <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                    data-target="#{{'userUpdateModal' . $element->id}}"><i
                                                        class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                            @else
                                                <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                    data-target="#{{'userUpdateModal' . $element->id}}"><i
                                                        class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

                                                <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                    data-target="#{{'userDeleteModal' . $element->id}}"><i class="fa fa-trash-o"
                                                        aria-hidden="true"></i></button>
                                            @endif

                                        </td>


                                        <td>
                                            @if ($element->level == 10)

                                                <button class="btn btn-xs btn-warning" title="Retailer" data-toggle="modal"
                                                    data-target="#{{'tsoupazilaAddModal' . $element->id}}"><i class="fa fa-plus"
                                                        aria-hidden="true"></i></button>
                                                @if (count($element->tsoupazila) > 0)
                                                    <button class="btn btn-xs btn-info" title="Retailer" data-toggle="modal"
                                                        data-target="#{{'tsoupazilaInfoModal' . $element->id}}"><i class="fa fa-info"
                                                            aria-hidden="true"></i></button>
                                                @endif
                                            @else
                                                -
                                            @endif




                                        </td>




                                        <td>
                                            @if ($element->level == 300)

                                                <button class="btn btn-xs btn-warning" title="Retailer" data-toggle="modal"
                                                    data-target="#{{'middistrictAddModal' . $element->id}}"><i class="fa fa-plus"
                                                        aria-hidden="true"></i></button>
                                                @if (count($element->middistrict) > 0)
                                                    <button class="btn btn-xs btn-info" title="Retailer" data-toggle="modal"
                                                        data-target="#{{'middistrictInfoModal' . $element->id}}"><i class="fa fa-info"
                                                            aria-hidden="true"></i></button>
                                                @endif
                                            @else
                                                -
                                            @endif



                                        </td>





                                        <td>
                                            @if ($element->level == 100)

                                                <button class="btn btn-xs btn-warning" title="Retailer" data-toggle="modal"
                                                    data-target="#{{'retailerAddModal' . $element->id}}"><i class="fa fa-plus"
                                                        aria-hidden="true"></i></button>

                                                @if (count($element->retailer) > 0)
                                                    <button class="btn btn-xs btn-info" title="Retailer" data-toggle="modal"
                                                        data-target="#{{'retailerInfoModal' . $element->id}}"><i class="fa fa-info"
                                                            aria-hidden="true"></i></button>
                                                @endif


                                            @else
                                                -
                                            @endif

                                        </td>


                                        <td>


                                            @if ($element->level == 100)

                                                <button class="btn btn-xs btn-warning" title="SR" data-toggle="modal"
                                                    data-target="#{{'srAddModal' . $element->id}}"><i class="fa fa-plus"
                                                        aria-hidden="true"></i></button>


                                                @if (count($element->sr) > 0)
                                                    <button class="btn btn-xs btn-info" title="SR" data-toggle="modal"
                                                        data-target="#{{'srInfoModal' . $element->id}}"><i class="fa fa-info"
                                                            aria-hidden="true"></i></button>
                                                @endif

                                            @else
                                                -
                                            @endif




                                        </td>






                                        <td>{{$element->firstname}} {{$element->lastname}}</td>


                                        <td>
                                            <button class="btn btn-xs btn-warning" data-toggle="modal"
                                                data-target="#{{'ratailerIdChangeModal' . $element->id}}">
                                                {{$element->officeid}}
                                            </button>
                                        </td>

                                        <td>

                                            @if ($element['email'])

                                                <button class="btn btn-xs btn-info" data-toggle="modal"
                                                    data-target="#{{'userEmailChangeModal' . $element['id']}}">
                                                    {{$element['email']}}
                                                </button>
                                            @else
                                                <button class="btn btn-xs btn-info" data-toggle="modal"
                                                    data-target="#{{'userEmailChangeModal' . $element['id']}}">
                                                    No Email
                                                </button>
                                            @endif
                                        </td>




                                        <td>

                                            @if ($element['alemail'])

                                                <button class="btn btn-xs btn-info" data-toggle="modal"
                                                    data-target="#{{'userAlternativeEmailChangeModal' . $element['id']}}">
                                                    {{$element['alemail']}}
                                                </button>
                                            @else
                                                <button class="btn btn-xs btn-info" data-toggle="modal"
                                                    data-target="#{{'userAlternativeEmailChangeModal' . $element['id']}}">
                                                    No Alternative Email
                                                </button>
                                            @endif
                                        </td>


                                        <td>{{$element->contact_name}}</td>
                                        <td>{{$element->contact}}</td>


                                        <td>
                                            @if ($element['division']['name'])
                                                {{$element['division']['name']}}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if ($element['district']['name'])
                                                {{$element['district']['name']}}
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td>
                                            @if ($element['upazila']['name'])
                                                {{$element['upazila']['name']}}
                                            @else
                                                -
                                            @endif
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
                                                <button class="btn btn-info btn-xs">TSO/TO</button>
                                            @elseif($element->level == 5)
                                                <button class="btn btn-info btn-xs">Service</button>
                                            @elseif($element->level == 6)
                                                <button class="btn btn-info btn-xs">Warehouse</button>
                                            @elseif($element->level == 7)
                                                <button class="btn btn-info btn-xs">Accounts</button>
                                            @else
                                                <button class="btn btn-warning btn-xs">SR</button>
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
                                            @if ($element->dist_return == true)
                                                <button class="btn btn-xs btn-primary" data-toggle="modal"
                                                    data-target="#{{'userReturnStatusModal' . $element->id}}">Enable</button>
                                            @else
                                                <button class="btn btn-xs btn-danger" data-toggle="modal"
                                                    data-target="#{{'userReturnStatusModal' . $element->id}}">Disable</button>
                                            @endif

                                        </td>


                                        <td>

                                            @if ($element->photo)
                                                <a target="_blank" href="{{ asset('storage/app/d/nokia/' . $element->photo) }}">
                                                    <img width="30px" height="20px"
                                                        src="{{ asset('storage/app/' . $element->photo) }}">
                                                </a>
                                            @else
                                                No Image File
                                            @endif
                                        </td>

                                        <td>

                                            @if ($element['nidimage'])
                                                <a target="_blank"
                                                    href="{{ asset('storage/app/d/nokia/' . $element['nidimage']) }}">
                                                    <img width="30px" height="20px"
                                                        src="{{ asset('storage/app/d/nokia/' . $element['nidimage']) }}">
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



    <!--custom tsoupazilaAddModal modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'tsoupazilaAddModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> Mapping Distributor </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->




                        <form action="{{route('admin.user.addUpazila')}}" method="post">
                            <!-- <h3 class="text-info">Do You Want To Add Retailer ?</h3> -->
                            <br>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" value="{{ $element->id }}">


                            <div class="form-group {{ $errors->has('upazila_id') ? 'has-error' : '' }}">
                                <label for="upazila">Distributor</label>
                                <select name="upazilas[]" id="upazila" class="form-control select3" multiple="multiple"
                                    style="width: 100%;" required="required">
                                    <option value="">Select Distributor</option>
                                    @foreach($distributors as $distributor)
                                        <option value="{{ $distributor['id'] }}">
                                            {{ $distributor['firstname'] . "-" . $distributor['officeid'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('upazila_id') }}</span>
                            </div>

                            <div class="form-group">
                                <button class="form-control btn btn-primary">Add</button>
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
    <!--custom tsoupazilaAddModal modal part================================ -->




    <!--custom tsoupazilaInfoModal modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'tsoupazilaInfoModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Do You Want To Delete Distributor From TSO ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->


                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Officeid</th>
                                    <th>Action</th>
                                </tr>


                            </thead>

                            <tbody>
                                @foreach ($element->tsoupazila as $tsoupazila)
                                    <tr>
                                        <td> {{$tsoupazila['name']}} </td>
                                        <td> {{$tsoupazila['bn_name']}} </td>
                                        <td>

                                            <a href="{{ route('admin.user.deleteUpazila', $tsoupazila['id']) }}"
                                                onclick="return confirm('Do you want to delete this data ?')"
                                                class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>

                                        </td>
                                    </tr>


                                @endforeach

                                <tr>

                                </tr>
                            </tbody>


                        </table>



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
    <!--custom tsoupazilaInfoModal modal part================================ -->










    <!--custom middistrictAddModal modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'middistrictAddModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Do You Want To Add Retailer ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->




                        <form action="{{route('admin.user.addDistrict')}}" method="post">
                            <!-- <h3 class="text-info">Do You Want To Add Retailer ?</h3> -->
                            <br>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" value="{{ $element->id }}">


                            <div class="form-group {{ $errors->has('district_id') ? 'has-error' : '' }}">
                                <label for="district">District</label>
                                <select name="districts[]" id="district" class="form-control select3" multiple="multiple"
                                    style="width: 100%;" required="required">
                                    <option value="">Select District</option>
                                    @foreach($districts as $district)
                                        <option value="{{ $district['id'] }}">{{ $district['name'] }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('district_id') }}</span>
                            </div>

                            <div class="form-group">
                                <button class="form-control btn btn-primary">Add</button>
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
    <!--custom middistrictAddModal modal part================================ -->




    <!--custom middistrictInfoModal modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'middistrictInfoModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Do You Want To Delete Retailer ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->


                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Bangla Name</th>
                                    <th>Action</th>
                                </tr>


                            </thead>

                            <tbody>
                                @foreach ($element->middistrict as $middistrict)
                                    <tr>
                                        <td> {{$middistrict['name']}} </td>
                                        <td> {{$middistrict['bn_name']}} </td>
                                        <td>

                                            <a href="{{ route('admin.user.deleteDistrict', $middistrict['id']) }}"
                                                onclick="return confirm('Do you want to delete this data ?')"
                                                class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>

                                        </td>
                                    </tr>


                                @endforeach

                                <tr>

                                </tr>
                            </tbody>


                        </table>



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
    <!--custom middistrictInfoModal modal part================================ -->













    <!--custom update modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'userAlternativeEmailChangeModal' . $element['id']}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element['alemail']}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->

                        <form action="{{route('admin.distributor.updateAlternativeEmail')}}" method="post" autocomplete="on"
                            enctype="multipart/form-data">
                            <h3 class="text-info">Do You Want To Add Or Change Alternative Email ?</h3>
                            <br>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="put">
                            <input type="hidden" name="id" value="{{ $element['id'] }}">






                            <div class="form-group {{ $errors->has('alemail') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label"> Alternative Email </label>

                                <div class="col-sm-10">
                                    <input type="email" id="alemail" name="alemail" class="form-control"
                                        placeholder="Enter Alternative Email" value="{{ old('alemail') }}" required="required">
                                    <span class="text-danger">{{ $errors->first('alemail') }}</span>
                                </div>

                            </div>
                            <br><br>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="form-control btn btn-warning">Update</button>
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



    <!--custom update modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'userEmailChangeModal' . $element['id']}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element['email']}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->

                        <form action="{{route('admin.distributor.updateEmail')}}" method="post" autocomplete="on"
                            enctype="multipart/form-data">
                            <h3 class="text-info">Do You Want To Add Or Change Email ?</h3>
                            <br>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="put">
                            <input type="hidden" name="id" value="{{ $element['id'] }}">






                            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label"> Email</label>

                                <div class="col-sm-10">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter  Email"
                                        value="{{ old('email') }}" required="required">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>

                            </div>

                            <br><br>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="form-control btn btn-warning">Update</button>
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







    <!--custom update modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'userUpdateModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element->firstname}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->

                        <form action="{{route('users.update', $element->id)}}" method="post" autocomplete="on"
                            enctype="multipart/form-data">
                            <h3 class="text-info">Do You Want To Update This Data ?</h3>
                            <br>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="put">
                            <input type="hidden" name="id" value="{{ $element->id }}">



                            <div class="col-md-12">
                                <div id="divisionArea" class="form-group {{ $errors->has('division_id') ? 'has-error' : '' }}">
                                    <label for="division">Division</label>
                                    <select name="division_id" id="division" class="form-control" style="width: 100%;"
                                        required="required">
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division['id'] }}" {{ $element['division']['id'] == $division['id'] ? ' selected="selected"' : '' }}>{{ $division['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('division_id') }}</span>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div id="districtArea" class="form-group {{ $errors->has('district_id') ? 'has-error' : '' }}">
                                    <label for="district">District</label>
                                    <select name="district_id" id="district" class="form-control" style="width: 100%;"
                                        required="required">
                                        <option value="">Select District</option>
                                        @foreach($districts as $district)
                                            <option value="{{ $district['id'] }}" {{ $element['district']['id'] == $district['id'] ? ' selected="selected"' : '' }}>{{ $district['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('district_id') }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div id="upazilaArea" class="form-group {{ $errors->has('upazila_id') ? 'has-error' : '' }}">
                                    <label for="upazila">Upazila</label>
                                    <select name="upazila_id" id="upazila" class="form-control" style="width: 100%;"
                                        required="required">
                                        <option value="">Select Upazila</option>
                                        @foreach($upazilas as $upazila)
                                            <option value="{{ $upazila['id'] }}" {{ $element['upazila']['id'] == $upazila['id'] ? ' selected="selected"' : '' }}>{{ $upazila['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('upazila_id') }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>

                                    <div>
                                        <input type="text" id="address" name="address" class="form-control"
                                            placeholder="Enter address" value="{{$element->address}}">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Category</label>

                                    <div>
                                        <input type="text" id="dis_cat" name="dis_cat" class="form-control"
                                            placeholder="Enter category" value="{{$element->dis_cat}}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
                                    <label for="firstname">Firstname:</label>
                                    <input type="text" id="firstname" name="firstname" class="form-control"
                                        placeholder="Enter Firstname" value="{{$element->firstname}}">
                                    <span class="text-danger">{{ $errors->first('firstname') }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{-- $errors->has('lastname') ? 'has-error' : '' --}}">
                                    <label for="lastname">Lastname:</label>
                                    <input type="text" id="lastname" name="lastname" class="form-control"
                                        placeholder="Enter Lastname" value="{{-- $element->lastname --}}">
                                    <span class="text-danger">{{-- $errors->first('lastname') --}}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('officeid') ? 'has-error' : '' }}">
                                    <label for="officeid">Office ID:</label>
                                    <input type="text" id="officeid" name="officeid" class="form-control"
                                        placeholder="Enter Office ID" value="{{$element->officeid}}" disabled="disabled">
                                    <span class="text-danger">{{ $errors->first('officeid') }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('contact_name') ? 'has-error' : '' }}">
                                    <label for="contact_name">Contact Name:</label>
                                    <input type="text" id="contact_name" name="contact_name" class="form-control"
                                        placeholder="Enter Contact" value="{{$element->contact_name}}">
                                    <span class="text-danger">{{ $errors->first('contact_name') }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('contact') ? 'has-error' : '' }}">
                                    <label for="contact">Contact:</label>
                                    <input type="text" id="contact" name="contact" class="form-control"
                                        placeholder="Enter Contact" value="{{$element->contact}}">
                                    <span class="text-danger">{{ $errors->first('contact') }}</span>
                                </div>
                            </div>

                            <div class="col-md-12">

                                <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                    <label for="image">Photo:</label>
                                    <input type="file" name="image">
                                    <span class="text-danger">{{ $errors->first('image') }}</span>
                                </div>

                            </div>

                            <div class="form-group">
                                <button class="form-control btn btn-success">Update</button>
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



    <!--custom delete modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'userDeleteModal' . $element->id}}" tabindex="-1" role="dialog"
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




                        <form action="{{route('users.destroy', $element->id)}}" method="post">
                            <h3 class="text-info">Do You Want To Delete This Data ?</h3>
                            <br>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="delete">

                            <div class="form-group">
                                <button class="form-control btn btn-danger">Delete</button>
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
    <!--custom delete modal part================================ -->





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






    <!--custom retailerAddModal modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'retailerAddModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Do You Want To Add Retailer ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->




                        <form action="{{route('admin.user.addRetailer')}}" method="post">
                            <!-- <h3 class="text-info">Do You Want To Add Retailer ?</h3> -->
                            <br>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" value="{{ $element->id }}">


                            <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                                <label for="user" class="control-label">Retailer</label>
                                <div class="">
                                    <select name="retailers[]" id="user_id" class="form-control select2" multiple="multiple"
                                        style="width: 100%;">

                                        @foreach ($retailers as $key => $element)
                                            <option value="{{$element['id']}}">{{$element['firstname']}} - {{$element['officeid']}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="form-control btn btn-primary">Add</button>
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
    <!--custom retailerAddModal modal part================================ -->


    <!--custom delete modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'srAddModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Do You Want To Add SR ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->




                        <form action="{{route('admin.user.addSr')}}" method="post">
                            <!-- <h3 class="text-info">Do You Want To Add Retailer ?</h3> -->
                            <br>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" value="{{ $element->id }}">


                            <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                                <label for="user" class="control-label">SR</label>
                                <div class="">
                                    <select name="srs[]" id="user_id" class="form-control select2" multiple="multiple"
                                        style="width: 100%;">

                                        @foreach ($srs as $key => $element)
                                            <option value="{{$element['id']}}">{{$element['firstname']}} - {{$element['officeid']}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="form-control btn btn-primary">Add</button>
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
    <!--custom delete modal part================================ -->





    <!--custom retailerInfoModal modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'retailerInfoModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Do You Want To Delete Retailer ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->


                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Retailer Code</th>
                                    <th>Action</th>
                                </tr>


                            </thead>

                            <tbody>
                                @foreach ($element->retailer as $retailer)
                                    <tr>
                                        <td> {{$retailer['name']}} </td>
                                        <td> {{$retailer['officeid']}} </td>
                                        <td>

                                            <a href="{{ route('admin.user.deleteRetailer', $retailer['id']) }}"
                                                onclick="return confirm('Do you want to delete this data ?')"
                                                class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>




                                        </td>
                                    </tr>


                                @endforeach

                                <tr>

                                </tr>
                            </tbody>


                        </table>



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
    <!--custom retailerInfoModal modal part================================ -->



    <!--custom delete modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'srInfoModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Do You Want To Delete SR ?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->


                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>SR Code</th>
                                    <th>Action</th>
                                </tr>


                            </thead>

                            <tbody>
                                @foreach ($element->sr as $sr)
                                    <tr>
                                        <td> {{$sr['name']}} </td>
                                        <td> {{$sr['officeid']}} </td>
                                        <td>

                                            <a href="{{ route('admin.user.deleteSr', $sr['id']) }}"
                                                onclick="return confirm('Do you want to delete this data ?')"
                                                class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>




                                        </td>
                                    </tr>


                                @endforeach

                                <tr>

                                </tr>
                            </tbody>


                        </table>



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
    <!--custom delete modal part================================ -->




    <!--custom hospitaldetailStatusModal modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'userReturnStatusModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element->email}}</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->


                        @if ($element->dist_return == true)
                            <form action="{{route('admin.user.disable', $element->id)}}" method="post">
                                <h3 class="text-info">Do You Want To Disable This User ?</h3>
                                <br>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="{{ $element->id }}">
                                <!-- <input name="_method" type="hidden" value="delete"> -->

                                <div class="form-group">
                                    <button class="form-control btn btn-danger">Disable</button>
                                </div>

                            </form>
                        @else
                            <form action="{{route('admin.user.enable', $element->id)}}" method="post">
                                <h3 class="text-info">Do You Want To Enable This User ?</h3>
                                <br>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="{{ $element->id }}">
                                <!-- <input name="_method" type="hidden" value="delete"> -->

                                <div class="form-group">
                                    <button class="form-control btn btn-primary">Enable</button>
                                </div>

                            </form>
                        @endif



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
    <!--custom hospitaldetailStatusModal modal part================================ -->


    <!--custom update modal part================================ -->


    @forelse ($users as $key => $element)
        <!-- Modal -->
        <div class="modal fade" id="{{'ratailerIdChangeModal' . $element->id}}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$element->firstname}} - {{$element->officeid}} </h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- body part -->

                        <form action="{{ route('admin.user.updateOfficeid') }}" method="post" autocomplete="on"
                            enctype="multipart/form-data">
                            <h3 class="text-info">Do You Want To Update Office ID ?</h3>
                            <br>

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input name="_method" type="hidden" value="put">
                            <input type="hidden" name="id" value="{{ $element->id }}">






                            <div class="form-group {{ $errors->has('officeid') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Office ID</label>

                                <div class="col-sm-10">
                                    <input type="text" id="officeid" name="officeid" class="form-control"
                                        placeholder="Enter Office ID" value="{{ old('officeid') }}" required="required">
                                    <span class="text-danger">{{ $errors->first('officeid') }}</span>
                                </div>

                            </div>

                            <br><br>


                            <div class="form-group {{ $errors->has('confirm_officeid') ? 'has-error' : '' }}">
                                <label class="col-sm-2 control-label">Confirm Office ID</label>

                                <div class="col-sm-10">
                                    <input type="text" id="confirm_officeid" name="confirm_officeid" class="form-control"
                                        placeholder="Enter Confirm Office ID" value="{{ old('confirm_officeid') }}"
                                        required="required">
                                    <span class="text-danger">{{ $errors->first('confirm_officeid') }}</span>
                                </div>

                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="form-control btn btn-warning">Update Office ID</button>
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
    <!-- // jquery area ========= -->
    <script type="text/javascript">

        $('#level').on('change', function (e) {
            var level = e.target.value;
            //console.log(level);
            if (level == 300 || level == 100) {
                $('#retailerArea').css({ 'display': 'block' });
            } else {
                //$('#user_id').empty();
                $('#retailerArea').css({ 'display': 'none' });
                //$('#user_id').val('');
            }
        });




        $('#division').on('change', function (e) {
            var division_id = e.target.value;
            //console.log(division_id);
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
