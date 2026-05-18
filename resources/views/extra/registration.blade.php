
  @extends('layouts.extra')

  @section('title')
    {{"Laravel Project :: Login Page"}}
  @endsection

  @section('content')


  <div class="container">
    <div class="card card-register mx-auto mt-5">
      <div class="card-header">Register an Account</div>
      <div class="card-body">


{{-- for for displaying success and errror message --}}
  <form method="POST" action="{{ route('auth.registration.store') }}" autocomplete="on">

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
      <button class="btn btn-success">Submit</button>
    </div>

  </form>




        <div class="text-center">
          <a class="d-block small mt-3" href="{{ route('auth.login') }}">Login Page</a>
          <!-- <a class="d-block small" href="forgot-password.html">Forgot Password?</a> -->
        </div>
      </div>
    </div>
  </div>
    
  @endsection

