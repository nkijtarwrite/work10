<section class="auth-shell">
    <div class="auth-card">
        <span class="eyebrow">會員登入</span>
        <h1>回到西式甜點商場</h1>
        <p>登入後可以查看購物車、送出預訂並追蹤自己的訂單。</p>

        <form class="form-grid" method="post" action="<?= e(route('login')) ?>">
            <?= csrf_field() ?>
            <label>
                <span>電子信箱</span>
                <input type="email" name="email" placeholder="admin@dessert.local" required>
            </label>
            <label>
                <span>密碼</span>
                <input type="password" name="password" placeholder="請輸入密碼" required>
            </label>
            <button class="button" type="submit">登入</button>
        </form>

        <p class="form-note">還沒有帳號？<a href="<?= e(route('register')) ?>">立即註冊</a></p>
    </div>
</section>