<header class="main-header">
  <!-- Logo -->
  <a href="{{ route('distributor.dashboard') }}" class="logo">

@if (@$_SESSION["logo"])
  <img src="{{ asset('storage/app/d/nokia/' . $_SESSION['logo']) }}"
       alt="logo" style="width:200px;height:64px">
@else
  <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }}"
       alt="logo" style="width:200px;height:64px">
@endif

  </a>

  <nav class="navbar navbar-static-top">
    <a href="#" class="sidebar-toggle" data-toggle="push-menu"></a>

    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

{{-- Notifications --}}
@if (@$_SESSION["returnCount"] > 0)
<li class="dropdown notifications-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-bell-o"></i>
    <span class="label label-warning">{{ $_SESSION["returnCount"] }}</span>
  </a>
  <ul class="dropdown-menu">
    <li class="header">
      You have {{ $_SESSION["returnCount"] }} notifications
    </li>
    <li>
      <ul class="menu">
        <li>
          <a href="{{ route('distributor.returnProduct') }}">
            <i class="fa fa-undo text-aqua"></i>
            {{ $_SESSION["returnCount"] }} product return requests
          </a>
        </li>
      </ul>
    </li>
    <li class="footer">
      <a href="{{ route('distributor.returnProduct') }}">View all</a>
    </li>
  </ul>
</li>
@endif

{{-- User --}}
<li class="dropdown user user-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
@if (Auth::user()->photo)
  <img src="{{ asset('storage/app/d/nokia/' . Auth::user()->photo) }}" class="user-image">
@else
  <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="user-image">
@endif
    <span class="hidden-xs">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
  </a>

  <ul class="dropdown-menu">
    <li class="user-header">
@if (Auth::user()->photo)
  <img src="{{ asset('storage/app/d/nokia/' . Auth::user()->photo) }}" class="img-circle">
@else
  <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle">
@endif
      <p>
        {{ Auth::user()->firstname }} {{ Auth::user()->lastname }} - Distributor
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

{{-- ================= SIDEBAR ================= --}}
<aside class="main-sidebar">
<section class="sidebar">

<div class="user-panel">
  <div class="pull-left image">
@if (Auth::user()->photo)
  <img src="{{ asset('storage/app/d/nokia/' . Auth::user()->photo) }}" class="img-circle">
@else
  <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle">
@endif
  </div>
  <div class="pull-left info">
    <p>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</p>
    <a><i class="fa fa-circle text-success"></i> Online</a>
  </div>
</div>

<ul class="sidebar-menu" data-widget="tree">
<li class="header">MAIN NAVIGATION</li>

<li>
  <a href="{{ route('distributor.dashboard') }}">
    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
  </a>
</li>

<li>
  <a href="{{ route('distributor.distributor') }}">
    <i class="fa fa-user"></i> <span>Profile</span>
  </a>
</li>

<li>
  <a href="{{ route('distributor.order') }}">
    <i class="fa fa-shopping-cart"></i> <span>Add Order</span>
  </a>
</li>

<li>
  <a href="{{ route('distributor.dailyPurchaseApprove') }}">
    <i class="fa fa-truck"></i> <span>Daily Product Receive</span>
  </a>
</li>

<li>
  <a href="{{ route('distributor.sale') }}">
    <i class="fa fa-line-chart"></i> <span>Sale Product</span>
  </a>
</li>

<li>
  <a href="{{ route('distributor.upload1') }}">
    <i class="fa fa-upload"></i> <span>Secondary Bulk Upload</span>
  </a>
</li>

<li class="treeview">
  <a href="#">
    <i class="fa fa-undo"></i> <span>Returns</span>
    <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
    <li><a href="{{ route('distributor.returndProduct') }}"><i class="fa fa-circle-o"></i> ST2 Direct Transfer</a></li>
    <li><a href="{{ route('distributor.sreturnProduct') }}"><i class="fa fa-circle-o"></i> ST1 Return Apply</a></li>
  </ul>
</li>

<li>
  <a href="{{ route('distributor.retailer') }}">
    <i class="fa fa-building"></i> <span>Add Retail</span>
  </a>
</li>

<li>
  <a href="{{ route('distributor.sr') }}">
    <i class="fa fa-user-plus"></i> <span>Add SR</span>
  </a>
</li>

<li class="treeview">
  <a href="#">
    <i class="fa fa-file-text-o"></i> <span>Reports</span>
    <i class="fa fa-angle-left pull-right"></i>
  </a>
  <ul class="treeview-menu">
    <li><a href="{{ route('distributor.dailyPurchaseReport') }}"><i class="fa fa-circle-o"></i> LD Purchase (IMEI)</a></li>
    <li><a href="{{ route('distributor.dailySalesReport') }}"><i class="fa fa-circle-o"></i> LD Sales (IMEI)</a></li>
    <li><a href="{{ route('distributor.dailyReplaceReport') }}"><i class="fa fa-circle-o"></i> Replace Report</a></li>
    <li><a href="{{ route('distributor.dailyPurchaseReportV1') }}"><i class="fa fa-circle-o"></i> LD Purchase (Qty)</a></li>
    <li><a href="{{ route('distributor.dailySalesReportV1') }}"><i class="fa fa-circle-o"></i> LD Sales (Qty)</a></li>
    <li><a href="{{ route('distributor.dailyStockReport') }}"><i class="fa fa-circle-o"></i> LD Stock</a></li>
    <li><a href="{{ route('distributor.dailyStockReportV1') }}"><i class="fa fa-circle-o"></i> LD Stock (All)</a></li>
    <li><a href="{{ route('distributor.dailyCampaignReport') }}"><i class="fa fa-circle-o"></i> Retailer Sales</a></li>
    <li><a href="{{ route('distributor.dailyRetailerStockReportForRetailer') }}"><i class="fa fa-circle-o"></i> Retailer Stock</a></li>
    <li><a href="{{ route('distributor.dailyRtlStockReportV1') }}"><i class="fa fa-circle-o"></i> Retailer Stock (Details)</a></li>
    <li><a href="{{ route('distributor.distributorImeiStockReport') }}"><i class="fa fa-circle-o"></i> Distributor IMEI Stock</a></li>
    <li><a href="{{ route('distributor.retailerImeiStockReport') }}"><i class="fa fa-circle-o"></i> Retailer IMEI Stock</a></li>
    <li><a href="{{ route('distributor.retailerEdit') }}"><i class="fa fa-circle-o"></i> Distributor Retail</a></li>
  </ul>
</li>

<li>
  <a href="{{ route('distributor.wcheckProduct') }}">
    <i class="fa fa-shield"></i> <span>Warranty Checks</span>
  </a>
</li>

<li>
  <a href="https://salextra.xyz/salextra/nokia.php" target="_blank">
    <i class="fa fa-globe"></i> <span>Warranty Activation</span>
  </a>
</li>

</ul>
</section>
</aside>
