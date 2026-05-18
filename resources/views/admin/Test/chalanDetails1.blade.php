@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Chalan Details"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       Chalan Details
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> ASO</a></li>
        <li class="active"><a href="{{ route('admin.chalanDetails') }}">Chalan Details</a></li>
      </ol>
    </section>

  
<!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }} ">
            <small class="pull-right">Invoice Date: {{$chalan['invoice']['date']}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong>Care Nutrations Limited</strong><br>
            Factory<br>
            Nanakhi, Sadipur<br>
            Sonargaon, Narayanganj<br>
            Bangladesh<br>
            Vat Reg. No. : 000626588
            
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          To

          <address>
            <strong>{{$chalan['distributor']['distributor']}}</strong><br>
            {{$chalan['distributor']['duid']}}<br>
            {{$chalan['distributor']['address']}}<br>
            Contact - {{$chalan['distributor']['contact']}}<br>
            TIN - {{$chalan['distributor']['tin']}}<br>
            Account No - {{$chalan['distributor']['baccount']}}<br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
<h4 style="font-weight: bolder;">Delivery Invoice</h4>

          <b>Order Invoice : </b> {{$chalan['invoice']['invo_id']}}<br>
          <b>Factory Invoice : </b> {{$chalan['purch_id']}}<br>
          <b>Chalan : </b> {{$chalan['chalan_no']}}<br>
          <b>Chalan Date : </b> {{$chalan['chalan_date']}}<br>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>Sl.</th>
              <th>Product Name</th>
              <th>Product Quantity</th>
              <th>Carton</th>
              <!-- <th>Unit Price</th>
              <th>Subtotal</th> -->
            </tr>
            </thead>
            <tbody>

@php
  $key = 1;
  //$arr_price = 0;
@endphp              
@foreach ($chalan['purchaseproduct'] as $element)
  
  @php
    

    $qty = $element['product_qty'];
    
    $arr_qty[] = $qty;


    $carton = $element['carton_count'];

    $divided = $qty/$carton ;

    $divided1 = substr($divided, 0, strpos($divided, '.')) ;

    $mod = $qty%$carton ;




  @endphp


  <tr>
    <td>{{$key++}}</td>
    <td>{{$element['product_name']}} - {{$element['product_sku']}} </td>
    <td>{{$element['product_qty']}}</td>
    <td>
      @if ($mod > 0)
        {{$divided1}} Carton And {{$mod}} Box
      @else
        {{$divided}} Carton
      @endif
    </td>
    <!-- <td>{{$element['product_price']}}</td>
    <td>{{$element['product_price'] * $element['product_qty']}} </td> -->
  </tr>

@php
  $arr_total[] = $element['product_price'] * $element['product_qty'];

@endphp
@endforeach

@php
  $total_quantity = array_sum($arr_qty);
  $totalword = array_sum($arr_total);
  $vattotal=($totalword*15)/100;
  $totalwordinword= $totalword+$vattotal;

  $total = array_sum($arr_total);


//==============carton count for total quantity==============

    $qty = $total_quantity;


    $carton = $element['carton_count'];

    $divided = $qty/$carton ;

    $divided1 = substr($divided, 0, strpos($divided, '.')) ;

    $mod = $qty%$carton ;

//==============carton count for total quantity==============


 @endphp
            
            </tbody>
          </table>
        </div>

      </div>
      <!-- /.row -->
      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
         <!--  <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
           <b>Total(In Words):</b> @php $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
         echo $f->format($totalwordinword); @endphp
         </p> -->
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Total</p>

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th>Total Carton:</th>
                <td>
@if ($mod > 0)
        {{$divided1}} Carton And {{$mod}} Box
      @else
        {{$divided}} Carton
      @endif
              </td>
              </tr>

              

            </table>
          </div>
        </div>
        <!-- /.col -->


<!-- ===================================================================== -->
  <div class="col-md-12">
    
    <table class="table">
      <tr>
        <th>
          <p style="margin-top:60px">
            <span> ----------------</span><br>
            <span>Received By</span><br>
          </p>
          
        </th>

        <th></th><th></th>
        <th></th><th></th>
        <th></th><th></th>

        <th>
          <p style="margin-top:60px">
            <span> -----------------------------</span><br>
            <span>Authorised Signature</span><br>
          </p>

        </th>
      </tr>
    </table>

  </div>

<!-- ===================================================================== -->






      </div>
      <!-- /.row -->

<div class="row">
  <div class="col-xs-12">
    <hr>
<address>
 
  <span style="color:tomato;font-weight: bolder;">Head Office :</span>  Appartment B3 | House 36 | Rd 12 | Block E | Banani | Dhaka 1213<br>
  <span style="color:tomato;font-weight: bolder;">Registered Office :</span>  09 Mohakhali | High Tower | Dhaka <br>

  <span style="color:tomato;font-weight: bolder;">Contact No :</span>  +8801883065401 <br>
  
  
</address>
  </div>

</div>

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="#" target="_blank" class="btn btn-default" onClick="window.print()"><i class="fa fa-print"></i> Print</a>
      
        </div>
      </div>
    </section>
    <!-- /.content -->


 
  </div>
<!-- content part================================ -->
@endsection