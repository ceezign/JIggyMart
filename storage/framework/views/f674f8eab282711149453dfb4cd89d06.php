<?php $__env->startSection('title', 'Manage Users'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Users</h2>
<table class="table">
    <thead><tr><th>Name</th><th>Email</th><th>Roles</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($user->name); ?></td>
            <td><?php echo e($user->email); ?></td>
            <td><?php echo e($user->roles->pluck('name')->join(', ')); ?></td>
            <td><span class="badge <?php echo e($user->status === 'active' ? 'bg-success' : 'bg-danger'); ?>"><?php echo e($user->status); ?></span></td>
            <td>
                <?php if($user->status === 'active'): ?>
                    <form method="POST" action="<?php echo e(route('admin.users.suspend', $user)); ?>"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-danger">Suspend</button></form>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('admin.users.reinstate', $user)); ?>"><?php echo csrf_field(); ?><button class="btn btn-sm btn-outline-success">Reinstate</button></form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($users->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/admin/users.blade.php ENDPATH**/ ?>