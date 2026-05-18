@extends('layouts.master_admin')

@section('title')
  {{"DMS :: Finished Goods"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Finished Goods
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> FACTORY</a></li>
        <li class="active"><a href="{{ route('admin.quantity') }}">Finished Goods</a></li>
      </ol>
    </section>


    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Finished Goods</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
  <form class="form-horizontal" method="POST" action="{{ route('admin.quantity.store') }}" autocomplete="on" enctype="multipart/form-data">

<div class="box-body">
    @if(count($errors))
      <div class="alert alert-danger alert-dismissible">
        <strong>Whoops!</strong> There were some problems with your input.
        <br/>
        <ul>
          @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @if(Session::has('success'))
      

      <div class="alert alert-success alert-dismissible fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> {{Session::get('success')}}
      </div>

    @endif
</div>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
{{-- for for displaying success and errror message --}}



                <div class="box-body">

<div class="col-md-12">
  <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
    <label for="dob">Date :</label>
    <input type="text" id="datepicker" name="date" class="form-control" placeholder="Enter Date" value="{{ old('date') }}">
    <span class="text-danger">{{ $errors->first('date') }}</span>
  </div>
</div>



<div class="col-md-12">
  <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
    <label for="category">productCategory:</label>

      <select name="product_category_id" id="productCategory" class="form-control">
        <option value="">Select Product Category</option>
        @foreach($productCategories as $productCategory )
          <option value="{{ $productCategory['id'] }}">{{ $productCategory['category'] }}</option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('category') }}</span>
  </div>
</div>

<div class="col-md-12">
  <div class="form-group {{ $errors->has('product') ? 'has-error' : '' }}">
    <label for="product">Product:</label>

      <select name="product_id" id="product" class="form-control">
        <option value="">Select Product</option>
      </select>          

    <span class="text-danger">{{ $errors->first('product') }}</span>
  </div>
</div>


<div class="col-md-12">
  <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
    <label for="price">Product Quantity:</label>
    <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter Product Quantity" value="{{ old('quantity') }}">
    <span class="text-danger">{{ $errors->first('quantity') }}</span>
  </div>
</div>


                </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
        </div>
      </div>
      <div class="row">
            <div class="box box-warning">
            <div class="box-header">
              <h3 class="box-title">Product List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>Product Category</th>
                  <th>Product</th>
                  <th>SKU</th>
                  <th>Quantity</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>

@foreach ($quantities as $element)
              <tr>
                <td>{{$element['product_category']['category']}}</td>
                <td>{{$element['product']['product']}}</td>
                <td>{{$element['product']['sku']}}</td>
                <td>{{$element['quantity']}}</td>
                <td>{{$element['date']}}</td>



        <td>
  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'quantityUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'quantityDeleteModal'. $element['id']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

        </td>
              </tr>
@endforeach       
                
               
              
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


@forelse ($quantities as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'quantityUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['quantity']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('admin.quantity.update')}}" method="post" autocomplete="on" enctype="multipart/form-data">
  <h5 class="text-info">Do You Want To Update This Data ?</h5>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">




<!-- <div class="form-group {{-- $errors->has('quantity') ? 'has-error' : '' --}}">
  <label for="quantity">Distributor:</label>
  <input type="text" id="quantity" name="quantity" class="form-control" placeholder="Enter Distributor Name" value="{{-- $element['quantity'] --}}">
  <span class="text-danger">{{-- $errors->first('quantity') --}}</span>
</div> -->



<div class="col-md-12">
  <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
    <label for="dob">Date:</label>
    <input type="text" id="datepicker{{$element['id']}}" name="date" class="form-control" placeholder="Enter Date" value="{{$element['date']}}">
    <span class="text-danger">{{ $errors->first('date') }}</span>
  </div>
</div>

<!-- jquery area =========== -->

<script>
  $(function () {
    $('#datepicker{{$element['id']}}').datepicker({
      format: 'dd/mm/yyyy',
      autoclose: true
    });
  })
</script>
<!-- jquery area =========== -->





<div class="col-md-12">
  <div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
    <label for="category">productCategory:</label>

      <select name="product_category_id" id="productCategory{{$element['id']}}" class="form-control">
        <option value="">Select Product Category</option>
        @foreach($productCategories as $productCategory )
          <option value="{{ $productCategory['id'] }}" {{ $element['product_category_id'] == $productCategory['id'] ? ' selected="selected"' : '' }}>{{ $productCategory['category'] }}</option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('category') }}</span>
  </div>
</div>




<div class="col-md-12">
  <div class="form-group {{ $errors->has('product') ? 'has-error' : '' }}">
    <label for="product">Product:</label>

      <select name="product_id" id="product{{$element['id']}}" class="form-control">
@foreach ($products as $product)
  <option value="{{ $product['id'] }}" {{ $element['product_id'] == $product['id'] ? ' selected="selected"' : '' }}>{{ $product['product'] . ' (' .$product['sku'] . ')' }}</option>
@endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('product') }}</span>
  </div>
</div>

<!-- jquery area========================== -->

<script type="text/javascript">
  
  $('#productCategory{{$element['id']}}').on('change', function(e){
    var product_category_id = e.target.value;
    console.log(product_category_id);
    var route = "{{route('admin.productSelectBoxOnCategoryWithAjax')}}/"+product_category_id;
    $.get(route, function(data) {
      console.log(data);
      $('#product{{$element['id']}}').empty();
      
      $.each(data, function(index,data){
        $('#product{{$element['id']}}').append('<option value="' + data.id + '">' + data.product +' ' +'(' + data.sku + ')' + '</option>');
      });
    });
  });

</script>
<!-- jquery area========================== -->


<div class="col-md-12">
  <div class="form-group {{ $errors->has('quantity') ? 'has-error' : '' }}">
    <label for="price">Product Quantity:</label>
    <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Enter Product Quantity" value="{{ $element['quantity'] }}">
    <span class="text-danger">{{ $errors->first('quantity') }}</span>
  </div>
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


@forelse ($quantities as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'quantityDeleteModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['quantity']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('admin.quantity.delete',$element['id'])}}" method="post">
   <h5 class="text-info">Do You Want To Delete This Data ?</h5>
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
        </div>
      </div>
    </div>
@empty
  {{'Data not found'}}
@endforelse
<!--custom delete modal part================================ -->



<!-- jquery area========================== -->

<script type="text/javascript">
  
  $('#productCategory').on('change', function(e){
    var product_category_id = e.target.value;
    console.log(product_category_id);
    var route = "{{route('admin.productSelectBoxOnCategoryWithAjax')}}/"+product_category_id;
    $.get(route, function(data) {
      console.log(data);
      $('#product').empty();
      
      $.each(data, function(index,data){
        $('#product').append('<option value="' + data.id + '">' + data.product +' ' +'(' + data.sku + ')' + '</option>');
      });
    });
  });

</script>
<!-- jquery area========================== -->
@endsection