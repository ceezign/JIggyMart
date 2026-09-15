<?php $__env->startSection('title', 'Seller Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-1">Seller Hub</h2>
<p class="text-muted mb-4"><?php echo e(auth()->user()->store_name ?? auth()->user()->name); ?>

    <?php if(auth()->user()->seller_status !== 'approved'): ?>
        <span class="badge bg-warning-subtle text-warning-emphasis text-capitalize"><?php echo e(auth()->user()->seller_status); ?></span>
    <?php endif; ?>
</p>

<?php if(auth()->user()->seller_status !== 'approved'): ?>
    <div class="alert alert-warning">Your seller account is <strong><?php echo e(auth()->user()->seller_status); ?></strong>. You can't publish listings until an administrator approves your application.</div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold"><?php echo e($stats['total_products']); ?></div><small class="text-muted">Products</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold"><?php echo e($stats['active_listings']); ?></div><small class="text-muted">Active</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold"><?php echo e($stats['out_of_stock']); ?></div><small class="text-muted">Out of Stock</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold">₦<?php echo e(number_format($stats['total_sales'], 0)); ?></div><small class="text-muted">Revenue</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold"><?php echo e($stats['pending_orders']); ?></div><small class="text-muted">Pending Orders</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fs-5 fw-bold"><?php echo e($stats['completed_orders']); ?></div><small class="text-muted">Delivered</small></div></div>
</div>

<div class="d-flex gap-2 mb-4">
    <a href="<?php echo e(route('seller.products.index')); ?>" class="btn btn-outline-secondary btn-sm">My Products</a>
    <a href="<?php echo e(route('seller.products.create')); ?>" class="btn btn-primary btn-sm">+ Add Product</a>
    <a href="<?php echo e(route('seller.orders')); ?>" class="btn btn-outline-secondary btn-sm">Orders</a>
    <a href="<?php echo e(route('seller.sales')); ?>" class="btn btn-outline-secondary btn-sm">Sales Analytics</a>
    <a href="<?php echo e(route('seller.profile')); ?>" class="btn btn-outline-secondary btn-sm">Store Profile</a>
</div>

<div class="card p-3">
    <h5>Recent Orders</h5>
    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="d-flex justify-content-between border-bottom py-2">
            <span><?php echo e($order->order_number); ?></span>
            <span class="text-capitalize"><?php echo e($order->status); ?></span>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-muted">No orders yet.</p>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/seller/index.blade.php ENDPATH**/ ?>