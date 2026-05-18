@extends('layouts.master_sales1')

@section('title')
  {{"DMS :: Add Product"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Product
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('sales.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> SALES</a></li>
        <li class="active"><a href="{{ route('sales.product') }}">Add Product</a></li>
      </ol>
    </section>

  

    <!-- Main content -->
    <section class="content-header">
      <div class="row">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Product</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('sales.product.store') }}" autocomplete="on" enctype="multipart/form-data">
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
        <div class="form-group {{ $errors->has('product_category_id') ? 'has-error' : '' }}">
          <label for="productCategory">Product Category:</label>

            <select name="product_category_id" id="productCategory" class="form-control">
              <option value="">Select Product Category</option>
              @foreach($productCategories as $productCategory )
                <option value="{{ $productCategory['id'] }}">{{ $productCategory['category'] }}</option>
              @endforeach
            </select>          

          <span class="text-danger">{{ $errors->first('product_category_id') }}</span>
        </div>
      </div>


      <!-- <div class="col-md-12">
        <div class="form-group {{-- $errors->has('product') ? 'has-error' : '' --}}">
          <label for="product">Product:</label>
      
            <select name="product" id="product" class="form-control">
              <option value="">Select Product</option>
            </select>          
      
          <span class="text-danger">{{-- $errors->first('product') --}}</span>
        </div>
      </div> -->

      <div class="col-md-12">
        <div class="form-group {{ $errors->has('product') ? 'has-error' : '' }}">
          <label for="product">Product:</label>
          <input type="text" id="product" name="product" class="form-control" placeholder="Enter Product" value="{{ old('product') }}">
          <span class="text-danger">{{ $errors->first('product') }}</span>
        </div>
      </div>
                  

      <div class="col-md-12">
        <div class="form-group {{ $errors->has('sku') ? 'has-error' : '' }}">
          <label for="sku">Product SKU:</label>
          <input type="text" id="sku" name="sku" class="form-control" placeholder="Enter Product SKU" value="{{ old('sku') }}">
          <span class="text-danger">{{ $errors->first('sku') }}</span>
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
          <label for="price">Unit Price:</label>
          <input type="number" id="price" name="price" class="form-control" placeholder="Enter Unit Price" value="{{ old('price') }}" step=any>
          <span class="text-danger">{{ $errors->first('price') }}</span>
        </div>
      </div>

      <div class="col-md-12">
        <div class="form-group {{ $errors->has('carton_count') ? 'has-error' : '' }}">
          <label for="carton_count">Carton Count:</label>
          <input type="number" id="carton_count" name="carton_count" class="form-control" placeholder="Enter Carton Count" value="{{ old('carton_count') }}">
          <span class="text-danger">{{ $errors->first('carton_count') }}</span>
        </div>
      </div>

      <div class="col-md-12">
        
        <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
          <label for="image">Photo:</label>
          <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg"/>
          <span class="text-danger">{{ $errors->first('image') }}</span>
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
                  <th>Product SKU</th>
                  <th>Unit Price</th>
                  <th>Carton Count</th>
                  <th>Product Image</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
@foreach ($products as $element)
                <tr>
                  <td>{{$element['product_category']['category']}}</td>
                  <td>{{$element['product']}}</td>
                  <td>{{$element['sku']}}</td>
                  <td>{{$element['price']}}</td>
                  <td>{{$element['carton_count']}}</td>
                  <td> 

@if ($element['photo'])
<a target="_blank" href="{{ asset( 'storage/app/' . $element['photo']) }}">
  <img width="30px" height="20px" src="{{ asset( 'storage/app/' . $element['photo']) }}"> 
</a>
@else
  No Image File
@endif
                    

                  </td>
<td>

    <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'productUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'productDeleteModal'. $element['id']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>


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


@forelse ($products as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'productUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['product']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('sales.product.update')}}" method="post" autocomplete="on" enctype="multipart/form-data">
  <h5 class="text-info">Do You Want To Update This Data ?</h5>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">


<div class="form-group {{ $errors->has('category') ? 'has-error' : '' }}">
  <label for="productCategory">ProductCategory:</label>

    <select name="product_category_id" id="productCategory" class="form-control">
      <option value="">Select ProductCategory</option>
      @foreach($productCategories as $productCategory )
        <option value="{{ $productCategory['id'] }}" {{ $element['product_category_id'] == $productCategory['id'] ? ' selected="selected"' : '' }}>{{ $productCategory['category'] }}</option>
      @endforeach
    </select>          

  <span class="text-danger">{{ $errors->first('productCategory') }}</span>
</div>


<div class="form-group {{ $errors->has('product') ? 'has-error' : '' }}">
  <label for="product">Product:</label>
  <input type="text" id="product" name="product" class="form-control" placeholder="Enter Product Name" value="{{ $element['product'] }}">
  <span class="text-danger">{{ $errors->first('product') }}</span>
</div>


<div class="form-group {{ $errors->has('sku') ? 'has-error' : '' }}">
  <label for="sku">Product SKU:</label>
  <input type="text" id="sku" name="sku" class="form-control" placeholder="Enter Product SKU" value="{{ $element['sku'] }}">
  <span class="text-danger">{{ $errors->first('sku') }}</span>
</div>


<div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
  <label for="price">Unit Price:</label>
  <input type="number" id="price" name="price" step=any class="form-control" placeholder="Enter Unit Price" value="{{ $element['price'] }}">
  <span class="text-danger">{{ $errors->first('price') }}</span>
</div>



<div class="form-group {{ $errors->has('carton_count') ? 'has-error' : '' }}">
  <label for="carton_count">Carton Count:</label>
  <input type="number" id="carton_count" name="carton_count" class="form-control" placeholder="Enter Carton Count" value="{{ $element['carton_count'] }}">
  <span class="text-danger">{{ $errors->first('carton_count') }}</span>
</div>




<div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
  <label for="image">Photo:</label>
  <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg"/>
  <span class="text-danger">{{ $errors->first('image') }}</span>
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


@forelse ($products as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'productDeleteModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['product']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('sales.product.delete',$element['id'])}}" method="post">
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







<script type="text/javascript">
  
// jquery for hospital =========

  /*$('#productCategory').on('change', function(e){
    var product_category_id = e.target.value;
    console.log(product_category_id);
    var route = "{{--route('sales.productSelectBoxOnCategoryWithAjax')--}}/"+product_category_id;
    $.get(route, function(data) {
      console.log(data);
      $('#product').empty();
      
      $.each(data, function(index,data){
        $('#product').append('<option value="' + data.product + '">' + data.product + '</option>');
      });
    });
  });*/


  // data table show result=================

</script>



@endsection