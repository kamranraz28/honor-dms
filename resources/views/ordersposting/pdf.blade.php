<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Invoice-SAP</title>

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
        <div class="col-md-12">
            <h4 class="card-title">Invoice No:
                {{ $ordersposting->id . '' . date('dmY', strtotime($ordersposting->updated_at)) }}</h4>
            <p style="text-align: right">
                @php
                    echo date('d-M-Y');
                @endphp

            </p>
            <table style="width: 100%">
                <thead>
                    <tr>
                        <td colspan="4" rowspan="2"><br>
                            <b> Salextra Limited</b><br>
                            Address: 17/1 Sataish Road, Gazipura, Tongi West; Tongi PS; Gazipur-1710; Bangladesh<br>
                            &nbsp;<br>
                        </td>
                        <td colspan="4">Order No:
                            @php
                                echo $ordersposting->Order->id;
                            @endphp
                        </td>
                    </tr>
                    <tr>
                        {{--  <th colspan="4">Approve:
                                    {{ $ordersposting->created_at }}
                                </th>  --}}
                        <td colspan="4">
                            Invoice No:
                            {{ $ordersposting->id . '' . date('dmY', strtotime($ordersposting->updated_at)) }}

                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" rowspan="3">
                            <b>{{ $ordersposting->Order->usersd->firstname ?? '-' }}</b><br>
                             Contact No:
                            {{ $ordersposting->Order->usersd->contact ?? '-' }} <br>
                            Address:
                           {{ $ordersposting->Order->usersd->address ?? '-' }}
                        </td>
                        <td colspan="4">Approve by:
                            {{ $ordersposting->Userinfo->firstname ?? '-'}}
                            {{ $ordersposting->Userinfo->lastname ?? '-'}}</td>
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
                        
                        <th>Product Name</th>
                        <th>Qty</th>
                        <th>Rate</th>
                        <th>Value</th>
                        <th>Discount</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 1;
                        $sum = 0;
                        $total = 0;
                    @endphp


                    @foreach ($ordersposting->OrderspostingDetails as $item)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>
                                {{ $item->Product->model }}
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

                            @php
                                $total += $item->quantity;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>

                        <td colspan="2"> <b>Total</b> </td>
                        <td><b>{{ $total }}</b></td>
                        <td colspan="3"></td>
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
            

        </div>
        
    </div>
    <div style="position: fixed; bottom: 0; width: 100%; text-align: left;">
    <i>This is Computer Generated Invoice. No signature required. </i>
</div>

</body>

</html>
