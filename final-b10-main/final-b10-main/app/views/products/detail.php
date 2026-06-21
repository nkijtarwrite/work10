<section class="product-detail">
    <div class="detail-image">
        <img src="<?= e(product_image_url($product)) ?>" alt="<?= e($product['name']) ?>">
    </div>
    <div class="detail-copy">
        <span class="eyebrow"><?= e($product['category']) ?></span>
        <h1><?= e($product['name']) ?></h1>
        <p class="price-large"><?= e(money((float) $product['price'])) ?></p>
        <p><?= e($product['description']) ?></p>

        <div class="detail-meta">
            <span>庫存 <?= (int) $product['stock'] ?></span>
            <span>可取貨：最晚前一天 18:00 下單</span>
        </div>

        <form class="detail-form" method="post" action="<?= e(route('cart-add')) ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
            <input type="hidden" name="back" value="<?= e(route('product', ['id' => $product['id']])) ?>">
            <label>
                <span>數量</span>
                <input type="number" name="quantity" value="1" min="1" max="<?= (int) $product['stock'] ?>">
            </label>
            <button class="button" type="submit">加入購物車</button>
        </form>
    </div>
</section>