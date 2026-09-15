<?php $__env->startSection('title', 'Manage Products'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Products</h2>
<table class="table">
    <thead><tr><th>Name</th><th>Seller</th><th>Category</th><th>Price</th><th>Status</th><th></th></tr></thead>
    <tbody>
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($product->name); ?></td>
            <td><?php echo e($product->seller->store_name ?? $product->seller->name); ?></td>
            <td><?php echo e($product->category->name); ?></td>
            <td>₦<?php echo e(number_format($product->price, 2)); ?></td>
            <td>
                <form method="POST" action="<?php echo e(route('admin.products.status', $product)); ?>" class="d-flex gap-1">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <?php $__currentLoopData = ['draft','pending','active','out_of_stock','suspended','archived']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php if($product->status == $status): echo 'selected'; endif; ?>><?php echo e(ucfirst(str_replace('_',' ',$status))); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </form>
            </td>
            <td>
                <form method="POST" action="<?php echo e(route('admin.products.destroy', $product)); ?>">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php echo e($products->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/admin/products.blade.php ENDPATH**/ ?>