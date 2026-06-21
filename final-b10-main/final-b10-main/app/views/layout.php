<?php
/** @var string $title */
/** @var string $content */
/** @var ?array $user */
/** @var int $cartCount */

$successMessage = flash_pull('success');
$errorMessage = flash_pull('error');
?>
<!doctype html>
<html lang="zh-Hant">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · <?= e(APP_NAME) ?></title>
    <link rel="stylesheet" href="assets/style.css">
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="page-shell">
        <header class="topbar">
            <a class="brand" href="<?= e(route('home')) ?>">
                <span class="brand-mark">DM</span>
                <span>
                    <strong><?= e(APP_NAME) ?></strong>
                    <small>甜點預訂與商場管理系統</small>
                </span>
            </a>

            <nav class="nav-links">
                <a href="<?= e(route('home')) ?>">首頁</a>
                <a href="<?= e(route('products')) ?>">甜點選購</a>
                <a href="<?= e(route('cart')) ?>">購物車 <span class="pill"><?= (int) $cartCount ?></span></a>
                <?php if ($user !== null): ?>
                    <a href="<?= e(route('my-orders')) ?>">我的訂單</a>
                    <?php if (($user['role'] ?? 'customer') === 'admin'): ?>
                        <a href="<?= e(route('admin/dashboard')) ?>">管理後台</a>
                    <?php endif; ?>
                    <span class="user-chip">Hi, <?= e($user['name']) ?></span>
                    <a class="ghost-link" href="<?= e(route('logout')) ?>">登出</a>
                <?php else: ?>
                    <a href="<?= e(route('login')) ?>">登入</a>
                    <a class="button small" href="<?= e(route('register')) ?>">註冊</a>
                <?php endif; ?>
            </nav>
        </header>

        <main class="page-content">
            <?php if ($successMessage !== null): ?>
                <div class="alert success"><?= e($successMessage) ?></div>
            <?php endif; ?>

            <?php if ($errorMessage !== null): ?>
                <div class="alert error"><?= e($errorMessage) ?></div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>
</body>
</html>