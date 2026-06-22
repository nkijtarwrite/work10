<section class="section-head">
    <div>
        <span class="eyebrow">商品總覽</span>
        <h1>西式甜點選購區</h1>
    </div>
    <form class="inline-filter" method="get" action="<?= e(route('products')) ?>">
        <input type="hidden" name="page" value="products">
        <input type="search" name="q" value="<?= e($keyword) ?>" placeholder="搜尋甜點名稱或描述">
        <select name="category">
            <option value="">全部分類</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= e($category) ?>" <?= $selectedCategory === $category ? 'selected' : '' ?>><?= e($category) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="button small" type="submit">篩選</button>
    </form>
</section>

<section class="card-grid">
    <?php foreach ($products as $product): ?>
        <article class="product-card">
            <img src="<?= e(product_image_url($product)) ?>" alt="<?= e($product['name']) ?>">
            <div class="card-body">
                <div class="card-meta">
                    <span><?= e($product['category']) ?></span>
                    <strong><?= e(money((float) $product['price'])) ?></strong>
                </div>
                <h3><?= e($product['name']) ?></h3>
                <p><?= e($product['description']) ?></p>
                <div class="stock-row">
                    <span>庫存 <?= (int) $product['stock'] ?></span>
                    <span class="status online">可預訂</span>
                </div>
                <div class="card-actions">
                    <a class="button small secondary" href="<?= e(route('product', ['id' => $product['id']])) ?>">詳細</a>
                    <form method="post" action="<?= e(route('cart-add')) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="back" value="<?= e(route('products')) ?>">
                        <button class="button small" type="submit">加入購物車</button>
                    </form>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</section>