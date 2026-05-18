@extends('layouts.master_admin')

@section('title')
	{{"Laravel Project :: Admin Dashboard"}}
@endsection


@section('content')

<!-- content part================================ -->

<div class="content-wrapper">
  <div class="container-fluid">
    
    

      <!-- Section 2-->

      <div class="row">
      
        <div class="col-xl-12 col-sm-12 mb-12">


        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <i class="fa fa-area-chart"></i>
            <a href="{{ route('admin.formWithoutFile') }}">File With Form</a>
          </li>
          <li class="breadcrumb-item active">File With Form Page</li>
        </ol>

          <div class="card1 text-black bg-info1 o-hidden h-100">
            
<!-- ================================== form area==================================== -->
{{-- for for displaying success and errror message --}}
  <form method="POST" action="{{ route('admin.formWithoutFile.store') }}" autocomplete="on">

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
        <div class="form-group {{ $errors->has('firstname') ? 'has-error' : '' }}">
          <label for="firstname">First Name:</label>
          <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter First Name" value="{{ old('firstname') }}">
          <span class="text-danger">{{ $errors->first('firstname') }}</span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('lastname') ? 'has-error' : '' }}">
          <label for="lastname">Last Name:</label>
          <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter Last Name" value="{{ old('lastname') }}">
          <span class="text-danger">{{ $errors->first('lastname') }}</span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
          <label for="email">Email:</label>
          <input type="text" id="email" name="email" class="form-control" placeholder="Enter Email" value="{{ old('email') }}">
          <span class="text-danger">{{ $errors->first('email') }}</span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('mobileno') ? 'has-error' : '' }}">
          <label for="mobileno">Mobile No:</label>
          <input type="text" id="mobileno" name="mobileno" class="form-control" placeholder="Enter Mobile No" value="{{ old('mobileno') }}">
          <span class="text-danger">{{ $errors->first('mobileno') }}</span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password" >
          <span class="text-danger">{{ $errors->first('password') }}</span>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group {{ $errors->has('confirm_password') ? 'has-error' : '' }}">
          <label for="confirm_password">Confirm Password:</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Enter Confirm Passowrd">
          <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="form-group {{ $errors->has('details') ? 'has-error' : '' }}">
          <label for="details">Details:</label>
          <textarea name="details" id="details" class="form-control" placeholder="Enter Details">{{ old('details') }}</textarea>
          <span class="text-danger">{{ $errors->first('details') }}</span>
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

 





<!-- ======= last 2 div========== -->
  </div>
</div>
    <!-- /.container-fluid-->

<!-- content part================================ -->
@endsection