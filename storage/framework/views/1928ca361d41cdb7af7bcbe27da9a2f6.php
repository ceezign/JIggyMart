<?php $__env->startSection('title', 'Edit Product'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Edit Product</h2>
<div class="card p-4" style="max-width: 700px;">
    <form method="POST" action="<?php echo e(route('seller.products.update', $product)); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <?php echo $__env->make('dashboard.seller.products._form', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-primary mt-2">Update Product</button>
    </form>

    <?php if($product->images->isNotEmpty()): ?>
        <hr>
        <h6>Existing Images</h6>
        <div class="d-flex gap-2 flex-wrap">
            <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center">
                    <img src="<?php echo e($img->url); ?>" style="width:80px;height:80px;object-fit:cover;" class="rounded border">
                    <form method="POST" action="<?php echo e(route('seller.products.images.destroy', [$product, $img])); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="btn btn-sm btn-link text-danger p-0">Remove</button>
                    </form>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/seller/products/edit.blade.php ENDPATH**/ ?>