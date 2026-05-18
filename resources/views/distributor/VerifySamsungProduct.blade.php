<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>@yield('title', '"Sales Automation Process :: Product Verification"')</title>
@if (@$_SESSION["favicon"] )
 <link rel="shortcut icon" type="image/x-icon" href="{{ asset( 'storage/app/' . $_SESSION['favicon']) }}">
@else
  <link rel="shortcut icon" type="image/x-icon" href="{{ asset('resources/assets/dms/dist/img/favicon.ico') }}">
@endif
    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

<style type="text/css">
  body{ 

      background-image: url(../../resources/assets/bgimages/samsung.jpg);
      background-attachment: fixed;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      width: 100%;
      height: 100%;
    }


</style>
  </head>



  <body>
  

<div class="container">
  <div class="row">

 <div style="margin: 100px auto">
   
   <div class="col-md-offset-2 col-md-8">
     
  

    <div class="panel-group">
      <div class="panel panel-default">
        <!-- <div class="panel-heading">Panel Header</div> -->
        <div class="panel-body">

          <p class="text-center text-danger" style="font-weight: bolder;font-size: 16px">Verify Your Original Accessories </p>

<br>

    <!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form class="form-horizontal" method="POST" action="{{ route('guest.verifySamsungProduct.store') }}" autocomplete="on" enctype="multipart/form-data">
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

    @if(Session::has('error'))
      

      <div class="alert alert-danger alert-dismissible fade in">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>{{Session::get('error')}}</strong> 
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
          <div class="form-group {{ $errors->has('sno') ? 'has-error' : '' }}">
            <label for="sno">Prodcut Serial Number:</label>
            <input type="text" id="sno" name="sno" class="form-control" placeholder="Input Your Prodcut Serial Number" value="{{ old('sno') }}" required="required">
            <span class="text-danger">{{ $errors->first('sno') }}</span>
          </div>
        </div>


        <div class="col-md-12">
          <div class="form-group">
            <button type="submit" class="btn btn-success form-control" style="margin-bottom: 0px;">Check</button>
          </div>
        </div>
      </div>


  </form>

<!-- ================================== form area==================================== -->
@if ($dataCount > 0)
<p style="visibility: hidden;">hidden </p>
<hr>


<div class="col-md-4">
   
  @if ($data->photo)
  <a target="_blank" href="{{ asset( 'storage/app/' . $data->photo) }}">
    <img class="img-responsive" width="80%" style="margin-top: 60px;" src="{{ asset( 'storage/app/' . $data->photo) }}"> 
  </a>
  @else
    <img class="img-responsive" src="../../resources/assets/bgimages/product.jpg" width="80%">
  @endif




</div>

<div class="col-md-8">


  <table class="table table-striped">
    <thead>
      <tr>
        <th>{{$data->product}}</th>
        <th>{{$data->model}}</th>
      </tr>
    </thead>

    <tbody>
@foreach ($data->specification as $key=>$element)
  @if ($key < 3)
    <tr>
      <td>
        <span style="padding-right:2px;" class="glyphicon glyphicon-list-alt"></span>
        <p style="font-weight: bolder;">{{$element['name']}}</p>
      </td>
      <td>
        <p>{{$element['specificationdetails']}}</p>
      </td>
    </tr>
  @endif
@endforeach
      
    </tbody>
  </table>


</div>

<div class="col-md-12" style="overflow-x: hidden;overflow-y: scroll; height: 300px;white-space:nowrap; width:100%">
  

  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#Specification">Specification</a></li>
    <li><a data-toggle="tab" href="#Details">Details</a></li>
  </ul>

  <div class="tab-content">

    <div id="Specification" class="tab-pane fade in active">
      <table class="table table-striped">
        @foreach ($data->specification as $key=>$element)

          <tr>
            <td>
              <span style="padding-right:2px;" class="glyphicon glyphicon-list-alt"></span>
              <p style="font-weight: bolder;">{{$element['name']}}</p>
            </td>
            <td>
              <p>{{$element['specificationdetails']}}</p>
            </td>
          </tr>

@endforeach
      </table>
    </div>
    <div id="Details" class="tab-pane fade">
      
      @if ($data->details)
        <p class="text-warnig">{{$data->details}}</p>
      @else
        <p class="text-danger">No details found</p>
      @endif
      
    </div>
  </div>

</div>



@endif
        </div>
      </div>
    </div>




























  </div>

 </div>



  </div>
</div>







@php
  Session::forget(['sno','error']);
@endphp

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  </body>
</html>