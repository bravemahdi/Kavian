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

<div class="container cart-page-container">

    <div class="cart-page-header">
        <h1 class="cart-page-title">
            <i class="fas fa-shopping-cart me-2"></i>
            <?= $is_fa ? 'سبد خرید' : 'Shopping Cart' ?>
        </h1>
        <a href="products.php" class="btn-back-link">
            <i class="fas fa-arrow-left me-1"></i>
            <?= $is_fa ? 'ادامه خرید' : 'Continue Shopping' ?>
        </a>
    </div>

    <div id="cartLoading" class="cart-loading">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden"><?= $is_fa ? 'در حال بارگذاری...' : 'Loading...' ?></span>
        </div>
    </div>

    <div id="cartEmpty" class="cart-empty" style="display:none;">
        <div class="cart-empty-icon"><i class="fas fa-shopping-basket"></i></div>
        <h3><?= $is_fa ? 'سبد خرید شما خالی است' : 'Your cart is empty' ?></h3>
        <p><?= $is_fa ? 'محصولات دلخواهتان را به سبد اضافه کنید.' : 'Add your favourite products to the cart.' ?></p>
        <a href="products.php" class="btn-go-shop">
            <i class="fas fa-store me-2"></i>
            <?= $is_fa ? 'رفتن به فروشگاه' : 'Go to Shop' ?>
        </a>
    </div>

    <div id="cartContent" style="display:none;">
        <div class="row g-4">

            <div class="col-lg-8">
                <div class="cart-items-card">
                    <div class="cart-items-header">
                        <span id="cartItemCount"></span>
                        <button class="btn-clear-cart" onclick="clearCart()">
                            <i class="fas fa-trash-alt me-1"></i>
                            <?= $is_fa ? 'خالی کردن سبد' : 'Clear Cart' ?>
                        </button>
                    </div>
                    <div id="cartItemsList"></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-summary-card">
                    <h4 class="cart-summary-title">
                        <i class="fas fa-receipt me-2"></i>
                        <?= $is_fa ? 'خلاصه سفارش' : 'Order Summary' ?>
                    </h4>
                    <div class="cart-summary-row">
                        <span><?= $is_fa ? 'جمع کل' : 'Subtotal' ?></span>
                        <span id="summarySubtotal">—</span>
                    </div>
                    <div class="cart-summary-divider"></div>
                    <div class="cart-summary-row cart-summary-total">
                        <span><?= $is_fa ? 'مبلغ قابل پرداخت' : 'Total Payable' ?></span>
                        <span id="summaryTotal">—</span>
                    </div>
                    <a href="checkout.php" class="btn-checkout" id="checkoutBtn">
                        <i class="fas fa-lock me-2"></i>
                        <?= $is_fa ? 'ادامه و پرداخت' : 'Proceed to Checkout' ?>
                    </a>
                    <div class="cart-secure-note">
                        <i class="fas fa-shield-alt me-1"></i>
                        <?= $is_fa ? 'پرداخت امن و رمزگذاری‌شده' : 'Secure encrypted payment' ?>
                    </div>
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
const IS_FA    = <?= $is_fa ? 'true' : 'false' ?>;
const CART_API = '/Kavian/mashahir/api/cart-api.php';

function loadCart() {
    fetch(CART_API + '?action=get')
        .then(r => r.json())
        .then(data => {
            document.getElementById('cartLoading').style.display = 'none';
            if (!data.success) { showCartError(); return; }
            if (data.count === 0) {
                document.getElementById('cartEmpty').style.display = 'flex';
            } else {
                document.getElementById('cartContent').style.display = 'block';
                renderCartItems(data.items);
                renderSummary(data.total);
            }
        })
        .catch(() => {
            document.getElementById('cartLoading').style.display = 'none';
            showCartError();
        });
}

function renderCartItems(items) {
    const countEl = document.getElementById('cartItemCount');
    countEl.textContent = IS_FA
        ? `${items.length} کالا در سبد`
        : `${items.length} item${items.length !== 1 ? 's' : ''} in cart`;
    document.getElementById('cartItemsList').innerHTML =
        items.map(item => buildItemHtml(item)).join('');
}

