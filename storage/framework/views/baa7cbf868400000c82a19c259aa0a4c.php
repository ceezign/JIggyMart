<?php $__env->startSection('title', 'Wishlist'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">My Wishlist</h2>
<div class="row row-cols-2 row-cols-md-4 g-4">
    <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col">
            <div class="card h-100">
                <img src="<?php echo e($item->product->images->first()?->url ?? 'https://placehold.co/300'); ?>" class="card-img-top" style="height:160px;object-fit:cover;">
                <div class="card-body">
                    <h6><?php echo e($item->product->name); ?></h6>
                    <p class="fw-bold">₦<?php echo e(number_format($item->product->effective_price, 2)); ?></p>
                    <div class="d-flex gap-2">
                        <form method="POST" action="<?php echo e(route('wishlist.move', $item)); ?>">
                            <?php echo csrf_field(); ?>
                            <button class="btn btn-sm btn-primary">Move to Cart</button>
                        </form>
                        <form method="POST" action="<?php echo e(route('wishlist.destroy', $item)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted">Your wishlist is empty.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/customer/wishlist.blade.php ENDPATH**/ ?>