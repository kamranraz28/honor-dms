@extends('layouts.master_sales1')

@section('title')
  {{"DMS :: Finished Good History"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Distributor Invoice History
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('sales.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> REPORTS</a></li>
        <li class="active"><a href="{{ route('sales.factoryFinishedGoodHistoryDetails') }}">Finished Goods Details History</a></li>
      </ol>
    </section>

<section class="content-header">
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Finished Goods Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
<div class="table-responsive1" style="overflow-x: scroll;overflow-y: scroll; height: 250px;white-space:nowrap; width:100%">

<table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                   
                <tr>
                  <th>Product</th>
                  
                  <th>SKU</th>
                  <th>Quantity(Boxs)</th>
                  <th>Carton Count</th>
                  <th>Date</th>
                </tr>
                </thead>
                <tbody>
@foreach ($quantities as $element)
  
  @php
    $qty = $element['quantity'];
    
    $carton = $element['product']['carton_count'];

    $divided = $qty/$carton ;

    $divided1 = substr($divided, 0, strpos($divided, '.')) ;

    $mod = $qty%$carton ;
  @endphp


  <tr>
    
    <td>{{$element['product']['product']}}</td>



    <td>
      {{$element['product']['sku']}}
    </td>

    <td>
      {{$element['quantity']}}
    </td>

    <td>
      @if ($mod > 0)
        {{$divided1}} Carton And {{$mod}} Box
      @else
        {{$divided}} Carton
      @endif
    </td>

    <td>
      {{$element['date']}}
    </td>

  </tr>
@endforeach
              
                </tbody>
               
              </table>




</div>
            </div>
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </section>
    
 
  </div>
<!-- content part================================ -->


<!--custom delete modal part================================ -->



<!--custom delete modal part================================ -->

@endsection