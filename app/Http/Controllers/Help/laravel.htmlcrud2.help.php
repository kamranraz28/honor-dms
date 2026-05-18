@extends('layouts.master_admin')

@section('title')
  {{"E-Warranty Ststem :: Specification"}}
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
    <section class="content-header">
      <div class="row">
        <div class="">
      
      <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title">Add Specification</h3>
            </div>
    

    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('admin.specification.store') }}" autocomplete="on" enctype="multipart/form-data">
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
        
  <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
    <label for="product">Product :</label>

      <select name="product_id" id="product" class="form-control" required="required">
        <option value="">Select Product</option>
        @foreach($products as $key=>$product )
          <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('product_id') }}</span>
  </div>
       
</div>




        <div class="col-md-12">
          <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
            <label for="name">Specification:</label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Enter Specification Name" value="{{ old('name') }}">
            <span class="text-danger">{{ $errors->first('name') }}</span>
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
        <div class="form-group {{ $errors->has('specificationdetails') ? 'has-error' : '' }}">
          <label for="specificationdetails">Specification Details:</label>
          <textarea id="specificationdetails" name="specificationdetails" rows="1" class="form-control" placeholder="Input Specification Details">{{ old('specificationdetails') }}</textarea>
          <span class="text-danger">{{ $errors->first('specificationdetails') }}</span>
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
              <h3 class="box-title">Specification List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Product </th>
                  <th>Specification </th>
                  <th>Specification Details </th>
                  <th>Details </th>
                  <th>Created Date </th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
@foreach ($specifications as $key=>$element)
     
     
                <tr>
                  
                  <td>{{$key + 1}}</td>
                  <td>{{$element->product['name']}}</td>
                  <td>{{$element->name}}</td>
                  
                  <td class="text-justify" style="cursor:pointer;color: black;font-weight: bolder" data-toggle="modal" data-target="#{{'specificdetailsfoModal'. $element->id}}">
                    
@if ($element->specificationdetails)
  {!! substr($element->specificationdetails, 0, 40) !!}
@else
  N/A
@endif

                  </td>
                  
                  <td class="text-justify" style="cursor:pointer;color: black;font-weight: bolder" data-toggle="modal" data-target="#{{'detailsfoModal'. $element->id}}">
                    
@if ($element->details)
  {!! substr($element->details, 0, 40) !!}
@else
  N/A
@endif

                  </td>


                  <td>{{date_format(date_create($element->created_at),"d-M-Y")}}</td>
                  
                  <td>

  <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#{{'specificationUpdateModal'. $element->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

  <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#{{'specificationDeleteModal'. $element->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>

                  </td>

                </tr>
@endforeach 
                </tbody>
               
              </table>


<table>
  
  <tbody>
      <tr>
        <td colspan="4">
          {{ $specifications->links() }}
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


@forelse ($specifications as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'specificationUpdateModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->name}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<form action="{{route('admin.specification.update')}}" method="post">
  <h3 class="text-info">Do You Want To Update This Data ?</h3>
   <br>

  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <input name="_method" type="hidden" value="put">
  <input type="hidden" name="id" value="{{ $element->id }}">

  <div class="form-group {{ $errors->has('product_id') ? 'has-error' : '' }}">
    <label for="product">Product:</label>

      <select name="product_id" id="product" class="form-control">
        <option value="">Select Product</option>
        @foreach($products as $product )
          <option value="{{ $product['id'] }}" {{ $element['product_id'] == $product['id'] ? ' selected="selected"' : '' }}>{{ $product['name'] }}</option>
        @endforeach
      </select>          

    <span class="text-danger">{{ $errors->first('product_id') }}</span>
  </div>


<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
  <label for="name">Specification:</label>
  <input type="text" id="name" name="name" class="form-control" placeholder="Enter Product Name" value="{{ $element->name }}">
  <span class="text-danger">{{ $errors->first('name') }}</span>
</div>


<div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
  <label for="details">Details:</label>
  <textarea id="details1" name="details" class="form-control" placeholder="Input Details" required="required">{{ $element->details }}</textarea>
  <span class="text-danger">{{ $errors->first('details') }}</span>
</div>

<div class="form-group {{ $errors->has('specificationdetails') ? 'has-error' : '' }}">
  <label for="specificationdetails">Specification Details:</label>
  <textarea id="details1" name="specificationdetails" class="form-control" placeholder="Input Specification Details" required="required">{{ $element->specificationdetails }}</textarea>
  <span class="text-danger">{{ $errors->first('specificationdetails') }}</span>
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


@forelse ($specifications as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'specificationDeleteModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->name}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->




  <form action="{{route('admin.specification.delete',$element->id)}}" method="post">
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






<!--custom specificdetailsfoModal modal part================================ -->


@forelse ($specifications as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'specificdetailsfoModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->name}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<p>
  {!! nl2br($element->specificationdetails) !!}
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

<!--custom specificdetailsfoModal modal part================================ -->





<!--custom detailsfoModal modal part================================ -->


@forelse ($specifications as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'detailsfoModal'. $element->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element->name}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
<!-- body part -->

<p>
  {!! nl2br($element->details) !!}
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