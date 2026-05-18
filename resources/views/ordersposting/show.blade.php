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
            <div class="row new-box" style=" width: 1000px; ">
                <h4 class="card-title">Invoice No:
                    {{ $ordersposting->id . '' . date('dmY', strtotime($ordersposting->updated_at)) }}</h4>
                <p style="text-align: right">
                    @php
                        echo date('d-M-Y');
                    @endphp

                </p>
                <table class="table-bordered table">
                    <thead>
                        <tr>
                            <td colspan="4" rowspan="3"><br>
                                <b> Salextra Limited</b><br>
                                Address: 9KA/KHA Level 6,Tejgaon Industrial Area,<br>
                                Tejgaon, Dhaka-1208<br>
                                &nbsp;<br>
                            </td>
                            <td colspan="4">Order No:
                                @php
                                    echo $ordersposting->Order->id . date('dmY', strtotime($ordersposting->Order->updated_at));
                                @endphp
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                Invoice No:
                                {{ $ordersposting->id . '' . date('dmY', strtotime($ordersposting->updated_at)) }}

                            </td>

                        </tr>
                        <tr>
                            <td colspan="4">
                                Status:

                                @switch($ordersposting->status)
                                    @case(0)
                                        <b>Save as Drft</b>
                                    @break

                                    @case(1)
                                        <b>Waiting to review</b>
                                    @break

                                    @case(2)
                                        <b>Order Processing</b>
                                    @break

                                    @case(3)
                                        <b>Order Submit</b>
                                    @break

                                    @case(5)
                                        <b>Order Close</b>
                                    @break

                                    @default
                                @endswitch

                            </td>

                        </tr>
                        <tr>
                            <td colspan="4" rowspan="3">
                                <b>{{ $ordersposting->Order->usersd->firstname }}</b><br>
                                Address:
                                {{ $ordersposting->Order->tsoupazila->deleardetails->address }}
                            </td>
                            <td colspan="4">Approve by:
                                {{ $ordersposting->Userinfo->firstname }}
                                {{ $ordersposting->Userinfo->lastname }}</td>
                        </tr>
                        <tr>
                            <td colspan="4"> Challan No.
                                {{ $ordersposting->Order->id }}{{ $ordersposting->id }}
                            </td>
                            {{--  <th colspan="4">Note: </th>  --}}


                        </tr>
                        <tr>
                            <td colspan="4"> Challan Date.

                                @php
                                    echo date('d/m/Y', strtotime($ordersposting->updated_at));
                                @endphp

                            </td>
                            {{--  <th colspan="4">Note: </th>  --}}


                        </tr>


                        <tr>
                            <th>Sl</th>
                            <th>Porduct Name</th>
                            <th>Product Model</th>
                            <th>Qty</th>
                            <th>Rate</th>
                            <th>Value(BDT)</th>
                            <th>Discount</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = 1;
                            $sum = 0;
                        @endphp


                        @foreach ($ordersposting->OrderspostingDetails as $item)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $item->Product->name }}</td>
                                <td>{{ $item->Product->model }}
                                   
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>@moneybdt($item->price)</td>
                                <td>@moneybdt($item->price * $item->quantity)</td>
                                <td>@moneybdt($item->price_acc)</td>
                                <td>@moneybdt($item->price * $item->quantity - $item->price_acc)
                                </td>
                                @php
                                    $sum += $item->price * $item->quantity - $item->price_acc;
                                @endphp
                            </tr>
                        @endforeach
                        <tr>

                            <td colspan="7"> <b>Total</b> </td>
                            <td> <strong> <b>@moneybdt($sum)</b> </td>
                        </tr>

                    </tbody>
                </table>
                <p style="text-align: right">
                    <b> @php
                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        echo ucwords($f->format($sum)) . ' Taka Only';
                    @endphp
                    </b>
                </p>
                <b><u>Declaration</u></b>
                <p>We declare that this invoice shows the actual price<br>of the goods described and
                    that all particulars are true and correct</p>
                <i>
                    <div class="text-center">This is Computer Generated Invoice </div>
                </i>
            </div>
        </section>
    </div>
@endsection
