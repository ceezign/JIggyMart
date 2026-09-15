<div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-select" required>
        <option value="">Select category</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}" @selected(old('category_id', $product->category_id ?? '') == $cat->id)>{{ $cat->name }}</option>
        @endforeach
    </select>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Price (₦)</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Discount Price (₦)</label>
        <input type="number" step="0.01" name="discount_price" class="form-control" value="{{ old('discount_price', $product->discount_price ?? '') }}">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">SKU</label>
        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Stock Quantity</label>
        <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Brand</label>
        <input type="text" name="brand" class="form-control" value="{{ old('brand', $product->brand ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Condition</label>
        <select name="condition" class="form-select">
            <option value="new" @selected(old('condition', $product->condition ?? 'new') == 'new')>New</option>
            <option value="used" @selected(old('condition', $product->condition ?? '') == 'used')>Used</option>
            <option value="refurbished" @selected(old('condition', $product->condition ?? '') == 'refurbished')>Refurbished</option>
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4" required>{{ old('description', $product->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Product Images</label>
    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
</div>
