<div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $product->name ?? '')); ?>" required>
</div>
<div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-select" required>
        <option value="">Select category</option>
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cat->id); ?>" <?php if(old('category_id', $product->category_id ?? '') == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Price (₦)</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?php echo e(old('price', $product->price ?? '')); ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Discount Price (₦)</label>
        <input type="number" step="0.01" name="discount_price" class="form-control" value="<?php echo e(old('discount_price', $product->discount_price ?? '')); ?>">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control" value="<?php echo e(old('sku', $product->sku ?? '')); ?>" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Stock Quantity</label>
        <input type="number" name="stock_quantity" class="form-control" value="<?php echo e(old('stock_quantity', $product->stock_quantity ?? 0)); ?>" required>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Brand</label>
        <input type="text" name="brand" class="form-control" value="<?php echo e(old('brand', $product->brand ?? '')); ?>">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Condition</label>
        <select name="condition" class="form-select">
            <option value="new" <?php if(old('condition', $product->condition ?? 'new') == 'new'): echo 'selected'; endif; ?>>New</option>
            <option value="used" <?php if(old('condition', $product->condition ?? '') == 'used'): echo 'selected'; endif; ?>>Used</option>
            <option value="refurbished" <?php if(old('condition', $product->condition ?? '') == 'refurbished'): echo 'selected'; endif; ?>>Refurbished</option>
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4" required><?php echo e(old('description', $product->description ?? '')); ?></textarea>
</div>
<div class="mb-3">
    <label class="form-label">Product Images</label>
    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
</div>
<?php /**PATH C:\Users\IT\web development\laravel\marketplace\resources\views/dashboard/seller/products/_form.blade.php ENDPATH**/ ?>