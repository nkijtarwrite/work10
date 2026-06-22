<?php
declare(strict_types=1);

function app_path(string $path = ''): string
{
    return $path === '' ? APP_ROOT : APP_ROOT . '/' . ltrim($path, '/\\');
}

function public_path(string $path = ''): string
{
    return app_path('public' . ($path === '' ? '' : '/' . ltrim($path, '/\\')));
}

function data_path(string $path = ''): string
{
    return app_path('data' . ($path === '' ? '' : '/' . ltrim($path, '/\\')));
}

function ensure_directory(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

function load_json(string $file, array $default = []): array
{
    if (!is_file($file)) {
        return $default;
    }

    $content = file_get_contents($file);
    $decoded = json_decode((string) $content, true);

    return is_array($decoded) ? $decoded : $default;
}

function save_json(string $file, array $data): void
{
    ensure_directory(dirname($file));
    file_put_contents(
        $file,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        LOCK_EX
    );
}

function now_string(): string
{
    return date('Y-m-d H:i:s');
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function route(string $page, array $params = []): string
{
    return '?' . http_build_query(array_merge(['page' => $page], $params));
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_pull(string $key): ?string
{
    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $message = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return is_string($message) ? $message : null;
}

function csrf_token(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    }

    return (string) $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return;
    }

    $token = $_POST['_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(419);
        exit('CSRF token mismatch');
    }
}

function money(float $value): string
{
    return 'NT$' . number_format($value, 0);
}

function safe_int(mixed $value, int $default = 0): int
{
    return is_numeric($value) ? (int) $value : $default;
}

function normalize_email(string $email): string
{
    return strtolower(trim($email));
}

function init_storage(): void
{
    ensure_directory(data_path());
    ensure_directory(data_path('outbox')); 
    ensure_directory(public_path('uploads'));

    foreach (['users.json', 'products.json', 'orders.json'] as $file) {
        if (!is_file(data_path($file))) {
            save_json(data_path($file), []);
        }
    }

    $users = load_users(false);
    if (count($users) === 0) {
        $admin = [
            'id' => 1,
            'name' => '系統管理員',
            'email' => 'admin@dessert.local',
            'password_hash' => password_hash('Admin1234!', PASSWORD_DEFAULT),
            'role' => 'admin',
            'active' => true,
            'activation_token' => null,
            'created_at' => now_string(),
        ];
        save_users([$admin]);
    }

    $products = load_products(false);
    if (count($products) === 0) {
        save_products(seed_products());
    }
}

function seed_products(): array
{
    return [
        [
            'id' => 1,
            'name' => '經典提拉米蘇',
            'category' => '蛋糕',
            'price' => 420,
            'stock' => 18,
            'description' => '咖啡香與馬斯卡彭交織的經典口味，適合節慶與聚會。',
            'image' => '',
            'active' => true,
            'created_at' => now_string(),
            'updated_at' => now_string(),
        ],
        [
            'id' => 2,
            'name' => '法式檸檬塔',
            'category' => '塔派',
            'price' => 260,
            'stock' => 24,
            'description' => '酸香平衡的檸檬餡，搭配酥脆塔皮與義式蛋白霜。',
            'image' => '',
            'active' => true,
            'created_at' => now_string(),
            'updated_at' => now_string(),
        ],
        [
            'id' => 3,
            'name' => '焦糖千層蛋糕',
            'category' => '蛋糕',
            'price' => 360,
            'stock' => 20,
            'description' => '層層手工煎皮與絲滑奶油夾餡，甜而不膩。',
            'image' => '',
            'active' => true,
            'created_at' => now_string(),
            'updated_at' => now_string(),
        ],
        [
            'id' => 4,
            'name' => '黑森林櫻桃塔',
            'category' => '塔派',
            'price' => 300,
            'stock' => 16,
            'description' => '櫻桃果香、可可與鮮奶油交疊的濃郁系甜點。',
            'image' => '',
            'active' => true,
            'created_at' => now_string(),
            'updated_at' => now_string(),
        ],
        [
            'id' => 5,
            'name' => '焙茶可麗露',
            'category' => '點心',
            'price' => 150,
            'stock' => 36,
            'description' => '外脆內嫩，焙茶香氣清爽，適合下午茶單點。',
            'image' => '',
            'active' => true,
            'created_at' => now_string(),
            'updated_at' => now_string(),
        ],
        [
            'id' => 6,
            'name' => '草莓巴斯克乳酪',
            'category' => '乳酪蛋糕',
            'price' => 380,
            'stock' => 14,
            'description' => '濃厚乳酪搭配草莓果醬，口感柔滑綿密。',
            'image' => '',
            'active' => true,
            'created_at' => now_string(),
            'updated_at' => now_string(),
        ],
    ];
}

function load_users(bool $activeOnly = false): array
{
    $users = load_json(data_path('users.json'), []);

    if (!$activeOnly) {
        return $users;
    }

    return array_values(array_filter($users, static fn (array $user): bool => (bool) ($user['active'] ?? false)));
}

function save_users(array $users): void
{
    save_json(data_path('users.json'), array_values($users));
}

function find_user_by_id(int $id): ?array
{
    foreach (load_users(false) as $user) {
        if ((int) $user['id'] === $id) {
            return $user;
        }
    }

    return null;
}

function find_user_by_email(string $email): ?array
{
    $needle = normalize_email($email);

    foreach (load_users(false) as $user) {
        if (normalize_email((string) ($user['email'] ?? '')) === $needle) {
            return $user;
        }
    }

    return null;
}

function find_user_by_token(string $token): ?array
{
    foreach (load_users(false) as $user) {
        if (($user['activation_token'] ?? null) === $token) {
            return $user;
        }
    }

    return null;
}

function upsert_user(array $user): void
{
    $users = load_users(false);
    $replaced = false;

    foreach ($users as $index => $existing) {
        if ((int) $existing['id'] === (int) $user['id']) {
            $users[$index] = $user;
            $replaced = true;
            break;
        }
    }

    if (!$replaced) {
        $users[] = $user;
    }

    save_users($users);
}

function next_user_id(): int
{
    $users = load_users(false);

    if ($users === []) {
        return 1;
    }

    return max(array_map(static fn (array $user): int => (int) $user['id'], $users)) + 1;
}

function register_user(string $name, string $email, string $password): array
{
    if (find_user_by_email($email)) {
        throw new RuntimeException('此信箱已經註冊過。');
    }

    $user = [
        'id' => next_user_id(),
        'name' => trim($name),
        'email' => normalize_email($email),
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'role' => 'customer',
        'active' => false,
        'activation_token' => bin2hex(random_bytes(16)),
        'created_at' => now_string(),
    ];

    upsert_user($user);

    return $user;
}

function activate_user_by_token(string $token): bool
{
    $user = find_user_by_token($token);
    if ($user === null) {
        return false;
    }

    $user['active'] = true;
    $user['activation_token'] = null;
    upsert_user($user);

    return true;
}

function send_activation_mail(array $user): string
{
    $link = route('activate', ['token' => $user['activation_token']]);
    $message = implode("\n", [
        APP_NAME . ' 帳號啟用通知',
        '親愛的 ' . $user['name'] . '：',
        '請點擊以下連結完成帳號啟用：',
        $link,
    ]);

    file_put_contents(data_path('outbox/activation-' . $user['id'] . '.txt'), $message);
    @mail((string) $user['email'], APP_NAME . ' 帳號啟用通知', $message);

    return $link;
}

function login_user(string $email, string $password): array
{
    $user = find_user_by_email($email);

    if ($user === null) {
        throw new RuntimeException('找不到這個帳號。');
    }

    if (!(bool) ($user['active'] ?? false)) {
        throw new RuntimeException('帳號尚未啟用，請先完成信件驗證。');
    }

    if (!password_verify($password, (string) $user['password_hash'])) {
        throw new RuntimeException('密碼錯誤。');
    }

    $_SESSION['user_id'] = $user['id'];

    return $user;
}

function logout_user(): void
{
    unset($_SESSION['user_id']);
}

function current_user(): ?array
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $user = find_user_by_id((int) $_SESSION['user_id']);

    if ($user === null || !(bool) ($user['active'] ?? false)) {
        return null;
    }

    return $user;
}

