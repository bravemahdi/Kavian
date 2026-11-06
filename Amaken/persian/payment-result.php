<?php
require_once __DIR__ . '/../includes/config.php';

// ─── احراز هویت: کاربر باید لاگین باشد ───────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id'])) {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header('Location: /Kavian/login.php?redirect=' . $redirect);
    exit;
}
// ──────────────────────────────────────────────────────────────

// خواندن پارامترها — فقط مقادیر مجاز پذیرفته می‌شوند
$status   = in_array($_GET['status'] ?? '', ['success', 'failed']) ? $_GET['status'] : 'failed';
$order_id = (int)($_GET['order_id'] ?? 0);
$ref      = preg_replace('/[^A-Za-z0-9\-]/', '', $_GET['ref'] ?? '');

$is_success = ($status === 'success');

// دریافت جزئیات سفارش از دیتابیس
$order      = null;
$orderItems = [];

if ($order_id > 0) {
    try {
        $stmt = $pdo->prepare(
            "SELECT o.*, 
                    GROUP_CONCAT(oi.product_title SEPARATOR '||') AS titles
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.id
             WHERE o.id = ?
             GROUP BY o.id"
        );
        $stmt->execute([$order_id]);
        $order = $stmt->fetch();

        if ($order) {
            $itemsStmt = $pdo->prepare(
                "SELECT * FROM order_items WHERE order_id = ?"
            );
            $itemsStmt->execute([$order_id]);
            $orderItems = $itemsStmt->fetchAll();
        }
    } catch (PDOException $e) {
        // اگر خطا بود، ادامه بدون جزئیات سفارش
    }
}

