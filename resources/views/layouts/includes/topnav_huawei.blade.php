<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('huawei.dashboard') }}" class="logo">
   

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



<!-- =====returnCount================= -->
@if (@$_SESSION["returnCount"] > 0)
  
<!-- =====notification================= -->
<li class="dropdown notifications-menu">
  <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <i class="fa fa-bell-o"></i>
    <span class="label label-warning">
      {{$_SESSION["returnCount"]}}
   </span>
  </a>
  <ul class="dropdown-menu">
    <li class="header">You have 
      {{$_SESSION["returnCount"]}}
     notifications</li>
    <li>
      <!-- inner menu: contains the actual data -->
      <ul class="menu">
        <li>
          <a href="{{ route('huawei.returnProductAll') }}">
            <i class="fa fa-undo text-aqua"></i> 
              {{$_SESSION["returnCount"]}}
             piece product want to return 
          </a>
        </li>
      </ul>
    </li>
    <li class="footer"><a href="{{ route('huawei.returnProductAll') }}">View all</a></li>
  </ul>
</li>

<!-- =====notification================= -->

@endif
<!-- =====returnCount================= -->



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
                  {{Auth::user()->firstname}} {{Auth::user()->lastname}} - Huawei
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
          
          <li><a href="{{ route('huawei.dashboard') }}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>


        <li class="treeview">
          <a href="#">
            <i class="fa fa-cog"></i>
            <span>Profile </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('huawei.huawei') }}"><i class="fa fa-circle-o text-aqua"></i>Profile</a></li>
          </ul>
        </li>


        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i>
            <span>Reports </span>
          </a>
          <ul class="treeview-menu">
            
            <!-- <li><a href="{{ route('huawei.dailySalesReportWithDistrict') }}"><i class="fa fa-circle-o text-aqua"></i>Daily Sales Report With District</a></li>
            
            <li><a href="{{ route('huawei.dailyCampaignReportWithDistrict') }}"><i class="fa fa-circle-o text-aqua"></i>Daily Campaign Report With District</a></li>
             -->
            

            <li><a href="{{ route('huawei.dailyRetailerStockReport') }}"><i class="fa fa-circle-o text-aqua"></i>Retailer Stock Report</a></li>


            <li><a href="{{ route('huawei.dailySalesReport') }}"><i class="fa fa-circle-o text-aqua"></i>Retailer Sales Report</a></li>
        
            <li><a href="{{ route('huawei.dailyCampaignReport') }}"><i class="fa fa-circle-o text-aqua"></i>Campaign Report</a></li>

            <li><a href="{{ route('huawei.dailyReturnReport') }}"><i class="fa fa-circle-o text-aqua"></i>Return Report</a></li>


             <li><a href="{{ route('huawei.dailyStockReport') }}"><i class="fa fa-circle-o text-aqua"></i>Stock Report</a></li>

            <li><a href="{{ route('huawei.dailyPurchaseSaleReport') }}"><i class="fa fa-circle-o text-aqua"></i>Purchase Or Sales Report</a></li>

            <li><a href="{{ route('huawei.dailyDistributorSalesReport') }}"><i class="fa fa-circle-o text-aqua"></i>Distributor Sales Report</a></li>

            <li><a href="{{ route('huawei.retailerCheckReport') }}"><i class="fa fa-circle-o text-aqua"></i>Retailer Mapping Report</a></li>


          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i>
            <span>Returns </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{route('huawei.returnProductAll')}}"><i class="fa fa-circle-o text-aqua"></i>Return All Product</a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i>
            <span>Warranty Check </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ route('huawei.wcheckProduct') }}"><i class="fa fa-circle-o text-aqua"></i>Warranty Check</a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i>
            <span>Verify </span>
          </a>
          <ul class="treeview-menu">
            <li><a target="_blank" href="{{ route('guest.verifySamsungProduct') }}"><i class="fa fa-circle-o text-aqua"></i>Verify Product</a></li>
          </ul>
        </li>


        <!-- <li class="treeview">
          <a href="#">
            <i class="fa fa-table"></i>
            <span>Accounts Section</span>
          </a>
        
          
          <ul class="treeview-menu">
            
            <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i>
                <span>Settings</span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Distributor Stock</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> View Distributor Slaes</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> Distributor Invoice History</a></li>
              </ul>
            </li>
        
        
            <li class="treeview">
              <a href="#">
                <i class="fa fa-table"></i>
                <span>Report</span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o text-red"></i> Distributor Stock</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> View Distributor Slaes</a></li>
                <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> Distributor Invoice History</a></li>
              </ul>
            </li>
        
        
          </ul>
        
        
        </li>
        
         -->







      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>