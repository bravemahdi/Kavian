<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'متد نامعتبر است'], JSON_UNESCAPED_UNICODE);
    exit;
}

$productId  = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$authorName = trim($_POST['author_name'] ?? '');
$rating     = isset($_POST['rating']) ? (int)$_POST['rating'] : 5;
$content    = trim($_POST['content'] ?? '');
$lang       = isset($_POST['lang']) && in_array($_POST['lang'], ['fa', 'en'], true) ? $_POST['lang'] : 'fa';

if ($productId <= 0 || $authorName === '' || $content === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'نام، متن نظر و محصول الزامی است'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($rating < 1 || $rating > 5) $rating = 5;

try {

    $stmt = $pdo->prepare("SELECT id, celebrity_id FROM mashahir_products WHERE id = ? AND is_active = 1");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'محصول یافت نشد'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $contentFa = $lang === 'fa' ? $content : null;
    $contentEn = $lang === 'en' ? $content : null;

    $ins = $pdo->prepare("INSERT INTO mashahir_reviews
        (celebrity_id, product_id, author_name, rating, content_fa, content_en, lang, status, is_active)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 0)");
    $ins->execute([
        $product['celebrity_id'],
        $productId,
        $authorName,
        $rating,
        $contentFa,
        $contentEn,
        $lang,
    ]);

    echo json_encode([
        'success' => true,
        'message' => $lang === 'fa'
            ? 'نظر شما ثبت شد و پس از بررسی و تایید مدیر نمایش داده خواهد شد.'
            : 'Your review has been submitted and will appear after admin approval.',
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'خطای پایگاه‌داده: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
