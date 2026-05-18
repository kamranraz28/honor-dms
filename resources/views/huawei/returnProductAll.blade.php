@extends('layouts.master_huawei')

@section('title')
  {{"E-Warranty Ststem :: Return Product"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('huawei.bc.bc')
    <!-- bc part================================ -->


  
    <!-- Main content -->
    <section class="content-header">
   

      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Returning Prodcut List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Status </th>
                  <th>Distributor </th>
                  <th>Retailer </th>
                  <th>Product Name </th>
                  <th>Product Model </th>
                  <th>IMEI </th>
                  <th>S.No </th>
                  <th>Created Date </th>
                  <th>Updated Date </th>
                </tr>
                </thead>
                <tbody>
@foreach ($preturns as $key => $element)
  
  <tr>
    <td>{{$key + 1}} </td>

    <td>

      @if ($element->status == 1)
        <button class="btn btn-xs btn-danger">RT Processed</button> 
      @elseif ($element->status == 2)
        <button class="btn btn-xs btn-warning"> MD Processed</button>
      @else
        <button class="btn btn-xs btn-primary">ND Processed</button>
      @endif
    </td>
  <td>{{$element->distributor}} </td>
    <td>{{$element->retailer}} </td>
    <td>{{$element->product['name']}} </td>
    <td>{{$element->product['model']}} </td>
    <td>{{$element->imei}} </td>
    <td>{{$element->sno}} </td>
    <td>{{date_format(date_create($element->created_at),"d-M-Y")}}</td>
    <td>{{date_format(date_create($element->updated_at),"d-M-Y")}}</td>



  </tr>


@endforeach
                </tbody>
               
              </table>
<table>
  
  <tbody>
      <tr>
        <td colspan="6">
          {{ $preturns->links() }}
        </td>
      </tr>
  </tbody>

</table>
            </div>
            <div class="clear"></div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
    </section>
    




 
  </div>
<!-- content part================================ -->


<!-- ************************************************* -->
@endsection