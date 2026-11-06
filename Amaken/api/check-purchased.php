<?php
/**
 * Check Purchased API — mashahir
 * GET ?product_id=X
 *
 * منطق:
 * - اگه کاربر PDF با pdf_lang مشخص خریده  → فقط همون زبان
 * - اگه pdf_lang = NULL                    → همه editions PDF این محصول
 * - اگه نسخه چاپی خریده                   → یک آیتم چاپی برمی‌گردونه
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    echo json_encode(['purchased' => false, 'items' => []], JSON_UNESCAPED_UNICODE);
    exit;
}

$pdo        = require __DIR__ . '/../../app/config/config.php';
$user_id    = (int)$_SESSION['user_id'];
$product_id = (int)($_GET['product_id'] ?? 0);

if (!$product_id) {
    echo json_encode(['purchased' => false, 'items' => []], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // سفارش‌های paid این کاربر برای این محصول
    $stmt = $pdo->prepare("
        SELECT oi.id AS order_item_id, oi.pdf_lang, oi.product_type, oi.product_title,
               o.id AS order_id, o.order_code, o.created_at
        FROM order_items oi
        JOIN orders o
          ON o.id      = oi.order_id
         AND o.user_id = ?
         AND o.status  = 'paid'
        WHERE oi.product_id = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$user_id, $product_id]);
    $orderRows = $stmt->fetchAll();

    if (empty($orderRows)) {
        echo json_encode(['purchased' => false, 'items' => []], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // همه editions PDF این محصول رو یک‌بار بگیر
    $allEditions = [];
    $edStmt = $pdo->prepare("
        SELECT lang, label_fa, label_en, file_path
        FROM mashahir_product_editions
        WHERE product_id   = ?
          AND edition_type = 'pdf'
          AND is_active    = 1
        ORDER BY sort_order ASC, id ASC
    ");
    $edStmt->execute([$product_id]);
    foreach ($edStmt->fetchAll() as $ed) {
        $allEditions[$ed['lang']] = $ed;
    }

    $items       = [];
    $seenEditions = []; // زبان‌هایی که قبلاً اضافه شدن
    $hasPdfOrder = false;
    $hasPrintOrder = false;
    $bestPdfOrderItemId = null; // order_item_id آخرین سفارش PDF

    foreach ($orderRows as $row) {
        if ($row['product_type'] === 'pdf') {
            $hasPdfOrder = true;
            if ($bestPdfOrderItemId === null) {
                $bestPdfOrderItemId = (int)$row['order_item_id'];
            }

            if ($row['pdf_lang'] !== null && isset($allEditions[$row['pdf_lang']])) {
                // زبان مشخص — فقط همون زبان رو اضافه کن
                $lang = $row['pdf_lang'];
                if (!isset($seenEditions[$lang])) {
                    $seenEditions[$lang] = true;
                    $ed = $allEditions[$lang];
                    $items[] = [
                        'order_item_id' => (int)$row['order_item_id'],
                        'order_id'      => (int)$row['order_id'],
                        'order_code'    => $row['order_code'],
                        'product_type'  => 'pdf',
                        'product_title' => $row['product_title'],
                        'pdf_lang'      => $lang,
                        'pdf_label_fa'  => $ed['label_fa'],
                        'pdf_label_en'  => $ed['label_en'],
                        'pdf_file_path' => $ed['file_path'],
                        'created_at'    => $row['created_at'],
                    ];
                }
            }
            // pdf_lang=null → بعداً همه editions رو اضافه می‌کنیم
        } elseif ($row['product_type'] === 'print' && !$hasPrintOrder) {
            $hasPrintOrder = true;
            $items[] = [
                'order_item_id' => (int)$row['order_item_id'],
                'order_id'      => (int)$row['order_id'],
                'order_code'    => $row['order_code'],
                'product_type'  => 'print',
                'product_title' => $row['product_title'],
                'pdf_lang'      => null,
                'pdf_label_fa'  => null,
                'pdf_label_en'  => null,
                'pdf_file_path' => null,
                'created_at'    => $row['created_at'],
            ];
        }
    }

    // اگه PDF خریده شده ولی با pdf_lang=null (زبان مشخص نشده)
    // همه editions این محصول رو نمایش بده
    if ($hasPdfOrder && empty($seenEditions)) {
        foreach ($allEditions as $lang => $ed) {
            $items[] = [
                'order_item_id' => $bestPdfOrderItemId,
                'order_id'      => null,
                'order_code'    => null,
                'product_type'  => 'pdf',
                'product_title' => null,
                'pdf_lang'      => $lang,
                'pdf_label_fa'  => $ed['label_fa'],
                'pdf_label_en'  => $ed['label_en'],
                'pdf_file_path' => $ed['file_path'],
                'created_at'    => null,
            ];
        }
    }

    // اگه اصلاً edition نداشتیم ولی PDF خریده بود — حداقل یک آیتم عمومی بده
    if ($hasPdfOrder && empty($items) && !$hasPrintOrder) {
        $firstOrder = null;
        foreach ($orderRows as $r) {
            if ($r['product_type'] === 'pdf') { $firstOrder = $r; break; }
        }
        if ($firstOrder) {
            $items[] = [
                'order_item_id' => (int)$firstOrder['order_item_id'],
                'order_id'      => (int)$firstOrder['order_id'],
                'order_code'    => $firstOrder['order_code'],
                'product_type'  => 'pdf',
                'product_title' => $firstOrder['product_title'],
                'pdf_lang'      => null,
                'pdf_label_fa'  => null,
                'pdf_label_en'  => null,
                'pdf_file_path' => null,
                'created_at'    => $firstOrder['created_at'],
            ];
        }
    }

    echo json_encode(['purchased' => true, 'items' => $items], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode(['purchased' => false, 'items' => [], 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
