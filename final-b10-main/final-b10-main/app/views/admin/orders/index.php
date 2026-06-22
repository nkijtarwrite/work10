<section class="section-head">
    <div>
        <span class="eyebrow">訂單管理</span>
        <h1>查看與更新訂單</h1>
    </div>
</section>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>訂單編號</th>
                <th>會員</th>
                <th>取貨日期</th>
                <th>金額</th>
                <th>狀態</th>
                <th>更新狀態</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= e($order['order_no']) ?></td>
                    <td><?= e($order['customer_name']) ?><br><small><?= e($order['customer_email']) ?></small></td>
                    <td><?= e($order['pickup_date']) ?></td>
                    <td><?= e(money((float) $order['total'])) ?></td>
                    <td><span class="status <?= e($order['status']) ?>"><?= e($order['status']) ?></span></td>
                    <td>
                        <form class="inline-status" method="post" action="<?= e(route('admin/order-update')) ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $order['id'] ?>">
                            <select name="status">
                                <?php foreach (['pending' => '待處理', 'confirmed' => '已確認', 'completed' => '已完成', 'cancelled' => '已取消'] as $value => $label): ?>
                                    <option value="<?= e($value) ?>" <?= ($order['status'] ?? '') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <button class="button small" type="submit">更新</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>