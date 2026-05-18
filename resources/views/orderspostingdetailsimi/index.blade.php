@extends('layouts.master_warehouse')

@section('title')
    {{ 'DMS  :: Order Panel' }}
@endsection

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper order-panel-wrapper">
        <!-- bc part================================ -->
        @include('warehouse.bc.bc')
        <!-- bc part================================ -->

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-sm-12">
                    <div class="order-card">
                        <div class="order-card-header">
                            <div class="order-card-header-left">
                                <h1 class="order-title">
                                    Pending Delivery List
                                </h1>
                                <p class="order-subtitle">
                                    Manage pending, processing and completed deliveries in one place.
                                </p>
                            </div>
                            <div class="order-card-header-right">
                                <span class="order-pill">
                                    <i class="fa fa-clock-o"></i>
                                    Real-time status view
                                </span>
                            </div>
                        </div>

                        {{-- Alerts --}}
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success order-alert">
                                <p>{{ $message }}</p>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger order-alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Filters & Search --}}
                        <div class="order-filters">
                            <form action="{{ route('orderSearch') }}" method="post" class="order-search-form">
                                @csrf
                                <label for="order" class="order-label">Search by Order</label>
                                <div class="order-search-group">
                                    <input type="text"
                                           name="order"
                                           id="order"
                                           class="form-control order-input"
                                           placeholder="Enter Order Number">
                                    <button type="submit" class="btn btn-primary order-search-btn">
                                        <i class="fa fa-search"></i>
                                        Search
                                    </button>
                                </div>
                            </form>

                            <form id="myForm"
                                  action="{{ route('orderspostingdetailsimis.index') }}"
                                  method="GET"
                                  class="order-status-form">
                                <label for="dropdown" class="order-label">Filter by Status</label>
                                <select id="dropdown" class="form-control order-select" name="search">
                                    {{-- <option value="0">All</option> --}}
                                    <option value="1" {{ 1 == $queryarray ? 'selected' : '' }}>Waiting to Add IMEI</option>
                                    <option value="2" {{ 2 == $queryarray ? 'selected' : '' }}>Processing</option>
                                    <option value="3" {{ 3 == $queryarray ? 'selected' : '' }}>Waiting to Delivery</option>
                                    <option value="5" {{ 5 == $queryarray ? 'selected' : '' }}>Delivery Completed</option>
                                </select>
                            </form>
                        </div>

                        <div class="order-card-body">
                            <div class="table-responsive order-table-wrapper">
                                <table class="table order-table" style="width:100%">
                                    <thead>
                                        <tr class="thead">
                                            <th>Info</th>
                                            <th>LD Address</th>
                                            <th>Product</th>
                                            <th>Finance Remarks</th>
                                            <th>Approved QTY (Pcs)</th>
                                            <th>Uploaded QTY (Pcs)</th>
                                            <th>Add IMEI</th>
                                            <th>Delivery Info</th>
                                            <th>Action</th>
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
                                                    // Toggle class only when order number changes
                                                    $rowClass = ($item->order->id == $prevOrderNumber)
                                                        ? $rowClass
                                                        : ($rowClass == 'different-color'
                                                            ? 'same-color'
                                                            : 'different-color');
                                                @endphp

                                                <tr class="order-row {{ $rowClass }}">
                                                    <td>
                                                        <b>
                                                            Order No: {{ $item->order->id }}<br>
                                                            Invoice No:
                                                            {{ $item->id }}{{ date('dmY', strtotime($item->updated_at)) }}<br>
                                                        </b>
                                                        {{ $item->Order->usersd->firstname }}<br>
                                                        {{ $item->Order->usersd->officeid }}<br>
                                                        {{ $item->Order->usersd->contact }}<br>
                                                    </td>

                                                    <td>
                                                        {{ $item->Order->usersd->address ?? '-' }}<br>
                                                    </td>

                                                    <td>{{ $sumitem->Product->model }}</td>
                                                    <td>{{ $item->remarks ?? '-' }}<br></td>
                                                    <td>{{ $sumitem->quantity }}</td>
                                                    <td>{{ count($sumitem->imeilist) }}</td>

                                                    {{-- Add IMEI column --}}
                                                    @if($item->status == 1)
                                                        <td>
                                                            @if (count($sumitem->imeilist) == $sumitem->quantity)
                                                                <span class="order-badge order-badge-success">Delivered</span>
                                                            @else
                                                                <a href="{{ route('orderspostingdetailsimis.edit', $sumitem->id) }}"
                                                                   class="btn btn-success order-btn-compact order-action-btn"
                                                                   title="Add IMEI">
                                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    @endif

                                                    @if($item->status == 2)
                                                        <td>
                                                            @if (count($sumitem->imeilist) == $sumitem->quantity)
                                                                <span class="order-badge order-badge-success">Delivered</span>
                                                            @else
                                                                <a href="{{ route('add_pending_imei', $sumitem->id) }}"
                                                                   class="btn btn-success order-btn-compact order-action-btn"
                                                                   title="Add IMEI">
                                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    @endif

                                                    @if($item->status == 3)
                                                        <td>
                                                            @if (count($sumitem->imeilist) == $sumitem->quantity)
                                                                <span class="order-badge order-badge-success">Delivered</span>
                                                            @else
                                                                <a href="{{ route('orderspostingdetailsimis.edit', $sumitem->id) }}"
                                                                   class="btn btn-success order-btn-compact order-action-btn"
                                                                   title="Add IMEI">
                                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                                </a>
                                                            @endif
                                                        </td>
                                                    @endif

                                                    @if($item->status == 5)
                                                        <td>
                                                            @if (count($sumitem->imeilist) == $sumitem->quantity)
                                                                <span class="order-badge order-badge-success">Delivered</span>
                                                            @else
                                                                <b>Few IMEI Delivered (Contact Admin)</b>
                                                            @endif
                                                        </td>
                                                    @endif

                                                    {{-- Delivery Info --}}
                                                    <td>
                                                        @if ($item->delivery_info)
                                                            {{ $item->delivery_info }} <br>
                                                            <i data-toggle="modal"
                                                               data-target="#editModal_{{ $item->id }}"
                                                               class="fa fa-edit order-edit-icon"></i>

                                                            <div id="editModal_{{ $item->id }}" class="modal fade" role="dialog">
                                                                <div class="modal-dialog">
                                                                    <!-- Modal content-->
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Edit Delivery Information</h4>
                                                                        </div>
                                                                        <form action="{{ route('deliveryInfo_edit', $item->id) }}"
                                                                              method="get">
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <label for="delivery_info_{{ $item->id }}">
                                                                                        Delivery Information
                                                                                    </label>
                                                                                    <input type="text"
                                                                                           class="form-control"
                                                                                           id="delivery_info_{{ $item->id }}"
                                                                                           name="delivery_info"
                                                                                           placeholder="Enter Delivery Information"
                                                                                           required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                        class="btn btn-default"
                                                                                        data-dismiss="modal">Close</button>
                                                                                <button type="submit"
                                                                                        class="btn btn-success">
                                                                                    Confirm
                                                                                </button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                    {{-- Actions --}}
                                                    <td class="order-actions-cell">
                                                        @if ($loop->first)
                                                            @if ($item->status == 3)
                                                                <button type="button"
                                                                        class="btn btn-warning order-action-btn"
                                                                        data-toggle="modal"
                                                                        data-target="#myModal_{{ $item->id }}">
                                                                    <i class="fa fa-check" aria-hidden="true"></i>
                                                                    Confirm Delivery
                                                                </button>

                                                                <a href="{{ route('ordersimei.edit', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-barcode" aria-hidden="true"></i>
                                                                    View Uploaded IMEI
                                                                </a>

                                                                <div id="myModal_{{ $item->id }}" class="modal fade" role="dialog">
                                                                    <div class="modal-dialog">
                                                                        <!-- Modal content-->
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                                <h4 class="modal-title">Add Delivery Information</h4>
                                                                            </div>
                                                                            <form action="{{ route('preesell.add', $item->id) }}"
                                                                                  id="blah_{{ $item->id }}"
                                                                                  method="get">
                                                                                <div class="modal-body">
                                                                                    <div class="form-group">
                                                                                        <label for="delivery_info_modal_{{ $item->id }}">
                                                                                            Delivery Information
                                                                                        </label>
                                                                                        <input type="text"
                                                                                               class="form-control"
                                                                                               id="delivery_info_modal_{{ $item->id }}"
                                                                                               name="delivery_info"
                                                                                               placeholder="Enter Delivery Information"
                                                                                               required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                            class="btn btn-default"
                                                                                            id="closeButton_{{ $item->id }}"
                                                                                            data-dismiss="modal">Close</button>
                                                                                    <button type="submit"
                                                                                            class="btn btn-success"
                                                                                            id="blahButton_{{ $item->id }}"
                                                                                            onclick="disableAndChange('{{ $item->id }}')">
                                                                                        Confirm
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <a href="{{ route('ordersimei.edit', $item->id) }}"
                                                                   class="btn btn-danger order-action-btn">
                                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                                    Edit
                                                                </a>
                                                            @endif

                                                            @if ($item->status == 5)
                                                                <a href="{{ route('chalan.get', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                    Challan Print
                                                                </a>

                                                                <a href="{{ route('printinvoice', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                    Invoice Print
                                                                </a>

                                                                <a href="{{ route('ordersimei.edit', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-barcode" aria-hidden="true"></i>
                                                                    View Uploaded IMEI
                                                                </a>
                                                            @endif

                                                            @if ($item->status == 2)
                                                                <a href="{{ route('chalan.get', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                    Challan Print
                                                                </a>

                                                                <a href="{{ route('printinvoice', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                    Invoice Print
                                                                </a>

                                                                <a href="{{ route('ordersimei.edit', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-barcode" aria-hidden="true"></i>
                                                                    View Uploaded IMEI
                                                                </a>
                                                            @endif

                                                            @if ($item->status == 1)
                                                                <a href="{{ route('chalan.get', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                    Challan Print
                                                                </a>

                                                                <a href="{{ route('printinvoice', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    <i class="fa fa-print" aria-hidden="true"></i>
                                                                    Invoice Print
                                                                </a>

                                                                <a href="{{ route('formatDownload', $item->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-success order-action-btn">
                                                                    Download Format
                                                                </a>

                                                                <form action="{{ route('formatUpload') }}"
                                                                      method="POST"
                                                                      autocomplete="on"
                                                                      enctype="multipart/form-data"
                                                                      class="order-upload-form">
                                                                    @csrf
                                                                    <input id="csv_file"
                                                                           type="file"
                                                                           name="csv_file"
                                                                           required>
                                                                    <button class="btn btn-primary order-action-btn"
                                                                            type="submit">
                                                                        Submit File
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>

                                                @php
                                                    $prevOrderNumber = $item->order->id;
                                                @endphp

                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="order-pagination">
                        {{ $ordersposting->appends(['search' => $queryarray])->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Scoped styles for this page only --}}
    <style>
        .order-panel-wrapper {
            background: #f3f4f6;
        }

        .order-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 24px 24px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.12);
            border: 1px solid #e5e7eb;
            animation: orderCardFadeIn 0.45s ease-out;
        }

        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .order-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: #111827;
        }

        .order-subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: #6b7280;
        }

        .order-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            background: linear-gradient(135deg, #22c1c3, #6366f1);
            color: #f9fafb;
            box-shadow: 0 10px 24px rgba(79, 70, 229, 0.3);
            white-space: nowrap;
        }

        .order-pill i {
            font-size: 13px;
        }

        .order-alert {
            border-radius: 10px;
        }

        /* Filters */
        .order-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 20px;
            align-items: flex-end;
        }

        .order-search-form,
        .order-status-form {
            max-width: 400px;
            flex: 1 1 260px;
        }

        .order-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: #6b7280;
            margin-bottom: 4px;
            display: block;
        }

        .order-search-group {
            display: flex;
            gap: 8px;
        }

        .order-input {
            border-radius: 999px !important;
            padding-inline: 14px;
        }

        .order-search-btn {
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .order-search-btn i {
            font-size: 13px;
        }

        .order-select {
            border-radius: 999px !important;
        }

        /* Table + rows */
        .order-table-wrapper {
            border-radius: 14px;
            overflow: auto;
            border: 1px solid #e5e7eb;
            background: #ffffff;
        }

        .order-table thead tr {
            background: linear-gradient(90deg, #eef2ff, #e0f2fe);
        }

        .order-table thead th {
            border-bottom: 1px solid #d1d5db;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #4b5563;
            white-space: nowrap;
        }

        .order-row {
            transition: background-color 0.22s ease, transform 0.15s ease, box-shadow 0.15s ease;
            position: relative;
        }

        .order-row:hover {
            transform: scale(1.002);
            box-shadow: 0 6px 12px rgba(15, 23, 42, 0.06);
        }

        /* ========================
           GROUP COLORS PER ORDER
           1,3,5,... => same-color
           2,4,6,... => different-color
           ======================== */

        /* Group A (1st, 3rd, 5th order...) */
        .order-panel-wrapper .same-color {
            background-color: #ffffff;
            border-left: 4px solid #8b5cf6;
        }

        /* Group B (2nd, 4th, 6th order...) */
        .order-panel-wrapper .different-color {
            background-color: #e0f2fe;
            border-left: 4px solid #0ea5e9;
        }

        .order-panel-wrapper .same-color:hover {
            background-color: #f5f3ff;
        }

        .order-panel-wrapper .different-color:hover {
            background-color: #bfdbfe;
        }

        /* Badges & compact buttons */
        .order-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 11px;
        }

        .order-badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .order-btn-compact {
            padding: 3px 10px !important;
        }

        .order-edit-icon {
            cursor: pointer;
            color: #2563eb;
            margin-top: 4px;
        }

        .order-edit-icon:hover {
            color: #1d4ed8;
        }

        /* ACTION COLUMN: all buttons same size & style */
        .order-actions-cell {
            min-width: 190px;
        }

        .order-actions-cell .order-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px !important;
            width: 100%;
            margin-bottom: 6px;
            text-align: left;
            white-space: nowrap;
        }

        .order-actions-cell .order-upload-form {
            margin-top: 4px;
        }

        .order-actions-cell .order-upload-form input[type="file"] {
            font-size: 11px;
            margin-bottom: 4px;
        }

        .order-pagination {
            margin-top: 12px;
        }

        /* Animation */
        @keyframes orderCardFadeIn {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* RESPONSIVE */
        @media (max-width: 992px) {
            .order-actions-cell .order-action-btn {
                font-size: 11px;
                padding: 5px 8px;
            }
        }

        @media (max-width: 768px) {
            .order-card {
                padding: 16px 14px;
            }

            .order-card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .order-filters {
                flex-direction: column;
            }

            .order-search-group {
                flex-direction: column;
            }

            .order-actions-cell .order-action-btn {
                font-size: 11px;
                padding: 5px 8px;
                width: 100%;
            }

            .order-table-wrapper {
                border-radius: 10px;
            }
        }
        /* BOOTSTRAP 3 MODAL FIX — REQUIRED */
body.modal-open .order-row,
body.modal-open .order-card,
body.modal-open .content-wrapper {
    transform: none !important;
}

body.modal-open .order-row:hover {
    transform: none !important;
}

    </style>
@endsection

@push('scripts')
    <script>
        // Auto-submit status filter dropdown
        var dropdown = document.getElementById("dropdown");
        if (dropdown) {
            dropdown.addEventListener("change", function () {
                document.getElementById("myForm").submit();
            });
        }

        // Disable confirm button to prevent double submit, per item id
        function disableAndChange(id) {
            var submitButton = document.getElementById('blahButton_' + id);
            var closeButton = document.getElementById('closeButton_' + id);
            var form = document.getElementById('blah_' + id);

            if (submitButton && form) {
                submitButton.disabled = true;
                submitButton.innerHTML = 'Processing... Please wait, do not submit again.';
                form.submit();
            }
            if (closeButton) {
                closeButton.style.display = 'none';
            }
        }

        $('.modal').appendTo('body');

    </script>
@endpush