function require_login(): array
{
    $user = current_user();
    if ($user === null) {
        flash_set('error', '請先登入再繼續。');
        redirect(route('login'));
    }

    return $user;
}

function require_admin(): array
{
    $user = require_login();
    if (($user['role'] ?? 'customer') !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }

    return $user;
}

function load_products(bool $activeOnly = true): array
{
    $products = load_json(data_path('products.json'), []);

    if (!$activeOnly) {
        return $products;
    }

    return array_values(array_filter($products, static fn (array $product): bool => (bool) ($product['active'] ?? false)));
}

function save_products(array $products): void
{
    save_json(data_path('products.json'), array_values($products));
}

function find_product(int $id, bool $activeOnly = true): ?array
{
    foreach (load_products($activeOnly) as $product) {
        if ((int) $product['id'] === $id) {
            return $product;
        }
    }

    if ($activeOnly) {
        return null;
    }

    foreach (load_products(false) as $product) {
        if ((int) $product['id'] === $id) {
            return $product;
        }
    }

    return null;
}

function next_product_id(): int
{
    $products = load_products(false);

    if ($products === []) {
        return 1;
    }

    return max(array_map(static fn (array $product): int => (int) $product['id'], $products)) + 1;
}

function save_product(array $product): void
{
    $products = load_products(false);
    $replaced = false;

    foreach ($products as $index => $existing) {
        if ((int) $existing['id'] === (int) $product['id']) {
            $products[$index] = $product;
            $replaced = true;
            break;
        }
    }

    if (!$replaced) {
        $products[] = $product;
    }

    save_products($products);
}

