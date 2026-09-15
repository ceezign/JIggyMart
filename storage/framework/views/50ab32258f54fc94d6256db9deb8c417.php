<?php $__env->startSection('title', 'Order '.$order->order_number); ?>
<?php $__env->startSection('content'); ?>
<h2 class="mb-1">Order <?php echo e($order->order_number); ?></h2>
<p class="text-muted">Placed <?php echo e($order->created_at->format('M d, Y h:ia')); ?> · Status:
    <span class="badge bg-info-subtle text-info-emphasis text-capitalize"><?php echo e($order->status); ?></span>
</p>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card p-3 mb-3">
            <h5>Items</h5>
            <table class="table">
                <tbody>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->product_name); ?></td>
                        <td><?php echo e($item->quantity); ?> × ₦<?php echo e(number_format($item->unit_price, 2)); ?></td>
                        <td>₦<?php echo e(number_format($item->line_total, 2)); ?></td>
                        <td>
                            <?php if($order->status === 'delivered' && !$item->review): ?>
                                <a href="#reviewModal<?php echo e($item->id); ?>" data-bs-toggle="modal" class="btn btn-sm btn-outline-secondary">Leave Review</a>
                                <div class="modal fade" id="reviewModal<?php echo e($item->id); ?>">
                                    <div class="modal-dialog">
                                        <form method="POST" action="<?php echo e(route('reviews.store')); ?>" class="modal-content p-3">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="order_item_id" value="<?php echo e($item->id); ?>">
                                            <h6>Review <?php echo e($item->product_name); ?></h6>
                                            <select name="rating" class="form-select mb-2">
                                                <?php for($i=5;$i>=1;$i--): ?><option value="<?php echo e($i); ?>"><?php echo e($i); ?> Star<?php echo e($i>1?'s':''); ?></option><?php endfor; ?>
                                            </select>
                                            <textarea name="comment" class="form-control mb-2" placeholder="Share your experience"></textarea>
                                            <button class="btn btn-primary btn-sm">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <?php if($order->shippingAddress): ?>
        <div class="card p-3">
            <h5>Shipping Address</h5>
            <p class="mb-0"><?php echo e($order->shippingAddress->full_name); ?><br>
            <?php echo e($order->shippingAddress->line1); ?>, <?php echo e($order->shippingAddress->city); ?>, <?php echo e($order->shippingAddress->state); ?><br>
            <?php echo e($order->shippingAddress->phone); ?></p>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h5>Summary</h5>
            <div class="d-flex justify-content-between"><span>Subtotal</span><span>₦<?php echo e(number_format($order->subtotal, 2)); ?></span></div>
            <div class="d-flex justify-content-between"><span>Shipping</span><span>₦<?php echo e(number_format($order->shipping_total, 2)); ?></span></div>
            <div class="d-flex justify-content-between"><span>Tax</span><span>₦<?php echo e(number_format($order->tax_total, 2)); ?></span></div>
            <hr>
            <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>₦<?php echo e(number_format($order->grand_total, 2)); ?></span></div>

            <?php if($order->transaction): ?>
                <hr>
                <p class="small text-muted mb-0">Transaction Ref: <?php echo e($order->transaction->reference); ?></p>
                <p class="small text-muted">Payment status: <span class="text-capitalize"><?php echo e($order->transaction->status); ?></span></p>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cancel', $order)): ?>
                <form method="POST" action="<?php echo e(route('orders.cancel', $order)); ?>" class="mt-2">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Cancel this order?')">Cancel Order</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/orders/show.blade.php ENDPATH**/ ?>