// اگر پرداخت موفق بود، سبد خرید را خالی کن
if ($is_success && $order_id > 0) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $session_id = session_id();
    $user_id    = $_SESSION['user_id'] ?? null;
    try {
        $clrSql = "DELETE FROM cart WHERE session_id = ?" . ($user_id ? " OR user_id = ?" : "");
        $clrStmt = $pdo->prepare($clrSql);
        $params  = [$session_id];
        if ($user_id) $params[] = $user_id;
        $clrStmt->execute($params);
    } catch (PDOException $e) {}
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container payment-result-container">

    <!-- کارت نتیجه اصلی -->
    <div class="payment-result-card <?= $is_success ? 'result-success' : 'result-failed' ?>">

        <!-- آیکون وضعیت -->
        <div class="result-icon-wrapper">
            <?php if ($is_success): ?>
                <div class="result-icon result-icon-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="result-ripple"></div>
            <?php else: ?>
                <div class="result-icon result-icon-failed">
                    <i class="fas fa-times-circle"></i>
                </div>
            <?php endif; ?>
        </div>

        <!-- عنوان وضعیت -->
        <h2 class="result-title">
            <?php if ($is_success): ?>
                <?= $is_fa ? 'پرداخت موفق!' : 'Payment Successful!' ?>
            <?php else: ?>
                <?= $is_fa ? 'پرداخت ناموفق' : 'Payment Failed' ?>
            <?php endif; ?>
        </h2>

        <!-- پیام -->
        <p class="result-message">
            <?php if ($is_success): ?>
                <?= $is_fa
                    ? 'سفارش شما با موفقیت ثبت و پرداخت شد. اطلاعات سفارش به ایمیل شما ارسال خواهد شد.'
                    : 'Your order has been successfully placed and paid. Order details will be sent to your email.'
                ?>
            <?php else: ?>
                <?= $is_fa
                    ? 'متأسفانه پرداخت با مشکل مواجه شد. می‌توانید دوباره تلاش کنید.'
                    : 'Unfortunately the payment failed. You can try again.'
                ?>
            <?php endif; ?>
        </p>

        <?php if ($is_success && $ref): ?>
        <!-- کد پیگیری -->
        <div class="result-ref-box">
            <span class="result-ref-label">
                <i class="fas fa-hashtag me-1"></i>
                <?= $is_fa ? 'کد پیگیری:' : 'Reference Code:' ?>
            </span>
            <span class="result-ref-code" dir="ltr"><?= htmlspecialchars($ref) ?></span>
        </div>
        <?php endif; ?>

        <?php if ($order): ?>
        <!-- جزئیات سفارش -->
        <div class="result-order-details">
            <h5 class="result-details-title">
                <i class="fas fa-receipt me-2"></i>
                <?= $is_fa ? 'جزئیات سفارش' : 'Order Details' ?>
            </h5>

            <div class="result-order-meta">
                <div class="result-meta-row">
                    <span class="meta-label">
                        <i class="fas fa-barcode me-1"></i>
                        <?= $is_fa ? 'کد سفارش:' : 'Order Code:' ?>
                    </span>
                    <span class="meta-value" dir="ltr"><?= htmlspecialchars($order['order_code']) ?></span>
                </div>
                <div class="result-meta-row">
                    <span class="meta-label">
                        <i class="fas fa-user me-1"></i>
                        <?= $is_fa ? 'نام:' : 'Name:' ?>
                    </span>
                    <span class="meta-value"><?= htmlspecialchars($order['full_name']) ?></span>
                </div>
                <div class="result-meta-row">
                    <span class="meta-label">
                        <i class="fas fa-envelope me-1"></i>
                        <?= $is_fa ? 'ایمیل:' : 'Email:' ?>
                    </span>
                    <span class="meta-value" dir="ltr"><?= htmlspecialchars($order['email']) ?></span>
                </div>
                <?php if (!empty($order['shipping_address'])): ?>
                <div class="result-meta-row">
                    <span class="meta-label">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        <?= $is_fa ? 'آدرس ارسال:' : 'Shipping Address:' ?>
                    </span>
                    <span class="meta-value" style="text-align:right;font-size:0.83rem;max-width:240px;">
                        <?= nl2br(htmlspecialchars($order['shipping_address'])) ?>
                    </span>
                </div>
                <?php endif; ?>
                <div class="result-meta-row">
                    <span class="meta-label">
                        <i class="fas fa-money-bill-wave me-1"></i>
                        <?= $is_fa ? 'مبلغ:' : 'Amount:' ?>
                    </span>
                    <span class="meta-value">
                        <?= number_format((float)$order['final_amount']) ?>
                        <?= $is_fa ? ' تومان' : ' Toman' ?>
                    </span>
                </div>
            </div>

            <?php if (!empty($orderItems)): ?>
            <div class="result-items-list">
                <div class="result-items-header"><?= $is_fa ? 'محصولات خریداری‌شده:' : 'Purchased Items:' ?></div>
                <?php foreach ($orderItems as $item): ?>
                <div class="result-item-row">
                    <div class="result-item-info">
                        <span class="result-item-title"><?= htmlspecialchars($item['product_title']) ?></span>
                        <span class="result-item-badge">
                            <?= $item['product_type'] === 'pdf'
                                ? '<i class="fas fa-file-pdf me-1"></i>PDF'
                                : '<i class="fas fa-print me-1"></i>' . ($is_fa ? 'چاپی' : 'Print')
                            ?>
                        </span>
                    </div>
                    <div class="result-item-price">
                        <?= number_format((float)$item['total_price']) ?>
                        <small><?= $is_fa ? 'ت' : 'T' ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- دکمه‌های اقدام -->
        <div class="result-actions">
            <?php if ($is_success): ?>
                <a href="products.php" class="result-btn result-btn-primary">
                    <i class="fas fa-store me-2"></i>
                    <?= $is_fa ? 'ادامه خرید' : 'Continue Shopping' ?>
                </a>
                <a href="index.php" class="result-btn result-btn-secondary">
                    <i class="fas fa-home me-2"></i>
                    <?= $is_fa ? 'صفحه اصلی' : 'Home' ?>
                </a>
            <?php else: ?>
                <a href="checkout.php" class="result-btn result-btn-retry">
                    <i class="fas fa-redo me-2"></i>
                    <?= $is_fa ? 'تلاش مجدد' : 'Try Again' ?>
                </a>
                <a href="cart.php" class="result-btn result-btn-secondary">
                    <i class="fas fa-shopping-cart me-2"></i>
                    <?= $is_fa ? 'بازگشت به سبد' : 'Back to Cart' ?>
                </a>
            <?php endif; ?>
        </div>

    </div>

</div>

<!-- نوار پایین موبایل -->
<div class="bottom-nav">
    <a href="index.php" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon-m.png" alt="Home" class="bottom-nav-icon">
        <span><?= $is_fa ? 'خانه' : 'Home' ?></span>
    </a>
    <a href="profile.php" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon2-m.png" alt="Celebrities" class="bottom-nav-icon">
        <span><?= $is_fa ? 'مشاهیر' : 'Celebs' ?></span>
    </a>
    <a href="products.php" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon1-m.png" alt="Products" class="bottom-nav-icon">
        <span><?= $is_fa ? 'محصولات' : 'Products' ?></span>
    </a>
    <a href="cart.php" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon3-m.png" alt="Cart" class="bottom-nav-icon">
        <span><?= $is_fa ? 'سبد' : 'Cart' ?></span>
    </a>
    <a href="#" class="bottom-nav-item">
        <span><?= $is_fa ? 'بیشتر' : 'More' ?></span>
        <img src="<?= IMG_PATH ?>icon5-m.png" alt="More" class="bottom-icon-btn">
    </a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
