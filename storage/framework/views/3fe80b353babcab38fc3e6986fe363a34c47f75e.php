<header class="main-header">
    <a href="<?php echo e(route('accounts.dashboard')); ?>" class="logo">

        <?php if(@$_SESSION['logo']): ?>
            <img src="<?php echo e(asset('storage/app/d/nokia/' . $_SESSION['logo'])); ?>"
                class="responsive no-repeat" alt="logo"
                style="width: 200px; height: 64px">
        <?php else: ?>
            <img src="<?php echo e(asset('resources/assets/dms/dist/img/logo.png')); ?>"
                class="responsive no-repeat" alt="logo"
                style="width: 200px; height: 64px">
        <?php endif; ?>

    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button"></a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <?php if(Auth::user()->photo): ?>
                            <img src="<?php echo e(asset('storage/app/d/nokia/' . Auth::user()->photo)); ?>"
                                class="user-image" alt="User Image">
                        <?php else: ?>
                            <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>"
                                class="user-image" alt="User Image">
                        <?php endif; ?>

                        <span class="hidden-xs"><?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?></span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <?php if(Auth::user()->photo): ?>
                                <img src="<?php echo e(asset('storage/app/d/nokia/' . Auth::user()->photo)); ?>"
                                    class="user-image" alt="User Image">
                            <?php else: ?>
                                <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>"
                                    class="img-circle" alt="User Image">
                            <?php endif; ?>

                            <p>
                                <?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?>

                                <small>Registered on <?php echo e(substr(Auth::user()->created_at, 0, 4)); ?></small>
                            </p>
                        </li>

                        <li class="user-footer">
                            <div class="pull-right">
                                <a href="<?php echo e(route('logout')); ?>" class="btn btn-info btn-flat">Sign out</a>
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
                <?php if(Auth::user()->photo): ?>
                    <img src="<?php echo e(asset('storage/app/d/nokia/' . Auth::user()->photo)); ?>"
                        class="img-circle" alt="User Image">
                <?php else: ?>
                    <img src="<?php echo e(asset('resources/assets/dms/dist/img/user2-160x160.jpg')); ?>"
                        class="img-circle" alt="User Image">
                <?php endif; ?>
            </div>
            <div class="pull-left info">
                <p><?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->lastname); ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-flat">
                        <i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>

        <ul class="sidebar-menu" data-widget="tree">

            <li class="header">MAIN NAVIGATION</li>

            <li>
                <a href="<?php echo e(route('accounts.dashboard')); ?>">
                    <i class="fa fa-home"></i> <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('orderspostings.index')); ?>">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Order List</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('account.comparison')); ?>">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Order Comparison</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('accounts.pendingReport')); ?>">
                    <i class="fa fa-clock-o"></i>
                    <span>Pending Order Report</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('accounts.todaysProductWiseReport')); ?>">
                    <i class="fa fa-calendar-check-o"></i>
                    <span>Todays All Order</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('accounts.dailyStockReport')); ?>">
                    <i class="fa fa-archive"></i>
                    <span>Warehouse Stock</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('accounts.vatReport')); ?>">
                    <i class="fa fa-percent"></i>
                    <span>VAT Report</span>
                </a>
            </li>

            <li>
                <a href="<?php echo e(route('accounts.closeReport')); ?>">
                    <i class="fa fa-check-square-o"></i>
                    <span>Close Report</span>
                </a>
            </li>


            <!------ DISTRIBUTOR WISE REPORT ------>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>Distributor Wise Report</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo e(route('accounts.distributorDeliveryReport')); ?>"><i class="fa fa-truck"></i> Distributor Delivery Report</a></li>
                    <li><a href="<?php echo e(route('accounts.deliveryReport')); ?>"><i class="fa fa-file-text-o"></i> Delivery Report</a></li>
                </ul>
            </li>

            <!------ PRODUCT WISE REPORT ------>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-cubes"></i>
                    <span>Product Wise Report</span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo e(route('accounts.stockReceiveReport')); ?>"><i class="fa fa-sign-in"></i> Stock Receive Report</a></li>
                    <li><a href="<?php echo e(route('accounts.stockDeliveryReport')); ?>"><i class="fa fa-sign-out"></i> Delivery Report</a></li>
                    <li><a href="<?php echo e(route('accounts.currentStockReport')); ?>"><i class="fa fa-database"></i> Current Stock Report</a></li>
                </ul>
            </li>

            <!------ VERIFY ------>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-check-circle"></i>
                    <span>Verify</span>
                </a>
                <ul class="treeview-menu">
                    <li><a target="_blank" href="<?php echo e(route('guest.verifySamsungProduct')); ?>"><i class="fa fa-search-plus text-aqua"></i> Verify Product</a></li>
                </ul>
            </li>

        </ul>

    </section>
</aside>
