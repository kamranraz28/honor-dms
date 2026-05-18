@extends('layouts.master_sales1')

@section('title')
  {{"DMS :: Secondary Sales"}}
@endsection


@section('content')

<!-- content part================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Return Products
        <small>Control panel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('sales.dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"></i> REPORTS</a></li>
        <li class="active"><a href="{{ route('sales.preturnproduct') }}">Return Products</a></li>
      </ol>
    </section>

 
    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Search Return Products</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
             <form class="form-horizontal" method="POST" action="{{ route('sales.preturnproduct.store') }}" autocomplete="on" enctype="multipart/form-data">
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
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
{{-- for for displaying success and errror message --}}
                <div class="box-body">
                <div class="form-group">
                  <label class="col-sm-2 control-label">Date</label>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="fdate" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker"  required="required" autocomplete="off">
                    </div>
                  </div>

                  <div class="col-sm-5">
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input name="todate" placeholder="DD/MM/YYYY" type="text" class="form-control pull-right" id="datepicker1"  required="required" autocomplete="off">
                    </div>
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
              <h3 class="box-title">Stock List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>

                <tr>
                  <th colspan="7"> Date: {{$from_date}} to {{$to_date}}</th>
                </tr>
                  
                <tr>
                  <th>Region Name</th>
                  <th>Territory Name</th>
                  <th>Distributor Name</th>
                  <th>Product Name</th>
                  <th>Product SKU</th>
                  <th>Product Qantity(Boxs)</th>
                  <th>Product Price</th>
                  <th>Date</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
@foreach ($preturnproducts as $element)
  
  <tr>
    <td>{{$element['region']['region']}}</td>
    <td>{{$element['territory']['territory']}}</td>
    <td>{{$element['distributor']['distributor']}} </td>
    <td>{{$element['product']['product']}} </td>
    <td>{{$element['product']['sku']}} </td>
    <td>{{round($element['product_qty'], 2) }}</td>
    <td>{{round($element['product_price'], 2) }}</td>
    <td>{{$element['date']}}</td>



                  <td>

  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'preturnproductsUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'preturnproductsDeleteModal'. $element['id']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

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


@forelse ($preturnproducts as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'preturnproductsUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['product_qty']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('sales.preturnproduct.update')}}" method="post">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">

<div class="form-group {{ $errors->has('product_qty') ? 'has-error' : '' }}">
  <label for="product_qty">Product Quantity:</label>
  <input type="number" id="product_qty" name="product_qty" class="form-control" placeholder="Enter Product Quantity" value="{{ $element['product_qty'] }}">
  <span class="text-danger">{{ $errors->first('product_qty') }}</span>
</div>

<div class="form-group {{ $errors->has('product_price') ? 'has-error' : '' }}">
  <label for="product_price">Product Price:</label>
  <input type="number" id="product_qty" name="product_price" class="form-control" placeholder="Enter Product Price" value="{{ $element['product_price'] }}">
  <span class="text-danger">{{ $errors->first('product_price') }}</span>
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


@forelse ($preturnproducts as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'preturnproductsDeleteModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['product_qty']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('sales.preturnproduct.delete',$element['id'])}}" method="post">
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
        </div>
      </div>
    </div>
@empty
  {{'Data not found'}}
@endforelse
<!--custom delete modal part================================ -->








@endsection