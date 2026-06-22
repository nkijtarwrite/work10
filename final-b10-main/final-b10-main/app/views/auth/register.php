<section class="auth-shell">
    <div class="auth-card">
        <span class="eyebrow">會員註冊</span>
        <h1>建立甜點商場帳號</h1>
        <p>註冊後系統會產生啟用信，完成信件驗證後即可登入。</p>

        <?php if (!empty($registered)): ?>
            <div class="activation-box">
                <strong>註冊成功</strong>
                <p>系統已寫入啟用信測試內容，帳號 <?= e($registeredEmail) ?> 需要先完成啟用。</p>
                <p>啟用連結：<a href="<?= e($activationLink) ?>"><?= e($activationLink) ?></a></p>
                <a class="button small" href="<?= e(route('login')) ?>">前往登入</a>
            </div>
        <?php endif; ?>

        <form class="form-grid" method="post" action="<?= e(route('register')) ?>">
            <?= csrf_field() ?>
            <label>
                <span>姓名</span>
                <input type="text" name="name" required>
            </label>
            <label>
                <span>電子信箱</span>
                <input type="email" name="email" required>
            </label>
            <label>
                <span>密碼</span>
                <input type="password" name="password" minlength="8" required>
            </label>
            <label>
                <span>確認密碼</span>
                <input type="password" name="password_confirm" minlength="8" required>
            </label>
            <button class="button" type="submit">註冊並寄送啟用信</button>
        </form>

        <p class="form-note">已經有帳號？<a href="<?= e(route('login')) ?>">前往登入</a></p>
    </div>
</section>