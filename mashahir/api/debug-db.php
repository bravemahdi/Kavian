<?php
/**
 * DB Debug — فقط برای تشخیص مشکل، بعد از تست حذف کن
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) session_start();

$pdo = require __DIR__ . '/../../app/config/config.php';

$productId = (int)($_GET['product_id'] ?? 1);
$userId    = (int)($_SESSION['user_id'] ?? 0);

// ۰. تمام جداول دیتابیس
$tableStmt = $pdo->query("SHOW TABLES");
$result['all_tables'] = $tableStmt->fetchAll(PDO::FETCH_COLUMN);

// ۱. وضعیت جلسه
$result['session'] = [
    'user_id'    => $userId,
    'logged_in'  => $_SESSION['logged_in'] ?? null,
];

// ۲. بررسی جدول mashahir_product_editions برای این محصول
$stmt = $pdo->prepare("SELECT * FROM mashahir_product_editions WHERE product_id = ?");
$stmt->execute([$productId]);
$result['editions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ۳. بررسی سفارش‌های این کاربر برای این محصول
if ($userId) {
    $stmt = $pdo->prepare("
        SELECT oi.id, oi.product_id, oi.product_type, oi.pdf_lang, oi.product_title,
               o.id AS order_id, o.status, o.created_at
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        WHERE oi.product_id = ? AND o.user_id = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$productId, $userId]);
    $result['user_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $result['user_orders'] = 'not_logged_in';
}

// ۴. نتیجه check-purchased query
if ($userId) {
    $stmt = $pdo->prepare("
        SELECT oi.id AS order_item_id, oi.pdf_lang, oi.product_type,
               o.id AS order_id, o.order_code, o.status, o.created_at,
               pp.id AS edition_id, pp.lang AS edition_lang,
               pp.label_fa, pp.label_en, pp.file_path
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id AND o.user_id = ? AND o.status = 'paid'
        LEFT JOIN mashahir_product_editions pp
          ON pp.product_id   = oi.product_id
         AND pp.edition_type = 'pdf'
         AND pp.is_active    = 1
         AND pp.lang = COALESCE(oi.pdf_lang, (
               SELECT lang FROM mashahir_product_editions
               WHERE product_id = oi.product_id
                 AND edition_type = 'pdf'
                 AND is_active = 1
               ORDER BY sort_order ASC, id ASC
               LIMIT 1
             ))
        WHERE oi.product_id = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$userId, $productId]);
    $result['check_purchased_query'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ۵. همه orders این کاربر (paid)
if ($userId) {
    $stmt = $pdo->prepare("
        SELECT o.id, o.order_code, o.status, o.final_amount, o.created_at,
               COUNT(oi.id) AS item_count
        FROM orders o
        LEFT JOIN order_items oi ON oi.order_id = o.id
        WHERE o.user_id = ? AND o.status = 'paid'
        GROUP BY o.id
        ORDER BY o.created_at DESC
        LIMIT 10
    ");
    $stmt->execute([$userId]);
    $result['paid_orders'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
