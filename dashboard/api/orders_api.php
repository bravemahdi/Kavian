<?php
/**
 * Orders API — داشبورد کاربر
 * 
 * GET ?action=my-orders&status=all|pending|paid|failed  → سفارش‌های کاربر جاری
 * GET ?action=detail&order_id=X                         → جزئیات یک سفارش
 */

header('Content-Type: application/json; charset=utf-8');

// فقط لاگین بودن کافیه — نه نقش admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'ابتدا وارد شوید.']);
    exit;
}

$pdo    = require __DIR__ . '/../../app/config/config.php';
$userId = (int)($_SESSION['user_id'] ?? 0);

if (!$userId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'احراز هویت لازم است.']);
    exit;
}

$action = $_GET['action'] ?? 'my-orders';

// ────────────────────────────────────────────────────────────
// GET: لیست سفارش‌های کاربر
// ────────────────────────────────────────────────────────────
if ($action === 'my-orders') {
    $status = $_GET['status'] ?? 'all';
    $allowed = ['all', 'pending', 'paid', 'failed', 'cancelled'];
    if (!in_array($status, $allowed, true)) $status = 'all';

    $sql = "SELECT 
                o.id,
                o.order_code,
                o.full_name,
                o.email,
                o.final_amount,
                o.status,
                o.payment_ref,
                o.created_at,
                COUNT(oi.id) AS item_count
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            WHERE o.user_id = ?";

    $params = [$userId];

    if ($status !== 'all') {
        $sql .= " AND o.status = ?";
        $params[] = $status;
    }

    $sql .= " GROUP BY o.id ORDER BY o.created_at DESC";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $orders = $stmt->fetchAll();

        // آمارهای خلاصه
        $statsStmt = $pdo->prepare(
            "SELECT 
                COUNT(*) AS total,
                SUM(status = 'paid') AS paid_count,
                SUM(status = 'pending') AS pending_count,
                SUM(status = 'failed') AS failed_count,
                SUM(CASE WHEN status = 'paid' THEN final_amount ELSE 0 END) AS total_paid
             FROM orders WHERE user_id = ?"
        );
        $statsStmt->execute([$userId]);
        $stats = $statsStmt->fetch();

        echo json_encode([
            'success' => true,
            'data'    => $orders,
            'count'   => count($orders),
            'stats'   => [
                'total'        => (int)$stats['total'],
                'paid_count'   => (int)$stats['paid_count'],
                'pending_count'=> (int)$stats['pending_count'],
                'failed_count' => (int)$stats['failed_count'],
                'total_paid'   => (float)$stats['total_paid'],
            ],
        ], JSON_UNESCAPED_UNICODE);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'خطای دیتابیس.']);
    }
    exit;
}

// ────────────────────────────────────────────────────────────
// GET: جزئیات یک سفارش
// ────────────────────────────────────────────────────────────
if ($action === 'detail') {
    $order_id = (int)($_GET['order_id'] ?? 0);
    if (!$order_id) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'شناسه سفارش اشتباه است.']);
        exit;
    }

    try {
        $oStmt = $pdo->prepare(
            "SELECT * FROM orders WHERE id = ? AND user_id = ?"
        );
        $oStmt->execute([$order_id, $userId]);
        $order = $oStmt->fetch();

        if (!$order) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'سفارش یافت نشد.']);
            exit;
        }

        $iStmt = $pdo->prepare(
            "SELECT oi.*,
                    COALESCE(p.title_fa, p.title_en, 'محصول') AS product_title,
                    p.image AS product_image,
                    p.slug  AS product_slug,
                    pe.file_path AS pdf_file_path,
                    pe.label_fa  AS pdf_label_fa,
                    pe.label_en  AS pdf_label_en
             FROM order_items oi
             LEFT JOIN mashahir_products p ON p.id = oi.product_id
             LEFT JOIN mashahir_product_editions pe
                    ON pe.product_id   = oi.product_id
                   AND pe.edition_type = 'pdf'
                   AND pe.is_active    = 1
                   AND pe.lang = COALESCE(oi.pdf_lang, (
                         SELECT lang FROM mashahir_product_editions
                         WHERE product_id = oi.product_id
                           AND edition_type = 'pdf'
                           AND is_active = 1
                         ORDER BY sort_order ASC, id ASC
                         LIMIT 1
                       ))
             WHERE oi.order_id = ?"
        );
        $iStmt->execute([$order_id]);
        $items = $iStmt->fetchAll();

        echo json_encode([
            'success' => true,
            'order'   => $order,
            'items'   => $items,
        ], JSON_UNESCAPED_UNICODE);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'خطای دیتابیس.']);
    }
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'عملیات نامعتبر است.']);
