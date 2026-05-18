@extends('layouts.master_admin')

@section('title', 'Sales Automation Process :: User Management')

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <section class="col-lg-12">

                {{-- ================= PAGE HEADING ================= --}}
                <div class="box box-warning">
                    <div class="box-header with-border clearfix">

                        <h3 class="box-title text-danger pull-left">
                            <i class="fa fa-users"></i> Retailer Management
                            <small class="text-muted" style="display:block;font-size:12px;margin-top:4px;">
                                Manage system retailers & access
                            </small>
                        </h3>

                        {{-- ACTION BUTTONS --}}
                        <div class="pull-right">

                            <a href="{{ route('admin.retailer.download') }}" class="action-btn action-sync">
                                <span class="btn-icon">
                                    <i class="fa fa-download"></i>
                                </span>
                                <span class="btn-text">
                                    Download All
                                </span>
                                <span class="action-chip">
                                    Submit
                                </span>
                            </a>

                            <a href="{{ route('admin.retailer.create') }}" class="action-btn action-sync"
                                style="margin-left:8px;">
                                <span class="btn-icon">
                                    <i class="fa fa-plus"></i>
                                </span>
                                <span class="btn-text">
                                    Add Retailer
                                </span>
                                <span class="action-chip">
                                    New
                                </span>
                            </a>

                        </div>

                    </div>
                </div>



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


                {{-- ================= USER TABLE ================= --}}
                <div class="box box-warning">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example" class="ui celled table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Retailer Name</th>
                                        <th>Contact Name</th>
                                        <th>Market Name</th>
                                        <th>Email</th>
                                        <th>Retailer ID</th>
                                        <th>Retailer Type</th>
                                        <th>Contact No.</th>
                                        <th>Division</th>
                                        <th>District</th>
                                        <th>Upazila</th>
                                        <th>Level</th>
                                        <th>Password Status</th>
                                        <th>Status</th>
                                        <th>Photo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @php
                                        $levels = [
                                            500 => ['Admin', 'danger'],
                                            400 => ['Top Management', 'info'],
                                            300 => ['Mid Management', 'info'],
                                            200 => ['Retailer', 'info'],
                                            100 => ['Distributor', 'info'],
                                            10 => ['TSO/TSM', 'info'],
                                            1000 => ['Huawei', 'info'],
                                            5 => ['Service Center', 'info'],
                                            6 => ['Warehouse', 'info'],
                                            7 => ['Accounts', 'info'],
                                        ];
                                    @endphp

                                    @foreach ($users as $user)
                                        @php
                                            [$label, $color] = $levels[$user->level] ?? ['SR', 'warning'];
                                        @endphp

                                        <tr>
                                            <td>{{ $users->firstItem() + $loop->index }}</td>

                                            <td>
                                                <strong>{{ $user->firstname }}</strong><br>
                                            </td>

                                            <td>{{ $user->contact_name ?? '-' }}</td>

                                            <td>{{ $user->market_name ?? '-' }}</td>

                                            <td>{{ $user->email ?? '-' }}</td>

                                            <td>
                                                <strong>{{ $user->officeid }}</strong><br>
                                            </td>

                                            <td>{{ $user->store_type ?? '-' }}</td>

                                            <td>{{ $user->contact ?? '-' }}</td>

                                            <td>{{ optional($user->division)->name ?? '-' }}</td>
                                            <td>{{ optional($user->district)->name ?? '-' }}</td>
                                            <td>{{ optional($user->upazila)->name ?? '-' }}</td>

                                            <td>
                                                <button class="btn btn-xs btn-{{ $color }}">{{ $label }}</button>
                                            </td>

                                            <td>
                                                <button class="btn btn-xs btn-danger open-password-modal"
                                                    data-id="{{ $user->id }}" data-name="{{ $user->firstname }}"
                                                    data-toggle="modal" data-target="#passwordModal">
                                                    Change
                                                </button>
                                            </td>

                                            <td>
                                                @if ($user->active)
                                                    <button class="btn btn-xs btn-success open-status-modal"
                                                        data-id="{{ $user->id }}" data-active="1" data-toggle="modal"
                                                        data-target="#statusModal">
                                                        Active
                                                    </button>
                                                @else
                                                    <button class="btn btn-xs btn-danger open-status-modal"
                                                        data-id="{{ $user->id }}" data-active="0" data-toggle="modal"
                                                        data-target="#statusModal">
                                                        Inactive
                                                    </button>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($user->photo)
                                                    <a target="_blank" href="{{ asset('storage/app/d/nokia/' . $user->photo) }}">
                                                        View
                                                    </a>
                                                @else
                                                    No Image File
                                                @endif
                                            </td>

                                            <td>
                                                <button class="btn btn-xs btn-primary open-update-modal"
                                                    data-id="{{ $user->id }}" data-firstname="{{ $user->firstname }}"
                                                    data-contact_name="{{ $user->contact_name }}"
                                                    data-market_name="{{ $user->market_name }}"
                                                    data-store_type="{{ $user->store_type }}"
                                                    data-officeid="{{ $user->officeid }}" data-contact="{{ $user->contact }}"
                                                    data-address="{{ $user->address }}" data-division="{{ $user->division_id }}"
                                                    data-district="{{ $user->district_id }}"
                                                    data-upazila="{{ $user->upazila_id }}" data-toggle="modal"
                                                    data-target="#retailerUpdateModal">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </button>

                                                <button class="btn btn-xs btn-danger open-delete-modal"
                                                    data-id="{{ $user->id }}" data-name="{{ $user->firstname }}"
                                                    data-toggle="modal" data-target="#retailerDeleteModal">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </td>



                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            {{ $users->links() }}
                        </div>
                    </div>
                </div>

            </section>
        </section>
    </div>

    {{-- PASSWORD MODAL --}}
    <div class="modal fade" id="passwordModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pwd_user_name"></h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('admin.user.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="pwd_user_id">

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>

                        <button class="btn btn-warning btn-block">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- STATUS MODAL --}}
    <div class="modal fade" id="statusModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('admin.user.changeActiveStatus') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="status_user_id">
                    <input type="hidden" name="active" id="status_active">

                    <div class="modal-header">
                        <h4>Status Change</h4>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Able MODAL --}}
    <div class="modal fade" id="ableModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="{{ route('admin.user.changeAbleStatus') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="able_user_id">
                    <input type="hidden" name="status" id="able_user_status">

                    <div class="modal-header">
                        <h4>Status Change</h4>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="retailerUpdateModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <form id="updateForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h4 class="modal-title" id="updateModalTitle"></h4>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id" id="update_id">
                        <input type="hidden" name="division_id" id="update_division">
                        <input type="hidden" name="district_id" id="update_district">
                        <input type="hidden" name="upazila_id" id="update_upazila">

                        <div class="form-group">
                            <label>Retailer Name</label>
                            <input type="text" name="firstname" id="update_firstname" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" name="contact_name" id="update_contact_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Market Name</label>
                            <input type="text" name="market_name" id="update_market_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Store Type</label>
                            <select name="store_type" id="update_store_type" class="form-control">
                                <option value="">Select Type</option>
                                <option>BP Retail</option>
                                <option>RSA Retail</option>
                                <option>SIS Retail</option>
                                <option>GRT Retail</option>
                                <option>BS Retail</option>
                                <option>SP Retail</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Retailer ID</label>
                            <input type="text" name="officeid" id="update_officeid" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Contact</label>
                            <input type="text" name="contact" id="update_contact" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" id="update_address" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Photo</label>
                            <input type="file" name="image">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-success">Update</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <div class="modal fade" id="retailerDeleteModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h4 class="modal-title" id="deleteModalTitle"></h4>
                        <button class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger">Delete</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    <script>
        $('.open-password-modal').click(function () {
            $('#pwd_user_id').val($(this).data('id'));
            $('#pwd_user_name').text($(this).data('name'));
        });

        $('.open-status-modal').click(function () {
            $('#status_user_id').val($(this).data('id'));
            $('#status_active').val($(this).data('active'));
        });
    </script>

    <script>
        const userUpdateUrl = "{{ route('users.update', ':id') }}";
        const userDeleteUrl = "{{ route('users.destroy', ':id') }}";
    </script>


    <script>
        $('.open-update-modal').click(function () {
            let id = $(this).data('id');

            $('#updateForm').attr(
                'action',
                userUpdateUrl.replace(':id', id)
            );

            $('#updateModalTitle').text($(this).data('firstname'));

            $('#update_id').val(id);
            $('#update_firstname').val($(this).data('firstname'));
            $('#update_contact_name').val($(this).data('contact_name'));
            $('#update_market_name').val($(this).data('market_name'));
            $('#update_store_type').val($(this).data('store_type'));
            $('#update_officeid').val($(this).data('officeid'));
            $('#update_contact').val($(this).data('contact'));
            $('#update_address').val($(this).data('address'));

            $('#update_division').val($(this).data('division'));
            $('#update_district').val($(this).data('district'));
            $('#update_upazila').val($(this).data('upazila'));
        });

        $('.open-delete-modal').click(function () {
            let id = $(this).data('id');

            $('#deleteForm').attr(
                'action',
                userDeleteUrl.replace(':id', id)
            );

            $('#deleteModalTitle').text(
                'Delete ' + $(this).data('name') + '?'
            );
        });
    </script>


@endsection
