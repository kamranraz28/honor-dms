<?php $__env->startSection('title'); ?>
    <?php echo e("Sales Automation Process :: Products List"); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <section class="col-lg-12 connectedSortable">

                <div class="box box-warning">

                    <div class="box-header with-border">
                        <h3 class="box-title text-danger">Products List</h3>
                        
                        <div class="box-tools pull-right">
                            <a href="<?php echo e(route('products.create')); ?>"
                               class="action-btn action-sync">
                                <span class="btn-icon">
                                    <i class="fa fa-plus"></i>
                                </span>
                                <span class="btn-text">Add Product</span>
                                <span class="action-chip">Create</span>
                            </a>
                        </div>
                    </div>

                    <div class="box-body">

                        
                        <?php if(Session::has('success')): ?>
                            <div class="alert alert-success alert-dismissible fade in">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <strong>Success!</strong> <?php echo e(Session::get('success')); ?>

                            </div>
                        <?php endif; ?>

                        <br>
                        <br>
                        <br>

                        
                        <table id="example"
                               class="ui celled table"
                               cellspacing="0"
                               width="100%">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Action</th>
                                    <th>Product</th>
                                    <th>Model</th>
                                    <th>Product Code</th>
                                    <th>LD Price (BDT)</th>
                                    <th>Color</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>Chalan Type</th>
                                    <th>Details</th>
                                    <th>Created Date</th>
                                    <th>Image</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>

                                        
                                        <td>
                                            <a class="btn btn-xs btn-primary"
                                                    href="<?php echo e(route('products.edit', $element['id'])); ?>">
                                                <i class="fa fa-pencil-square-o"></i>
                                            </a>

                                            
                                            <form action="<?php echo e(route('products.destroy', $element['id'])); ?>"
                                                method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this product?');">

                                                <?php echo e(csrf_field()); ?>

                                                <?php echo e(method_field('DELETE')); ?>


                                                <button type="submit" class="btn btn-xs btn-danger">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </form>
                                        </td>

                                        <td><?php echo e($element['name']); ?></td>
                                        <td><?php echo e($element['model']); ?></td>
                                        <td><?php echo e($element['product_code']); ?></td>
                                        <td><?php echo e($element['dp']); ?></td>
                                        <td><?php echo e($element['color']); ?></td>
                                        <td><?php echo e($element['brand']['name'] ?? 'N/A'); ?></td>
                                        <td><?php echo e($element['cat']['name'] ?? 'N/A'); ?></td>
                                        <td><?php echo e($element['chalan_type']); ?></td>

                                        
                                        <td class="text-justify"
                                            style="cursor:pointer;color:black;font-weight:bolder"
                                            data-toggle="modal"
                                            data-target="#<?php echo e('detailsfoModal'.$element['id']); ?>">

                                            <?php if($element['details']): ?>
                                                <?php echo substr($element['details'], 0, 40); ?>

                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php echo e(date_format(date_create($element['created_at']), "d-M-Y")); ?>

                                        </td>

                                        
                                        <td>
                                            <?php if($element['photo']): ?>
                                                <a target="_blank"
                                                   href="<?php echo e(asset('storage/app/d/nokia/' . $element['photo'])); ?>">
                                                    View Photo
                                                </a>
                                            <?php else: ?>
                                                No Image File
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>

                        </table>

                    </div>
                </div>

            </section>
        </div>

    </section>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master_admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>