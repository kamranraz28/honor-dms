@extends('layouts.master_admin')

@section('title')
    {{ 'DMS :: Order Panel' }}
@endsection

@section('content')

<style>
    /* 🎨 Classy Global & Typography */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5; /* Lighter, professional grey background */
    }

    /* 📦 Card Container */
    .new-box {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1); /* Slightly more pronounced shadow for elegance */
        margin-bottom: 25px;
        border: 1px solid #e0e4eb; /* Light border */
    }

    .card-header {
        border-bottom: 1px solid #dcdfe3; /* Slightly darker border */
        padding: 20px 30px;
        background-color: #ffffff;
        border-radius: 12px 12px 0 0;
    }

    .orader {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50; /* Deep blue-grey for professionalism */
    }

    .card-body {
        padding: 30px;
    }

    /* 📝 Forms & Inputs */
    .form-control {
        border-radius: 6px;
        border: 1px solid #c9d2db;
        box-shadow: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-control:focus {
        border-color: #5d9cec; /* Professional blue accent focus */
        box-shadow: 0 0 0 0.2rem rgba(93, 156, 236, 0.2);
    }

    /* 🏷️ Buttons */
    .btn {
        border-radius: 6px;
        font-weight: 500;
        padding: 9px 18px;
        transition: all 0.2s ease-in-out;
        border: none;
        text-transform: capitalize;
    }

    .btn:hover {
        opacity: 0.95;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-primary {
        background: #5d9cec; /* Deep professional blue */
        color: #fff;
    }

    .btn-warning {
        background: #ffc107;
        color: #343a40;
    }

    .btn-danger {
        background: #e74c3c;
        color: #fff;
    }

    .btn-info {
        background: #3498db; /* A distinct blue for 'Change Status' */
        color: #fff;
    }

    /* 📊 Table Styling */
    .table {
        background: #ffffff;
        border-collapse: separate;
        margin-bottom: 0;
    }

    th, td {
        padding: 18px 25px; /* Increased padding */
        text-align: left;
        border-bottom: 1px solid #e5e8ec;
    }

    th {
        background: #f8f9fa; /* Very light header background */
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
    }

    .same-color {
        background: #ffffff;
    }

    .different-color {
        background: #fbfbfb; /* Almost white stripe */
    }

    .table tr:last-child td {
        border-bottom: none;
    }

    /* 🛠️ Action Column Styling (NEW) */
    .btn-action-group {
        display: flex;
        flex-direction: column;
        gap: 8px; /* Added space between buttons */
    }

    .btn-action-group .btn {
        width: 100%; /* Make buttons full width inside the group */
    }

    /* 💬 Alerts */
    .alert {
        border-radius: 8px;
        font-weight: 500;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* 🖼️ Modals */
    .modal-content {
        border-radius: 10px;
        padding: 25px;
    }

    /* 📱 Responsive Adjustments */
    @media (min-width: 992px) {
        /* Fix the search layout on larger screens */
        .search-form-row > div {
            padding-right: 15px; /* Standard gutter */
        }
        .order-search-form {
            display: flex;
            align-items: flex-end; /* Align input and button bottoms */
            gap: 10px;
        }
    }

    @media (max-width: 768px) {
        .content-wrapper {
            padding: 10px;
        }
        .card-body {
            padding: 15px;
        }
        th, td {
            padding: 12px 15px;
        }
        .btn-action-group {
             flex-direction: row; /* Stack buttons horizontally on mobile */
             flex-wrap: wrap;
        }
        .btn-action-group .btn {
            width: auto; /* Allow buttons to size naturally on mobile */
        }
        .order-search-form {
            flex-direction: column;
        }
    }
</style>


<div class="content-wrapper">
    <br>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">{{ $message }}</div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-error">{{ $message }}</div>
                @endif

                <div class="card new-box">
                    <div class="card-header">
                        <h1 class="orader">Order List</h1>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4 search-form-row">

                            <div class="col-md-6 mb-3 mb-md-0">
                                <form action="{{route('admin.orderSearch')}}" method="post" class="order-search-form">
                                    @csrf
                                    <div class="form-group mb-0 flex-grow-1">
                                        <label for="order" class="mb-1">Search By Order:</label>
                                        <input type="text" name="order" class="form-control" placeholder="Enter Order Number to Search">
                                    </div>
                                    <div style="margin-top: 25px;">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                            </div>

                            <div class="col-md-6">
                                <form id="myForm" action="{{ route('admin.orderList') }}" method="GET">
                                    <div class="form-group mb-0">
                                        <label for="dropdown" class="mb-1">Filter By Status:</label>
                                        <select id="dropdown" class="form-control" name="search" onchange="document.getElementById('myForm').submit()">
                                            <option value="1" {{ 1 == $queryarray ? 'selected' : '' }}>Waiting to Add IMEI</option>
                                            <option value="2" {{ 2 == $queryarray ? 'selected' : '' }}>Processing</option>
                                            <option value="3" {{ 3 == $queryarray ? 'selected' : '' }}>Waiting to delivery</option>
                                            <option value="5" {{ 5 == $queryarray ? 'selected' : '' }}>Delivery Completed</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Info</th>
                                        <th>LD Address</th>
                                        <th>Product</th>
                                        <th>Approved QTY (Pcs)</th>
                                        <th>Uploaded QTY (Pcs)</th>
                                        <th>Price (Price*QTY)</th>
                                        <th>IMEI Status</th>
                                        <th>Delivery Info</th>
                                        <th style="min-width: 180px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $prevOrderNumber = null;
                                        $rowClass = 'different-color';
                                    @endphp
                                    @foreach ($ordersposting->sortByDesc('order.id') as $item)
                                        @foreach ($item->orderspostingDetails as $sumitem)
                                            @php
                                                $rowClass = ($item->order->id == $prevOrderNumber) ? $rowClass : ($rowClass == 'different-color' ? 'same-color' : 'different-color');
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td>
                                                    <b>Order No: {{$item->order->id}}<br>
                                                    Invoice No: {{ $item->id }}{{ date('dmY', strtotime($item->updated_at)) }}</b><br>
                                                    {{ $item->Order->usersd->firstname }}<br>
                                                    {{ $item->Order->usersd->officeid }}<br>
                                                    {{ $item->Order->usersd->contact }}
                                                </td>
                                                <td>{{ $item->Order->usersd->address ?? '-' }}</td>
                                                <td>{{ $sumitem->Product->model }}</td>
                                                <td>{{ $sumitem->quantity }}</td>
                                                <td>{{ count($sumitem->imeilist) }}</td>
                                                <td>{{ number_format($sumitem->price * $sumitem->quantity, 2) }}</td>
                                                <td>
                                                    @if(count($sumitem->imeilist) == $sumitem->quantity)
                                                        <span class="badge badge-success" style="background-color: #34a853; color: white; padding: 5px 10px; border-radius: 4px;">Delivered</span>
                                                    @else
                                                        <span class="badge badge-warning" style="background-color: #fbbc05; color: black; padding: 5px 10px; border-radius: 4px;">Pending</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->delivery_info ?? '-' }}</td>
                                                <td>
                                                    @if ($item->order->id != $prevOrderNumber)
                                                        <div class="btn-action-group">
                                                            <a class="btn btn-warning btn-sm" href="{{ route('admin.orderEdit', $item->id) }}">Review</a>

                                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#changeModal_{{ $item->id }}">
                                                                Change Status
                                                            </button>

                                                            <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal_{{ $item->id }}">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php $prevOrderNumber = $item->order->id; @endphp
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{ $ordersposting->appends(['search' => $queryarray])->links() }}
            </div>
        </div>
    </section>
