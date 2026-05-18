<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo">
        <!-- <?php if(@$_SESSION["logo"] ): ?>
            <img src="<?php echo e(asset( 'storage/app/d/nokia/' . $_SESSION['logo'])); ?>" class="responsive no-repeat" alt="logo" style="width: 200px; height: 64px">
        <?php else: ?>
            <img src="<?php echo e(asset('resources/assets/dms/dist/img/logo.png')); ?>" class="responsive no-repeat" alt="logo" style="width: 200px; height: 64px">
        <?php endif; ?> -->
        <img src="<?php echo e(asset('resources/assets/dms/dist/img/logo.png')); ?>" class="responsive no-repeat" alt="logo" style="width: 200px; height: 64px">

    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"></a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Jobs button with icon -->
          <li>
            <a target="_blank" href="<?php echo e(url('jobs')); ?>" class="btn btn-info">
              <i class="fa fa-briefcase"></i> Jobs
            </a>
          </li>

          <!-- notifications (requestRetailerCount) -->
          <?php if(@$_SESSION["requestRetailerCount"] > 0): ?>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"><?php echo e($_SESSION["requestRetailerCount"]); ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo e($_SESSION["requestRetailerCount"]); ?> notifications</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="<?php echo e(route('admin.inactiveretailer')); ?>">
                      <i class="fa fa-users text-aqua"></i>
                      <?php echo e($_SESSION["requestRetailerCount"]); ?> new retailers has requested
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="<?php echo e(route('admin.inactiveretailer')); ?>">View all</a></li>
            </ul>
          </li>
          <?php endif; ?>

          <!-- notifications (returnCount) -->
          <?php if(@$_SESSION["returnCount"] > 0): ?>
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"><?php echo e($_SESSION["returnCount"]); ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?php echo e($_SESSION["returnCount"]); ?> notifications</li>
              <li>
                <ul class="menu">
                  <li>
                    <a href="<?php echo e(route('admin.returnProduct')); ?>">
                      <i class="fa fa-undo text-aqua"></i>
                      <?php echo e($_SESSION["returnCount"]); ?> piece product want to return
                    </a>
                  </li>
                </ul>
              </li>
              <li class="footer"><a href="<?php echo e(route('admin.returnProduct')); ?>">View all</a></li>
            </ul>
          </li>
          <?php endif; ?>

          <!-- User Account -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- <?php if(Auth::user()->photo): ?>
                <img src="<?php echo e(asset( 'storage/app/d/nokia/' . Auth::user()->photo)); ?>" class="user-image" alt="User Image">
              <?php else: ?>
                <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>" class="user-image" alt="User Image">
              <?php endif; ?> -->
              <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>" class="user-image" alt="User Image">

              <span class="hidden-xs"><?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <!-- <?php if(Auth::user()->photo): ?>
                  <img src="<?php echo e(asset( 'storage/app/d/nokia/' . Auth::user()->photo)); ?>" class="user-image" alt="User Image">
                <?php else: ?>
                  <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>" class="img-circle" alt="User Image">
                <?php endif; ?> -->
                <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?> - Admin
                  <small>Registered on <?php echo e(substr(Auth::user()->created_at, 0,4)); ?></small>
                </p>
              </li>

              <li class="user-footer">
                <div class="pull-left">
                  <button class="btn btn-info btn-flat" data-toggle="modal" data-target="#<?php echo e('userPasswordChangeModal'); ?>">
                    <i class="fa fa-key"></i> Change Password
                  </button>
                </div>
                <div class="pull-right">
                  <a href="<?php echo e(route('logout')); ?>" class="btn btn-info btn-flat">
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
        <!-- <?php if(Auth::user()->photo): ?>
          <img src="<?php echo e(asset( 'storage/app/d/nokia/' . Auth::user()->photo)); ?>" class="img-circle" alt="User Image">
        <?php else: ?>
          <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>" class="img-circle" alt="User Image">
        <?php endif; ?> -->
        <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>" class="img-circle" alt="User Image">

      </div>
      <div class="pull-left info">
        <p><?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?></p>
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
        <a href="<?php echo e(route('admin.dashboard')); ?>">
          <i class="fa fa-tachometer"></i> <span>Dashboard</span>
        </a>
      </li>

      <?php if(auth()->check() && auth()->user()->email === 'info@synergyinterface.com'): ?>
        <li>
            <a target="_blank" href="<?php echo e(route('ai.index')); ?>">
                <i class="fa fa-cogs"></i> <span>AI Action</span>
            </a>
        </li>
      <?php endif; ?>


      <li>
        <a href="<?php echo e(route('users.index')); ?>">
          <i class="fa fa-users"></i> <span>Add Users</span>
        </a>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-cog"></i> <span>Configurations</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?php echo e(route('admin.setting')); ?>"><i class="fa fa-circle-o text-aqua"></i> Config</a></li>
          <li><a href="<?php echo e(route('admin.salesrepresentative')); ?>"><i class="fa fa-circle-o text-aqua"></i> SR</a></li>
          <li><a href="<?php echo e(route('admin.retailer')); ?>"><i class="fa fa-circle-o text-aqua"></i> Retailer</a></li>
          <li><a href="<?php echo e(route('admin.retailerdwnld')); ?>"><i class="fa fa-circle-o text-aqua"></i> Retailer Download</a></li>
          <li><a href="<?php echo e(route('admin.user.CheckRetailer')); ?>"><i class="fa fa-circle-o text-aqua"></i> Check Retailer</a></li>
          <li><a href="<?php echo e(route('admin.inactiveretailer')); ?>"><i class="fa fa-circle-o text-aqua"></i> Requested Retailer</a></li>
          <li><a href="<?php echo e(route('brands.index')); ?>"><i class="fa fa-circle-o text-aqua"></i> Brand</a></li>
          <li><a href="<?php echo e(route('categories.index')); ?>"><i class="fa fa-circle-o text-aqua"></i> Category</a></li>
          <li><a href="<?php echo e(route('promortkeys.index')); ?>"><i class="fa fa-circle-o text-aqua"></i> Promo Key</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-table"></i> <span>Product</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?php echo e(route('products.index')); ?>"><i class="fa fa-circle-o text-aqua"></i> Product Setup</a></li>
          <li><a href="<?php echo e(route('specifications.index')); ?>"><i class="fa fa-circle-o text-aqua"></i> Product Spec</a></li>
          <li><a href="<?php echo e(route('stocks.index')); ?>"><i class="fa fa-circle-o text-aqua"></i> Product Add</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-bullhorn"></i> <span>Promotion</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?php echo e(route('promotions.index')); ?>"><i class="fa fa-circle-o text-aqua"></i> Promotion</a></li>
          <li><a href="<?php echo e(route('admin.promort')); ?>"><i class="fa fa-circle-o text-aqua"></i> Pre Book/Retailer Promotion</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-upload"></i> <span>Bulk Upload</span>
        </a>
        <ul class="treeview-menu">
          <li><a href="<?php echo e(route('admin.upload1')); ?>"><i class="fa fa-circle-o text-aqua"></i> Upload</a></li>
        </ul>
      </li>

      <li>
        <a href="<?php echo e(route('admin.orderList')); ?>">
          <i class="fa fa-list-alt"></i> <span>Order List</span>
        </a>
      </li>

      <li class="treeview">
        <a href="#">
          <i class="fa fa-file-text-o"></i> <span>Reports</span>
        </a>
        <ul class="treeview-menu">

          <li><a href="<?php echo e(route('admin.stockReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Stock Report</a></li>

          <li><a href="<?php echo e(route('admin.retailerImeiStockReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> IMEI Stock Report (LD & RT)</a></li>

          <li><a href="<?php echo e(route('admin.dailyStockReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Distributor Stock Amount</a></li>

          <li><a href="<?php echo e(route('admin.dailyRetailerStockReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Retailer Stock Amount</a></li>

          <li><a href="<?php echo e(route('admin.dailySalesReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Retailer Sales Report</a></li>
          
          <li><a href="<?php echo e(route('admin.dailyReplaceReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Replace Report</a></li>
          <li><a href="<?php echo e(route('admin.dailyReturnReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Return Report</a></li>
          <li><a href="<?php echo e(route('admin.primaryTransferReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Primary Transfer Report</a></li>
          <li><a href="<?php echo e(route('admin.transferReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Secondary Transfer Report</a></li>

          
          <li><a href="<?php echo e(route('admin.dailyPurchaseSaleReport1')); ?>"><i class="fa fa-circle-o text-aqua"></i> Primary & Secondary DL</a></li>
          
          
          
          <li><a href="<?php echo e(route('admin.dailyDistStockReportV1')); ?>"><i class="fa fa-circle-o text-aqua"></i> Distributor Details Stock Report</a></li>
          
          <li><a href="<?php echo e(route('admin.retailerCheckReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Retailer Mapping Report</a></li>
          <li><a href="<?php echo e(route('admin.tsoCheckReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> TSO Mapping Report</a></li>
          
          
          <li><a href="<?php echo e(route('admin.dailyimeivReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> IMEI/SNO Cycle Report</a></li>
          <li><a href="<?php echo e(route('admin.vatReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> VAT Report</a></li>
          <li><a href="<?php echo e(route('admin.warehouseStockReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Warehouse Stock Report</a></li>
          <li><a href="<?php echo e(route('admin.todaysOrder')); ?>"><i class="fa fa-circle-o text-aqua"></i> Today's Order Report</a></li>
          <li><a href="<?php echo e(route('admin.pendingOrder')); ?>"><i class="fa fa-circle-o text-aqua"></i> Pending Order Report</a></li>

        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-exclamation-triangle"></i> <span>Incomplete Report</span></a>
        <ul class="treeview-menu">
          <li><a href="<?php echo e(route('admin.incompleteReport')); ?>"><i class="fa fa-circle-o text-aqua"></i> Incomplete Report</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-undo"></i> <span>Returns</span></a>
        <ul class="treeview-menu">
          <li><a href="<?php echo e(route('admin.returnProduct')); ?>"><i class="fa fa-circle-o text-aqua"></i> ST1 Return Approval</a></li>
          <li><a href="<?php echo e(route('admin.returnProductAll')); ?>"><i class="fa fa-circle-o text-aqua"></i> Return All Product</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-wrench"></i> <span>Warranty Check</span></a>
        <ul class="treeview-menu">
          <li><a href="<?php echo e(route('admin.wcheckProduct')); ?>"><i class="fa fa-circle-o text-aqua"></i> Warranty Check</a></li>
        </ul>
      </li>

      <li class="treeview">
        <a href="#"><i class="fa fa-bolt"></i> <span>Warranty Activation</span></a>
        <ul class="treeview-menu">
          <li><a href="<?php echo e(route('admin.activewarranty')); ?>"><i class="fa fa-circle-o text-aqua"></i> Active Warranty (Data Entry)</a></li>
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
