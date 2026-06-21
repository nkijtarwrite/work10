<section class="section-head">
    <div>
        <span class="eyebrow">會員管理</span>
        <h1>啟用與停用會員</h1>
    </div>
</section>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>姓名</th>
                <th>信箱</th>
                <th>角色</th>
                <th>狀態</th>
                <th>建立時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $member): ?>
                <tr>
                    <td><?= e($member['name']) ?></td>
                    <td><?= e($member['email']) ?></td>
                    <td><?= e($member['role']) ?></td>
                    <td><span class="status <?= (bool) $member['active'] ? 'online' : 'offline' ?>"><?= (bool) $member['active'] ? '啟用' : '停用' ?></span></td>
                    <td><?= e($member['created_at']) ?></td>
                    <td>
                        <?php if (($member['role'] ?? 'customer') !== 'admin'): ?>
                            <form method="post" action="<?= e(route('admin/user-toggle')) ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= (int) $member['id'] ?>">
                                <button class="button small secondary" type="submit"><?= (bool) $member['active'] ? '停用' : '啟用' ?></button>
                            </form>
                        <?php else: ?>
                            <span class="muted">系統管理員</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>