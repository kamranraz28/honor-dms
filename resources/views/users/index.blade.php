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
                            <i class="fa fa-users"></i> User Management
                            <small class="text-muted" style="display:block;font-size:12px;margin-top:4px;">
                                Manage system users, roles & access
                            </small>
                        </h3>

                        {{-- ACTION BUTTONS --}}
                        <div class="pull-right">

                            <a href="{{ route('admin.user.download') }}" class="action-btn action-sync">
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

                            <a href="{{ route('users.create') }}" class="action-btn action-sync"
                                style="margin-left:8px;">
                                <span class="btn-icon">
                                    <i class="fa fa-plus"></i>
                                </span>
                                <span class="btn-text">
                                    Add User
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

                {{-- ================= USER SUMMARY SECTION ================= --}}
                @php
                    $levelNames = [
                        100 => 'Distributor',
                        10 => 'TSO / TSM',
                        5 => 'Service Center',
                        6 => 'Warehouse',
                        7 => 'Accounts',
                        300 => 'Mid Management',
                        400 => 'Top Management',
                        1000 => 'Huawei',
                    ];
                @endphp

                <div class="row">
                    @foreach ($summary as $level => $row)

                        @if (!isset($levelNames[$level]))
                            @continue
                        @endif

                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h4 class="box-title">
                                        {{ $levelNames[$level] }}
                                    </h4>
                                </div>

                                <div class="box-body">
                                    <p>
                                        <strong>Total:</strong>
                                        <span class="badge bg-blue">{{ $row->total }}</span>
                                    </p>

                                    <p class="text-success">
                                        <strong>Active:</strong>
                                        <span class="badge bg-green">{{ $row->active }}</span>
                                    </p>

                                    <p class="text-danger">
                                        <strong>Inactive:</strong>
                                        <span class="badge bg-red">{{ $row->inactive }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </div>


                {{-- ================= USER TABLE ================= --}}
                <div class="box box-warning">
                    <div class="box-body">
                        <div class="table-responsive">
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
                                        <th>Password</th>
                                        <th>Status</th>
                                        <th>Return</th>
                                        <th>Photo</th>
                                        <th>NID</th>
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
                                            <td>
                                                <a href="{{ route('admin.singleuser', $user->id) }}" target="_blank"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fa fa-info"></i>
                                                </a>
                                            </td>

                                            <td>
                                                <button class="btn btn-xs btn-{{ $color }}">{{ $label }}</button>
                                            </td>

                                            <td>
                                                {{ $user->dis_cat ?? '-' }}
                                            </td>

                                            <td>
                                                <strong>{{ $user->firstname }}</strong><br>
                                            </td>

                                            <td>{{ $user->email ?? '-' }}</td>

                                            <td>{{ $user->alemail ?? '-' }}</td>

                                            <td>
                                                <strong>{{ $user->officeid }}</strong><br>
                                            </td>

                                            <td>{{ $user->contact_name ?? '-' }}</td>

                                            <td>{{ $user->contact ?? '-' }}</td>

                                            <td>{{ optional($user->division)->name ?? '-' }}</td>
                                            <td>{{ optional($user->district)->name ?? '-' }}</td>
                                            <td>{{ optional($user->upazila)->name ?? '-' }}</td>
                                            <td>{{ $user->address ?? '-' }}</td>
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
                                                @if ($user->status)
                                                    <button class="btn btn-xs btn-success open-able-modal" data-id="{{ $user->id }}"
                                                        data-active="0" data-toggle="modal" data-target="#ableModal">
                                                        Enable
                                                    </button>
                                                @else
                                                    <button class="btn btn-xs btn-danger open-able-modal" data-id="{{ $user->id }}"
                                                        data-active="1" data-toggle="modal" data-target="#ableModal">
                                                        Disable
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
                                                @if ($user->nidimage)
                                                    <a target="_blank" href="{{ asset('storage/app/d/nokia/' . $user->nidimage) }}">
                                                        View
                                                    </a>
                                                @else
                                                    No Image File
                                                @endif
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

    <script>
        $('.open-password-modal').click(function () {
            $('#pwd_user_id').val($(this).data('id'));
            $('#pwd_user_name').text($(this).data('name'));
        });

        $('.open-status-modal').click(function () {
            $('#status_user_id').val($(this).data('id'));
            $('#status_active').val($(this).data('active'));
        });

        $('.open-able-modal').click(function () {
            $('#able_user_id').val($(this).data('id'));
            $('#able_user_status').val($(this).data('status'));
        });
    </script>
@endsection
