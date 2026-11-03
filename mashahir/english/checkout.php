<?php
require_once __DIR__ . '/../includes/config.php';

// ─── Auth: user must be logged in ─────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['user_id'])) {
    $redirect = urlencode($_SERVER['REQUEST_URI']);
    header('Location: /Kavian/login.php?redirect=' . $redirect);
    exit;
}
// ──────────────────────────────────────────────────────────────

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container checkout-container">

    <!-- Progress steps -->
    <div class="checkout-steps">
        <div class="checkout-step active">
            <span class="step-num">1</span>
            <span class="step-label"><?= $is_fa ? 'سبد خرید' : 'Cart' ?></span>
        </div>
        <div class="checkout-step-arrow"><i class="fas fa-chevron-right"></i></div>
        <div class="checkout-step active">
            <span class="step-num">2</span>
            <span class="step-label"><?= $is_fa ? 'اطلاعات' : 'Details' ?></span>
        </div>
        <div class="checkout-step-arrow"><i class="fas fa-chevron-right"></i></div>
        <div class="checkout-step">
            <span class="step-num">3</span>
            <span class="step-label"><?= $is_fa ? 'پرداخت' : 'Payment' ?></span>
        </div>
    </div>

    <div id="checkoutLoading" class="cart-loading">
        <div class="spinner-border text-success" role="status"></div>
    </div>

    <div id="checkoutEmpty" class="cart-empty" style="display:none;">
        <div class="cart-empty-icon"><i class="fas fa-shopping-basket"></i></div>
        <h3><?= $is_fa ? 'سبد خرید شما خالی است' : 'Your cart is empty' ?></h3>
        <a href="products.php" class="btn-go-shop">
            <i class="fas fa-store me-2"></i>
            <?= $is_fa ? 'رفتن به فروشگاه' : 'Go to Shop' ?>
        </a>
    </div>

    <div id="checkoutContent" style="display:none;">
        <div class="row g-4">

            <!-- Form column -->
            <div class="col-lg-7">
                <div class="checkout-form-card">
                    <h4 class="checkout-section-title">
                        <i class="fas fa-user-circle me-2"></i>
                        <?= $is_fa ? 'اطلاعات تماس' : 'Contact Information' ?>
                    </h4>

                    <form id="checkoutForm" novalidate>

                        <div class="form-group mb-3">
                            <label class="form-label" for="fullName">
                                <?= $is_fa ? 'نام و نام خانوادگی' : 'Full Name' ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="fullName" name="full_name"
                                   class="form-control checkout-input"
                                   placeholder="<?= $is_fa ? 'مثال: علی رضایی' : 'e.g. John Smith' ?>"
                                   required>
                            <div class="invalid-feedback" id="fullNameError"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="email">
                                <?= $is_fa ? 'ایمیل' : 'Email' ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="email" id="email" name="email"
                                   class="form-control checkout-input"
                                   placeholder="example@email.com"
                                   dir="ltr" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="phone">
                                <?= $is_fa ? 'شماره تماس' : 'Phone' ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone"
                                   class="form-control checkout-input"
                                   placeholder="<?= $is_fa ? '۰۹۱۲۳۴۵۶۷۸۹' : '+1 555 000 0000' ?>"
                                   dir="ltr" required>
                            <div class="invalid-feedback" id="phoneError"></div>
                        </div>

                        <!-- Shipping address — shown only when cart has print items -->
                        <div class="form-group mb-3 shipping-address-box" id="shippingAddressBox" style="display:none;">
                            <div class="shipping-notice">
                                <i class="fas fa-truck me-2"></i>
                                <?= $is_fa
                                    ? 'سفارش شما شامل نسخه چاپی است. لطفاً آدرس ارسال را وارد کنید.'
                                    : 'Your order includes a print edition. Please enter your shipping address.'
                                ?>
                            </div>
                            <label class="form-label mt-3" for="shippingAddress">
                                <?= $is_fa ? 'آدرس کامل ارسال' : 'Full Shipping Address' ?>
                                <span class="text-danger">*</span>
                            </label>
                            <textarea id="shippingAddress" name="shipping_address"
                                      class="form-control checkout-input"
                                      rows="3"
                                      placeholder="<?= $is_fa
                                          ? 'استان، شهر، خیابان، پلاک، کد پستی...'
                                          : 'Street, City, State/Province, ZIP/Postal Code, Country...'
                                      ?>"></textarea>
                            <div class="invalid-feedback" id="shippingAddressError"></div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label" for="notes">
                                <?= $is_fa ? 'یادداشت (اختیاری)' : 'Notes (optional)' ?>
                            </label>
                            <textarea id="notes" name="notes"
                                      class="form-control checkout-input"
                                      rows="3"
                                      placeholder="<?= $is_fa ? 'در صورت نیاز یادداشت بگذارید...' : 'Leave a note if needed...' ?>"></textarea>
                        </div>

                        <div id="checkoutError" class="checkout-error" style="display:none;"></div>

                    </form>
                </div>

                <!-- Simulated payment gateway -->
                <div class="checkout-form-card mt-3" id="paymentGatewayBox" style="display:none;">
                    <h4 class="checkout-section-title">
                        <i class="fas fa-credit-card me-2"></i>
                        <?= $is_fa ? 'درگاه پرداخت' : 'Payment Gateway' ?>
                    </h4>
                    <div class="payment-gateway-mock">
                        <div class="gateway-logo">
                            <i class="fas fa-university me-2"></i>
                            <?= $is_fa ? 'درگاه پرداخت شبیه‌سازی‌شده' : 'Simulated Payment Gateway' ?>
                        </div>
                        <div class="gateway-amount-display">
                            <span class="gateway-label"><?= $is_fa ? 'مبلغ قابل پرداخت:' : 'Amount Due:' ?></span>
                            <span class="gateway-amount" id="gatewayAmount"></span>
                            <span class="gateway-currency"><?= $is_fa ? 'تومان' : 'Toman' ?></span>
                        </div>
                        <div class="gateway-card-mock">
                            <div class="mock-card">
                                <div class="mock-card-num">•••• •••• •••• 1234</div>
                                <div class="mock-card-info">
                                    <span><?= $is_fa ? 'تست' : 'Test' ?></span>
                                    <span>12/28</span>
                                </div>
                            </div>
                        </div>
                        <div class="gateway-note">
                            <i class="fas fa-info-circle me-1"></i>
                            <?= $is_fa
                                ? 'این یک پرداخت شبیه‌سازی‌شده است. هیچ مبلغ واقعی کسر نمی‌شود.'
                                : 'This is a simulated payment. No real amount will be charged.'
                            ?>
                        </div>
                        <button class="btn-pay-now" id="btnPayNow" onclick="processPayment()">
                            <span id="payBtnText">
                                <i class="fas fa-lock me-2"></i>
                                <?= $is_fa ? 'پرداخت امن' : 'Secure Payment' ?>
                            </span>
                            <span id="payBtnLoading" style="display:none;">
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                <?= $is_fa ? 'در حال پردازش...' : 'Processing...' ?>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary column -->
            <div class="col-lg-5">
                <div class="cart-summary-card checkout-summary-card">
                    <h4 class="cart-summary-title">
                        <i class="fas fa-receipt me-2"></i>
                        <?= $is_fa ? 'خلاصه سفارش' : 'Order Summary' ?>
                    </h4>
                    <div id="checkoutItemsList" class="checkout-items-list"></div>
                    <div class="cart-summary-divider"></div>
                    <div class="cart-summary-row cart-summary-total">
                        <span><?= $is_fa ? 'مبلغ قابل پرداخت' : 'Total Payable' ?></span>
                        <span id="checkoutTotal">—</span>
                    </div>
                    <button class="btn-checkout" id="btnProceed" onclick="proceedToPayment()">
                        <i class="fas fa-arrow-right me-2"></i>
                        <?= $is_fa ? 'ادامه به پرداخت' : 'Continue to Payment' ?>
                    </button>
                    <a href="cart.php" class="btn-back-link d-block text-center mt-2">
                        <i class="fas fa-arrow-left me-1"></i>
                        <?= $is_fa ? 'بازگشت به سبد' : 'Back to Cart' ?>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>

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
    <a href="cart.php" class="bottom-nav-item active-btn">
        <img src="<?= IMG_PATH ?>icon3-m.png" alt="Cart" class="bottom-nav-icon">
        <span><?= $is_fa ? 'سبد' : 'Cart' ?></span>
    </a>
    <a href="#" class="bottom-nav-item">
        <span><?= $is_fa ? 'بیشتر' : 'More' ?></span>
        <img src="<?= IMG_PATH ?>icon5-m.png" alt="More" class="bottom-icon-btn">
    </a>
