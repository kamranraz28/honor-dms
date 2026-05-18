@extends('layouts.master_accounts')

@section('title')
    {{ 'E-Warranty Ststem :: Dashboard' }}
@endsection

@section('content')
    <!-- content part================================ -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- bc part================================ -->
        @include('accounts.bc.bc')
        <!-- bc part================================ -->

        <!-- Main content -->
        <section class="content">
            <div class="row new-box">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h1 class="orader">
                                    {{ __('Order List') }}
                                </h1>
                            </div>
                        </div>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <form id="myForm" action="{{ route('orderspostings.index') }}" method="GET"
                            style="max-width: 400px; margin: 40px 0px;">
                            <label for="dropdown">Select an option:</label>
                            <select id="dropdown" class="form-control" name="search">
                                <option id="option_0" value="0"
                                    {{ is_null($queryarray) || (is_array($queryarray) && in_array(0, $queryarray)) ? 'selected' : '' }}>
                                    Submitted</option>
                                <option id="option_1" value="1"
                                    {{ !is_null($queryarray) && is_array($queryarray) && in_array(1, $queryarray) ? 'selected' : '' }}>
                                    Waiting</option>
                                <option id="option_5" value="5"
                                    {{ !is_null($queryarray) && is_array($queryarray) && in_array(5, $queryarray) ? 'selected' : '' }}>
                                    Closed</option>
                                <option id="option_7" value="7"
                                    {{ !is_null($queryarray) && is_array($queryarray) && in_array(7, $queryarray) ? 'selected' : '' }}>
                                    Rejected</option>
                            </select>


                            <br>

                            <div class="form-group">
                                <label for="fdate" class="control-label">From Date:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="fdate" placeholder="YYYY-MM-DD" value="{{ $fdate ? $fdate : '' }}"
                                        type="text" class="form-control pull-right" id="datepicker1" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="todate" class="control-label">To Date:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="todate" placeholder="YYYY-MM-DD" value="{{ $todate ? $todate : '' }}"
                                        type="text" class="form-control pull-right" id="datepicker2" autocomplete="off">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                            </div>
                        </form>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="display" cellspacing="0" width="100%">
                                    <thead class="thead">
                                        <tr>
                                            <th>SN</th>

                                            <th>Order Number</th>
                                            <th>Order by</th>
                                            <th>LD</th>
                                            <th>Number of Items</th>
                                            <th>Total Quantity</th>
                                            <th>Value(BDT)</th>
                                            <th>Finance Remarks</th>
                                            <th>Order Remarks</th>
                                            <th>Order Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orderspostings as $ordersposting)
                                            <tr>
                                                <td>{{ ++$i }}</td>

                                                <td>{{ $ordersposting->orader_number ?? '-' }}</td>
                                                <td>{{ $ordersposting->Order->users->firstname ?? '-' }}
                                                    {{ $ordersposting->Order->users->lastname ?? '-' }}
                                                    ({{ $ordersposting->Order->users->officeid ?? '-' }})
                                                </td>
                                                <td>{{ $ordersposting->Order->usersd->firstname ?? '-' }}<br>
                                                    {{ $ordersposting->Order->usersd->officeid ?? '-' }}
                                                </td>

                                                <td>
                                                    {{ count($ordersposting->OrderspostingDetails) ? count($ordersposting->OrderspostingDetails) : '-' }}
                                                </td>

                                                <td>
                                                    @if ($ordersposting->OrderspostingDetails->isNotEmpty())
                                                        @php
                                                            $totalQuantity = 0;
                                                        @endphp

                                                        @foreach ($ordersposting->OrderspostingDetails as $detail)
                                                            @php
                                                                $totalQuantity += $detail->quantity;
                                                            @endphp
                                                        @endforeach

                                                        {{ $totalQuantity }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($ordersposting->OrderspostingDetails->isNotEmpty())
                                                        @php
                                                            $totalValue = 0;
                                                        @endphp

                                                        @foreach ($ordersposting->OrderspostingDetails as $detail)
                                                            @php
                                                                $totalValue += $detail->price * $detail->quantity;
                                                            @endphp
                                                        @endforeach

                                                        {{ number_format($totalValue) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>




                                                <td>
                                                    @if (!empty($ordersposting->remarks))
                                                        {{ $ordersposting->remarks }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $ordersposting->Order->remarks ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $ordersposting->created_at ?? '-' }}
                                                </td>
                                                @switch($ordersposting->status)
                                                    @case(0)
                                                        <td>
                                                            <p class="testdraft"> Submitted</p>
                                                        </td>
                                                        <td>
                                                            <div style="padding-bottom: 3px">
                                                                <a class="btn btn-md btn-primary"
                                                                    href="{{ route('account.details', $ordersposting->orader_number) }}">Details</a>
                                                            </div>
                                                            <div style="padding-bottom: 3px">
                                                                <a class="btn btn-md btn-warning"
                                                                    href="{{ route('orderspostings.edit', $ordersposting->id) }}">{{ __('Review') }}</a>
                                                            </div>

                                                            <button type="button" class="btn btn-md btn-danger" data-toggle="modal"
                                                                data-target="#myModal_{{ $ordersposting->id }}">Reject</button>

                                                            <div id="myModal_{{ $ordersposting->id }}" class="modal fade"
                                                                role="dialog">
                                                                <div class="modal-dialog">
                                                                    <!-- Modal content-->
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal">&times;</button>
                                                                            <h4 class="modal-title">Cancel Order</h4>
                                                                        </div>
                                                                        <form
                                                                            action="{{ route('orderposting_delete', $ordersposting->id) }}"
                                                                            method="get">
                                                                            <div class="modal-body">
                                                                                <div class="form-group">
                                                                                    <label for="cancel_reason">About
                                                                                        Cancellation</label>
                                                                                    <input type="text" class="form-control"
                                                                                        id="cancel_reason" name="cancel_reason"
                                                                                        placeholder="Enter Cancel Reason" required>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default"
                                                                                    data-dismiss="modal">Close</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-success">Confirm</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    @break

                                                    @case(1)
                                                        <td>
                                                            <p class="testdraft"> Waiting</p>
                                                        </td>
                                                        <td>
                                                            <div style="padding-bottom: 3px">
                                                                <a class="btn btn-md btn-primary"
                                                                    href="{{ route('account.details', $ordersposting->orader_number) }}">Details</a>
                                                            </div>
                                                            <form
                                                                action="{{ route('orderspostings.destroy', $ordersposting->id) }}"
                                                                method="POST">
                                                                @csrf

                                                                <a class="btn btn-md btn-info"
                                                                    href="{{ route('orderspostings.edit', $ordersposting->id) }}">
                                                                    {{ __('Edit ') }}</a>
                                                            </form>
                                                        </td>
                                                    @break

                                                    @case(2)
                                                        <td>
                                                            <p class="testdanger"> Processing</p>
                                                        </td>
                                                        <td>
                                                            <div style="padding-bottom: 3px">
                                                                <a class="btn btn-md btn-primary"
                                                                    href="{{ route('account.details', $ordersposting->orader_number) }}">Details</a>
                                                            </div>
                                                            <a class="btn btn-md btn-primary"
                                                                href="{{ route('orderspostings.show', $ordersposting->id) }}">{{ __('Check the invoice ') }}</a>
                                                        </td>
                                                    @break

                                                    @case(3)
                                                        <td>
                                                            <p class="testdanger"> Waiting to delivery </p>
                                                        </td>

                                                        <td>
                                                            <div style="padding-bottom: 3px">
                                                                <a class="btn btn-md btn-primary"
                                                                    href="{{ route('account.details', $ordersposting->orader_number) }}">Details</a>
                                                            </div>
                                                            <a class="btn btn-md btn-primary"
                                                                href="{{ route('orderspostings.show', $ordersposting->id) }}">{{ __('Print invoice') }}</a>
                                                        </td>
                                                    @break

                                                    @case(7)
                                                        <td>
                                                            <p class="testdanger"> Cancelled </p>
                                                        </td>

                                                        <td>


                                                            <a class="btn btn-md btn-danger"
                                                                href="{{ route('orderposting_reverse', $ordersposting->id) }}"
                                                                onclick="return con()">{{ __('Reverse') }}</a>
                                                        </td>
                                                    @break

                                                    @case(5)
                                                        <td>
                                                            <p class="testsuccess">Closed</p>
                                                        </td>
                                                        <td>
                                                            <div style="padding-bottom: 3px">
                                                                <a class="btn btn-md btn-primary"
                                                                    href="{{ route('account.details', $ordersposting->orader_number) }}">Details</a>
                                                            </div>
                                                            <a class="btn btn-md btn-success" target="_blank"
                                                                href="{{ route('postinginvoice.print', $ordersposting->id) }}">{{ __('Print') }}</a>
                                                        </td>
                                                    @break

                                                    @default
                                                @endswitch

                                                {{-- @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-md"> {{ __('Delete') }}</button>
                                        --}}


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    {{ $orderspostings->appends(['search' => $queryarray, 'fdate' => $fdate, 'todate' => $todate])->links() }}
                </div>
            </div>
    </div>
    </div>
@endsection

@push('scripts')
    <script>
        var dropdown = document.getElementById("dropdown");
        dropdown.addEventListener("change", function() {
            document.getElementById("myForm").submit();
        });
    </script>
    <script>
        function con() {
            return confirm("Do You Want to Restore the data?");
        }
    </script>

    <script>
        // Get the queryarray value from PHP
        var queryarray = @json($queryarray);

        // Loop through each option in the dropdown
        for (var i = 0; i < queryarray.length; i++) {
            var optionId = 'option_' + queryarray[i];
            var option = document.getElementById(optionId);

            if (option) {
                option.selected = true; // Set the option as selected
            }
        }
    </script>
@endpush
