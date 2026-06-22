<section class="section-head">
    <div>
        <span class="eyebrow">我的訂單</span>
        <h1>訂單追蹤</h1>
    </div>
</section>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>訂單編號</th>
                <th>取貨日期</th>
                <th>金額</th>
                <th>狀態</th>
                <th>建立時間</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= e($order['order_no']) ?></td>
                    <td><?= e($order['pickup_date']) ?></td>
                    <td><?= e(money((float) $order['total'])) ?></td>
                    <td><span class="status <?= e($order['status']) ?>"><?= e($order['status']) ?></span></td>
                    <td><?= e($order['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>