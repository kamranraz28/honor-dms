@extends('layouts.master_admin')

@section('title')
  {{"E-Warranty Ststem :: DOS Report"}}
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
    <section class="content">


<!-- Main row -->
<div class="row">
  <!-- Left col -->
  


<!-- ==============one section area ================= -->


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title text-danger">Incomplete Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
  
            <!-- form start -->
             


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>

<!-- ==============one section area ================= -->

<!-- ==============one section area ================= -->


  <section class="col-lg-12 connectedSortable">
          <!-- Recent Invoice -->
          <div class="box box-warning">
            <div class="box-header">

            </div>
            <!-- /.box-header -->
            <div class="box-body">


<table id="example" class="ui celled table" width="100%">
         

<thead>
    <tr>
        <th>Order Number</th>
        <th>Product Model</th>
        <th>Approved Quantity</th>
        <th>Uploaded Quantity</th>
        <th>Missing Product</th>
        <th>Uploaded IMEI</th>
    </tr>
</thead>
<tbody>
    @foreach ($mismatchedResults as $key => $element)
    <tr>
        <td>{{$element['order_number']}}</td>
        <td>{{ isset($element['model']) ? $element['model'] : 'N/A' }}</td>
        <td>{{$element['quantity']}}</td>
        <td>{{$element['count']}}</td>
        <td>{{$element['quantity'] - $element['count']}}</td>
        <td>
            <div style="margin-top: 10px;">
            <a href="{{ route('incompleteIMEIView', [$element['orderspostingdetails_id'], $element['product_id']]) }}" target="_blank"
                class="btn btn-sm btn-success">
                <i class="fa fa-barcode" aria-hidden="true"></i> View Uploaded IMEI
            </a>

            </div>
        </td>

    </tr>
    @endforeach
</tbody>


  </table>







            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->


  </section>

<!-- ==============one section area ================= -->









</div>
<!-- /.row (main row) -->
















    </section>
    <!-- /.content -->
 
  </div>
<!-- /.content-wrapper -->




@php
  Session::forget(['all_report','distributor_id','fdate','todate']);
@endphp
<!-- content part================================ -->
@endsection