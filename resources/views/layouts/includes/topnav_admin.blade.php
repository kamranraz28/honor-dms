<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('admin.dashboard') }}" class="logo">
        <!-- @if (@$_SESSION["logo"] )
            <img src="{{ asset( 'storage/app/d/nokia/' . $_SESSION['logo']) }}" class="responsive no-repeat" alt="logo" style="width: 200px; height: 64px">
        @else
            <img src="{{ asset('resources/assets/dms/dist/img/logo.png') }}" class="responsive no-repeat" alt="logo" style="width: 200px; height: 64px">
        @endif -->
        <img
            src="{{ asset('resources/assets/dms/dist/img/logo.png') }}"
            class="responsive no-repeat"
            alt="logo"
            style="
                width: 200px;
                height: 64px;
                object-fit: contain;
                filter: brightness(0) invert(1);
            "
        >
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"></a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Jobs button with icon -->
          <li>
            <a target="_blank" href="{{ url('jobs') }}" class="btn btn-info">
              <i class="fa fa-briefcase"></i> Jobs
            </a>
          </li>

          <!-- notifications (requestRetailerCount) -->
          @if (@$_SESSION["requestRetailerCount"] > 0)
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
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
                      {{ $_SESSION["requestRetailerCount"] }} new retailers has requested
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="{{ route('admin.inactiveretailer') }}">View all</a></li>
            </ul>
          </li>
          @endif

          <!-- notifications (returnCount) -->
          @if (@$_SESSION["returnCount"] > 0)
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
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
                      {{ $_SESSION["returnCount"] }} piece product want to return
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="{{ route('admin.returnProduct') }}">View all</a></li>
            </ul>
          </li>
          @endif

          <!-- User Account -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- @if (Auth::user()->photo)
                <img src="{{ asset( 'storage/app/d/nokia/' . Auth::user()->photo) }}" class="user-image" alt="User Image">
              @else
                <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">
              @endif -->
              <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="user-image" alt="User Image">

              <span class="hidden-xs">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <!-- @if (Auth::user()->photo)
                  <img src="{{ asset( 'storage/app/d/nokia/' . Auth::user()->photo) }}" class="user-image" alt="User Image">
                @else
                  <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
                @endif -->
                <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

                <p>
                  {{ Auth::user()->firstname }} {{ Auth::user()->lastname }} - Admin
                  <small>Registered on {{ substr(Auth::user()->created_at, 0,4) }}</small>
                </p>
              </li>

              <li class="user-footer">
                <div class="pull-left">
                  <button class="btn btn-info btn-flat" data-toggle="modal" data-target="#{{'userPasswordChangeModal'}}">
                    <i class="fa fa-key"></i> Change Password
                  </button>
                </div>
                <div class="pull-right">
                  <a href="{{ route('logout') }}" class="btn btn-info btn-flat">
                    <i class="fa fa-sign-out"></i> Sign out
                  </a>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
</header>