function buildItemHtml(item) {
    const title  = IS_FA ? (item.title_fa || item.title_en) : (item.title_en || item.title_fa);
    const author = IS_FA ? item.author_fa : item.author_en;

    let typeLabel, typeIcon;
    if (item.product_type === 'pdf') {
        typeIcon = 'fa-file-pdf';
        const langLabel = IS_FA
            ? (item.pdf_label_fa || item.pdf_lang || 'PDF')
            : (item.pdf_label_en || item.pdf_lang || 'PDF');
        typeLabel = (IS_FA ? 'نسخه PDF' : 'PDF Edition') + ' — ' + langLabel;
    } else {
        typeIcon  = 'fa-print';
        typeLabel = IS_FA ? 'نسخه چاپی' : 'Print Edition';
    }

    const imgSrc   = item.image || placeholderImg();

    return `
    <div class="cart-item" id="cartItem_${item.cart_id}">
        <div class="cart-item-img">
            <img src="${imgSrc}" alt="${escHtml(title)}"
                 onerror="this.src='${placeholderImg()}'">
        </div>
        <div class="cart-item-info">
            <div class="cart-item-title">${escHtml(title)}</div>
            ${author ? `<div class="cart-item-author"><i class="fas fa-feather-alt me-1"></i>${escHtml(author)}</div>` : ''}
            <div class="cart-item-type"><i class="fas ${typeIcon} me-1"></i>${typeLabel}</div>
        </div>
        <div class="cart-item-controls">
            <button class="cart-remove-btn" onclick="removeItem(${item.cart_id})" aria-label="Remove" title="Remove from cart">
                <i class="fas fa-times"></i>
            </button>
            <div class="cart-qty-box">
                <button class="cart-qty-btn" onclick="changeQty(${item.cart_id}, ${item.quantity - 1})"
                        ${item.quantity <= 1 ? 'disabled' : ''} aria-label="Decrease">
                    <i class="fas fa-minus"></i>
                </button>
                <span class="cart-qty-val">${item.quantity}</span>
                <button class="cart-qty-btn" onclick="changeQty(${item.cart_id}, ${item.quantity + 1})"
                        ${item.quantity >= 10 ? 'disabled' : ''} aria-label="Increase">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <div class="cart-item-price">
                <span class="cart-price-unit">${formatPrice(item.unit_price)}</span>
                <span class="cart-price-label">${IS_FA ? 'تومان' : 'T'}</span>
            </div>
        </div>
    </div>`;
}

function renderSummary(total) {
    const fmt = formatPrice(total) + ' ' + (IS_FA ? 'تومان' : 'T');
    document.getElementById('summarySubtotal').textContent = fmt;
    document.getElementById('summaryTotal').textContent    = fmt;
}

function changeQty(cartId, newQty) {
    if (newQty < 1 || newQty > 10) return;
    fetch(CART_API, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ action: 'update', cart_id: cartId, quantity: newQty })
    })
    .then(r => r.json())
    .then(data => { if (data.success) { renderCartItems(data.items); renderSummary(data.total); } });
}

function removeItem(cartId) {
    const el = document.getElementById('cartItem_' + cartId);
    if (el) { el.style.opacity = '0.4'; el.style.pointerEvents = 'none'; }
    fetch(CART_API, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ action: 'remove', cart_id: cartId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (data.count === 0) {
                document.getElementById('cartContent').style.display = 'none';
                document.getElementById('cartEmpty').style.display   = 'flex';
            } else {
                renderCartItems(data.items);
                renderSummary(data.total);
            }
            updateCartBadge(data.count);
        }
    });
}

function clearCart() {
    if (!confirm(IS_FA ? 'آیا سبد خرید خالی شود؟' : 'Clear the entire cart?')) return;
    fetch(CART_API, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ action: 'clear' })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('cartContent').style.display = 'none';
            document.getElementById('cartEmpty').style.display   = 'flex';
            updateCartBadge(0);
        }
    });
}

function updateCartBadge(count) {
    document.querySelectorAll('.cart-badge').forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? 'flex' : 'none';
    });
}

function formatPrice(val) { return Number(val).toLocaleString('en-US'); }

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str || ''));
    return d.innerHTML;
}

function placeholderImg() {
    return "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='100'%3E%3Crect width='80' height='100' fill='%23ede5dc'/%3E%3Ctext x='50%25' y='50%25' text-anchor='middle' dy='.3em' fill='%23a3846b' font-size='28'%3E📚%3C/text%3E%3C/svg%3E";
}

function showCartError() {
    document.getElementById('cartEmpty').style.display = 'flex';
}

document.addEventListener('DOMContentLoaded', loadCart);
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
