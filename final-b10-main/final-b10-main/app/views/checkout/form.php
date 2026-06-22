<section class="section-head">
    <div>
        <span class="eyebrow">預約取貨</span>
        <h1>填寫取貨資訊</h1>
    </div>
</section>

<div class="checkout-layout">
    <form class="form-card" method="post" action="<?= e(route('checkout')) ?>">
        <?= csrf_field() ?>
        <label>
            <span>取貨日期</span>
            <input type="date" name="pickup_date" required>
        </label>
        <label>
            <span>備註</span>
            <textarea name="note" rows="5" placeholder="例如：蛋糕蠟燭、少糖、提前取貨時間"></textarea>
        </label>
        <button class="button" type="submit">送出預約</button>
    </form>

    <aside class="summary-card">
        <h2>目前購物車</h2>
        <?php foreach ($cart['items'] as $item): ?>
            <div class="summary-line"><span><?= e($item['product']['name']) ?> x <?= (int) $item['quantity'] ?></span><strong><?= e(money((float) $item['line_total'])) ?></strong></div>
        <?php endforeach; ?>
        <div class="summary-line total"><span>總計</span><strong><?= e(money((float) $cart['subtotal'])) ?></strong></div>
    </aside>
</div>