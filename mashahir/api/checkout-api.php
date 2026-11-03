<?php
/**
 * Checkout API — mashahir
 *
 * POST body (JSON):
 *   full_name   string  — نام کامل
 *   email       string  — ایمیل
 *   phone       string  — شماره (اختیاری)
 *   notes       string  — یادداشت (اختیاری)
 *
 * Response (success):
 *   { success: true, order_id: int, order_code: string, amount: float }
 */

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require __DIR__ . '/../../app/config/config.php';

$session_id = session_id();
$user_id    = $_SESSION['user_id'] ?? null;

$input     = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$full_name        = trim($input['full_name']        ?? '');
$email            = trim($input['email']            ?? '');
$phone            = trim($input['phone']            ?? '');
$notes            = trim($input['notes']            ?? '');
$shipping_address = trim($input['shipping_address'] ?? '');

// اعتبارسنجی
if (empty($full_name) || empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'نام، ایمیل و شماره تماس الزامی هستند.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'شماره تماس الزامی است.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'فرمت ایمیل نادرست است.'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // دریافت آیتم‌های سبد
    $cartSql = "SELECT c.id AS cart_id, c.product_id, c.product_type, c.quantity, c.price,
                       p.title_fa, p.title_en
                FROM cart c
                JOIN mashahir_products p ON c.product_id = p.id
                WHERE c.session_id = ? " . ($user_id ? "OR c.user_id = ?" : "");

    $cartParams = [$session_id];
    if ($user_id) $cartParams[] = $user_id;

    $stmt = $pdo->prepare($cartSql);
    $stmt->execute($cartParams);
    $cartItems = $stmt->fetchAll();

    if (empty($cartItems)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'سبد خرید شما خالی است.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // بررسی آیا سبد شامل نسخه چاپی است
    $hasPrint = false;
    foreach ($cartItems as $item) {
        if ($item['product_type'] === 'print') {
            $hasPrint = true;
            break;
        }
    }

    // آدرس برای نسخه چاپی اجباری است
    if ($hasPrint && empty($shipping_address)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'برای ارسال نسخه چاپی، آدرس ارسال الزامی است.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // محاسبه جمع کل
    $total = 0;
    foreach ($cartItems as $item) {
        $total += (float)$item['price'] * (int)$item['quantity'];
    }

    // کد سفارش یکتا
    $order_code = 'ORD-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));

    // درج سفارش
    $pdo->beginTransaction();

    $orderStmt = $pdo->prepare(
        "INSERT INTO orders (order_code, user_id, session_id, full_name, email, phone,
                             shipping_address, total_amount, discount_amount, final_amount, status, notes)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, 'pending', ?)"
    );
    $orderStmt->execute([
        $order_code, $user_id, $session_id,
        $full_name, $email, $phone,
        $shipping_address ?: null,
        $total, $total, $notes,
    ]);
    $order_id = (int)$pdo->lastInsertId();

    // درج آیتم‌های سفارش
    $itemStmt = $pdo->prepare(
        "INSERT INTO order_items (order_id, product_id, product_title, product_type, pdf_lang, quantity, unit_price, total_price)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    foreach ($cartItems as $item) {
        $title = $item['title_fa'] ?: $item['title_en'];
        // pdf_lang از cart می‌آید
        $item_pdf_lang = ($item['product_type'] === 'pdf') ? ($item['pdf_lang'] ?? null) : null;
        $itemStmt->execute([
            $order_id,
            (int)$item['product_id'],
            $title,
            $item['product_type'],
            $item_pdf_lang,
            (int)$item['quantity'],
            (float)$item['price'],
            (float)$item['price'] * (int)$item['quantity'],
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'success'    => true,
        'order_id'   => $order_id,
        'order_code' => $order_code,
        'amount'     => $total,
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'خطای دیتابیس: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
