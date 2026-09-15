<?php $__env->startSection('title', 'Manage Transactions'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Transactions</h2>
<table class="table">
    <thead><tr><th>Reference</th><th>User</th><th>Order</th><th>Amount</th><th>Status</th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($txn->reference); ?></td>
            <td><?php echo e($txn->user->name); ?></td>
            <td><?php echo e($txn->order->order_number); ?></td>
            <td>₦<?php echo e(number_format($txn->amount, 2)); ?></td>
            <td class="text-capitalize"><?php echo e($txn->status); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($transactions->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/admin/transactions.blade.php ENDPATH**/ ?>