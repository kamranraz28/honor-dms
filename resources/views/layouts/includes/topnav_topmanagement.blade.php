<header class="main-header">
  <!-- Logo -->
  <a href="{{ route('topmanagement.dashboard') }}" class="logo">
    @if (@$_SESSION["logo"])
      <img src="{{ asset('storage/app/d/nokia/' . $_SESSION['logo']) }}" style="width:200px;height:64px">
    @else
      <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }}" style="width:200px;height:64px">
    @endif
  </a>

  <nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu"></a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="{{ Auth::user()->photo ? asset('storage/app/'.Auth::user()->photo) : asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="user-image">
            <span class="hidden-xs">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              <img src="{{ Auth::user()->photo ? asset('storage/app/'.Auth::user()->photo) : asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle">
              <p>
                {{ Auth::user()->firstname }} {{ Auth::user()->lastname }} - Top Management
                <small>Registered {{ substr(Auth::user()->created_at,0,4) }}</small>
              </p>
            </li>
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

<aside class="main-sidebar">
<section class="sidebar">

  <div class="user-panel">
    <div class="pull-left image">
      <img src="{{ Auth::user()->photo ? asset('storage/app/'.Auth::user()->photo) : asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle">
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
      <a href="{{ route('topmanagement.dashboard') }}">
        <i class="fa fa-dashboard"></i>
        <span>Dashboard</span>
      </a>
    </li>

    {{-- Profile --}}
    <li class="treeview">
      <a href="#">
        <i class="fa fa-user"></i>
        <span>Profile</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ route('topmanagement.topmanagement') }}"><i class="fa fa-circle-o"></i> Profile</a></li>
      </ul>
    </li>

    {{-- Reports --}}
    <li class="treeview">
      <a href="#">
        <i class="fa fa-file-text-o"></i>
        <span>Reports</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ route('topmanagement.dailySalesReport') }}"><i class="fa fa-circle-o"></i> Retailer Sales Report</a></li>
        <li><a href="{{ route('topmanagement.dailyCampaignReport') }}"><i class="fa fa-circle-o"></i> Campaign Report</a></li>
        <li><a href="{{ route('topmanagement.dailyReplaceReport') }}"><i class="fa fa-circle-o"></i> Replace Report</a></li>
        <li><a href="{{ route('topmanagement.dailyRetailerStockReport') }}"><i class="fa fa-circle-o"></i> Retailer Stock Report</a></li>
        <li><a href="{{ route('topmanagement.dailyStockReport') }}"><i class="fa fa-circle-o"></i> Stock Report</a></li>
        <li><a href="{{ route('topmanagement.dailyPurchaseSaleReport') }}"><i class="fa fa-circle-o"></i> Purchase / Sales Report</a></li>
        <li><a href="{{ route('topmanagement.dailyDistributorSalesReport') }}"><i class="fa fa-circle-o"></i> Distributor Sales Report</a></li>
        <li><a href="{{ route('topmanagement.primarySecondaryDLReport') }}"><i class="fa fa-circle-o"></i> Primary & Secondary DL</a></li>
        <li><a href="{{ route('topmanagement.dosReport') }}"><i class="fa fa-circle-o"></i> DOS Report (Distributor)</a></li>
        <li><a href="{{ route('topmanagement.distributorImeiStockReport') }}"><i class="fa fa-circle-o"></i> Distributor IMEI Stock</a></li>
        <li><a href="{{ route('topmanagement.retailerImeiStockReport') }}"><i class="fa fa-circle-o"></i> Retailer IMEI Stock</a></li>
        <li><a href="{{ route('topmanagement.wodReport') }}"><i class="fa fa-circle-o"></i> WOD Report</a></li>
      </ul>
    </li>

    {{-- Warranty Check --}}
    <li class="treeview">
      <a href="#">
        <i class="fa fa-search"></i>
        <span>Warranty Check</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ route('topmanagement.wcheckProduct') }}"><i class="fa fa-circle-o"></i> Warranty Check</a></li>
      </ul>
    </li>

    {{-- Verify --}}
    <li class="treeview">
      <a href="#">
        <i class="fa fa-check-circle"></i>
        <span>Verify</span>
        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
      </a>
      <ul class="treeview-menu">
        <li><a target="_blank" href="{{ route('guest.verifySamsungProduct') }}"><i class="fa fa-circle-o"></i> Verify Product</a></li>
      </ul>
    </li>

  </ul>
</section>
</aside>
