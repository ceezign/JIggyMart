<?php $__env->startSection('title', 'My Products'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between mb-4">
    <h2>My Products</h2>
    <a href="<?php echo e(route('seller.products.create')); ?>" class="btn btn-primary">+ Add Product</a>
</div>
<table class="table align-middle">
    <thead><tr><th>Image</th><th>Name</th><th>Price</th><th>Stock</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><img src="<?php echo e($product->images->first()?->url ?? 'https://placehold.co/50'); ?>" style="width:50px;height:50px;object-fit:cover;" class="rounded"></td>
            <td><?php echo e($product->name); ?></td>
            <td>₦<?php echo e(number_format($product->price, 2)); ?></td>
            <td><?php echo e($product->stock_quantity); ?></td>
            <td><span class="badge bg-secondary-subtle text-secondary-emphasis text-capitalize"><?php echo e(str_replace('_',' ',$product->status)); ?></span></td>
            <td>
                <a href="<?php echo e(route('seller.products.edit', $product)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                <form method="POST" action="<?php echo e(route('seller.products.destroy', $product)); ?>" class="d-inline">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($products->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/seller/products/index.blade.php ENDPATH**/ ?>