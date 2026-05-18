@extends('layouts.master_admin')

@section('title')
  {{"Laravel Project :: Admin Dashboard"}}
@endsection


@section('content')

<!-- content part================================ -->

<div class="content-wrapper">
  <div class="container-fluid">
    
    


<!-- Section 1 start-->

    <div class="row">
      
      <div class="col-xl-12 col-sm-12 mb-12">


        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <i class="fa fa-area-chart"></i>
            <a href="{{ route('admin.formWithoutFile') }}">File With Form</a>
          </li>
          <li class="breadcrumb-item active">File With Form Page</li>
        </ol>


            
<!-- ================================== form area==================================== -->
 <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Form With File
        </div>
        <div class="card-body">
          <div class="formWithFileData">
    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form method="POST" action="{{ route('admin.formWithFile.store') }}" autocomplete="on" enctype="multipart/form-data">

    @if(count($errors))
      <div class="alert alert-danger">
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
      <div class="alert alert-info">
        {{Session::get('success')}}
      </div>
    @endif

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
{{-- for for displaying success and errror message --}}



    <div class="row">
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
          <label for="name">Product Name:</label>
          <input type="text" id="name" name="name" class="form-control" placeholder="Enter Product Name" value="{{ old('name') }}">
          <span class="text-danger">{{ $errors->first('name') }}</span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
          <label for="title">Product Title:</label>
          <input type="text" id="title" name="title" class="form-control" placeholder="Enter Product Title" value="{{ old('title') }}">
          <span class="text-danger">{{ $errors->first('title') }}</span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
          <label for="price">Product Price:</label>
          <input type="text" id="price" name="price" class="form-control" placeholder="Product Price" value="{{ old('price') }}">
          <span class="text-danger">{{ $errors->first('price') }}</span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
          <label for="description">Product Description:</label>
          <input type="text" id="description" name="description" class="form-control" placeholder="Enter Product Description" value="{{ old('description') }}">
          <span class="text-danger">{{ $errors->first('description') }}</span>
        </div>
      </div>
    </div>



    
    <!-- <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <select id="maxOption2" class="selectpicker show-menu-arrow form-control" multiple data-max-options="4">
              <option>chicken</option>
              <option>turkey</option>
              <option>goose</option>
              <option>goose</option>
              <option>goose</option>
              <option disabled>duck</option>
            </select>
            <span class="text-danger"></span>
          </div>
        </div>
      </div>  -->  
     


    


    <div class="row">
      <div class="col-md-12">
        
        <div class="form-group {{ $errors->has('images') ? 'has-error' : '' }}">
          <label for="images">Product Images:</label>
          <input type="file" name="images[]" multiple />
          <span class="text-danger">{{ $errors->first('images') }}</span>
        </div>

      </div>
    </div>



    <div class="form-group">
      <button class="form-control btn btn-success">Submit</button>
    </div>

  </form>

<!-- ================================== form area==================================== -->




          </div>



        </div>
      </div>
<!-- ================================== form area==================================== -->


  <!-- div for row and col- -->
        
        </div>
     </div>    
    
<!-- div for row and col- -->

<!-- Section 1 end-->


<!-- Section 2 start-->

    <div class="row">
      
      <div class="col-xl-12 col-sm-12 mb-12">



            
<!-- ================================== form area==================================== -->
 <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Data Table Example
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Title</th>
                  <th>Price</th>
                  <th>Descripttion</th>
                  <th>Images</th>
                  <th>Action</th>
                </tr>
              </thead>
              <!-- <tfoot>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Title</th>
                  <th>Price</th>
                  <th>Descripttion</th>
                  <th>Images</th>
                  <th>Action</th>
                </tr>
              </tfoot> -->
              <tbody>
                @forelse ($products as $key => $element)
                  <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$element['name']}}</td>
                    <td>{{$element['title']}}</td>
                    <td>{{$element['price']}}</td>
                    <td>
<p class="text-justify" style="cursor:pointer" 
  data-toggle="modal" data-target="#{{'productsmodal'. $element['id']}}">
  {{substr($element['description'], 0, 50) }}
</p>
                    
                    </td>
                    <td>
  
                    @foreach ($element['productsphoto'] as $photo)

<a href="{{ asset( 'storage/app/' . $photo['filename']) }}" target="_blank">
  <img class="img" 
  src="{{ asset( 'storage/app/' . $photo['filename']) }}" 
  alt="{{ $photo['filename'] }}" 
    style="width:40px;height:20px"/>
</a>

                      
                      @endforeach
                    </td>
                    
                    <td>
                      <a href="" class="text-success">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                      </a>
                      <a href="" class="text-danger">
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                      </a>
                    </td>
                  </tr>
                @empty
                <tr>
                  <td>{{0}}</td>
                    <td>{{'Not Available'}}</td>
                    <td>{{'Not Available'}}</td>
                    <td>{{'Not Available'}}</td>
                    <td>{{'Not Available'}}</td>
                    <td>{{'Not Available'}}</td>
                    <td>{{'Not Available'}}</td>
                </tr>
                  
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
<!-- ================================== form area==================================== -->


  <!-- div for row and col- -->
        
        </div>
     </div> 
<!-- div for row and col- -->

<!-- Section 2 end-->


<!-- ======= last 2 div========== -->
  </div>
</div>
    <!-- /.container-fluid-->
<!--custom modal part================================ -->


@forelse ($products as $key => $element)
  <!-- Modal -->
  <div class="modal fade" id="{{'productsmodal'. $element['id']}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{$element['title']}}</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>

          <div class="modal-body">
            {{$element['description']}}
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
<!--custom modal part================================ -->
<!-- content part================================ -->
@endsection




