<?php $__env->startSection('title', 'Become a Seller'); ?>
<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card p-4">
            <h3 class="mb-3">Become a Seller</h3>
            <p class="text-muted">Tell us about your store. Your application will be reviewed by our team before you can publish listings.</p>
            <form method="POST" action="<?php echo e(route('seller.register.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label class="form-label">Store name</label>
                    <input type="text" name="store_name" class="form-control" required value="<?php echo e(old('store_name')); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Store description</label>
                    <textarea name="store_description" class="form-control" rows="4"><?php echo e(old('store_description')); ?></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Submit Application</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/auth/register-seller.blade.php ENDPATH**/ ?>