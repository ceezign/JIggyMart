<?php $__env->startSection('title', 'Add Product'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Add Product</h2>
<div class="card p-4" style="max-width: 700px;">
    <form method="POST" action="<?php echo e(route('seller.products.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('dashboard.seller.products._form', ['product' => null], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-primary mt-2">Create Product</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/seller/products/create.blade.php ENDPATH**/ ?>