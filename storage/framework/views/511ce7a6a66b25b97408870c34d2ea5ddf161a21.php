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
                                    <?php echo e(__('Order Comparison')); ?>

                                </h1>
                            </div>
                        </div>
                        <?php if($message = Session::get('success')): ?>
                            <div class="alert alert-success">
                                <p><?php echo e($message); ?></p>
                            </div>
                        <?php endif; ?>

                        <form id="myForm" action="<?php echo e(route('account.comparison')); ?>" method="GET"
                            style="max-width: 400px; margin: 40px 0px;">

                            <div class="form-group">
                                <label for="fdate" class="control-label">From Date:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="fdate" placeholder="YYYY-MM-DD" value="<?php echo e($fdate ? $fdate : ''); ?>"
                                        type="text" class="form-control pull-right" id="datepicker1" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="todate" class="control-label">To Date:</label>
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="todate" placeholder="YYYY-MM-DD" value="<?php echo e($todate ? $todate : ''); ?>"
                                        type="text" class="form-control pull-right" id="datepicker2" autocomplete="off">
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
                                    <thead>
                                        <tr>
                                            <th>Order Number</th>
                                            <th>Details Quantity</th>
                                            <th>Approved Quantity</th>
                                            <th>Difference</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $report; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr
                                                style="background-color: <?php echo e($row['status'] == 'Mismatch' ? '#f8d7da' : '#d4edda'); ?>">
                                                <td><?php echo e($row['order_id']); ?></td>
                                                <td><?php echo e($row['details_qty']); ?></td>
                                                <td><?php echo e($row['posting_qty']); ?></td>
                                                <td><?php echo e($row['difference']); ?></td>
                                                <td><?php echo e($row['status']); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.master_accounts', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>