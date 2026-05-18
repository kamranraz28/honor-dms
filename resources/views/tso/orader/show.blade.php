@extends('layouts.master_tso')

@section('title')
    {{ 'E-Warranty Ststem :: Dashboard' }}
@endsection


@section('content')
    <!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- bc part================================ -->
        <!-- bc part================================ -->

        <!-- Main content -->
        <section class="content">

            <div class="row new-box">
                <div class="col-sm-12">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-sm-6">
                    <h1 class="orader">Requested order</h1>
                    <form action="{{ route('oraderposting.store') }}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                        <table class="table-bordered table">
                            <thead>
                                <tr>
                                    <th colspan="3">Order Number: {{ $orader->id }}
                                        <input type="hidden" name="orader_number" value="{{ $orader->id }}">
                                    </th>
                                    <th colspan="3">Date: {{ $orader->created_at->format('Y-m-d') }} <br> Time: {{ $orader->created_at->format('H:i:s') }} </th>
                                </tr>
                                <tr>
                                    <th colspan="3">Dist Name: {{ @$orader->users->firstname }}</th>
                                    <th colspan="3">Dist code: {{ @$orader->users->officeid }}</th>
                                </tr>
                                {{--  <tr>
                                        <th colspan="3">TSO: @dump($orader->user)</th>
                                        <th colspan="3">user_id: {{ $orader->user_id }}</th>
                                    </tr>  --}}
                                <tr>
                                    <th colspan="3">Status:
                                        @switch($orader->status)
                                            @case(0)
                                                <strong>Drft</strong>
                                            @break

                                            @case(1)
                                                <strong>Waiting</strong>
                                            @break

                                            @case(2)
                                                <strong>Processing</strong>
                                            @break

                                            @case(3)
                                                <strong>Delivered</strong>
                                            @break

                                            @default
                                        @endswitch
                                    </th>
                                    <th colspan="3"> </th>

                                </tr>


                                <tr>
                                    <th>Item No</th>
                                    <th>product</th>
                                    <th>price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $sum = 0;
                                @endphp
                                @foreach ($oraderdetails as $item)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ @$item->product->name }}
                                            <input type="hidden" name="product_id[]" value="{{ $item->product_id }}"
                                                class="form-control name_unitprice" hidden />
                                        </td>
                                        <td>@moneybdt($item->price)
                                            <input type="hidden" name="unitprice[]" value="{{ $item->price }}"
                                                class="form-control name_unitprice" hidden />
                                        </td>
                                        <td>{{ $item->quantity }} <input type="hidden" name="quintity[]"
                                                value="{{ $item->quantity }}" class="form-control name_quintity" hidden />
                                        </td>

                                        <td>@moneybdt($item->price * $item->quantity)</td>
                                        @php
                                            $sum += $item->price * $item->quantity;
                                        @endphp
                                    </tr>
                                @endforeach
                                <tr>

                                    <td colspan="4"> Total </td>
                                    <td> @moneybdt($sum)</td>
                                </tr>

                            </tbody>
                        </table>

                        @if (!$orader->status)
                            <button type="submit" class="btn btn-success btn-lg">Confirm Order</button>
                        @endif
                    </form>
                </div>

                @if ($orader->status && $postings->count())
                    <div class="col-sm-6">
                        <h1>Approve order</h1>
                        <table class="table-bordered table">
                            <thead>
                                <tr>
                                    <th colspan="4">Order Number: {{ $orader->id }}
                                        <input type="hidden" name="orader_number" value="{{ $orader->id }}">
                                    </th>
                                     <th colspan="3">Date: {{ $orader->created_at->format('Y-m-d') }} <br> Time: {{ $orader->created_at->format('H:i:s') }} </th>
                                </tr>
                                <tr>
                                    <th colspan="4">Dist Name: {{ @$orader->users->firstname }}</th>
                                    <th colspan="4">Dist code: {{ @$orader->users->officeid }}</th>
                                </tr>

                                <tr>
                                    <th colspan="4">Status:
                                        @switch($orader->status)
                                            @case(0)
                                                <strong>Drft</strong>
                                            @break

                                            @case(1)
                                                <strong>Waiting</strong>
                                            @break

                                            @case(2)
                                                <strong>Processing</strong>
                                            @break

                                            @case(3)
                                                <strong>Delivered</strong>
                                            @break

                                            @default
                                        @endswitch
                                    </th>
                                    <th colspan="3"> </th>

                                </tr>


                                <tr>
                                    <th>Item No</th>
                                    <th>product</th>
                                    <th>price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Discount</th>
                                    <th>Final Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $sum = 0;
                                @endphp

                                @foreach ($postings as $value)
                                    @for ($count = 0; $count < count($value->OrderspostingDetails); $count++)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $value->OrderspostingDetails[$count]->product->model }} ({{$value->OrderspostingDetails[$count]->product->model}})
                                            </td>
                                            <td>@moneybdt($value->OrderspostingDetails[$count]->price)</td>
                                            <td>{{ $value->OrderspostingDetails[$count]->quantity }}
                                            </td>
                                            <td>@moneybdt($value->OrderspostingDetails[$count]->price * $value->OrderspostingDetails[$count]->quantity)</td>
                                            <td>@moneybdt($value->OrderspostingDetails[$count]->price_acc)
                                            </td>
                                            <td>@moneybdt(($value->OrderspostingDetails[$count]->price * $value->OrderspostingDetails[$count]->quantity) - $value->OrderspostingDetails[$count]->price_acc)
                                            </td>
                                            @php
                $subtotal = $value->OrderspostingDetails[$count]->price * $value->OrderspostingDetails[$count]->quantity;
                $sum += $subtotal - $value->OrderspostingDetails[$count]->price_acc;
            @endphp
                                        </tr>
                                    @endfor
                                @endforeach
                                <tr>

                                    <td colspan="6"> Total </td>
                                    <td> @moneybdt($sum)</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </section>
        <!-- /.content -->

    </div>
    <!-- /.content-wrapper -->





    <!-- content part================================ -->
@endsection
