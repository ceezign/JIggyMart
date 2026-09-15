<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-1">Welcome back, <?php echo e(auth()->user()->name); ?>!</h2>
<p class="text-muted mb-4">Here's what's happening with your account.</p>

<?php if(auth()->user()->hasRole('seller')): ?>
    <?php if(auth()->user()->seller_status === 'pending'): ?>
        <div class="alert alert-warning">Your seller application for <strong><?php echo e(auth()->user()->store_name); ?></strong> is awaiting admin approval. We'll let you know as soon as it's reviewed.</div>
    <?php elseif(auth()->user()->seller_status === 'rejected'): ?>
        <div class="alert alert-danger">Your seller application was not approved. <a href="<?php echo e(route('seller.register')); ?>">Re-apply here</a>.</div>
    <?php endif; ?>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold"><?php echo e($stats['total_orders']); ?></div><small class="text-muted">Total Orders</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold"><?php echo e($stats['pending_orders']); ?></div><small class="text-muted">Pending Orders</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold"><?php echo e($stats['completed_orders']); ?></div><small class="text-muted">Completed Orders</small></div></div>
    <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold">₦<?php echo e(number_format($stats['total_spending'], 2)); ?></div><small class="text-muted">Total Spending</small></div></div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Recent Orders</h5>
            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <a href="<?php echo e(route('orders.show', $order)); ?>"><?php echo e($order->order_number); ?></a>
                    <span class="text-capitalize"><?php echo e($order->status); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted">No orders yet.</p>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3">
            <h5>Recent Transactions</h5>
            <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex justify-content-between border-bottom py-2">
                    <span><?php echo e($txn->reference); ?></span>
                    <span>₦<?php echo e(number_format($txn->amount, 2)); ?> · <span class="text-capitalize"><?php echo e($txn->status); ?></span></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted">No transactions yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <a href="<?php echo e(route('dashboard.profile')); ?>" class="btn btn-outline-secondary btn-sm">Profile</a>
    <a href="<?php echo e(route('addresses.index')); ?>" class="btn btn-outline-secondary btn-sm">Addresses</a>
    <a href="<?php echo e(route('orders.index')); ?>" class="btn btn-outline-secondary btn-sm">Orders</a>
    <a href="<?php echo e(route('dashboard.transactions')); ?>" class="btn btn-outline-secondary btn-sm">Transactions</a>
    <a href="<?php echo e(route('wishlist.index')); ?>" class="btn btn-outline-secondary btn-sm">Wishlist</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/customer/index.blade.php ENDPATH**/ ?>