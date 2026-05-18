<?php $__env->startSection('title'); ?>
<?php echo e('E-Warranty Ststem :: Dashboard'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- content part================================ -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- bc part================================ -->
    <?php echo $__env->make('accounts.bc.bc', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <!-- bc part================================ -->

    <!-- Main content -->
    <section class="content">
        <div class="row new-box">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <h1 class="orader">
                                <?php echo e(__('Order List')); ?>

                            </h1>
                        </div>
                    </div>
                    <?php if($message = Session::get('success')): ?>
                    <div class="alert alert-success">
                        <p><?php echo e($message); ?></p>
                    </div>
                    <?php endif; ?>

                    <form id="myForm" action="<?php echo e(route('orderspostings.index')); ?>" method="GET"
                        style="max-width: 400px; margin: 40px 0px;">
                        <label for="dropdown">Select an option:</label>
                        <select id="dropdown" class="form-control" name="search">
    <option id="option_0" value="0" <?php echo e((is_null($queryarray) || (is_array($queryarray) && in_array(0, $queryarray))) ? 'selected' : ''); ?>>Submitted</option>
    <option id="option_1" value="1" <?php echo e((!is_null($queryarray) && is_array($queryarray) && in_array(1, $queryarray)) ? 'selected' : ''); ?>>Waiting</option>
    <option id="option_5" value="5" <?php echo e((!is_null($queryarray) && is_array($queryarray) && in_array(5, $queryarray)) ? 'selected' : ''); ?>>Closed</option>
    <option id="option_7" value="7" <?php echo e((!is_null($queryarray) && is_array($queryarray) && in_array(7, $queryarray)) ? 'selected' : ''); ?>>Rejected</option>
</select>


                        <br>

                        <div class="form-group">
                            <label for="fdate" class="control-label">From Date:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="fdate" placeholder="YYYY-MM-DD"
                                    value="<?php echo e($fdate ? $fdate : ''); ?>" type="text"
                                    class="form-control pull-right" id="datepicker1" autocomplete="off">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="todate" class="control-label">To Date:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="todate" placeholder="YYYY-MM-DD"
                                    value="<?php echo e($todate ? $todate : ''); ?>" type="text"
                                    class="form-control pull-right" id="datepicker2" autocomplete="off">
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </div>
                    </form>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" cellspacing="0" width="100%">
                                <thead class="thead">
                                    <tr>
                                        <th>SN</th>

                                        <th>Order Number</th>
                                        <th>Order by</th>
                                        <th>LD</th>
                                        <th>Number of Items</th>
                                        <th>Total Quantity</th>
                                        <th>Value(BDT)</th>
                                        <th>Finance Remarks</th>
                                        <th>Order Remarks</th>
                                        <th>Order Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $orderspostings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ordersposting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e(++$i); ?></td>

                                        <td><?php echo e($ordersposting->orader_number ?? '-'); ?></td>
                                        <td><?php echo e($ordersposting->Order->users->firstname ?? '-'); ?>

                                            <?php echo e($ordersposting->Order->users->lastname ?? '-'); ?>

                                            (<?php echo e($ordersposting->Order->users->officeid ?? '-'); ?>)
                                        </td>
                                        <td><?php echo e($ordersposting->Order->usersd->firstname ?? '-'); ?><br>
                                            <?php echo e($ordersposting->Order->usersd->officeid ?? '-'); ?>

                                        </td>

                                       <td>
        <?php echo e(count($ordersposting->OrderspostingDetails) ? count($ordersposting->OrderspostingDetails) : '-'); ?>

    </td>

                                        <td>
                                            <?php if($ordersposting->OrderspostingDetails->isNotEmpty()): ?>
                                                <?php
                                                $totalQuantity = 0;
                                                ?>

                                                <?php $__currentLoopData = $ordersposting->OrderspostingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                    $totalQuantity += $detail->quantity;
                                                    ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                <?php echo e($totalQuantity); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($ordersposting->OrderspostingDetails->isNotEmpty()): ?>
                                            <?php
                                            $totalValue = 0;
                                            ?>

                                            <?php $__currentLoopData = $ordersposting->OrderspostingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                            $totalValue += $detail->price * $detail->quantity;
                                            ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <?php echo e(number_format($totalValue)); ?>                                            <?php else: ?>
                                            -
                                            <?php endif; ?>
                                        </td>




                                        <td><?php if(!empty($ordersposting->remarks)): ?>
                                            <?php echo e($ordersposting->remarks); ?>

                                            <?php else: ?>
                                            -
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo e($ordersposting->Order->remarks ?? '-'); ?>

                                        </td>
                                        <td>
                                            <?php echo e($ordersposting->created_at ?? '-'); ?>

                                        </td>
                                        <?php switch($ordersposting->status ):
                                        case (0): ?>
                                        <td>
                                            <p class="testdraft"> Submitted</p>
                                        </td>
                                        <td>
                                            <div style="padding-bottom: 3px">
                                                <a class="btn btn-md btn-primary"
                                                    href="<?php echo e(route('account.details', $ordersposting->orader_number)); ?>">Details</a>
                                            </div>
                                            <div style="padding-bottom: 3px">
                                                <a class="btn btn-md btn-warning"
                                                    href="<?php echo e(route('orderspostings.edit', $ordersposting->id)); ?>"><?php echo e(__('Review')); ?></a>
                                            </div>

                                            <button type="button" class="btn btn-md btn-danger" data-toggle="modal"
                                                data-target="#myModal_<?php echo e($ordersposting->id); ?>">Reject</button>

                                            <div id="myModal_<?php echo e($ordersposting->id); ?>" class="modal fade" role="dialog">
                                                <div class="modal-dialog">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            <h4 class="modal-title">Cancel Order</h4>
                                                        </div>
                                                        <form action="<?php echo e(route('orderposting_delete', $ordersposting->id)); ?>" method="get">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="cancel_reason">About Cancellation</label>
                                                                    <input type="text" class="form-control" id="cancel_reason" name="cancel_reason"
                                                                        placeholder="Enter Cancel Reason" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-success">Confirm</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>


                                        <?php break; ?>

                                        <?php case (1): ?>
                                        <td>
                                            <p class="testdraft"> Waiting</p>
                                        </td>
                                        <td>
                                        <div style="padding-bottom: 3px">
                                            <a class="btn btn-md btn-primary"
                                                href="<?php echo e(route('account.details', $ordersposting->orader_number)); ?>">Details</a>
                                        </div>
                                            <form action="<?php echo e(route('orderspostings.destroy', $ordersposting->id)); ?>"
                                                method="POST">
                                                <?php echo csrf_field(); ?>

                                                <a class="btn btn-md btn-info"
                                                    href="<?php echo e(route('orderspostings.edit', $ordersposting->id)); ?>">
                                                    <?php echo e(__('Edit ')); ?></a>
                                            </form>
                                        </td>
                                        <?php break; ?>

                                        <?php case (2): ?>
                                        <td>
                                            <p class="testdanger"> Processing</p>
                                        </td>
                                        <td>
                                        <div style="padding-bottom: 3px">
                                            <a class="btn btn-md btn-primary"
                                                href="<?php echo e(route('account.details', $ordersposting->orader_number)); ?>">Details</a>
                                        </div>
                                            <a class="btn btn-md btn-primary"
                                                href="<?php echo e(route('orderspostings.show', $ordersposting->id)); ?>"><?php echo e(__('Check the invoice ')); ?></a>
                                        </td>
                                        <?php break; ?>

                                        <?php case (3): ?>
                                        <td>
                                            <p class="testdanger"> Waiting to delivery </p>
                                        </td>

                                        <td>
                                        <div style="padding-bottom: 3px">
                                            <a class="btn btn-md btn-primary"
                                                href="<?php echo e(route('account.details', $ordersposting->orader_number)); ?>">Details</a>
                                        </div>
                                            <a class="btn btn-md btn-primary"
                                                href="<?php echo e(route('orderspostings.show', $ordersposting->id)); ?>"><?php echo e(__('Print invoice')); ?></a>
                                        </td>
                                        <?php break; ?>

                                        <?php case (7): ?>
                                        <td>
                                            <p class="testdanger"> Cancelled </p>
                                        </td>

                                        <td>


                                            <a class="btn btn-md btn-danger"
                                                href="<?php echo e(route('orderposting_reverse', $ordersposting->id)); ?>" onclick="return con()"><?php echo e(__('Reverse')); ?></a>
                                        </td>
                                        <?php break; ?>

                                        <?php case (5): ?>
                                        <td>
                                            <p class="testsuccess">Closed</p>
                                        </td>
                                        <td>
                                        <div style="padding-bottom: 3px">
                                            <a class="btn btn-md btn-primary"
                                                href="<?php echo e(route('account.details', $ordersposting->orader_number)); ?>">Details</a>
                                        </div>
                                            <a class="btn btn-md btn-success" target="_blank"
                                                href="<?php echo e(route('postinginvoice.print', $ordersposting->id)); ?>"><?php echo e(__('Print')); ?></a>
                                        </td>
                                        <?php break; ?>


                                        <?php default: ?>
                                        <?php endswitch; ?>

                                        


                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
               <?php echo e($orderspostings->appends(['search' => $queryarray, 'fdate' => $fdate, 'todate' => $todate])->links()); ?>

            </div>
        </div>
</div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    var dropdown = document.getElementById("dropdown");
    dropdown.addEventListener("change", function () {
        document.getElementById("myForm").submit();
    });
</script>
<script>
function con() {
 return  confirm("Do You Want to Restore the data?");
}
</script>

<script>
    // Get the queryarray value from PHP
    var queryarray = <?php echo json_encode($queryarray, 15, 512) ?>;

    // Loop through each option in the dropdown
    for (var i = 0; i < queryarray.length; i++) {
        var optionId = 'option_' + queryarray[i];
        var option = document.getElementById(optionId);
        
        if (option) {
            option.selected = true; // Set the option as selected
        }
    }
</script>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.master_accounts', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>