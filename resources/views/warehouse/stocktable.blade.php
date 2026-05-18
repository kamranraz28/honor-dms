@extends('layouts.master_warehouse')

@section('title')
  {{"Sales Automation Process :: Stock"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
      @include('warehouse.bc.bc')
    <!-- bc part================================ -->


  
    <!-- Main content -->
    <section class="content-header">
      
    


      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Stock List</h3>
              <!-- <a target="_blank" class="btn btn-sm btn-info pull-right" href="{{route('warehouse.stock.excel')}}">Export To Excel</a> -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="ui celled table" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th> Product </th>
                  <th> Model </th>
                  <th> Product Code </th>
                  <th> Brand </th>
                  <th> IMEI 1 </th>
                  <th> IMEI 2 </th>
                  <th> W-Period </th>
                  <th> Created Date </th>
                  <!-- <th> Action </th> -->
                </tr>
                </thead>
                <tbody>
@foreach ($stocks as $key=>$element)
     
     
                <tr>
                  
                  <td>{{$key + 1}}</td>
                  <td>{{$element->product['name']}}</td>
                  <td>{{$element->product['model']}}</td>
                  <td>{{$element->product['product_code']}}</td>
                  <td>{{$element->brand['name']}}</td>
                  <td>{{$element->sno}}</td>
                  <td>{{$element->imei}}</td>
                  <td>{{$element->wperiod}}</td>
                  <td>{{date_format(date_create($element->created_at),"d-M-Y")}}</td>
                  
                  <!-- <td>

  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'stockUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'stockDeleteModal'. $element->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

                  </td> -->

                </tr>
@endforeach 
                </tbody>
               
              </table>


<table>
  
  <tbody>
      <tr>
        <td colspan="8">
          {{ $stocks->links() }}
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


<!--custom update modal part================================ -->


@forelse ($stocks as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'stockUpdateModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->imei}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('admin.stock.update')}}" method="post">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element->id }}">


{{--   <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
    <label for="product">Product:</label>

      <select name="product_id" id="product" class="form-control">
        <option value="">Select Product</option>
        @foreach($products as $product )
          <option value="{{ $product['id'] }}" {{ $element['product_id'] == $product['id'] ? ' selected="selected"' : '' }}>{{ $product['name'] }}</option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('product_id') }}</span>
  </div> --}}


<div class="form-group {{ $errors->has('imei') ? 'has-error' : '' }}">
  <label for="imei">IMEI:</label>
  <input type="text" id="imei" name="imei" class="form-control" placeholder="Enter IMEI" value="{{ $element->imei }}">
  <span class="text-danger">{{ $errors->first('imei') }}</span>
</div>


<div class="form-group {{ $errors->has('sno') ? 'has-error' : '' }}">
  <label for="sno">Serial No:</label>
  <input type="text" id="sno" name="sno" class="form-control" placeholder="Enter Serial No No" value="{{ $element->sno }}">
  <span class="text-danger">{{ $errors->first('sno') }}</span>
</div>



<div class="form-group {{ $errors->has('wperiod') ? 'has-error' : '' }}">
  <label for="wperiod">Warranty Period:</label>
  <input type="number" id="wperiod" name="wperiod" class="form-control" placeholder="Warranty Period" value="{{ $element->wperiod }}">
  <span class="text-danger">{{ $errors->first('wperiod') }}</span>
</div>

  <div class="form-group">
    <button class="form-control btn btn-success">Update</button>
  </div>
</form>

<!-- body part -->
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
@empty
  {{'Data not found'}}
@endforelse
<!--custom update modal part================================ -->

<!--custom delete modal part================================ -->


@forelse ($stocks as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'stockDeleteModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">IMEI: {{$element->imei}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('warehouse.stock.delete',$element->id)}}" method="post">
   <h3 class="text-info">Do You Want To Delete This Data ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input name="_method" type="hidden" value="delete">
    
    <div class="form-group">
      <button class="form-control btn btn-danger">Delete</button>
    </div>

  </form>

<!-- body part -->
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
      
@empty
  {{'Data not found'}}
@endforelse
<!--custom delete modal part================================ -->




@endsection