</div>

@foreach ($ordersposting->unique('order.id') as $item)
    <div id="changeModal_{{ $item->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Status for Order #{{ $item->order->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.orderChangeStatus') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id }}">

                    <div class="modal-body">
                        <p>To change this order type <strong>"change this order status"</strong>:</p>
                        <input type="text" name="change_text" class="form-control mb-3 change-text-{{ $item->id }}" placeholder='Type "change this order status"' required>

                        <div class="form-group">
                            <select name="status" class="form-control" required>
                                <option value="" disabled selected>Select New Status</option>
                                <option value="1">Waiting to Add IMEI</option>
                                <option value="2">Processing</option>
                                <option value="3">Waiting to delivery</option>
                                <option value="5">Delivery Completed</option>
                            </select>
                        </div>
                        <br>

                        <div class="form-check" style="margin-top: 5px;">
                            <input class="form-check-input confirm-checkbox-change-{{ $item->id }}" type="checkbox" required>
                            <label class="form-check-label">
                                Changing this order status will update its status in the system
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info change-btn-{{ $item->id }}" disabled>Change Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal_{{ $item->id }}" class="modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Order #{{ $item->order->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form method="POST" action="{{ route('admin.orderDelete') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->id }}">

                    <div class="modal-body">
                        <p>To delete this order type <strong>"delete this order"</strong>:</p>
                        <input type="text" name="delete_text" class="form-control mb-3 delete-text-{{ $item->id }}" placeholder='Type "delete this order"' required>
                        <div class="form-check">
                            <input class="form-check-input confirm-checkbox-delete-{{ $item->id }}" type="checkbox" required>
                            <label class="form-check-label">
                                Deleting this order will completely remove it from the system
                            </label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger delete-btn-{{ $item->id }}" disabled>Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Logic for Delete Modals
    $('[id^="deleteModal_"]').each(function() {
        var modalId = $(this).attr('id').split('_')[1];
        var textInput = $('.delete-text-' + modalId);
        var checkbox = $('.confirm-checkbox-delete-' + modalId);
        var submitBtn = $('.delete-btn-' + modalId);

        function toggleDeleteButton() {
            var enable = textInput.val().trim() === 'delete this order' && checkbox.is(':checked');
            submitBtn.prop('disabled', !enable);
        }

        textInput.on('input', toggleDeleteButton);
        checkbox.on('change', toggleDeleteButton);

        $(this).on('show.bs.modal', function() {
            textInput.val('');
            checkbox.prop('checked', false);
            submitBtn.prop('disabled', true);
        });
    });

    // Logic for Change Status Modals
    $('[id^="changeModal_"]').each(function() {
        var modalId = $(this).attr('id').split('_')[1];
        var textInput = $('.change-text-' + modalId);
        var checkbox = $('.confirm-checkbox-change-' + modalId);
        var submitBtn = $('.change-btn-' + modalId);

        function toggleChangeButton() {
            var enable = textInput.val().trim() === 'change this order status' && checkbox.is(':checked');
            submitBtn.prop('disabled', !enable);
        }

        textInput.on('input', toggleChangeButton);
        checkbox.on('change', toggleChangeButton);

        $(this).on('show.bs.modal', function() {
            textInput.val('');
            checkbox.prop('checked', false);
            submitBtn.prop('disabled', true);
        });
    });
});
</script>

@endsection
