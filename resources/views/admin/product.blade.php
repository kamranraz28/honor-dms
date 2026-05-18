@extends('layouts.master_admin')

@section('title')
  {{"Sales Automation Process :: Product OR Model"}}
@endsection


@section('content')

<!-- content cat================================ -->

    <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content-header">
      <div class="row">
        <div class="">

      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Product</h3>
            </div>


    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('admin.product.store') }}" autocomplete="off" enctype="multipart/form-data">
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

  <div class="form-group {{ $errors->has('brand_id') ? 'has-error' : '' }}">
    <label for="brand">Brand :</label>

      <select name="brand_id" id="brand" class="form-control select2" required="required">
        <option value="">Select Brand</option>
        @foreach($brands as $key=>$brand )
          <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
        @endforeach
      </select>

    <span class="text-danger">{{ $errors->first('brand_id') }}</span>
  </div>

</div>


<div class="col-md-12">

  <div class="form-group {{ $errors->has('cat_id') ? 'has-error' : '' }}">
    <label for="category">Category :</label>

      <select name="cat_id" id="category" class="form-control select2" required="required">
        <option value="">Select Category</option>
        @foreach($cats as $key=>$cat )
          <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
        @endforeach
      </select>

    <span class="text-danger">{{ $errors->first('cat_id') }}</span>
  </div>

</div>




        <div class="col-md-12">
          <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter Product" value="{{ old('name') }}" required>
            <span class="text-danger">{{ $errors->first('name') }}</span>
          </div>
        </div>


        <div class="col-md-12">
          <div class="form-group {{ $errors->has('model') ? 'has-error' : '' }}">
            <label for="model">Product Model:</label>
            <input type="text" id="model" name="model" class="form-control" placeholder="Enter Product Model Name" value="{{ old('model') }}" required>
            <span class="text-danger">{{ $errors->first('model') }}</span>
          </div>
        </div>

 <div class="col-md-12">
          <div class="form-group {{ $errors->has('product_code') ? 'has-error' : '' }}">
            <label for="product_code">Product Code:</label>
            <input type="text" id="product_code" name="product_code" class="form-control" placeholder="Enter Product Code " value="{{ old('product_code') }}" required>
            <span class="text-danger">{{ $errors->first('product_code') }}</span>
          </div>
        </div>


         <div class="col-md-12">
          <div class="form-group {{ $errors->has('color') ? 'has-error' : '' }}">
            <label for="color">Color:</label>
            <input type="text" id="color" name="color" class="form-control" placeholder="Enter Product Color" value="{{ old('Color') }}" >
            <span class="text-danger">{{ $errors->first('color') }}</span>
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group {{ $errors->has('dp') ? 'has-error' : '' }}">
            <label for="dp">Distributor Price:</label>
            <input type="text" id="dp" name="dp" class="form-control" placeholder="Enter Distributor Price" value="{{ old('dp') }}" >
            <span class="text-danger">{{ $errors->first('dp') }}</span>
          </div>
        </div>

        <div class="col-md-12">
          <div class="form-group {{ $errors->has('chalan_type') ? 'has-error' : '' }}">
            <label for="chalan_type">Chalan Type:</label>
            <input type="text" id="chalan_type" name="chalan_type" class="form-control" placeholder="Enter Chalan Type" value="{{ old('chalan_type') }}" >
            <span class="text-danger">{{ $errors->first('chalan_type') }}</span>
          </div>
        </div>



      <div class="col-md-12">
        <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
          <label for="details">Details:</label>
          <textarea id="details" name="details" rows="1" class="form-control" placeholder="Input Details">{{ old('details') }}</textarea>
          <span class="text-danger">{{ $errors->first('details') }}</span>
        </div>
      </div>


      <div class="col-md-12">
        <div class="form-group{{ $errors->has('csv_file') ? ' has-error' : '' }}">
            <label for="image">Product Image</label>
            <input id="image" type="file" class="form-control" name="image">

            @if ($errors->has('image'))
                <span class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </span>
            @endif
        </div>

      </div>


      </div>

      <div class="box-footer">
        <button type="submit" class="btn btn-success pull-right">Submit</button>
      </div>

  </form>

<!-- ================================== form area==================================== -->



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
              <table id="example" class="ui celled table" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>

               {{--    <th>IsDontWorry </th> --}}

                  <th>Action</th>

                  {{-- <th>Extension Charge </th>
                  <th>Extension Day</th>
                  <th>Offer Duration </th> --}}
                  <th>Product </th>
                  <th>Model </th>
                  <th>Porduct Code </th>
                  <th>LD Price (BDT) </th>
                  <th>Color </th>
                  <th>Brand </th>
                  <th>Category </th>
                  <th>Chalan Type</th>
                  <th>Details </th>
                  <th>Created Date </th>
                  <th>Image </th>

                </tr>
                </thead>
                <tbody>
