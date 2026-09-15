<?php $__env->startSection('title', 'Your Cart'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Your Cart</h2>

<?php if($totals['items']->isEmpty()): ?>
    <div class="alert alert-info">Your cart is empty. <a href="<?php echo e(route('products.index')); ?>">Browse products</a>.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Line Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $totals['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?php echo e($item->product->images->first()?->url ?? 'https://placehold.co/60'); ?>" style="width:50px;height:50px;object-fit:cover;" class="rounded">
                                <div>
                                    <a href="<?php echo e(route('products.show', $item->product->slug)); ?>" class="text-decoration-none"><?php echo e($item->product->name); ?></a>
                                    <?php if($item->variant): ?><div class="small text-muted"><?php echo e($item->variant->name); ?>: <?php echo e($item->variant->value); ?></div><?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>₦<?php echo e(number_format($item->price_snapshot, 2)); ?></td>
                        <td style="width:140px;">
                            <form method="POST" action="<?php echo e(route('cart.update', $item)); ?>" class="d-flex gap-1">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <input type="number" name="quantity" value="<?php echo e($item->quantity); ?>" min="0" class="form-control form-control-sm">
                                <button class="btn btn-sm btn-outline-secondary" type="submit">↻</button>
                            </form>
                        </td>
                        <td>₦<?php echo e(number_format($item->line_total, 2)); ?></td>
                        <td>
                            <form method="POST" action="<?php echo e(route('cart.destroy', $item)); ?>">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger" type="submit">Remove</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        <div class="card p-3" style="width: 320px;">
            <div class="d-flex justify-content-between"><span>Subtotal</span><strong>₦<?php echo e(number_format($totals['subtotal'], 2)); ?></strong></div>
            <small class="text-muted">Shipping and taxes calculated at checkout.</small>
            <a href="<?php echo e(route('checkout.show')); ?>" class="btn btn-primary mt-3">Proceed to Checkout</a>
        </div>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/cart/index.blade.php ENDPATH**/ ?>