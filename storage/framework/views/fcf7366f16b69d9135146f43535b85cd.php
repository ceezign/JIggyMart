<?php $__env->startSection('title', 'Checkout'); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-4">Checkout</h2>
<div class="row g-4">
    <div class="col-md-7">
        <div class="card p-3 mb-3">
            <h5>Shipping Address</h5>
            <?php if($addresses->isEmpty()): ?>
                <p class="text-muted">You have no saved addresses yet.</p>
            <?php else: ?>
                <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check border rounded p-2 mb-2">
                        <input class="form-check-input" type="radio" name="address_id" form="checkoutForm" value="<?php echo e($address->id); ?>" id="addr<?php echo e($address->id); ?>" <?php if($loop->first): echo 'checked'; endif; ?>>
                        <label class="form-check-label" for="addr<?php echo e($address->id); ?>">
                            <strong><?php echo e($address->full_name); ?></strong> (<?php echo e($address->label); ?>)<br>
                            <?php echo e($address->line1); ?>, <?php echo e($address->city); ?>, <?php echo e($address->state); ?>, <?php echo e($address->country); ?><br>
                            <?php echo e($address->phone); ?>

                        </label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <a href="<?php echo e(route('addresses.index')); ?>" class="btn btn-sm btn-outline-secondary mt-2 align-self-start">Manage Addresses</a>
        </div>

        <div class="card p-3">
            <h5>Payment Method</h5>
            <select name="payment_method" form="checkoutForm" class="form-select">
                <option value="mock">Mock Gateway (Development)</option>
                <option value="paystack">Paystack</option>
                <option value="card">Card</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card p-3">
            <h5>Order Summary</h5>
            <?php $__currentLoopData = $totals['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex justify-content-between small mb-1">
                    <span><?php echo e($item->product->name); ?> × <?php echo e($item->quantity); ?></span>
                    <span>₦<?php echo e(number_format($item->line_total, 2)); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <hr>
            <div class="d-flex justify-content-between"><span>Subtotal</span><span>₦<?php echo e(number_format($totals['subtotal'], 2)); ?></span></div>
            <div class="d-flex justify-content-between"><span>Shipping</span><span>₦2,000.00</span></div>
            <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span>₦<?php echo e(number_format($totals['subtotal'] + 2000, 2)); ?></span></div>

            <form id="checkoutForm" method="POST" action="<?php echo e(route('checkout.store')); ?>" class="mt-3">
                <?php echo csrf_field(); ?>
                <button class="btn btn-primary w-100" type="submit" <?php if($addresses->isEmpty()): echo 'disabled'; endif; ?>>Place Order & Pay</button>
            </form>
            <small class="text-muted d-block mt-2">Prices and stock are re-validated on the server; nothing here is trusted from the browser.</small>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/checkout/index.blade.php ENDPATH**/ ?>