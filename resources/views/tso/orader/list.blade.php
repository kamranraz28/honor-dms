@extends('layouts.master_tso')

@section('title')
    {{ 'E-Warranty Ststem :: Orader List' }}
@endsection

@section('content')
    <!-- content part================================ -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- bc part================================ -->
        @include('tso.bc.bc')
        <!-- bc part================================ -->

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <!-- /.row -->
            <div class="new-box row">
                <div style="display: flex; justify-content: space-between; align-items: center;">

                    <h1 class="orader">
                        Order List
                    </h1>

                    <div class="float-right">
                        <a href="{{ route('tso.create') }}" class="btn btn-primary btn-lg float-right" data-placement="left">
                            {{ __('Create New Order') }}
                        </a>
                    </div>
                </div>

                @if ($message = Session::get('message'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="myForm" action="{{ route('tso.orader') }}" method="GET"
                        style="max-width: 400px; margin: 40px 0px;">
                        <label for="dropdown">Select an option:</label>
                        <select id="dropdown" class="form-control" name="search">
                            <option value="0" {{ $queryarray==0 ? 'selected' : '' }}>Draft</option>
                            <option value="1" {{ $queryarray==1 ? 'selected' : '' }}>Waiting</option>
                            <option value="2" {{ $queryarray==2 ? 'selected' : '' }}>Processing</option>
                            <option value="3" {{ $queryarray==3 ? 'selected' : '' }}>Submitted</option>
                            <option value="5" {{ $queryarray==5 ? 'selected' : '' }}>Delivered</option>
                            <option value="7" {{ $queryarray==7 ? 'selected' : '' }}>Cancelled</option>
                        </select>
                </form>


                <table id="example" class="display" cellspacing="5" width="100%">
                    <thead>
                        <tr>
                            <th>Order No</th>
                            <th>Dealer</th>
                            <th>Order Date </th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total Price</th>

                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderList as $item)
                        @php
                            $totalQty = 0;
                            $totalPrice = 0;

                            if ($item->orderposting && $item->orderposting->OrderspostingDetails) {
                                foreach ($item->orderposting->OrderspostingDetails as $detail) {
                                    $totalQty += $detail->quantity;
                                    $totalPrice += $detail->price * $detail->quantity;
                                }
                            } elseif (isset($item->details)) {
                                foreach ($item->details as $detail) {
                                    $totalQty += $detail->quantity;
                                    $totalPrice += $detail->price * $detail->quantity;
                                }
                            }
                        @endphp
                            <tr>
                                <td>{{ $item->id }} </td>
                                <td title="$item->status">{{ $item->users->firstname ?? '-'}} ({{ $item->users->officeid ?? '-'}})
                                </td>
                                <td>
                                    @php
                                        echo date('d-M-Y', strtotime($item->created_at));
                                    @endphp
                                </td>

                                <td>{{ $totalQty }}</td>
                                <td>{{ number_format($totalPrice / max($totalQty, 1), 2) }}</td>
                                <td>{{ number_format($totalPrice, 2) }}</td>

                                @switch($item->status)
                                    @case(0)
                                        <td>
                                            <p class="testdanger">Drft</p>
                                        </td>
                                        <td>

                                            <form action="{{ route('tso.destroy', $item->id) }}" method="POST">
                                                <a href="{{ route('tso.details', $item->id) }}" class="btn btn-md btn-primary">
                                                    Details
                                                </a>
                                                @if ($item->status == 0)
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-md">{{ __('Delete') }}</button>
                                                @endif
                                            </form>
                                        </td>
                                    @break

                                    @case(1)
                                        <td>
                                            <p class="testdanger">Waiting</p>
                                        </td>
                                        <td> <a href="{{ route('tso.details', "$item->id") }}" type="button"
                                                class="btn btn-md btn-primary">Details</a></td>
                                    @break

                                    @case(2)
                                        <td>
                                            <p class="testdraft"> Processing</p>
                                        </td>
                                        <td> <a href="{{ route('tso.details', "$item->id") }}"
                                                class="btn btn-md btn-primary">Approved </a></td>
                                    @break

                                    @case(3)
                                        <td>
                                            <p class="testdraft"> Submited </p>
                                        </td>
                                        <td><a href="{{ route('tso.details', "$item->id") }}"
                                                class="btn btn-md btn-primary">Details </a></td>
                                    @break

                                    @case(5)
                                        <td>
                                            <p class="testsuccess">Delivered </p>
                                        </td>
                                        <td><a href="{{ route('tso.details', "$item->id") }}"
                                                class="btn btn-md btn-success">Details </a>
                                            <a href="{{ route('tsorder.print', "$item->id") }}" target="_blank"
                                                class="btn btn-md btn-warning">Print
                                            </a>
                                        </td>
                                    @break

                                    @default
                                @endswitch

                            </tr>
                        @endforeach


                    </tbody>

                </table>
                {{ $orderList->appends(['search' => $queryarray])->links() }}



            </div>

        </section>

    </div>
    <!-- /.content-wrapper -->

    <!-- content part================================ -->
@endsection

@push('scripts')
<script>
    var dropdown = document.getElementById("dropdown");
    dropdown.addEventListener("change", function () {
        document.getElementById("myForm").submit();
    });
</script>
@endpush
