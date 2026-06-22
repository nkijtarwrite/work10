<section class="section-head">
    <div>
        <span class="eyebrow">購物車</span>
        <h1>確認你的甜點預訂清單</h1>
    </div>
</section>

<?php if ($cart['items'] === []): ?>
    <div class="empty-state">
        <h2>購物車目前是空的</h2>
        <p>先到商品總覽挑選甜點，再回來完成預訂。</p>
        <a class="button" href="<?= e(route('products')) ?>">前往選購</a>
    </div>
<?php else: ?>
    <form method="post" action="<?= e(route('cart-update')) ?>" class="cart-layout">
        <?= csrf_field() ?>
        <div class="cart-items">
            <?php foreach ($cart['items'] as $item): ?>
                <?php $product = $item['product']; ?>
                <article class="cart-row">
                    <img src="<?= e(product_image_url($product)) ?>" alt="<?= e($product['name']) ?>">
                    <div class="cart-info">
                        <h3><?= e($product['name']) ?></h3>
                        <p><?= e($product['category']) ?> · <?= e(money((float) $product['price'])) ?></p>
                    </div>
                    <label>
                        <span>數量</span>
                        <input type="number" name="quantities[<?= (int) $product['id'] ?>]" min="0" value="<?= (int) $item['quantity'] ?>">
                    </label>
                    <div class="cart-price"><?= e(money((float) $item['line_total'])) ?></div>
                    <button class="button small secondary" type="submit" formaction="<?= e(route('cart-remove')) ?>" formmethod="post" name="id" value="<?= (int) $product['id'] ?>">
                        移除
                    </button>
                </article>
            <?php endforeach; ?>
        </div>

        <aside class="summary-card">
            <h2>訂單摘要</h2>
            <div class="summary-line"><span>商品數</span><strong><?= (int) $cart['count'] ?></strong></div>
            <div class="summary-line"><span>小計</span><strong><?= e(money((float) $cart['subtotal'])) ?></strong></div>
            <button class="button secondary" type="submit">更新數量</button>
            <a class="button" href="<?= e(route('checkout')) ?>">前往預約取貨</a>
        </aside>
    </form>
<?php endif; ?>