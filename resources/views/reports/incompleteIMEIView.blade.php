@extends('layouts.master_admin')

@section('title')
    {{ 'E-Warranty System :: View IMEI' }}
@endsection

@section('content')
    <!-- content part================================ -->
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <!-- bc part================================ -->
        @include('admin.bc.bc')
        <!-- bc part================================ -->

        <!-- Main content -->
        <div class="new-box" style=" max-width: 1500px; padding-left: 10px;  margin-top: 100px;">
        <center> <h2>Order Number: {{ $report['orderNumber'] }}</h2> </center> <br>
        <table id="example" class="ui celled table" width="100%">
            @php
                $count = 1;
            @endphp
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Model</th>
                                <th>IMEI 1</th>
                                <th>IMEI 2</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($report['imei1'] as $key => $imei)
                            <tr>
                                <td>{{$count++}}</td>
                                <td>{{ $report['model'] }}</td>
                                <td>{{ $imei }}</td>
                                <td>{{ $report['imei2'][$key] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
        </div>

    </div>
@endsection
