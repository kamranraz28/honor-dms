<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Delivery Challan</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <style>
        .table_pad td {
            padding: 02px;
        }
    </style>

    <style>
        body {
    font-family: 'Roboto', sans-serif;
    font-size: 15px;
}
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

<body class="hold-transition skin-blue sidebar-mini">
 <?php
$imagePath = asset('resources/assets/dms/dist/img/chalanlogo.png');

// Disable SSL verification
$context = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ]
]);
$base64Image = base64_encode(file_get_contents($imagePath, false, $context));

  ?>
<img src="data:image/png;base64,{{ $base64Image }}" class="responsive no-repeat" alt="logo" style="width: 200px; height: 45px; ">



    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-body">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="new-box">

                                <h3 style="text-align: center"> Delivery Challan </h3>
                                <h6 style="text-align: right">
                                <?php
                                        echo 'Date: ' . date('d/m/Y') . '<br>';
                                        echo 'Time: ' . date('H:i:s');
                                    ?>


                                    </b>
                                </h6>
                                <table style="width: 100%">
                                    <thead>
                                        <tr>
                                            <td colspan="2" rowspan="2"><br>
                                                <b> Salextra Limited</b><br>
                                                Address: 17/1 Sataish Road, Gazipura, Tongi West; Tongi PS; Gazipur-1710; Bangladesh<br>
                                                &nbsp;<br>
                                            </td>
                                            <td colspan="2">Order No:
                                                @php
                                                    echo $ordersposting->Order->id;
                                                @endphp
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                Invoice No:
                                                {{ $ordersposting->id . '' . date('dmY', strtotime($ordersposting->updated_at)) }}

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="3">
                                                <b>{{ $ordersposting->Order->usersd->firstname }}</b><br>
                                                Contact No:
                                                {{ $ordersposting->Order->usersd->contact ?? '-' }} <br>
                                                Address:
                                                {{ $ordersposting->Order->usersd->address ?? '-' }}
                                            </td>
                                            <td colspan="2">Approved by:
                                                {{ $ordersposting->Userinfo->firstname }}
                                                {{ $ordersposting->Userinfo->lastname }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> Challan No.
                                                {{ $ordersposting->Order->id }}{{ $ordersposting->id }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2"> Challan Date.

                                                @php
                                                    echo date('d/m/Y', strtotime($ordersposting->updated_at));
                                                @endphp

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"> Account's Remarks:
                                                {{ $ordersposting->remarks ?? '-' }}
                                            </td>
                                        </tr>



                                        <tr style="text-align:left">
                                            <th>Sl</th>
                                            <th>Product Name</th>
                                            <th>Product Model</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                            $sum = 0;
                                        @endphp


                                        @foreach ($ordersposting->OrderspostingDetails as $item)
                                        <tr style="text-align:left">
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $item->Product->name }}</td>
                                            <td>{{ $item->Product->model }}
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                        </tr>
                                        @php
                                            $sum += $item->quantity;
                                        @endphp
                                        
                                        
                                        @endforeach
                                        <tr>
                                            <td colspan="3"> <b>Total</b> </td>
                                            
                                            <td> <b>{{ $sum }}</b></td>
                                        </tr>

                                    </tbody>
                                </table>
                                <br>
                                <b><u>Declaration</u></b>
                                <p>We declare that this invoice shows the actual price of the goods described and
                                    that all particulars are true and correct</p>
                                    

</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- /.row -->


                </div>
            </div>
        </div>
        <div style="position: fixed; bottom: 0; width: 100%; text-align: left;">
    <i>This is Computer Generated Invoice. No signature required. </i>
</div>
    </div>
    </div>
</body>

</html>
