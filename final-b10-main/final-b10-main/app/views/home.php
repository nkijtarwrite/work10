<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">精緻甜點預訂平台</span>
        <h1>把法式甜點做成一座能逛、能訂、能管理的商場。</h1>
        <p>支援會員註冊啟用、商品上架、購物車預訂、訂單管理與營收統計，適合期末專題 demo 使用。</p>
        <div class="hero-actions">
            <a class="button" href="<?= e(route('products')) ?>">立即選購</a>
            <a class="button secondary" href="<?= e(route('register')) ?>">建立會員</a>
        </div>
    </div>
    <div class="hero-panel">
        <div class="stats-grid">
            <div>
                <strong><?= count($featuredProducts) ?></strong>
                <span>精選商品</span>
            </div>
            <div>
                <strong>24H</strong>
                <span>預訂流程</span>
            </div>
            <div>
                <strong>Admin</strong>
                <span>後台統計</span>
            </div>
        </div>
        <div class="hero-note">
            <h2>展示重點</h2>
            <ul>
                <li>信件啟用帳號流程</li>
                <li>商品圖片上傳與管理</li>
                <li>購物車、預約與訂單統計</li>
            </ul>
        </div>
    </div>
</section>

<section class="section-head">
    <div>
        <span class="eyebrow">人氣推薦</span>
        <h2>今天就先挑一款甜點。</h2>
    </div>
    <a href="<?= e(route('products')) ?>">查看全部商品</a>
</section>

<section class="card-grid featured-grid">
    <?php foreach ($featuredProducts as $product): ?>
        <article class="product-card">
            <img src="<?= e(product_image_url($product)) ?>" alt="<?= e($product['name']) ?>">
            <div class="card-body">
                <div class="card-meta">
                    <span><?= e($product['category']) ?></span>
                    <strong><?= e(money((float) $product['price'])) ?></strong>
                </div>
                <h3><?= e($product['name']) ?></h3>
                <p><?= e($product['description']) ?></p>
                <div class="card-actions">
                    <a class="button small secondary" href="<?= e(route('product', ['id' => $product['id']])) ?>">查看商品</a>
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