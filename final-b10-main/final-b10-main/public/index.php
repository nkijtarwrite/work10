<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/app/bootstrap.php';

$page = $_GET['page'] ?? 'home';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    if ($page === 'logout') {
        logout_user();
        flash_set('success', '已成功登出。');
        redirect(route('home'));
    }

    if ($page === 'activate') {
        $token = (string) ($_GET['token'] ?? '');
        if ($token !== '' && activate_user_by_token($token)) {
            flash_set('success', '帳號已啟用，請登入。');
        } else {
            flash_set('error', '啟用連結無效或已失效。');
        }

        redirect(route('login'));
    }

    if ($page === 'register') {
        if ($method === 'POST') {
            verify_csrf();

            $name = trim((string) ($_POST['name'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $confirm = (string) ($_POST['password_confirm'] ?? '');

            if ($name === '' || $email === '' || $password === '') {
                throw new RuntimeException('請完整填寫註冊資料。');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new RuntimeException('請輸入有效的電子信箱。');
            }

            if (strlen($password) < 8) {
                throw new RuntimeException('密碼至少需要 8 個字元。');
            }

            if ($password !== $confirm) {
                throw new RuntimeException('兩次輸入的密碼不一致。');
            }

            $user = register_user($name, $email, $password);
            $activationLink = send_activation_mail($user);

            render('auth/register', [
                'title' => '會員註冊',
                'registered' => true,
                'activationLink' => $activationLink,
                'registeredEmail' => $user['email'],
            ]);
        }

        render('auth/register', ['title' => '會員註冊']);
    }

    if ($page === 'login') {
        if ($method === 'POST') {
            verify_csrf();

            $email = trim((string) ($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');
            $user = login_user($email, $password);

            flash_set('success', '歡迎回來，' . $user['name'] . '。');
            redirect(route('home'));
        }

        render('auth/login', ['title' => '會員登入']);
    }

    if ($page === 'home') {
        $featuredProducts = array_slice(load_products(true), 0, 6);
        render('home', [
            'title' => '首頁',
            'featuredProducts' => $featuredProducts,
        ]);
    }

    if ($page === 'products') {
        $products = load_products(true);
        $category = trim((string) ($_GET['category'] ?? ''));
        $keyword = trim((string) ($_GET['q'] ?? ''));

        if ($category !== '') {
            $products = array_values(array_filter($products, static fn (array $product): bool => (($product['category'] ?? '') === $category)));
        }

        if ($keyword !== '') {
            $products = array_values(array_filter($products, static fn (array $product): bool => str_contains((string) $product['name'], $keyword) || str_contains((string) $product['description'], $keyword)));
        }

        $categories = array_values(array_unique(array_map(static fn (array $product): string => (string) $product['category'], load_products(true))));

        render('products/list', [
            'title' => '商品總覽',
            'products' => $products,
            'categories' => $categories,
            'selectedCategory' => $category,
            'keyword' => $keyword,
        ]);
    }

    if ($page === 'product') {
        $productId = safe_int($_GET['id'] ?? 0);
        $product = find_product($productId, false);

        if ($product === null || !(bool) ($product['active'] ?? false)) {
            flash_set('error', '找不到這個商品。');
            redirect(route('products'));
        }

        render('products/detail', [
            'title' => $product['name'],
            'product' => $product,
        ]);
    }

    if ($page === 'cart-add') {
        verify_csrf();
        $productId = safe_int($_POST['id'] ?? $_GET['id'] ?? 0);
        $quantity = safe_int($_POST['quantity'] ?? 1, 1);
        $product = find_product($productId, true);

        if ($product === null) {
            throw new RuntimeException('找不到商品。');
        }

        cart_add($productId, $quantity);
        flash_set('success', '已加入購物車。');

        $back = (string) ($_POST['back'] ?? ($_GET['back'] ?? route('products')));
        redirect($back);
    }

    if ($page === 'cart-remove') {
        verify_csrf();
        $productId = safe_int($_POST['id'] ?? 0);
        cart_remove($productId);
        flash_set('success', '商品已從購物車移除。');
        redirect(route('cart'));
    }

    if ($page === 'cart-update') {
        verify_csrf();
        $quantities = is_array($_POST['quantities'] ?? null) ? $_POST['quantities'] : [];
        cart_update($quantities);
        flash_set('success', '購物車已更新。');
        redirect(route('cart'));
    }

    if ($page === 'cart') {
        $cart = cart_detail();
        render('cart/index', [
            'title' => '購物車',
            'cart' => $cart,
        ]);
    }

    if ($page === 'checkout') {
        $user = require_login();

        if ($method === 'POST') {
            verify_csrf();
            $pickupDate = trim((string) ($_POST['pickup_date'] ?? ''));
            $note = trim((string) ($_POST['note'] ?? ''));

            if ($pickupDate === '') {
                throw new RuntimeException('請選擇取貨日期。');
            }

            $order = create_order_from_cart($user, $pickupDate, $note);

            render('checkout/success', [
                'title' => '訂單完成',
                'order' => $order,
            ]);
        }

        $cart = cart_detail();
        if ($cart['items'] === []) {
            flash_set('error', '購物車是空的。');
            redirect(route('products'));
        }

        render('checkout/form', [
            'title' => '預約取貨',
            'cart' => $cart,
        ]);
    }

    if ($page === 'my-orders') {
        $user = require_login();
        $orders = array_values(array_filter(load_orders(), static fn (array $order): bool => (int) ($order['user_id'] ?? 0) === (int) $user['id']));
        render('orders/index', [
            'title' => '我的訂單',
            'orders' => $orders,
        ]);
    }

    if ($page === 'admin/dashboard') {
        require_admin();
        $stats = dashboard_stats();
        render('admin/dashboard', [
            'title' => '管理儀表板',
            'stats' => $stats,
            'chartData' => json_encode([
                'monthlyLabels' => $stats['monthly_labels'],
                'monthlyValues' => $stats['monthly_values'],
                'topLabels' => $stats['top_labels'],
                'topValues' => $stats['top_values'],
            ], JSON_UNESCAPED_UNICODE),
        ]);
    }

    if ($page === 'admin/products') {
        require_admin();
        render('admin/products/index', [
            'title' => '商品管理',
            'products' => load_products(false),
        ]);
    }

    if ($page === 'admin/product-form') {
        require_admin();
        $product = ['id' => 0, 'name' => '', 'category' => '', 'price' => 0, 'stock' => 0, 'description' => '', 'image' => '', 'active' => true];
        $productId = safe_int($_GET['id'] ?? 0);
        if ($productId > 0) {
            $found = find_product($productId, false);
            if ($found !== null) {
                $product = $found;
            }
        }

        render('admin/products/form', [
            'title' => $productId > 0 ? '編輯商品' : '新增商品',
            'product' => $product,
        ]);
    }

    if ($page === 'admin/product-save') {
        require_admin();
        verify_csrf();

        $id = safe_int($_POST['id'] ?? 0);
        $product = $id > 0 ? (find_product($id, false) ?? ['id' => $id]) : ['id' => next_product_id()];

        $product['name'] = trim((string) ($_POST['name'] ?? ''));
        $product['category'] = trim((string) ($_POST['category'] ?? ''));
        $product['price'] = (float) ($_POST['price'] ?? 0);
        $product['stock'] = max(0, safe_int($_POST['stock'] ?? 0));
        $product['description'] = trim((string) ($_POST['description'] ?? ''));
        $product['active'] = isset($_POST['active']);
        $product['image'] = upload_product_image($_FILES['image'] ?? null, (string) ($product['image'] ?? ''));
        $product['updated_at'] = now_string();

        if ($product['name'] === '' || $product['category'] === '') {
            throw new RuntimeException('商品名稱與分類不可為空。');
        }

        if ($product['price'] <= 0) {
            throw new RuntimeException('價格必須大於 0。');
        }

        if (!isset($product['created_at'])) {
            $product['created_at'] = now_string();
        }

        save_product($product);
        flash_set('success', '商品已儲存。');
        redirect(route('admin/products'));
    }

    if ($page === 'admin/product-delete') {
        require_admin();
        verify_csrf();
        $id = safe_int($_POST['id'] ?? 0);
        delete_product($id);
        flash_set('success', '商品已刪除。');
        redirect(route('admin/products'));
    }

    if ($page === 'admin/orders') {
        require_admin();
        render('admin/orders/index', [
            'title' => '訂單管理',
            'orders' => load_orders(),
        ]);
    }

    if ($page === 'admin/order-update') {
        require_admin();
        verify_csrf();
        update_order_status(safe_int($_POST['id'] ?? 0), (string) ($_POST['status'] ?? 'pending'));
        flash_set('success', '訂單狀態已更新。');
        redirect(route('admin/orders'));
    }

    if ($page === 'admin/users') {
        require_admin();
        render('admin/users/index', [
            'title' => '會員管理',
            'users' => load_users(false),
        ]);
    }

    if ($page === 'admin/user-toggle') {
        require_admin();
        verify_csrf();
        update_user_active(safe_int($_POST['id'] ?? 0));
        flash_set('success', '會員狀態已更新。');
        redirect(route('admin/users'));
    }

    http_response_code(404);
    render('home', ['title' => '找不到頁面', 'featuredProducts' => array_slice(load_products(true), 0, 6)]);
} catch (Throwable $exception) {
    flash_set('error', $exception->getMessage());
    redirect(route('home'));
}