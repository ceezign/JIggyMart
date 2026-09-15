<?php $__env->startSection('title', 'Admin Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Admin Dashboard</h2>
<div class="row g-3 mb-4">
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold"><?php echo e($stats['total_users']); ?></div><small class="text-muted">Users</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold"><?php echo e($stats['total_sellers']); ?></div><small class="text-muted">Sellers</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold"><?php echo e($stats['total_products']); ?></div><small class="text-muted">Products</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold"><?php echo e($stats['total_orders']); ?></div><small class="text-muted">Orders</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold">₦<?php echo e(number_format($stats['total_revenue'], 0)); ?></div><small class="text-muted">Revenue</small></div></div>
    <div class="col-md-2"><div class="card p-3 text-center"><div class="fw-bold"><?php echo e($stats['failed_transactions']); ?></div><small class="text-muted">Failed Txns</small></div></div>
</div>

<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-outline-secondary btn-sm">Users</a>
    <a href="<?php echo e(route('admin.sellers.index')); ?>" class="btn btn-outline-secondary btn-sm">Sellers</a>
    <a href="<?php echo e(route('admin.products.index')); ?>" class="btn btn-outline-secondary btn-sm">Products</a>
    <a href="<?php echo e(route('admin.categories.index')); ?>" class="btn btn-outline-secondary btn-sm">Categories</a>
    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-outline-secondary btn-sm">Orders</a>
    <a href="<?php echo e(route('admin.transactions.index')); ?>" class="btn btn-outline-secondary btn-sm">Transactions</a>
    <a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn btn-outline-secondary btn-sm">Reviews</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Recent Users</h5>
            <?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between border-bottom py-2"><span><?php echo e($u->name); ?></span><span class="text-muted"><?php echo e($u->email); ?></span></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Recent Orders</h5>
            <?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <a href="<?php echo e(route('admin.orders.show', $order)); ?>"><?php echo e($order->order_number); ?></a>
                    <span class="text-capitalize"><?php echo e($order->status); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/admin/index.blade.php ENDPATH**/ ?>