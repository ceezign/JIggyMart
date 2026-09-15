<?php $__env->startSection('title', 'My Orders'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">My Orders</h2>
<div class="table-responsive">
<table class="table">
    <thead><tr><th>Order #</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr></thead>
    <tbody>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($order->order_number); ?></td>
                <td><?php echo e($order->created_at->format('M d, Y')); ?></td>
                <td><?php echo e($order->items->count()); ?></td>
                <td>₦<?php echo e(number_format($order->grand_total, 2)); ?></td>
                <td><span class="badge bg-info-subtle text-info-emphasis text-capitalize"><?php echo e($order->status); ?></span></td>
                <td><a href="<?php echo e(route('orders.show', $order)); ?>" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
</div>
<?php echo e($orders->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/orders/index.blade.php ENDPATH**/ ?>