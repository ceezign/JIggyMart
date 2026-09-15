<?php $__env->startSection('title', 'Manage Categories'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Categories</h2>
<div class="row g-4">
    <div class="col-md-8">
        <table class="table">
            <thead><tr><th>Name</th><th>Parent</th><th>Active</th><th></th></tr></thead>
            <tbody>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($cat->name); ?></td>
                    <td><?php echo e($cat->parent->name ?? '—'); ?></td>
                    <td><?php echo e($cat->is_active ? 'Yes' : 'No'); ?></td>
                    <td>
                        <form method="POST" action="<?php echo e(route('admin.categories.destroy', $cat)); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php echo e($categories->links()); ?>

    </div>
    <div class="col-md-4">
        <div class="card p-3">
            <h6>Add Category</h6>
            <form method="POST" action="<?php echo e(route('admin.categories.store')); ?>">
                <?php echo csrf_field(); ?>
                <input type="text" name="name" class="form-control form-control-sm mb-2" placeholder="Category name" required>
                <select name="parent_id" class="form-select form-select-sm mb-2">
                    <option value="">No parent (top-level)</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="btn btn-primary btn-sm w-100">Add</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/admin/categories.blade.php ENDPATH**/ ?>