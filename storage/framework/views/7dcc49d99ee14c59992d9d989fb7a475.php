<?php $__env->startSection('title', 'Addresses'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">My Addresses</h2>
<div class="row g-4">
    <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4">
            <div class="card p-3">
                <strong><?php echo e($address->label); ?></strong> <?php if($address->is_default): ?><span class="badge bg-primary">Default</span><?php endif; ?>
                <p class="mb-1"><?php echo e($address->full_name); ?></p>
                <p class="mb-1 small"><?php echo e($address->line1); ?>, <?php echo e($address->city); ?>, <?php echo e($address->state); ?>, <?php echo e($address->country); ?></p>
                <p class="mb-2 small"><?php echo e($address->phone); ?></p>
                <form method="POST" action="<?php echo e(route('addresses.destroy', $address)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Add New Address</h6>
            <form method="POST" action="<?php echo e(route('addresses.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="text" name="label" class="form-control form-control-sm mb-2" placeholder="Label (e.g. Home)">
                <input type="text" name="full_name" class="form-control form-control-sm mb-2" placeholder="Full name" required>
                <input type="text" name="phone" class="form-control form-control-sm mb-2" placeholder="Phone" required>
                <input type="text" name="line1" class="form-control form-control-sm mb-2" placeholder="Address line 1" required>
                <input type="text" name="line2" class="form-control form-control-sm mb-2" placeholder="Address line 2">
                <input type="text" name="city" class="form-control form-control-sm mb-2" placeholder="City" required>
                <input type="text" name="state" class="form-control form-control-sm mb-2" placeholder="State" required>
                <input type="text" name="postal_code" class="form-control form-control-sm mb-2" placeholder="Postal Code">
                <input type="text" name="country" class="form-control form-control-sm mb-2" placeholder="Country" required value="Nigeria">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="is_default" value="1" id="isDefault">
                    <label class="form-check-label small" for="isDefault">Set as default</label>
                </div>
                <button class="btn btn-primary btn-sm w-100">Save Address</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/customer/addresses.blade.php ENDPATH**/ ?>