function delete_product(int $id): void
{
    $products = array_values(array_filter(load_products(false), static fn (array $product): bool => (int) $product['id'] !== $id));
    save_products($products);
}

function upload_product_image(?array $file, ?string $existing = null): ?string
{
    if ($file === null || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $existing;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('圖片上傳失敗。');
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    $original = (string) ($file['name'] ?? 'image');
    $extension = strtolower(pathinfo($original, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed, true)) {
        throw new RuntimeException('圖片格式僅支援 JPG、PNG、WEBP、GIF。');
    }

    $targetName = uniqid('dessert_', true) . '.' . $extension;
    $targetPath = public_path('uploads/' . $targetName);

    if (!move_uploaded_file((string) $file['tmp_name'], $targetPath)) {
        throw new RuntimeException('無法儲存上傳圖片。');
    }

    return 'uploads/' . $targetName;
}

function product_image_url(array $product): string
{
    $image = trim((string) ($product['image'] ?? ''));

    if ($image !== '') {
        if (preg_match('/^https?:\/\//i', $image) === 1) {
            return $image;
        }

        $normalized = ltrim($image, '/\\');
        if (is_file(public_path($normalized))) {
            return $normalized;
        }
    }

    return 'assets/placeholder.svg';
}

function cart_items(): array
{
    return is_array($_SESSION['cart'] ?? null) ? $_SESSION['cart'] : [];
}

function cart_count(): int
{
    return array_sum(array_map('intval', cart_items()));
}

function cart_add(int $productId, int $quantity = 1): void
{
    $cart = cart_items();
    $cart[$productId] = ($cart[$productId] ?? 0) + max(1, $quantity);
    $_SESSION['cart'] = $cart;
}

function cart_update(array $quantities): void
{
    $cart = cart_items();

    foreach ($quantities as $productId => $quantity) {
        $productId = (int) $productId;
        $quantity = (int) $quantity;

        if ($quantity <= 0) {
            unset($cart[$productId]);
            continue;
        }

        $cart[$productId] = $quantity;
    }

    $_SESSION['cart'] = $cart;
}

function cart_remove(int $productId): void
{
    $cart = cart_items();
    unset($cart[$productId]);
    $_SESSION['cart'] = $cart;
}

function cart_clear(): void
{
    $_SESSION['cart'] = [];
}

function cart_detail(): array
{
    $items = [];
    $subtotal = 0.0;

    foreach (cart_items() as $productId => $quantity) {
        $product = find_product((int) $productId, false);
        if ($product === null) {
            continue;
        }

        $quantity = max(1, (int) $quantity);
        $lineTotal = ((float) $product['price']) * $quantity;
        $subtotal += $lineTotal;

        $items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'line_total' => $lineTotal,
        ];
    }

    return [
        'items' => $items,
        'subtotal' => $subtotal,
        'count' => array_sum(array_map(static fn (array $item): int => $item['quantity'], $items)),
    ];
}

function load_orders(): array
{
    return load_json(data_path('orders.json'), []);
}

function save_orders(array $orders): void
{
    save_json(data_path('orders.json'), array_values($orders));
}

function next_order_id(): int
{
    $orders = load_orders();

    if ($orders === []) {
        return 1;
    }

    return max(array_map(static fn (array $order): int => (int) $order['id'], $orders)) + 1;
}

function generate_order_no(): string
{
    return 'DM' . date('Ymd') . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
}