@foreach ($products as $key=>$element)


                <tr>

                  <td>{{$key + 1}}</td>

{{-- <td>
  @if ($element["dwstatus"] == true)
       <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'ratailerDWStatusModal'. $element["id"]}}">Active</button>
  @else
    <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'ratailerDWStatusModal'. $element["id"]}}">Inactive</button>
  @endif
</td> --}}


<td>

  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'productUpdateModal'. $element['id']}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'productDeleteModal'. $element['id']}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

</td>

                  {{-- <td>{{$element['dwcharge']}}</td>
                  <td>{{$element['dwday']}}</td>
                  <td>{{$element['dwduration']}}</td> --}}
                  <td>{{$element['name']}}</td>
                  <td>{{$element['model']}}</td>
                  <td>{{$element['product_code']}}</td>
                  <td>{{$element['dp']}}</td>
                  <td>{{$element['color']}}</td>
                  <td>{{$element['brand']['name']}}</td>
                  <td>{{$element['cat']['name']}}</td>
                  <td>{{$element['chalan_type']}}</td>
                <td class="text-justify" style="cursor:pointer;color: black;font-weight: bolder" data-toggle="modal" data-target="#{{'detailsfoModal'. $element['id']}}">

@if ($element['details'])
  {!! substr($element['details'], 0, 40) !!}
@else
  N/A
@endif

                  </td>

                  <td>{{date_format(date_create($element['created_at']),"d-M-Y")}}</td>


<td>

  @if ($element['photo'])
  <a target="_blank" href="{{ asset( 'storage/app/d/nokia/' . $element['photo']) }}">
    View Photo
  </a>
  @else
    No Image File
  @endif

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
<!-- content cat================================ -->




<!--custom hospitaldetailStatusModal modal part================================ -->


@forelse ($products as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'ratailerDWStatusModal'. $element["id"]}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element["name"]}} - {{$element["model"]}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->


@if ($element["dwstatus"] == true)
  <form action="{{ route('admin.product.dontworry.inactive') }}" method="post">
   <h3 class="text-info">Do You Want To Inactive Dont't Wory Module ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element["id"] }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->

    <div class="form-group">
      <button class="form-control btn btn-danger">Inactive</button>
    </div>

  </form>
@else
   <form action="{{ route('admin.product.dontworry.active') }}" method="post">
   <h3 class="text-info">Do You Want To Active Dont't Wory Module ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id" value="{{ $element["id"] }}">
    <!-- <input name="_method" type="hidden" value="delete"> -->

    <div class="form-group">
      <button class="form-control btn btn-primary">Active</button>
    </div>

  </form>
@endif



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
<!--custom hospitaldetailStatusModal modal part================================ -->



<!--custom update modal cat================================ -->


@forelse ($products as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'productUpdateModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['name']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body cat -->
<div style="overflow-x: scroll;overflow-y: scroll; height: 350px;white-space:nowrap; width:100%">
<form action="{{route('admin.product.update')}}" method="post" enctype="multipart/form-data">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element['id'] }}">


<div class="form-group {{ $errors->has('dwcharge') ? 'has-error' : '' }}">
 {{--  <label for="dwcharge">Charge Extension:</label> --}}
  <input  type="hidden" id="dwcharge" name="dwcharge" class="form-control" placeholder="Enter Charge" value="{{ $element['dwcharge'] }}">
  <span class="text-danger">{{ $errors->first('dwcharge') }}</span>
</div>

<div class="form-group {{ $errors->has('dwday') ? 'has-error' : '' }}">
  {{-- <label for="dwday">Day Extension:</label> --}}
  <input type="hidden" id="dwday" name="dwday" class="form-control" placeholder="Enter Day" value="{{ $element['dwday'] }}">
  <span class="text-danger">{{ $errors->first('dwday') }}</span>
</div>

<div class="form-group {{ $errors->has('dwduration') ? 'has-error' : '' }}">
 {{--  <label for="dwduration">Offer Duration (Days):</label> --}}
  <input type="hidden" id="dwduration" name="dwduration" class="form-control" placeholder="Enter Duration" value="{{ $element['dwduration'] }}">
  <span class="text-danger">{{ $errors->first('dwduration') }}</span>
