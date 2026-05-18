@extends('layouts.master_sales1')

@section('title')
  {{"DMS :: Invoice Details"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <!-- <h1>
        Distributor Order
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> Admin</a></li>
        <li class="active"><a href="{{ route('admin.distributorInvoiceHistory') }}">Invoice Details</a></li>
      </ol> -->
    </section>

  
  
<!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }} ">
            
            <small class="pull-right">Date: {{$invoice['date']}}</small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong>{{$invoice['distributor']['distributor']}}</strong><br>
            {{$invoice['distributor']['duid']}}<br>
            {{$invoice['distributor']['address']}}<br>
            Contact - {{$invoice['distributor']['contact']}}<br>
            TIN - {{$invoice['distributor']['tin']}}<br>
            Account No - {{$invoice['distributor']['baccount']}}<br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          To

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
          <h3 style="font-weight: bolder;"> Invoice</h3>
          <b>Invoice : </b> {{$invoice['invo_id']}}<br>
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
              <th>Unit Price (Tk)</th>
              <th>Excluding Vat (Tk)</th>
              <th> Vat(15%) (Tk) </th>
              <th>Including Vat (Tk)</th>
            </tr>
            </thead>
            <tbody>

@php
  $key = 1;
  //$arr_price = 0;
@endphp              
@foreach ($invoice['invoproduct'] as $element)
  
  @php
    $qty = $element['product_qty'];
    
    $carton = $element['carton_count'];

    $divided = $qty/$carton ;

    $divided1 = substr($divided, 0, strpos($divided, '.')) ;

    $mod = $qty%$carton ;
//=============================================
    $product_price = $element['product_price'];
    $product_qty = $element['product_qty'];
    $totalPrice = $product_price*$product_qty;
    $totalVat = ($totalPrice*15)/100;
    $excludingVat = $totalPrice;
    $includingVat = $totalPrice + $totalVat;

//=============================================

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
    <td>Tk {{number_format($element['product_price'],2) }}</td>
    <td>{{number_format($excludingVat,2) }}</td>

    <td>{{number_format($totalVat,2)}} </td>
    <td>{{number_format($includingVat,2)}} </td>

  </tr>

@php
  $arr_total[] = $element['product_price'] * $element['product_qty'];

@endphp
@endforeach

@php
  $totalword = array_sum($arr_total);
  $vattotal=($totalword*15)/100;
  $totalwordinword= round($totalword+$vattotal);

 @endphp
            
            </tbody>
          </table>
        </div>

      </div>
      <!-- /.row -->
      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            <b>Total(In Words):</b> @php $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
echo $f->format($totalwordinword); @endphp
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <!-- <p class="lead">Total</p> -->

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th>Sub Total:</th>
                <td>
@php
  $total = array_sum($arr_total);
  $vat = ($total*15)/100;
  $grandTotal = $total + $vat;
@endphp   
TK {{ number_format($total,2) }}  
              </td>
              </tr>
              <tr>
                <th>Total VAT:</th>
                <td>
TK {{ number_format($vat,2) }} 

                </td>
              </tr>
              <tr>
                <th>Total:</th>
                <td>TK {{number_format($grandTotal) }} </td>
              </tr>
              <!-- <tr>
                <th>Deposit:</th>
                <td>TK {{number_format($invoice['deposit'],2) }} </td>
              </tr> -->
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

      <!-- /.row -->
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