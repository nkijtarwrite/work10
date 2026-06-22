<section class="section-head">
    <div>
        <span class="eyebrow">商品編輯</span>
        <h1><?= (int) $product['id'] > 0 ? '編輯商品' : '新增商品' ?></h1>
    </div>
</section>

<form class="form-card form-grid" method="post" enctype="multipart/form-data" action="<?= e(route('admin/product-save')) ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">

    <label>
        <span>商品名稱</span>
        <input type="text" name="name" value="<?= e($product['name']) ?>" required>
    </label>
    <label>
        <span>分類</span>
        <input type="text" name="category" value="<?= e($product['category']) ?>" required>
    </label>
    <label>
        <span>價格</span>
        <input type="number" name="price" min="1" value="<?= e((string) $product['price']) ?>" required>
    </label>
    <label>
        <span>庫存</span>
        <input type="number" name="stock" min="0" value="<?= e((string) $product['stock']) ?>" required>
    </label>
    <label class="full-width">
        <span>商品描述</span>
        <textarea name="description" rows="5" required><?= e($product['description']) ?></textarea>
    </label>
    <label>
        <span>商品圖片</span>
        <input type="file" name="image" accept="image/*">
    </label>
    <label class="checkbox-line">
        <input type="checkbox" name="active" <?= (bool) $product['active'] ? 'checked' : '' ?>>
        <span>上架中</span>
    </label>

    <button class="button" type="submit">儲存商品</button>
</form>