</div>

<script>
const IS_FA        = <?= $is_fa ? 'true' : 'false' ?>;
const CART_API     = '/Kavian/mashahir/api/cart-api.php';
const CHECKOUT_API = '/Kavian/mashahir/api/checkout-api.php';
const PAYMENT_API  = '/Kavian/mashahir/api/payment-simulate.php';
const RESULT_URL   = '/Kavian/mashahir/english/payment-result.php';

let currentOrderId = null;
let currentAmount  = 0;

function loadCart() {
    fetch(CART_API + '?action=get')
        .then(r => r.json())
        .then(data => {
            document.getElementById('checkoutLoading').style.display = 'none';
            if (!data.success || data.count === 0) {
                document.getElementById('checkoutEmpty').style.display = 'flex';
                return;
            }
            document.getElementById('checkoutContent').style.display = 'block';
            renderCheckoutSummary(data.items, data.total);
            currentAmount = data.total;
        })
        .catch(() => {
            document.getElementById('checkoutLoading').style.display = 'none';
            document.getElementById('checkoutEmpty').style.display = 'flex';
        });
}

function renderCheckoutSummary(items, total) {
    const listEl = document.getElementById('checkoutItemsList');
    listEl.innerHTML = items.map(item => {
        const title     = IS_FA ? (item.title_fa || item.title_en) : (item.title_en || item.title_fa);
        const typeLabel = item.product_type === 'pdf' ? 'PDF' : (IS_FA ? 'چاپی' : 'Print');
        return `
        <div class="checkout-item-row">
            <div class="checkout-item-title">
                ${escHtml(title)}
                <span class="checkout-item-type">${typeLabel}</span>
            </div>
            <div class="checkout-item-price">
                ${formatPrice(item.total_price)}
                <small>${IS_FA ? 'ت' : 'T'}</small>
            </div>
        </div>`;
    }).join('');

    document.getElementById('checkoutTotal').textContent =
        formatPrice(total) + ' ' + (IS_FA ? 'تومان' : 'Toman');

    const hasPrint = items.some(item => item.product_type === 'print');
    document.getElementById('shippingAddressBox').style.display = hasPrint ? 'block' : 'none';
}

