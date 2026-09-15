<?php $__env->startSection('title', 'Transactions'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">My Transactions</h2>
<table class="table">
    <thead><tr><th>Reference</th><th>Order</th><th>Amount</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
    <tbody>
        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($txn->reference); ?></td>
                <td><a href="<?php echo e(route('orders.show', $txn->order)); ?>"><?php echo e($txn->order->order_number); ?></a></td>
                <td>₦<?php echo e(number_format($txn->amount, 2)); ?></td>
                <td class="text-capitalize"><?php echo e($txn->payment_method); ?></td>
                <td><span class="badge bg-info-subtle text-info-emphasis text-capitalize"><?php echo e($txn->status); ?></span></td>
                <td><?php echo e($txn->created_at->format('M d, Y')); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($transactions->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/customer/transactions.blade.php ENDPATH**/ ?>