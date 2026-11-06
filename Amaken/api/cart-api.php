<?php
/**
 * Cart API — mashahir
 * 
 * Actions:
 *   GET  ?action=get             → لیست آیتم‌های سبد + جمع کل
 *   POST action=add              → افزودن آیتم
 *   POST action=remove           → حذف آیتم
 *   POST action=update           → تغییر تعداد
 *   POST action=clear            → خالی کردن سبد
 *   GET  ?action=count           → تعداد آیتم‌ها (برای badge)
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require __DIR__ . '/../../app/config/config.php';

$session_id = session_id();
$user_id    = $_SESSION['user_id'] ?? null;

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

// اگر body JSON بود
if (empty($action)) {
    $input  = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
} else {
    $input = $_POST;
}

try {
    switch ($action) {

        // ──────────────────────────────────────
        // GET: دریافت سبد
        // ──────────────────────────────────────
        case 'get':
            $items = getCartItems($pdo, $session_id, $user_id);
            $total = array_sum(array_column($items, 'total_price'));
            echo json_encode([
                'success' => true,
                'items'   => $items,
                'count'   => count($items),
                'total'   => $total,
            ], JSON_UNESCAPED_UNICODE);
            break;

        // ──────────────────────────────────────
        // GET: تعداد آیتم‌ها (برای badge)
        // ──────────────────────────────────────
        case 'count':
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) FROM cart
                 WHERE session_id = ? " . ($user_id ? "OR user_id = ?" : "")
            );
            $params = [$session_id];
            if ($user_id) $params[] = $user_id;
            $stmt->execute($params);
            $count = (int)$stmt->fetchColumn();
            echo json_encode(['success' => true, 'count' => $count], JSON_UNESCAPED_UNICODE);
            break;

        // ──────────────────────────────────────
        // POST: افزودن به سبد
        // ──────────────────────────────────────
        case 'add':
            $product_id   = (int)($input['product_id'] ?? 0);
            $product_type = in_array($input['product_type'] ?? '', ['print', 'pdf'])
                            ? $input['product_type']
                            : 'print';

            // زبان PDF — فقط برای product_type=pdf معنا دارد
            $pdf_lang = null;
            if ($product_type === 'pdf') {
                $raw_lang = trim($input['pdf_lang'] ?? '');
                $pdf_lang = $raw_lang !== '' ? $raw_lang : null;
            }

            if (!$product_id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'شناسه محصول اشتباه است.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // دریافت قیمت از دیتابیس
            $pStmt = $pdo->prepare(
                "SELECT id, title_fa, title_en,
                        price_print, sale_price_print,
                        price_pdf,   sale_price_pdf,
                        is_active
                 FROM mashahir_products WHERE id = ? AND is_active = 1"
            );
            $pStmt->execute([$product_id]);
            $product = $pStmt->fetch();

            if (!$product) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'محصول یافت نشد.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // محاسبه قیمت نهایی
            if ($product_type === 'print') {
                $price = $product['sale_price_print'] ?: $product['price_print'];
            } else {
                $price = $product['sale_price_pdf'] ?: $product['price_pdf'];
            }

            if (!$price) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'این نسخه قابل خریداری نیست.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // ── جلوگیری از خرید تکراری PDF (همان محصول + همان زبان) ──
            if ($product_type === 'pdf' && $user_id) {
                $dupStmt = $pdo->prepare(
                    "SELECT o.id FROM order_items oi
                     JOIN orders o ON o.id = oi.order_id
                     WHERE o.user_id       = ?
                       AND o.status        = 'paid'
                       AND oi.product_id   = ?
                       AND oi.product_type = 'pdf'
                       AND (
                           oi.pdf_lang = ?
                           OR (oi.pdf_lang IS NULL AND ? IS NULL)
                           OR oi.pdf_lang IS NULL
                       )
                     LIMIT 1"
                );
                $dupStmt->execute([$user_id, $product_id, $pdf_lang, $pdf_lang]);
                if ($dupStmt->fetch()) {
                    http_response_code(409);
                    echo json_encode([
                        'success'           => false,
                        'already_purchased' => true,
                        'message'           => 'شما این نسخه PDF را قبلاً خریداری کرده‌اید.',
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }

            // بررسی تکراری بودن (product_id + product_type + pdf_lang)
            $checkStmt = $pdo->prepare(
                "SELECT id, quantity FROM cart
                 WHERE (session_id = ? " . ($user_id ? "OR user_id = ?" : "") . ")
                   AND product_id = ? AND product_type = ?
                   AND (pdf_lang = ? OR (pdf_lang IS NULL AND ? IS NULL))"
            );
            $checkParams = [$session_id];
            if ($user_id) $checkParams[] = $user_id;
            $checkParams[] = $product_id;
            $checkParams[] = $product_type;
            $checkParams[] = $pdf_lang;
            $checkParams[] = $pdf_lang;
            $checkStmt->execute($checkParams);
            $existing = $checkStmt->fetch();

            if ($existing) {
                // افزایش تعداد
                $upStmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
                $upStmt->execute([$existing['id']]);
                $message = 'تعداد محصول در سبد افزایش یافت.';
            } else {
                // درج جدید
                $insStmt = $pdo->prepare(
                    "INSERT INTO cart (session_id, user_id, product_id, product_type, pdf_lang, quantity, price)
                     VALUES (?, ?, ?, ?, ?, 1, ?)"
                );
                $insStmt->execute([$session_id, $user_id, $product_id, $product_type, $pdf_lang, $price]);
                $message = 'محصول به سبد خرید افزوده شد.';
            }

            // تعداد جدید سبد
            $cntStmt = $pdo->prepare(
                "SELECT COUNT(*) FROM cart
                 WHERE session_id = ? " . ($user_id ? "OR user_id = ?" : "")
            );
            $cntParams = [$session_id];
            if ($user_id) $cntParams[] = $user_id;
            $cntStmt->execute($cntParams);
            $newCount = (int)$cntStmt->fetchColumn();

            echo json_encode([
                'success' => true,
                'message' => $message,
                'count'   => $newCount,
            ], JSON_UNESCAPED_UNICODE);
            break;

        // ──────────────────────────────────────
        // POST: حذف آیتم
        // ──────────────────────────────────────
        case 'remove':
            $cart_id = (int)($input['cart_id'] ?? 0);
            if (!$cart_id) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'شناسه آیتم اشتباه است.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $delStmt = $pdo->prepare(
                "DELETE FROM cart
                 WHERE id = ? AND (session_id = ? " . ($user_id ? "OR user_id = ?" : "") . ")"
            );
            $delParams = [$cart_id, $session_id];
            if ($user_id) $delParams[] = $user_id;
            $delStmt->execute($delParams);

            $items = getCartItems($pdo, $session_id, $user_id);
            $total = array_sum(array_column($items, 'total_price'));

            echo json_encode([
                'success' => true,
                'message' => 'آیتم از سبد حذف شد.',
                'items'   => $items,
                'count'   => count($items),
                'total'   => $total,
            ], JSON_UNESCAPED_UNICODE);
            break;

        // ──────────────────────────────────────
        // POST: تغییر تعداد
        // ──────────────────────────────────────
        case 'update':
            $cart_id  = (int)($input['cart_id']  ?? 0);
            $quantity = (int)($input['quantity']  ?? 1);

            if (!$cart_id || $quantity < 1) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'داده‌های نامعتبر.'], JSON_UNESCAPED_UNICODE);
                exit;
            }

            if ($quantity > 10) $quantity = 10; // حداکثر

            $updStmt = $pdo->prepare(
                "UPDATE cart SET quantity = ?
                 WHERE id = ? AND (session_id = ? " . ($user_id ? "OR user_id = ?" : "") . ")"
            );
            $updParams = [$quantity, $cart_id, $session_id];
            if ($user_id) $updParams[] = $user_id;
            $updStmt->execute($updParams);

            $items = getCartItems($pdo, $session_id, $user_id);
            $total = array_sum(array_column($items, 'total_price'));

            echo json_encode([
                'success' => true,
                'items'   => $items,
                'count'   => count($items),
                'total'   => $total,
            ], JSON_UNESCAPED_UNICODE);
            break;

        // ──────────────────────────────────────
        // POST: خالی کردن سبد
        // ──────────────────────────────────────
        case 'clear':
            $clrStmt = $pdo->prepare(
                "DELETE FROM cart
                 WHERE session_id = ? " . ($user_id ? "OR user_id = ?" : "")
            );
            $clrParams = [$session_id];
            if ($user_id) $clrParams[] = $user_id;
            $clrStmt->execute($clrParams);

            echo json_encode([
                'success' => true,
                'message' => 'سبد خرید خالی شد.',
                'count'   => 0,
            ], JSON_UNESCAPED_UNICODE);
            break;

        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'عملیات نامعتبر است.'], JSON_UNESCAPED_UNICODE);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'خطای دیتابیس: ' . $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

// ──────────────────────────────────────────────────────────────
// Helper: دریافت آیتم‌های سبد با اطلاعات کامل محصول
// ──────────────────────────────────────────────────────────────
function getCartItems(PDO $pdo, string $session_id, ?int $user_id): array
{
    $sql = "SELECT
                c.id         AS cart_id,
                c.product_id,
                c.product_type,
                c.pdf_lang,
                c.quantity,
                c.price      AS unit_price,
                (c.price * c.quantity) AS total_price,
                p.title_fa,
                p.title_en,
                p.slug,
                p.image,
                p.author_fa,
                p.author_en,
                p.price_print,
                p.sale_price_print,
                p.price_pdf,
                p.sale_price_pdf,
                -- برچسب زبان از جدول PDF
                pp.label_fa  AS pdf_label_fa,
                pp.label_en  AS pdf_label_en
            FROM cart c
            JOIN mashahir_products p ON c.product_id = p.id
            LEFT JOIN mashahir_product_editions pp
                   ON pp.product_id = c.product_id
                  AND pp.lang = c.pdf_lang
                  AND pp.edition_type = 'pdf'
                  AND pp.is_active = 1
            WHERE c.session_id = ? " . ($user_id ? "OR c.user_id = ?" : "") . "
            ORDER BY c.created_at DESC";

    $params = [$session_id];
    if ($user_id) $params[] = $user_id;

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $items = [];
    foreach ($rows as $row) {
        $items[] = [
            'cart_id'       => (int)$row['cart_id'],
            'product_id'    => (int)$row['product_id'],
            'product_type'  => $row['product_type'],
            'pdf_lang'      => $row['pdf_lang'],
            'pdf_label_fa'  => $row['pdf_label_fa'],
            'pdf_label_en'  => $row['pdf_label_en'],
            'quantity'      => (int)$row['quantity'],
            'unit_price'    => (float)$row['unit_price'],
            'total_price'   => (float)$row['total_price'],
            'title_fa'      => $row['title_fa'],
            'title_en'      => $row['title_en'],
            'slug'          => $row['slug'],
            'image'         => $row['image'] ? '/Kavian/mashahir/public/img/' . $row['image'] : null,
            'author_fa'     => $row['author_fa'],
            'author_en'     => $row['author_en'],
        ];
    }
    return $items;
}