</div>


  <div class="form-group {{ $errors->has('brand_id') ? 'has-error' : '' }}">
    <label for="brand">Brand:</label>

      <select name="brand_id" id="brand" class="form-control">
        <option value="">Select Brand</option>
        @foreach($brands as $brand )
          <option value="{{ $brand['id'] }}" {{ $element['brand_id'] == $brand['id'] ? ' selected="selected"' : '' }}>{{ $brand['name'] }}</option>
        @endforeach
      </select>

    <span class="text-danger">{{ $errors->first('brand_id') }}</span>
  </div>




  <div class="form-group {{ $errors->has('cat_id') ? 'has-error' : '' }}">
    <label for="cat">Category:</label>

      <select name="cat_id" id="cat" class="form-control updateselectcat{{$element['id']}}">
        <option value="">Select Category</option>
        @foreach($cats as $cat )
          <option value="{{ $cat['id'] }}" {{ $element['cat_id'] == $cat['id'] ? ' selected="selected"' : '' }}>{{ $cat['name'] }}</option>
        @endforeach
      </select>

    <span class="text-danger">{{ $errors->first('cat_id') }}</span>
  </div>





<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
  <label for="name">Product Name:</label>
  <input type="text" id="name" name="name" class="form-control" placeholder="Enter Product Name" value="{{ $element['name'] }}">
  <span class="text-danger">{{ $errors->first('name') }}</span>
</div>


<div class="form-group {{ $errors->has('model') ? 'has-error' : '' }}">
  <label for="model">Product Model:</label>
  <input type="text" id="model" name="model" class="form-control" placeholder="Enter Product Model Name" value="{{ $element['model'] }}">
  <span class="text-danger">{{ $errors->first('model') }}</span>
</div>

<div class="form-group {{ $errors->has('product_code') ? 'has-error' : '' }}">
  <label for="model">Product Code:</label>
  <input type="text" id="product_code" name="product_code" class="form-control" placeholder="Enter Product Code " value="{{ $element['product_code'] }}">
  <span class="text-danger">{{ $errors->first('product_code') }}</span>
</div>

<div class="form-group {{ $errors->has('dp') ? 'has-error' : '' }}">
  <label for="model">LD Price (DP):</label>
  <input type="text" id="dp" name="dp" class="form-control" placeholder="Enter LD Price (DP)" value="{{ $element['dp'] }}">
  <span class="text-danger">{{ $errors->first('color') }}</span>
</div>


<div class="form-group {{ $errors->has('color') ? 'has-error' : '' }}">
  <label for="model">Product Color:</label>
  <input type="text" id="color" name="color" class="form-control" placeholder="Enter Product Color" value="{{ $element['color'] }}">
  <span class="text-danger">{{ $errors->first('color') }}</span>
</div>

<div class="form-group {{ $errors->has('chalan_type') ? 'has-error' : '' }}">
  <label for="chalan_type">Chalan Type:</label>
  <input type="text" id="chalan_type" name="chalan_type" class="form-control" placeholder="Enter Chalan Type" value="{{ $element['chalan_type'] }}">
  <span class="text-danger">{{ $errors->first('chalan_type') }}</span>
</div>


<div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
  <label for="details">Details:</label>
  <textarea id="details1" name="details" class="form-control" placeholder="Input Details">{{ $element['details'] }}</textarea>
  <span class="text-danger">{{ $errors->first('details') }}</span>
</div>




  <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
    <label for="image">Image:</label>
    <input type="file" name="image">
    <span class="text-danger">{{ $errors->first('image') }}</span>
  </div>






  <div class="form-group">
    <button class="form-control btn btn-success">Update</button>
  </div>
</form>
</div>
<!-- body cat -->
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>



<script>
  /*$(function () {

    //Initialize Select2 Elements
    $('.updateselectbrand').select2();
    $('.updateselectcat').select2();

  })*/
</script>

@empty
  {{'Data not found'}}
@endforelse
<!--custom update modal cat================================ -->

<!--custom delete modal cat================================ -->


@forelse ($products as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'productDeleteModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['name']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body cat -->




  <form action="{{route('admin.product.delete',$element['id'])}}" method="post">
   <h3 class="text-info">Do You Want To Delete This Data ?</h3>
   <br>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input name="_method" type="hidden" value="delete">

    <div class="form-group">
      <button class="form-control btn btn-danger">Delete</button>
    </div>

  </form>

<!-- body cat -->
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
<!--custom delete modal cat================================ -->



<!--custom detailsfoModal modal part================================ -->


@forelse ($products as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'detailsfoModal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['name']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<p>
  {!! nl2br($element['details']) !!}
</p>

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

<!--custom detailsfoModal modal part================================ -->







@endsection
