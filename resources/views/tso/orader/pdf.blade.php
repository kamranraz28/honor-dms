<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table_pad td {
            padding: 02px;
        }
    </style>

    <style>
        b {
            font-weight: bold;
        }

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            font: 12px;
        }

        th {
            padding: 10px;
        }

        td {
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="row">

        <h1 class="orader">Requested order</h1>

        <table style="width: 100%">
            <thead>
                <tr>
                    <th colspan="2">Order Number: {{ $orader->id }}
                        <input type="hidden" name="orader_number" value="{{ $orader->id }}">
                    </th>
                    <th colspan="3">Created at: {{ $orader->created_at }} </th>
                </tr>
                <tr>
                    <th colspan="2">Dist Name: {{ @$orader->users->firstname }}</th>
                    <th colspan="3">Dist code: {{ @$orader->users->officeid }}</th>
                </tr>

                <tr>
                    <th colspan="2">Status: </th>
                    <th colspan="3">
                        @switch($orader->status)
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
                    </th>

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
                        <td>{{ $item->product->name }}
                            <input type="hidden" name="product_id[]" value="{{ $item->product_id }}"
                                class="form-control name_unitprice" hidden />
                        </td>
                        <td>@moneybdt($item->price)
                            <input type="hidden" name="unitprice[]" value="{{ $item->price }}"
                                class="form-control name_unitprice" hidden />
                        </td>
                        <td>{{ $item->quantity }} <input type="hidden" name="quintity[]" value="{{ $item->quantity }}"
                                class="form-control name_quintity" hidden />
                        </td>

                        <td>@moneybdt($item->price * $item->quantity)</td>
                        @php
                            $sum += $item->price * $item->quantity;
                        @endphp
                    </tr>
                @endforeach
                <tr>

                    <td colspan="4"> <b>Total</b> </td>
                    <td> <b>@moneybdt($sum)</b></td>
                </tr>

            </tbody>
        </table>
    </div>


    @if ($orader->status && $postings->count())
        <div class="row">
            <h1>Approve order</h1>
            <table style="width: 100%">
                            <thead>
                                <tr>
                                    <th colspan="4">Order Number: {{ $orader->id }}
                                        <input type="hidden" name="orader_number" value="{{ $orader->id }}">
                                    </th>
                                    <th colspan="4">Created at: {{ $orader->created_at }} </th>
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
                                                <strong>Complete</strong>
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
                                            <td>{{ $i }}</td>
                                            <td>{{ $value->OrderspostingDetails[$count]->product_id }}
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

</body>

</html>
