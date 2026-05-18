<?php $__env->startSection('title'); ?>
    <?php echo e("Sales Automation Process :: Create Product"); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <section class="col-lg-12 connectedSortable">

                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title text-danger">Create Product</h3>
                    </div>

                    <div class="box-body">

                        
                        <?php if(count($errors)): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                <strong>Whoops!</strong> There were some problems with your input.
                                <ul>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        
                        <?php if(Session::has('success')): ?>
                            <div class="alert alert-success alert-dismissible fade in">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>
                                <strong>Success!</strong> <?php echo e(Session::get('success')); ?>

                            </div>
                        <?php endif; ?>

                        
                        <form class="form-horizontal"
                              method="POST"
                              action="<?php echo e(route('products.store')); ?>"
                              enctype="multipart/form-data"
                              autocomplete="off">

                            <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                            <div class="box-body">

                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Brand</label>
                                        <input type="text"
                                               id="brand_search"
                                               class="form-control"
                                               placeholder="Type to Search brand..."
                                               list="brand_list"
                                               autocomplete="off"
                                               required>
                                        <datalist id="brand_list"></datalist>
                                        <input type="hidden" name="brand_id" id="brand_id">
                                        <span class="text-danger"><?php echo e($errors->first('brand_id')); ?></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label">Category</label>
                                        <input type="text"
                                               id="category_search"
                                               class="form-control"
                                               placeholder="Type to Search category..."
                                               list="category_list"
                                               autocomplete="off">
                                        <datalist id="category_list"></datalist>
                                        <input type="hidden" name="cat_id" id="category_id">
                                        <span class="text-danger"><?php echo e($errors->first('cat_id')); ?></span>
                                    </div>
                                </div>

                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Product Name</label>
                                        <input type="text"
                                               name="name"
                                               class="form-control"
                                               placeholder="Enter Product"
                                               value="<?php echo e(old('name')); ?>"
                                               required>
                                        <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label">Product Model</label>
                                        <input type="text"
                                               name="model"
                                               class="form-control"
                                               placeholder="Enter Product Model Name"
                                               value="<?php echo e(old('model')); ?>"
                                               required>
                                        <span class="text-danger"><?php echo e($errors->first('model')); ?></span>
                                    </div>
                                </div>

                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Product Code</label>
                                        <input type="text"
                                               name="product_code"
                                               class="form-control"
                                               placeholder="Enter Product Code"
                                               value="<?php echo e(old('product_code')); ?>"
                                               required>
                                        <span class="text-danger"><?php echo e($errors->first('product_code')); ?></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label">Color</label>
                                        <input type="text"
                                               name="color"
                                               class="form-control"
                                               placeholder="Enter Product Color"
                                               value="<?php echo e(old('color')); ?>">
                                        <span class="text-danger"><?php echo e($errors->first('color')); ?></span>
                                    </div>
                                </div>

                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">Distributor Price</label>
                                        <input type="text"
                                               name="dp"
                                               class="form-control"
                                               placeholder="Enter Distributor Price"
                                               value="<?php echo e(old('dp')); ?>">
                                        <span class="text-danger"><?php echo e($errors->first('dp')); ?></span>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label">Chalan Type</label>
                                        <input type="text"
                                               name="chalan_type"
                                               class="form-control"
                                               placeholder="Enter Chalan Type"
                                               value="<?php echo e(old('chalan_type')); ?>">
                                        <span class="text-danger"><?php echo e($errors->first('chalan_type')); ?></span>
                                    </div>
                                </div>

                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Details</label>
                                        <textarea name="details"
                                                  rows="2"
                                                  class="form-control"
                                                  placeholder="Input Details"><?php echo e(old('details')); ?></textarea>
                                        <span class="text-danger"><?php echo e($errors->first('details')); ?></span>
                                    </div>
                                </div>

                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Product Image</label>
                                        <input type="file"
                                               name="image"
                                               class="form-control">
                                        <span class="text-danger"><?php echo e($errors->first('image')); ?></span>
                                    </div>
                                </div>

                            </div>

                            <div class="box-footer">
                                <button type="submit" class="action-btn action-sync">
                                    <span class="btn-icon">
                                        <i class="fa fa-save"></i>
                                    </span>
                                    <span class="btn-text">Save Product</span>
                                    <span class="action-chip">Submit</span>
                                </button>
                            </div>

                        </form>
                        

                    </div>
                </div>

            </section>
        </div>

    </section>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master_admin', \Illuminate\Support\Arr::except(get_defined_vars(), array('__data', '__path')))->render(); ?>