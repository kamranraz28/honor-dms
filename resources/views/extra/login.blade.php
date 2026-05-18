@extends('layouts.extra')

@section('title')
    {{"Sales Automation Process :: Login"}}
@endsection

@section('content')

{{-- --- HYPER-ADVANCED STYLES AND ANIMATIONS --- --}}
<style type="text/css">
    /* 1. Overall Page Style: Dark Background with Perspective */
    /* attribution / footer line under the form */
.login-attribution{
  margin-top:18px;
  text-align:center;
  font-size:13px;
  color: #9fb3cc;
  opacity: 0.95;
  padding-top:8px;
  border-top: 1px solid rgba(255,255,255,0.03);
}
.login-attribution a{
  color: #00BFFF;
  text-decoration: none;
  font-weight: 600;
}
.login-attribution a:hover{
  text-decoration: underline;
  color: #7fd6ff;
}

    .login-page, .register-page {
        margin-top: 50px;
        background: #10101E; /* Very dark background */
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        overflow: hidden; /* Important for background particles */
        position: relative;
        font-family: 'Poppins', sans-serif; /* Recommended modern font */
    }

    /* 2. Animated Background Elements (Particles/Bubbles) */
    .bg-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
    }
    .bg-animation li {
        position: absolute;
        display: block;
        list-style: none;
        width: 20px;
        height: 20px;
        background: rgba(0, 123, 255, 0.15); /* Semi-transparent Blue */
        animation: animate 25s linear infinite;
        bottom: -150px;
        border-radius: 50%;
    }
    .bg-animation li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
    .bg-animation li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
    .bg-animation li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
    .bg-animation li:nth-child(4) { left: 40%; width: 60px; height: 60px; background: rgba(255, 255, 255, 0.1); animation-delay: 0s; animation-duration: 18s; }
    .bg-animation li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
    .bg-animation li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; background: rgba(0, 123, 255, 0.2); }
    /* You can add more li items with different nth-child values for more particles */

    @keyframes animate {
        0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
        100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
    }

    /* 3. Login Box Container: Glassmorphism and Elevated */
    .login-box {
        z-index: 10; /* Bring box to front */
        width: 100%;
        max-width: 520px;
        margin: 0;
        background: rgba(40, 50, 70, 0.3); /* Semi-transparent background */
        backdrop-filter: blur(10px); /* The frosted glass effect */
        border: 1px solid rgba(255, 255, 255, 0.1); /* Subtle white border */
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        border-radius: 20px; /* More rounded */
        overflow: hidden;
        animation: scaleIn 0.6s ease-out; /* Animation on load */
        transition: transform 0.3s ease;
    }
    .login-box:hover {
        transform: scale(1.02); /* Subtle zoom on hover */
    }

    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    /* 4. Login Logo and Header Area */
    .login-logo {
        padding: 40px 20px 10px;
        background: rgba(20, 30, 50, 0.5); /* Darker Header */
        text-align: center;
        border-bottom: 2px solid #00BFFF; /* Brighter accent line */
    }
    .login-logo img {
        width: auto;
        max-height: 100px;
        object-fit: contain;
        transition: filter 0.3s ease;
        filter: drop-shadow(0 0 5px rgba(0, 191, 255, 0.6)); /* Subtle glow on logo */
    }

    /* 5. Login Box Body (Form Area) */
    .login-box-body {
        padding: 40px;
        background: transparent; /* Rely on glass effect */
    }

    .login-box-msg {
        font-size: 1.3em;
        color: #F8F8FF;
        margin-bottom: 30px;
        font-weight: 200; /* Thinner font weight */
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
    }

    /* 6. Form Group Styling */
    .form-group label {
        color: #DDEEFF;
        font-weight: 300;
        margin-bottom: 5px;
        font-size: 0.9em;
        letter-spacing: 0.5px;
    }

    .form-control {
        background-color: rgba(60, 80, 110, 0.6); /* Semi-transparent input field */
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #FFFFFF !important;
        border-radius: 10px;
        padding: 12px 15px 12px 45px; /* Space for the icon */
        height: 50px;
        font-size: 1em;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
        transition: all 0.4s ease; /* Transition everything */
    }

    .form-control:focus {
        border-color: #00BFFF; /* Bright blue focus */
        background-color: rgba(80, 100, 130, 0.8);
        box-shadow: 0 0 15px rgba(0, 191, 255, 0.7), inset 0 1px 5px rgba(0, 0, 0, 0.4);
    }

    /* 7. Form Feedback Icons (Styled to look more modern) */
    .has-feedback .form-control-feedback {
        top: 45px;
        left: 0;
        width: 45px;
        height: 45px;
        line-height: 45px;
        color: #00BFFF; /* Vibrant icon color */
        text-align: center;
        font-size: 1.2em;
        transition: color 0.3s ease;
    }
    .form-group.has-error .form-control-feedback {
        color: #FF5733; /* Error state color */
    }

    /* 8. Button Styling: Gradient and Press Animation */
    .btn-primary.btn-block {
        background: linear-gradient(90deg, #00BFFF 0%, #007BFF 100%); /* Blue gradient */
        border: none;
        color: white;
        padding: 15px;
        font-size: 1.2em;
        font-weight: 600;
        border-radius: 10px;
        margin-top: 30px;
        transition: all 0.2s ease-out;
        box-shadow: 0 4px 15px rgba(0, 191, 255, 0.4);
        position: relative;
        overflow: hidden;
    }

    .btn-primary.btn-block:before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2); /* Shine effect */
        transition: all 0.5s ease;
    }

    .btn-primary.btn-block:hover {
        background: linear-gradient(90deg, #007BFF 0%, #00BFFF 100%); /* Reverse gradient on hover */
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 191, 255, 0.6);
    }
    .btn-primary.btn-block:hover:before {
        left: 100%; /* Move shine across button */
    }

    .btn-primary.btn-block:active {
        transform: translateY(1px); /* Press down effect */
        box-shadow: 0 2px 10px rgba(0, 191, 255, 0.3);
    }

    /* 9. Error/Message Alerts */
    .alert {
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        font-weight: 300;
    }
    .alert-danger {
        background-color: rgba(220, 53, 69, 0.2);
        color: #ffcdd2;
        border: 1px solid #FF5733;
    }
    .alert-info {
        background-color: rgba(0, 191, 255, 0.2);
        color: #e0f7fa;
        border: 1px solid #00BFFF;
    }
