<?php $__env->startSection('title', $product->name); ?>
<?php $__env->startSection('content'); ?>
<div class="row g-4">
    <div class="col-md-5">
        <img src="<?php echo e($product->images->first()?->url ?? 'https://placehold.co/500x400?text=No+Image'); ?>"
             class="img-fluid rounded shadow-sm" alt="<?php echo e($product->name); ?>"
             onerror="this.src='https://placehold.co/500x400?text=No+Image'">
        <div class="d-flex gap-2 mt-2">
            <?php $__currentLoopData = $product->images->skip(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <img src="<?php echo e($img->url); ?>" style="width:60px;height:60px;object-fit:cover;" class="rounded border">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="col-md-7">
        <span class="badge bg-secondary-subtle text-secondary-emphasis"><?php echo e($product->category->name); ?></span>
        <h2 class="mt-2"><?php echo e($product->name); ?></h2>
        <p class="text-muted">Sold by
            <strong><?php echo e($product->seller->store_name ?? $product->seller->name); ?></strong> · Condition: <?php echo e(ucfirst($product->condition)); ?></p>
        <div class="text-warning mb-2">
            <?php echo e(str_repeat('★', round($product->average_rating))); ?><?php echo e(str_repeat('☆', 5 - round($product->average_rating))); ?>

            <span class="text-muted">(<?php echo e($product->reviews_count); ?> reviews)</span>
        </div>

        <div class="mb-3">
            <?php if($product->discount_price): ?>
                <span class="fs-3 text-danger fw-bold">₦<?php echo e(number_format($product->discount_price, 2)); ?></span>
                <span class="fs-6 text-muted text-decoration-line-through ms-2">₦<?php echo e(number_format($product->price, 2)); ?></span>
            <?php else: ?>
                <span class="fs-3 fw-bold">₦<?php echo e(number_format($product->price, 2)); ?></span>
            <?php endif; ?>
        </div>

        <p><?php echo e($product->description); ?></p>

        <p class="small text-muted">
            <?php if($product->is_in_stock): ?>
                <span class="text-success">In stock</span> — <?php echo e($product->stock_quantity); ?> available
            <?php else: ?>
                <span class="text-danger">Out of stock</span>
            <?php endif; ?>
        </p>

        <?php if(auth()->guard()->check()): ?>
            <div class="d-flex gap-2">
                <form method="POST" action="<?php echo e(route('cart.store')); ?>" class="d-flex gap-2 align-items-center">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    <?php if($product->variants->isNotEmpty()): ?>
                        <select name="variant_id" class="form-select form-select-sm">
                            <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($variant->id); ?>"><?php echo e($variant->name); ?>: <?php echo e($variant->value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    <?php endif; ?>
                    <input type="number" name="quantity" value="1" min="1" max="<?php echo e($product->stock_quantity); ?>" class="form-control form-control-sm" style="width:80px;">
                    <button class="btn btn-primary" type="submit" <?php if(!$product->is_in_stock): echo 'disabled'; endif; ?>>Add to Cart</button>
                </form>
                <form method="POST" action="<?php echo e(route('wishlist.store', $product)); ?>">
                    <?php echo csrf_field(); ?>
                    <button class="btn btn-outline-secondary" type="submit"><?php echo e($isWishlisted ? '♥ In Wishlist' : '♡ Add to Wishlist'); ?></button>
                </form>
            </div>
        <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">Login to purchase</a>
        <?php endif; ?>
    </div>
</div>

<hr class="my-5">

<h4>Reviews</h4>
<?php $__empty_1 = true; $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="border-bottom py-2">
        <strong><?php echo e($review->user->name); ?></strong>
        <span class="text-warning"><?php echo e(str_repeat('★', $review->rating)); ?></span>
        <?php if($review->is_verified_purchase): ?><span class="badge bg-success-subtle text-success-emphasis">Verified Purchase</span><?php endif; ?>
        <p class="mb-0"><?php echo e($review->comment); ?></p>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <p class="text-muted">No reviews yet.</p>
<?php endif; ?>

<?php if($related->isNotEmpty()): ?>
    <h4 class="mt-5">Related products</h4>
    <div class="row row-cols-2 row-cols-md-4 g-4">
        <?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $r,'wishlistIds' => $wishlistIds]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($r),'wishlist-ids' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wishlistIds)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $attributes = $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $component = $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/products/show.blade.php ENDPATH**/ ?>