function create_order_from_cart(array $user, string $pickupDate, string $note): array
{
    $cart = cart_items();

    if ($cart === []) {
        throw new RuntimeException('購物車是空的。');
    }

    $products = load_products(false);
    $lineItems = [];
    $subtotal = 0.0;

    foreach ($cart as $productId => $quantity) {
        $productId = (int) $productId;
        $quantity = max(1, (int) $quantity);
        $product = null;

        foreach ($products as $candidate) {
            if ((int) $candidate['id'] === $productId) {
                $product = $candidate;
                break;
            }
        }

        if ($product === null || !(bool) ($product['active'] ?? false)) {
            throw new RuntimeException('購物車內有無效商品。');
        }

        if ((int) $product['stock'] < $quantity) {
            throw new RuntimeException($product['name'] . ' 庫存不足。');
        }

        $lineTotal = ((float) $product['price']) * $quantity;
        $subtotal += $lineTotal;
        $lineItems[] = [
            'product_id' => $productId,
            'name' => $product['name'],
            'price' => (float) $product['price'],
            'quantity' => $quantity,
            'line_total' => $lineTotal,
        ];
    }

    foreach ($lineItems as $line) {
        foreach ($products as $index => $candidate) {
            if ((int) $candidate['id'] === (int) $line['product_id']) {
                $products[$index]['stock'] = max(0, (int) $candidate['stock'] - (int) $line['quantity']);
                $products[$index]['updated_at'] = now_string();
                break;
            }
        }
    }

    save_products($products);

    $order = [
        'id' => next_order_id(),
        'order_no' => generate_order_no(),
        'user_id' => (int) $user['id'],
        'customer_name' => $user['name'],
        'customer_email' => $user['email'],
        'pickup_date' => $pickupDate,
        'note' => trim($note),
        'status' => 'pending',
        'items' => $lineItems,
        'subtotal' => $subtotal,
        'shipping_fee' => 0,
        'total' => $subtotal,
        'created_at' => now_string(),
        'updated_at' => now_string(),
    ];

    $orders = load_orders();
    $orders[] = $order;
    save_orders($orders);
    cart_clear();

    return $order;
}

function save_order(array $order): void
{
    $orders = load_orders();
    $replaced = false;

    foreach ($orders as $index => $existing) {
        if ((int) $existing['id'] === (int) $order['id']) {
            $orders[$index] = $order;
            $replaced = true;
            break;
        }
    }

    if (!$replaced) {
        $orders[] = $order;
    }

    save_orders($orders);
}

function find_order(int $id): ?array
{
    foreach (load_orders() as $order) {
        if ((int) $order['id'] === $id) {
            return $order;
        }
    }

    return null;
}

function update_order_status(int $id, string $status): void
{
    $order = find_order($id);
    if ($order === null) {
        throw new RuntimeException('找不到訂單。');
    }

    $order['status'] = $status;
    $order['updated_at'] = now_string();
    save_order($order);
}

function update_user_active(int $id): void
{
    $user = find_user_by_id($id);
    if ($user === null) {
        throw new RuntimeException('找不到會員。');
    }

    $user['active'] = !(bool) $user['active'];
    upsert_user($user);
}

function dashboard_stats(): array
{
    $orders = load_orders();
    $users = load_users(false);
    $products = load_products(false);

    $totalRevenue = array_sum(array_map(static fn (array $order): float => (float) ($order['total'] ?? 0), $orders));
    $pendingOrders = count(array_filter($orders, static fn (array $order): bool => ($order['status'] ?? '') === 'pending'));
    $completedOrders = count(array_filter($orders, static fn (array $order): bool => ($order['status'] ?? '') === 'completed'));
    $activeMembers = count(array_filter($users, static fn (array $user): bool => (bool) ($user['active'] ?? false) && ($user['role'] ?? 'customer') === 'customer'));

    $monthlyRevenue = [];
    foreach ($orders as $order) {
        $month = substr((string) ($order['created_at'] ?? now_string()), 0, 7);
        $monthlyRevenue[$month] = ($monthlyRevenue[$month] ?? 0) + (float) ($order['total'] ?? 0);
    }
    ksort($monthlyRevenue);

    $productSales = [];
    foreach ($orders as $order) {
        foreach (($order['items'] ?? []) as $item) {
            $key = (string) ($item['name'] ?? '未知商品');
            $productSales[$key] = ($productSales[$key] ?? 0) + (int) ($item['quantity'] ?? 0);
        }
    }
    arsort($productSales);

    return [
        'total_orders' => count($orders),
        'total_revenue' => $totalRevenue,
        'pending_orders' => $pendingOrders,
        'completed_orders' => $completedOrders,
        'active_members' => $activeMembers,
        'product_count' => count($products),
        'monthly_labels' => array_keys($monthlyRevenue),
        'monthly_values' => array_values($monthlyRevenue),
        'top_labels' => array_slice(array_keys($productSales), 0, 5),
        'top_values' => array_slice(array_values($productSales), 0, 5),
    ];
}

function render(string $view, array $data = []): void
{
    $viewFile = app_path('app/views/' . $view . '.php');

    if (!is_file($viewFile)) {
        http_response_code(500);
        exit('View not found: ' . $view);
    }

    $title = $data['title'] ?? APP_NAME;
    $user = current_user();
    $cartCount = cart_count();

    extract($data, EXTR_SKIP);

    ob_start();
    require $viewFile;
    $content = ob_get_clean();

    require app_path('app/views/layout.php');
    exit;
}
