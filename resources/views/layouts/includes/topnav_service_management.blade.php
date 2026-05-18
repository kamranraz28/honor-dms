<header class="main-header">
  <!-- Logo -->
  <a href="{{ route('admin.dashboard') }}" class="logo">
    @if (@$_SESSION["logo"])
      <img src="{{ asset('storage/app/' . $_SESSION['logo']) }}" style="width:200px;height:64px">
    @else
      <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }}" style="width:200px;height:64px">
    @endif
  </a>

  <nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu"></a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        {{-- Retailer Request Notification --}}
        @if (@$_SESSION["requestRetailerCount"] > 0)
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">{{ $_SESSION["requestRetailerCount"] }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">You have {{ $_SESSION["requestRetailerCount"] }} notifications</li>
            <li>
              <ul class="menu">
                <li>
                  <a href="{{ route('admin.inactiveretailer') }}">
                    <i class="fa fa-users text-aqua"></i>
                    {{ $_SESSION["requestRetailerCount"] }} new retailer requests
                  </a>
                </li>
              </ul>
            </li>
            <li class="footer"><a href="{{ route('admin.inactiveretailer') }}">View all</a></li>
          </ul>
        </li>
        @endif

        {{-- Return Notification --}}
        @if (@$_SESSION["returnCount"] > 0)
        <li class="dropdown notifications-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bell-o"></i>
            <span class="label label-warning">{{ $_SESSION["returnCount"] }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">You have {{ $_SESSION["returnCount"] }} notifications</li>
            <li>
              <ul class="menu">
                <li>
                  <a href="{{ route('admin.returnProduct') }}">
                    <i class="fa fa-undo text-aqua"></i>
                    {{ $_SESSION["returnCount"] }} product return requests
                  </a>
                </li>
              </ul>
            </li>
            <li class="footer"><a href="{{ route('admin.returnProduct') }}">View all</a></li>
          </ul>
        </li>
        @endif

        {{-- User Menu --}}
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ Auth::user()->photo ? asset('storage/app/upload/'.Auth::user()->photo) : asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="user-image">
            <span class="hidden-xs">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              <img src="{{ Auth::user()->photo ? asset('storage/app/upload/'.Auth::user()->photo) : asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle">
              <p>
                {{ Auth::user()->firstname }} {{ Auth::user()->lastname }} - Service Management
                <small>Registered {{ substr(Auth::user()->created_at,0,4) }}</small>
              </p>
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <button class="btn btn-info btn-flat" data-toggle="modal" data-target="#userPasswordChangeModal">
                  Change Password
                </button>
              </div>
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

<aside class="main-sidebar">
<section class="sidebar">

  <div class="user-panel">
    <div class="pull-left image">
      <img src="{{ Auth::user()->photo ? asset('storage/app/upload/'.Auth::user()->photo) : asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle">
    </div>
    <div class="pull-left info">
      <p>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</p>
      <a><i class="fa fa-circle text-success"></i> Online</a>
    </div>
  </div>

  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MAIN NAVIGATION</li>

    {{-- Dashboard --}}
    <li>
      <a href="{{ route('serviceManagement.dashboard') }}">
        <i class="fa fa-dashboard"></i>
        <span>Dashboard</span>
      </a>
    </li>

    {{-- Receive Product --}}
    <li>
      <a href="{{ route('serviceManagement.receiveProduct') }}">
        <i class="fa fa-download"></i>
        <span>Receive Product</span>
      </a>
    </li>

    {{-- Check Product --}}
    <li>
      <a href="{{ route('serviceManagement.checkProduct') }}">
        <i class="fa fa-search"></i>
        <span>Check Product</span>
      </a>
    </li>

    {{-- Deliver Product --}}
    <li>
      <a href="{{ route('serviceManagement.deliverProduct') }}">
        <i class="fa fa-truck"></i>
        <span>Deliver Product</span>
      </a>
    </li>

    {{-- Approve Delivery --}}
    <li>
      <a href="{{ route('serviceManagement.approveDeliverProduct') }}">
        <i class="fa fa-check"></i>
        <span>Approve Deliver Product</span>
      </a>
    </li>

    {{-- Canceled Product --}}
    <li>
      <a href="{{ route('serviceManagement.canceledProduct') }}">
        <i class="fa fa-times-circle"></i>
        <span>Canceled Product</span>
      </a>
    </li>

    {{-- Canceled Delivered --}}
    <li>
      <a href="{{ route('serviceManagement.canceledDeliveredProduct') }}">
        <i class="fa fa-ban"></i>
        <span>Canceled Delivered Product</span>
      </a>
    </li>

    {{-- Bulk Upload --}}
    <li>
      <a href="{{ route('serviceManagement.bulkUploadView') }}">
        <i class="fa fa-upload"></i>
        <span>Bulk Upload</span>
      </a>
    </li>

  </ul>
</section>
</aside>
