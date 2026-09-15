<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card p-3">
            <h6 class="mb-3">Filters</h6>
            <form method="GET">
                <?php if(request('search')): ?><input type="hidden" name="search" value="<?php echo e(request('search')); ?>"><?php endif; ?>
                <div class="mb-3">
                    <label class="form-label small">Category</label>
                    <select name="category" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All categories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <optgroup label="<?php echo e($cat->name); ?>">
                                <option value="<?php echo e($cat->id); ?>" <?php if(request('category') == $cat->id): echo 'selected'; endif; ?>>All <?php echo e($cat->name); ?></option>
                                <?php $__currentLoopData = $cat->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($child->id); ?>" <?php if(request('category') == $child->id): echo 'selected'; endif; ?>><?php echo e($child->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Price range (₦)</label>
                    <div class="d-flex gap-2">
                        <input type="number" name="min_price" class="form-control form-control-sm" placeholder="Min" value="<?php echo e(request('min_price')); ?>">
                        <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Max" value="<?php echo e(request('max_price')); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Condition</label>
                    <select name="condition" class="form-select form-select-sm">
                        <option value="">Any</option>
                        <option value="new" <?php if(request('condition') == 'new'): echo 'selected'; endif; ?>>New</option>
                        <option value="used" <?php if(request('condition') == 'used'): echo 'selected'; endif; ?>>Used</option>
                        <option value="refurbished" <?php if(request('condition') == 'refurbished'): echo 'selected'; endif; ?>>Refurbished</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small">Brand</label>
                    <select name="brand" class="form-select form-select-sm">
                        <option value="">Any</option>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($brand); ?>" <?php if(request('brand') == $brand): echo 'selected'; endif; ?>><?php echo e($brand); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="in_stock" value="1" id="inStock" <?php if(request('in_stock')): echo 'checked'; endif; ?>>
                    <label class="form-check-label small" for="inStock">In stock only</label>
                </div>
                <button class="btn btn-primary btn-sm w-100" type="submit">Apply Filters</button>
                <a href="<?php echo e(route('products.index')); ?>" class="btn btn-link btn-sm w-100">Clear</a>
            </form>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0 text-muted"><?php echo e($products->total()); ?> results <?php if(request('search')): ?> for "<?php echo e(request('search')); ?>" <?php endif; ?></p>
            <form method="GET" class="d-flex align-items-center gap-2">
                <?php $__currentLoopData = request()->except('sort'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="<?php echo e($k); ?>" value="<?php echo e($v); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <label class="small text-muted mb-0">Sort:</label>
                <select name="sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                    <option value="newest" <?php if(request('sort', 'newest') == 'newest'): echo 'selected'; endif; ?>>Newest</option>
                    <option value="price_asc" <?php if(request('sort') == 'price_asc'): echo 'selected'; endif; ?>>Price: Low to High</option>
                    <option value="price_desc" <?php if(request('sort') == 'price_desc'): echo 'selected'; endif; ?>>Price: High to Low</option>
                    <option value="rating" <?php if(request('sort') == 'rating'): echo 'selected'; endif; ?>>Highest Rated</option>
                    <option value="popular" <?php if(request('sort') == 'popular'): echo 'selected'; endif; ?>>Most Popular</option>
                </select>
            </form>
        </div>

        <div class="row row-cols-2 row-cols-md-3 g-4">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $product,'wishlistIds' => $wishlistIds]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product),'wishlist-ids' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($wishlistIds)]); ?>
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted">No products matched your filters.</p>
            <?php endif; ?>
        </div>

        <div class="mt-4"><?php echo e($products->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/products/index.blade.php ENDPATH**/ ?>