</style>
{{-- --- END HYPER-ADVANCED STYLES --- --}}

{{-- List for Animated Background Particles --}}
<ul class="bg-animation">
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
    <li></li>
</ul>

<div class="login-box">
  <div class="login-logo">
    <!-- @if (@$_SESSION["logo"] )
      <img src="{{ asset( 'storage/app/d/nokia/' . $_SESSION['logo']) }}" class="responsive no-repeat" alt="logo">
    @else
      <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }}" class="responsive no-repeat" alt="logo">
    @endif -->
    <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }}" class="responsive no-repeat" alt="logo">

  </div>
  <div class="login-box-body">

    <form method="post" action="{{ route('auth.login.store') }}">
        {{-- Messages and Errors --}}
        @if ($errors->all())
          <div class="alert alert-danger">
            <ul>
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if(Session::has('message'))
          <div class="alert alert-info">
            {{Session::get('message')}}
          </div>
        @endif
        {{-- End Messages and Errors --}}


        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
          <label for="exampleInputEmail1">Email address</label>
          <input class="form-control" name="email" id="exampleInputEmail1" type="text" placeholder="Enter email" value="{{ old('email') }}">
          <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>

        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
          <label for="exampleInputPassword1">Password</label>
          <input class="form-control" name="password" id="exampleInputPassword1" type="password" placeholder="Password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="submit" value="LOG IN SECURELY" class="btn btn-primary btn-block btn-flat">

    </form>

    <!-- attribution line -->
<div class="login-attribution" aria-hidden="true">
  Designed, Developed &amp; Maintained by
  <a href="https://synergyinterface.com/" target="_blank" rel="noopener noreferrer">Synergy Interface Ltd</a>
</div>

  </div>
  </div>

@endsection
