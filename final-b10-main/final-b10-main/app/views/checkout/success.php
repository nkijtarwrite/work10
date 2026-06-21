<section class="success-card">
    <span class="eyebrow">訂單成立</span>
    <h1>預約已成功送出</h1>
    <p>訂單編號：<?= e($order['order_no']) ?></p>
    <p>取貨日期：<?= e($order['pickup_date']) ?></p>
    <p>金額：<?= e(money((float) $order['total'])) ?></p>
    <div class="hero-actions">
        <a class="button" href="<?= e(route('my-orders')) ?>">查看我的訂單</a>
        <a class="button secondary" href="<?= e(route('products')) ?>">繼續選購</a>
    </div>
</section>