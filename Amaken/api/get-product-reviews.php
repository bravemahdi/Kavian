<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$lang       = isset($_GET['lang']) && in_array($_GET['lang'], ['fa', 'en']) ? $_GET['lang'] : 'en';

if ($product_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid product_id'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT
            r.id,
            r.author_name,
            r.rating,
            r.content_fa,
            r.content_en,
            r.hashtags,
            r.likes_count,
            r.created_at
        FROM mashahir_reviews r
        WHERE r.product_id = ?
          AND r.is_active   = 1
          AND r.lang        = ?
        ORDER BY r.likes_count DESC, r.created_at DESC
        LIMIT 20
    ");
    $stmt->execute([$product_id, $lang]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // تبدیل مقادیر عددی
    foreach ($reviews as &$r) {
        $r['rating']      = (int)$r['rating'];
        $r['likes_count'] = (int)$r['likes_count'];
    }
    unset($r);

    // تعداد کل نظرات این محصول (هر دو زبان)
    $countStmt = $pdo->prepare("
        SELECT COUNT(*) FROM mashahir_reviews
        WHERE product_id = ? AND is_active = 1 AND lang = ?
    ");
    $countStmt->execute([$product_id, $lang]);
    $total = (int)$countStmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'data'    => $reviews,
        'count'   => $total,
        'lang'    => $lang,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database error: ' . $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
