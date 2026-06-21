<section class="section-head">
    <div>
        <span class="eyebrow">商品管理</span>
        <h1>維護上架商品</h1>
    </div>
    <a class="button" href="<?= e(route('admin/product-form')) ?>">新增商品</a>
</section>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>商品</th>
                <th>分類</th>
                <th>價格</th>
                <th>庫存</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <div class="row-product">
                            <img src="<?= e(product_image_url($product)) ?>" alt="<?= e($product['name']) ?>">
                            <span><?= e($product['name']) ?></span>
                        </div>
                    </td>
                    <td><?= e($product['category']) ?></td>
                    <td><?= e(money((float) $product['price'])) ?></td>
                    <td><?= (int) $product['stock'] ?></td>
                    <td><span class="status <?= (bool) $product['active'] ? 'online' : 'offline' ?>"><?= (bool) $product['active'] ? '上架' : '下架' ?></span></td>
                    <td class="table-actions">
                        <a class="button small secondary" href="<?= e(route('admin/product-form', ['id' => $product['id']])) ?>">編輯</a>
                        <form method="post" action="<?= e(route('admin/product-delete')) ?>" onsubmit="return confirm('確定要刪除這個商品嗎？')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                            <button class="button small danger" type="submit">刪除</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>