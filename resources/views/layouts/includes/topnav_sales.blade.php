<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('sales.dashboard') }}" class="logo">

      @if (@$_SESSION["logo"] )
       
        <img src="{{ asset( 'storage/app/d/nokia/' . $_SESSION['logo']) }}" class="responsive no-repeat" alt="logo" style="width: 200px; height: 64px">
      @else
        <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }}" class="responsive no-repeat" alt="logo" style="width: 200px; height: 64px">
      @endif

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <!-- <span class="sr-only">Toggle navigation</span> -->
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
@if (Auth::user()->photo)
  <img src="{{ asset( 'storage/app/' . Auth::user()->photo) }}" class="user-image" alt="User Image">
@else
  <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
@endif
              
              <span class="hidden-xs">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">

@if (Auth::user()->photo)
  <img src="{{ asset( 'storage/app/' . Auth::user()->photo) }}" class="user-image" alt="User Image">
@else
  <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
@endif

                <p>
                  {{Auth::user()->firstname}} {{Auth::user()->lastname}} - Sales
                  <small>Registered on {{ substr(Auth::user()->created_at, 0,4) }}</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-info btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
@if (Auth::user()->photo)
  <img src="{{ asset( 'storage/app/' . Auth::user()->photo) }}" class="img-circle" alt="User Image">
@else
  <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
@endif
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->firstname}} {{Auth::user()->lastname}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li><a href="{{ route('sales.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>



      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>