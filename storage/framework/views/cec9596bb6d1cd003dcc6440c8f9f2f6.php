<?php $__env->startSection('title', 'Manage Sellers'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Sellers</h2>
<table class="table">
    <thead><tr><th>Store</th><th>Owner</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($seller->store_name); ?></td>
            <td><?php echo e($seller->name); ?> (<?php echo e($seller->email); ?>)</td>
            <td><span class="badge bg-secondary-subtle text-secondary-emphasis text-capitalize"><?php echo e($seller->seller_status); ?></span></td>
            <td>
                <?php if($seller->seller_status === 'pending'): ?>
                    <form method="POST" action="<?php echo e(route('admin.sellers.approve', $seller)); ?>" class="d-inline"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-success">Approve</button></form>
                    <form method="POST" action="<?php echo e(route('admin.sellers.reject', $seller)); ?>" class="d-inline"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-danger">Reject</button></form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($sellers->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/admin/sellers.blade.php ENDPATH**/ ?>