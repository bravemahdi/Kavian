<?php
/**
 * PDF Download API — mashahir
 * GET ?order_item_id=X
 * فقط صاحب سفارش paid می‌تواند دانلود کند
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    die('برای دانلود باید وارد شوید.');
}

$pdo        = require __DIR__ . '/../../app/config/config.php';
$userId      = (int)$_SESSION['user_id'];
$orderItemId = (int)($_GET['order_item_id'] ?? 0);
$langOverride = isset($_GET['lang']) ? trim($_GET['lang']) : null;

if (!$orderItemId) {
    http_response_code(400);
    die('شناسه آیتم نامعتبر است.');
}

try {
    // ابتدا بررسی می‌کنیم که این order_item متعلق به کاربر بوده و سفارش paid است
    // product_type می‌تواند 'pdf' یا NULL باشد — هر دو حالت را قبول می‌کنیم
    $stmt = $pdo->prepare("
        SELECT oi.id, oi.pdf_lang, oi.product_type, oi.product_id,
               o.user_id, o.status,
               pe.file_path, pe.label_fa, pe.label_en
        FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        LEFT JOIN mashahir_product_editions pe
               ON pe.product_id   = oi.product_id
              AND pe.edition_type = 'pdf'
              AND pe.is_active    = 1
              AND pe.lang = COALESCE(?, oi.pdf_lang, (
                    SELECT lang FROM mashahir_product_editions
                    WHERE product_id = oi.product_id
                      AND edition_type = 'pdf'
                      AND is_active = 1
                    ORDER BY sort_order ASC, id ASC
                    LIMIT 1
                  ))
        WHERE oi.id     = ?
          AND o.user_id = ?
          AND o.status  = 'paid'
        LIMIT 1
    ");
    $stmt->execute([$langOverride, $orderItemId, $userId]);
    $row = $stmt->fetch();

    if (!$row) {
        http_response_code(403);
        die('دسترسی مجاز نیست یا سفارش پیدا نشد.');
    }

    // اگه file_path خالی است، یعنی edition پیدا نشده
    if (empty($row['file_path'])) {
        http_response_code(404);
        die('فایل PDF برای این محصول تعریف نشده است.');
    }

    $filePath = $row['file_path'];

    // پشتیبانی از دو حالت:
    // ۱. مسیر نسبی مثل /Kavian/mashahir/pdfs/file.pdf  → DOCUMENT_ROOT . path
    // ۲. مسیر مطلق سیستمی مثل C:\wamp64\www\...       → مستقیم استفاده می‌شه
    if (DIRECTORY_SEPARATOR === '\\') {
        // ویندوز: اگه با حرف درایو شروع شه، مسیر مطلقه
        $isAbsolute = (strlen($filePath) >= 2 && $filePath[1] === ':');
    } else {
        // لینوکس: اگه با / شروع و شامل DOCUMENT_ROOT نباشه
        $isAbsolute = (strpos($filePath, $_SERVER['DOCUMENT_ROOT']) === 0);
    }

    if ($isAbsolute) {
        $absolutePath = $filePath;
    } else {
        // مسیر URL مثل /Kavian/mashahir/pdfs/file.pdf
        // تبدیل به مسیر فایل‌سیستمی
        $serverRoot   = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
        $cleanPath    = ltrim($filePath, '/\\');
        $absolutePath = $serverRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $cleanPath);
    }

    if (!file_exists($absolutePath)) {
        http_response_code(404);
        die('فایل PDF روی سرور یافت نشد. (مسیر: ' . htmlspecialchars($absolutePath) . ')');
    }

    $filename = basename($absolutePath);
    // نام فایل دانلود را از label یا نام فایل واقعی بسازیم
    $downloadName = !empty($row['label_fa'])
        ? $row['label_fa'] . '.pdf'
        : $filename;

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . rawurlencode($downloadName) . '"');
    header('Content-Length: ' . filesize($absolutePath));
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    readfile($absolutePath);
    exit;

} catch (PDOException $e) {
    http_response_code(500);
    die('خطای سرور: ' . $e->getMessage());
}
