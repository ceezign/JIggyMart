<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product', 'wishlistIds' => []]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['product', 'wishlistIds' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<div class="col">
    <div class="card product-card h-100">
        <?php if(auth()->guard()->check()): ?>
            <?php ($isWishlisted = in_array($product->id, $wishlistIds)); ?>
            <form method="POST" action="<?php echo e(route('wishlist.store', $product)); ?>" onclick="event.stopPropagation()">
                <?php echo csrf_field(); ?>
                <button type="submit" class="wishlist-heart <?php echo e($isWishlisted ? 'is-active' : ''); ?>"
                        title="<?php echo e($isWishlisted ? 'In your wishlist' : 'Add to wishlist'); ?>">
                    <?php echo e($isWishlisted ? '♥' : '♡'); ?>

                </button>
            </form>
        <?php endif; ?>

        <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="product-link">
            <span class="product-media">
                <img src="<?php echo e($product->images->first()?->url ?? 'https://placehold.co/400x300?text=No+Image'); ?>"
                     class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo e($product->name); ?>"
                     onerror="this.src='https://placehold.co/400x300?text=No+Image'">
            </span>
            <div class="card-body d-flex flex-column">
                <span class="category-pill mb-2 align-self-start"><?php echo e($product->category->name ?? ''); ?></span>
                <h6 class="card-title text-dark"><?php echo e(Str::limit($product->name, 45)); ?></h6>
                <div class="mb-1">
                    <?php if($product->discount_price): ?>
                        <span class="price-discount">₦<?php echo e(number_format($product->discount_price, 2)); ?></span>
                        <span class="price-strike small ms-1">₦<?php echo e(number_format($product->price, 2)); ?></span>
                    <?php else: ?>
                        <span class="price-tag">₦<?php echo e(number_format($product->price, 2)); ?></span>
                    <?php endif; ?>
                </div>
                <div class="small rating-stars mb-2">
                    <?php echo e(str_repeat('★', round($product->average_rating))); ?><?php echo e(str_repeat('☆', 5 - round($product->average_rating))); ?>

                    <span class="text-muted">(<?php echo e($product->reviews_count); ?>)</span>
                </div>
            </div>
        </a>

        <div class="card-body pt-0 mt-auto">
            <?php if(!$product->is_in_stock): ?>
                <span class="badge bg-danger">Out of stock</span>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('cart.store')); ?>" onclick="event.stopPropagation()">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button class="btn btn-sm btn-primary w-100" type="submit">Add to Cart</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\IT\web development\laravel\JiggyMart\resources\views/components/product-card.blade.php ENDPATH**/ ?>