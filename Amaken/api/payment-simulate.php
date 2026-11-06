<?php
/**
 * Payment Simulator — mashahir
 *
 * این فایل پرداخت را شبیه‌سازی می‌کند.
 * برای تست هر دو حالت موفق/ناموفق از متغیر FORCE_RESULT استفاده کنید.
 *
 * POST body (JSON):
 *   order_id     int     — شناسه سفارش
 *   amount       float   — مبلغ پرداخت (برای تأیید)
 *
 * Response:
 *   { success: bool, payment_ref: string, message: string }
 */

header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pdo = require __DIR__ . '/../../app/config/config.php';

// ═══════════════════════════════════════════════════════════════
// 🔧 کنترل دستی نتیجه پرداخت — برای تست
//
//   null   → تصادفی (۷۰٪ موفق)
//   true   → همیشه موفق
//   false  → همیشه ناموفق
// ═══════════════════════════════════════════════════════════════
const FORCE_RESULT = null;

// تأخیر شبیه‌سازی درگاه (ثانیه)
const SIMULATE_DELAY_SECONDS = 1;

// ───────────────────────────────────────────────────────────────

$input    = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$order_id = (int)($input['order_id'] ?? 0);
$amount   = (float)($input['amount']   ?? 0);

if (!$order_id || !$amount) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'اطلاعات سفارش ناقص است.'], JSON_UNESCAPED_UNICODE);
    exit;
}

// بررسی وجود سفارش
try {
    $stmt = $pdo->prepare("SELECT id, final_amount, status FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'خطای دیتابیس.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (!$order) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'سفارش یافت نشد.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($order['status'] === 'paid') {
    echo json_encode(['success' => true, 'message' => 'این سفارش قبلاً پرداخت شده است.', 'payment_ref' => ''], JSON_UNESCAPED_UNICODE);
    exit;
}

// ───────────────────────────────────────────────────────────────
// تأخیر شبیه‌سازی
// ───────────────────────────────────────────────────────────────
sleep(SIMULATE_DELAY_SECONDS);

// ───────────────────────────────────────────────────────────────
// تعیین نتیجه
// ───────────────────────────────────────────────────────────────
$paymentSuccess = simulatePayment();

// ───────────────────────────────────────────────────────────────
// به‌روزرسانی سفارش
// ───────────────────────────────────────────────────────────────
try {
    if ($paymentSuccess) {
        $payment_ref = generatePaymentRef();
        $pdo->prepare("UPDATE orders SET status = 'paid', payment_ref = ? WHERE id = ?")
            ->execute([$payment_ref, $order_id]);

        echo json_encode([
            'success'     => true,
            'payment_ref' => $payment_ref,
            'message'     => 'پرداخت با موفقیت انجام شد.',
        ], JSON_UNESCAPED_UNICODE);
    } else {
        $pdo->prepare("UPDATE orders SET status = 'failed' WHERE id = ?")
            ->execute([$order_id]);

        echo json_encode([
            'success' => false,
            'message' => 'پرداخت ناموفق بود. لطفاً دوباره تلاش کنید.',
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'خطا در ثبت نتیجه پرداخت.'], JSON_UNESCAPED_UNICODE);
}

// ═══════════════════════════════════════════════════════════════
// توابع شبیه‌سازی
// ═══════════════════════════════════════════════════════════════

/**
 * نتیجه پرداخت را شبیه‌سازی می‌کند.
 *
 * اگر FORCE_RESULT مقدار داشته باشد → همان مقدار برمی‌گردد
 * وگرنه → با احتمال ۷۰٪ موفق، ۳۰٪ ناموفق
 */
function simulatePayment(): bool
{
    if (FORCE_RESULT !== null) {
        return (bool) FORCE_RESULT;
    }
    // تصادفی: ۷۰٪ موفق
    return (rand(1, 10) <= 7);
}

/**
 * شماره پیگیری تصادفی تولید می‌کند
 */
function generatePaymentRef(): string
{
    return 'PAY-' . strtoupper(bin2hex(random_bytes(6)));
}