<!-- Left side column (sidebar) -->
<aside class="main-sidebar">
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <!-- @if (Auth::user()->photo)
          <img src="{{ asset( 'storage/app/d/nokia/' . Auth::user()->photo) }}" class="img-circle" alt="User Image">
        @else
          <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
        @endif -->
        <img src="{{ asset('resources/assets/dms/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image">

      </div>
      <div class="pull-left info">
        <p>{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>

    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
        </span>
      </div>
    </form>

    <!-- sidebar menu -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>

      <li>
        <a href="{{ route('admin.dashboard') }}">
          <i class="fa fa-tachometer"></i> <span>Dashboard</span>
        </a>
      </li>

      @if(auth()->check() && auth()->user()->email === 'info@synergyinterface.com')
        <li>
            <a target="_blank" href="{{ route('ai.index') }}">
                <i class="fa fa-cogs"></i> <span>AI Action</span>
            </a>
        </li>
      @endif


      <li>
        <a href="{{ route('users.index') }}">
          <i class="fa fa-users"></i> <span>Add Users</span>
        </a>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-cog"></i> <span>Configurations</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('admin.setting') }}"><i class="fa fa-circle-o text-aqua"></i> Config</a></li>
          <li><a href="{{ route('admin.salesrepresentative') }}"><i class="fa fa-circle-o text-aqua"></i> SR</a></li>
          <li><a href="{{ route('admin.retailer') }}"><i class="fa fa-circle-o text-aqua"></i> Retailer</a></li>
          <li><a href="{{ route('admin.retailerdwnld') }}"><i class="fa fa-circle-o text-aqua"></i> Retailer Download</a></li>
          <li><a href="{{ route('admin.user.CheckRetailer') }}"><i class="fa fa-circle-o text-aqua"></i> Check Retailer</a></li>
          <li><a href="{{ route('admin.inactiveretailer') }}"><i class="fa fa-circle-o text-aqua"></i> Requested Retailer</a></li>
          <li><a href="{{ route('brands.index') }}"><i class="fa fa-circle-o text-aqua"></i> Brand</a></li>
          <li><a href="{{ route('categories.index') }}"><i class="fa fa-circle-o text-aqua"></i> Category</a></li>
          <li><a href="{{ route('promortkeys.index') }}"><i class="fa fa-circle-o text-aqua"></i> Promo Key</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-table"></i> <span>Product</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('products.index') }}"><i class="fa fa-circle-o text-aqua"></i> Product Setup</a></li>
          <li><a href="{{ route('specifications.index') }}"><i class="fa fa-circle-o text-aqua"></i> Product Spec</a></li>
          <li><a href="{{ route('stocks.index') }}"><i class="fa fa-circle-o text-aqua"></i> Product Add</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-bullhorn"></i> <span>Promotion</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('promotions.index') }}"><i class="fa fa-circle-o text-aqua"></i> Promotion</a></li>
          <li><a href="{{ route('admin.promort') }}"><i class="fa fa-circle-o text-aqua"></i> Pre Book/Retailer Promotion</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-upload"></i> <span>Bulk Upload</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ route('admin.upload1') }}"><i class="fa fa-circle-o text-aqua"></i> Upload</a></li>
        </ul>
      </li>

      <li>
        <a href="{{ route('admin.orderList') }}">
          <i class="fa fa-list-alt"></i> <span>Order List</span>
        </a>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-file-text-o"></i> <span>Reports</span>
        </a>
        <ul class="treeview-menu">

          <li><a href="{{ route('admin.stockReport') }}"><i class="fa fa-circle-o text-aqua"></i> Stock Report</a></li>

          <li><a href="{{ route('admin.retailerImeiStockReport') }}"><i class="fa fa-circle-o text-aqua"></i> IMEI Stock Report (LD & RT)</a></li>

          <li><a href="{{ route('admin.dailyStockReport') }}"><i class="fa fa-circle-o text-aqua"></i> Distributor Stock Amount</a></li>

          <li><a href="{{ route('admin.dailyRetailerStockReport') }}"><i class="fa fa-circle-o text-aqua"></i> Retailer Stock Amount</a></li>

          <li><a href="{{ route('admin.dailySalesReport') }}"><i class="fa fa-circle-o text-aqua"></i> Retailer Sales Report</a></li>
          {{-- <li><a href="{{ route('admin.dailyCampaignReport') }}"><i class="fa fa-circle-o text-aqua"></i> Campaign Report</a></li> --}}
          <li><a href="{{ route('admin.dailyReplaceReport') }}"><i class="fa fa-circle-o text-aqua"></i> Replace Report</a></li>
          <li><a href="{{ route('admin.dailyReturnReport') }}"><i class="fa fa-circle-o text-aqua"></i> Return Report</a></li>
          <li><a href="{{ route('admin.primaryTransferReport') }}"><i class="fa fa-circle-o text-aqua"></i> Primary Transfer Report</a></li>
          <li><a href="{{ route('admin.transferReport') }}"><i class="fa fa-circle-o text-aqua"></i> Secondary Transfer Report</a></li>

          {{-- <li><a href="{{ route('admin.dailyPurchaseSaleReport') }}"><i class="fa fa-circle-o text-aqua"></i> Primary & Secondary Report Edit</a></li> --}}
          <li><a href="{{ route('admin.dailyPurchaseSaleReport1') }}"><i class="fa fa-circle-o text-aqua"></i> Primary & Secondary DL</a></li>
          {{-- <li><a href="{{ route('admin.wod') }}"><i class="fa fa-circle-o text-aqua"></i> WOD Report</a></li> --}}
          {{-- <li><a href="{{ route('admin.dosReport') }}"><i class="fa fa-circle-o text-aqua"></i> DOS Report (Distributor)</a></li> --}}
          {{-- <li><a href="{{ route('admin.retailerDosReport') }}"><i class="fa fa-circle-o text-aqua"></i> DOS Report (Retailer)</a></li> --}}
          <li><a href="{{ route('admin.dailyDistStockReportV1') }}"><i class="fa fa-circle-o text-aqua"></i> Distributor Details Stock Report</a></li>
          {{-- <li><a href="{{ route('admin.distributorImeiStockReport') }}"><i class="fa fa-circle-o text-aqua"></i> Distributor IMEI Stock Report</a></li> --}}
          <li><a href="{{ route('admin.retailerCheckReport') }}"><i class="fa fa-circle-o text-aqua"></i> Retailer Mapping Report</a></li>
          <li><a href="{{ route('admin.tsoCheckReport') }}"><i class="fa fa-circle-o text-aqua"></i> TSO Mapping Report</a></li>
          {{-- <li><a href="{{ route('admin.srCheckReport') }}"><i class="fa fa-circle-o text-aqua"></i> SR Mapping Report</a></li> --}}
          {{-- <li><a href="{{ route('admin.dailyRTSMSReport') }}"><i class="fa fa-circle-o text-aqua"></i> Retailer Wise Promotion</a></li> --}}
          <li><a href="{{ route('admin.dailyimeivReport') }}"><i class="fa fa-circle-o text-aqua"></i> IMEI/SNO Cycle Report</a></li>
          <li><a href="{{ route('admin.vatReport') }}"><i class="fa fa-circle-o text-aqua"></i> VAT Report</a></li>
          <li><a href="{{ route('admin.warehouseStockReport') }}"><i class="fa fa-circle-o text-aqua"></i> Warehouse Stock Report</a></li>
          <li><a href="{{ route('admin.todaysOrder') }}"><i class="fa fa-circle-o text-aqua"></i> Today's Order Report</a></li>
          <li><a href="{{ route('admin.pendingOrder') }}"><i class="fa fa-circle-o text-aqua"></i> Pending Order Report</a></li>

        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-exclamation-triangle"></i> <span>Incomplete Report</span></a>
        <ul class="treeview-menu">
          <li><a href="{{ route('admin.incompleteReport') }}"><i class="fa fa-circle-o text-aqua"></i> Incomplete Report</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-undo"></i> <span>Returns</span></a>
        <ul class="treeview-menu">
          <li><a href="{{route('admin.returnProduct')}}"><i class="fa fa-circle-o text-aqua"></i> ST1 Return Approval</a></li>
          <li><a href="{{route('admin.returnProductAll')}}"><i class="fa fa-circle-o text-aqua"></i> Return All Product</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-wrench"></i> <span>Warranty Check</span></a>
        <ul class="treeview-menu">
          <li><a href="{{ route('admin.wcheckProduct') }}"><i class="fa fa-circle-o text-aqua"></i> Warranty Check</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-bolt"></i> <span>Warranty Activation</span></a>
        <ul class="treeview-menu">
          <li><a href="{{ route('admin.activewarranty') }}"><i class="fa fa-circle-o text-aqua"></i> Active Warranty (Data Entry)</a></li>
          <li><a href="https://salextra.xyz/salextra/nokia.php" target="_blank"><i class="fa fa-circle-o text-aqua"></i> Warranty Activation From Web</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-check-circle"></i> <span>Verify</span></a>
        <ul class="treeview-menu">
          <li><a target="_blank" href="https://salextra.xyz/salextra/nokia/verify/verifyProducts"><i class="fa fa-circle-o text-aqua"></i> Verify Product</a></li>
        </ul>
      </li>

    </ul>
  </section>
</aside>