function proceedToPayment() {
    const fullName        = document.getElementById('fullName').value.trim();
    const email           = document.getElementById('email').value.trim();
    const phone           = document.getElementById('phone').value.trim();
    const notes           = document.getElementById('notes').value.trim();
    const shippingAddress = document.getElementById('shippingAddress').value.trim();
    const shippingVisible = document.getElementById('shippingAddressBox').style.display !== 'none';

    let valid = true;
    clearErrors();

    if (!fullName) {
        showFieldError('fullName', IS_FA ? 'لطفاً نام خود را وارد کنید.' : 'Please enter your name.');
        valid = false;
    }
    if (!email || !isValidEmail(email)) {
        showFieldError('email', IS_FA ? 'ایمیل معتبر وارد کنید.' : 'Enter a valid email.');
        valid = false;
    }
    if (!phone) {
        showFieldError('phone', IS_FA ? 'لطفاً شماره تماس را وارد کنید.' : 'Please enter your phone number.');
        valid = false;
    }
    if (shippingVisible && !shippingAddress) {
        showFieldError('shippingAddress', IS_FA ? 'لطفاً آدرس ارسال را وارد کنید.' : 'Please enter a shipping address.');
        valid = false;
    }
    if (!valid) return;

    const btn = document.getElementById('btnProceed');
    btn.disabled = true;
    btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>${IS_FA ? 'در حال پردازش...' : 'Processing...'}`;

    fetch(CHECKOUT_API, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            full_name:        fullName,
            email,
            phone,
            notes,
            shipping_address: shippingVisible ? shippingAddress : '',
        })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-arrow-right me-2"></i>${IS_FA ? 'ادامه به پرداخت' : 'Continue to Payment'}`;

        if (!data.success) {
            showGlobalError(data.message || (IS_FA ? 'خطا در ثبت سفارش.' : 'Order registration failed.'));
            return;
        }

        currentOrderId = data.order_id;
        currentAmount  = data.amount;

        document.getElementById('paymentGatewayBox').style.display = 'block';
        document.getElementById('gatewayAmount').textContent = formatPrice(data.amount);
        document.getElementById('btnProceed').style.display = 'none';
        document.getElementById('paymentGatewayBox').scrollIntoView({ behavior: 'smooth' });
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = `<i class="fas fa-arrow-right me-2"></i>${IS_FA ? 'ادامه به پرداخت' : 'Continue to Payment'}`;
        showGlobalError(IS_FA ? 'خطا در اتصال به سرور.' : 'Server connection error.');
    });
}

function processPayment() {
    if (!currentOrderId) return;
    const btn     = document.getElementById('btnPayNow');
    const btnText = document.getElementById('payBtnText');
    const btnLoad = document.getElementById('payBtnLoading');
    btn.disabled          = true;
    btnText.style.display = 'none';
    btnLoad.style.display = 'inline-flex';

    fetch(PAYMENT_API, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ order_id: currentOrderId, amount: currentAmount })
    })
    .then(r => r.json())
    .then(data => {
        const status = data.success ? 'success' : 'failed';
        const ref    = data.payment_ref || '';
        window.location.href = `${RESULT_URL}?status=${status}&order_id=${currentOrderId}&ref=${encodeURIComponent(ref)}`;
    })
    .catch(() => {
        btn.disabled          = false;
        btnText.style.display = 'inline-flex';
        btnLoad.style.display = 'none';
        showGlobalError(IS_FA ? 'خطا در ارتباط با درگاه پرداخت.' : 'Payment gateway connection error.');
    });
}

function isValidEmail(e) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(e); }

function showFieldError(fieldId, msg) {
    const el = document.getElementById(fieldId);
    if (el) el.classList.add('is-invalid');
    const errEl = document.getElementById(fieldId + 'Error');
    if (errEl) errEl.textContent = msg;
}

function clearErrors() {
    document.querySelectorAll('.checkout-input').forEach(el => el.classList.remove('is-invalid'));
    document.getElementById('checkoutError').style.display = 'none';
}

function showGlobalError(msg) {
    const el = document.getElementById('checkoutError');
    el.textContent = msg;
    el.style.display = 'block';
}

function formatPrice(val) { return Number(val).toLocaleString('en-US'); }

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str || ''));
    return d.innerHTML;
}

document.addEventListener('DOMContentLoaded', loadCart);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
