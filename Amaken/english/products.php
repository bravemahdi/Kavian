<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div class="row g-4">

        <!-- ===== فیلتر سایدبار دسکتاپ ===== -->
        <div class="col-lg-3" id="productFilterCol">
            <div class="filters-sidebar" id="productFilterSidebar">
                <div class="filter-title">
                    <i class="fas fa-sliders-h"></i>
                    <?= $is_fa ? 'فیلترها' : 'Filters' ?>
                </div>

                <!-- جستجو -->
                <div class="filter-group">
                    <span class="group-label">
                        <i class="fas fa-search me-1"></i>
                        <?= $is_fa ? 'جستجو' : 'Search' ?>
                    </span>
                    <input
                        type="text"
                        id="productSearchInput"
                        class="product-search-input"
                        placeholder="<?= $is_fa ? 'نام کتاب یا نویسنده...' : 'Book name or author...' ?>"
                        oninput="filterProducts()"
                        autocomplete="off"
                    >
                </div>

                <!-- فیلتر مشاهیر -->
                <div class="filter-group">
                    <span class="group-label">
                        <i class="fas fa-user me-1"></i>
                        <?= $is_fa ? 'مشاهیر' : 'Celebrities' ?>
                    </span>
                    <div id="sidebarCelebrityFilters"></div>
                </div>

                <!-- فیلتر دسته‌بندی -->
                <div class="filter-group">
                    <span class="group-label">
                        <i class="fas fa-tags me-1"></i>
                        <?= $is_fa ? 'دسته‌بندی' : 'Category' ?>
                    </span>
                    <div id="sidebarCategoryFilters"></div>
                </div>

                <!-- فیلتر ویژه -->
                <div class="filter-group">
                    <span class="group-label">
                        <i class="fas fa-star me-1"></i>
                        <?= $is_fa ? 'نوع محصول' : 'Product Type' ?>
                    </span>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="featured" id="filterFeatured" onchange="filterProducts()">
                        <label class="form-check-label" for="filterFeatured">
                            <?= $is_fa ? 'محصولات ویژه' : 'Featured Products' ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sale" id="filterSale" onchange="filterProducts()">
                        <label class="form-check-label" for="filterSale">
                            <?= $is_fa ? 'دارای تخفیف' : 'On Sale' ?>
                        </label>
                    </div>
                </div>

                <div class="filter-actions">
                    <button class="btn-apply" onclick="filterProducts()">
                        <i class="fas fa-check me-1"></i>
                        <?= $is_fa ? 'اعمال' : 'Apply' ?>
                    </button>
                    <button class="btn-clear" onclick="clearProductFilters()">
                        <i class="fas fa-undo me-1"></i>
                        <?= $is_fa ? 'پاک کردن' : 'Clear' ?>
                    </button>
                </div>
            </div>
        </div>

        <!-- ===== محتوای اصلی ===== -->
        <div class="col-lg-9">

            <!-- هدر صفحه -->
            <div class="products-page-header">
                <div class="products-page-title">
                    <i class="fas fa-book-open"></i>
                    <?= $is_fa ? 'محصولات' : 'Products' ?>
                    <span class="products-count" id="productsCount"></span>
                </div>
                <div class="products-sort">
                    <select id="productSort" onchange="sortProducts()" class="sort-select">
                        <option value="default"><?= $is_fa ? 'مرتب‌سازی' : 'Sort by' ?></option>
                        <option value="price_asc"><?= $is_fa ? 'قیمت: کم به زیاد' : 'Price: Low to High' ?></option>
                        <option value="price_desc"><?= $is_fa ? 'قیمت: زیاد به کم' : 'Price: High to Low' ?></option>
                        <option value="featured"><?= $is_fa ? 'ویژه‌ها اول' : 'Featured First' ?></option>
                    </select>
                </div>
            </div>

            <!-- دکمه فیلتر موبایل -->
            <div class="filter-header-mobile" id="mobileFilterToggle">
                <button class="filter-icon-btn" onclick="toggleProductFilter()">
                    <i class="fas fa-sliders-h"></i>
                    <?= $is_fa ? 'فیلتر' : 'Filter' ?>
                    <span class="filter-count" id="productFilterCount">0</span>
                </button>
            </div>

            <!-- فیلتر موبایل dropdown -->
            <div class="filter-dropdown" id="productFilterDropdown">
                <div class="filter-group">
                    <span class="group-label">
                        <i class="fas fa-search me-1"></i>
                        <?= $is_fa ? 'جستجو' : 'Search' ?>
                    </span>
                    <input
                        type="text"
                        id="productSearchMobile"
                        class="product-search-input"
                        placeholder="<?= $is_fa ? 'نام کتاب یا نویسنده...' : 'Book name or author...' ?>"
                        oninput="filterProducts()"
                        autocomplete="off"
                    >
                </div>
                <div class="filter-group">
                    <span class="group-label">
                        <i class="fas fa-user me-1"></i>
                        <?= $is_fa ? 'مشاهیر' : 'Celebrities' ?>
                    </span>
                    <div id="mobileCelebrityFilters"></div>
                </div>
                <div class="filter-group">
                    <span class="group-label">
                        <i class="fas fa-star me-1"></i>
                        <?= $is_fa ? 'نوع محصول' : 'Product Type' ?>
                    </span>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="featured" id="mFilterFeatured" onchange="filterProducts()">
                        <label class="form-check-label" for="mFilterFeatured">
                            <?= $is_fa ? 'محصولات ویژه' : 'Featured' ?>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="sale" id="mFilterSale" onchange="filterProducts()">
                        <label class="form-check-label" for="mFilterSale">
                            <?= $is_fa ? 'دارای تخفیف' : 'On Sale' ?>
                        </label>
                    </div>
                </div>
                <div class="filter-actions">
                    <button class="btn-apply" onclick="filterProducts(); toggleProductFilter()">
                        <i class="fas fa-check me-1"></i>
                        <?= $is_fa ? 'اعمال' : 'Apply' ?>
                    </button>
                    <button class="btn-clear" onclick="clearProductFilters()">
                        <i class="fas fa-undo me-1"></i>
                        <?= $is_fa ? 'پاک کردن' : 'Clear' ?>
                    </button>
                </div>
            </div>

            <!-- گرید محصولات -->
            <div id="productsGrid"></div>

        </div>
    </div>

    <div class="text-center mt-4 py-3 border-top border-light">
        <p class="text-muted small">
            <i class="fas fa-chevron-<?= $is_fa ? 'right' : 'left' ?>"></i> <?= SITE_NAME ?> <i class="fas fa-chevron-<?= $is_fa ? 'left' : 'right' ?>"></i>
        </p>
    </div>
</div>

<script>
const IS_FA = <?= $is_fa ? 'true' : 'false' ?>;
const LANG  = '<?= $current_lang ?>';

let allProducts  = [];
let displayedProducts = [];

// ===== بارگذاری محصولات از API =====
function loadProducts() {
    const url = `/Kavian/mashahir/api/get-products-list.php?lang=${LANG}`;

    fetch(url)
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                showProductsError(IS_FA ? 'خطا در بارگذاری محصولات' : 'Error loading products');
                return;
            }

            allProducts      = data.data;
            displayedProducts = [...allProducts];

            buildFilterCheckboxes(data.celebrities || []);
            renderProducts(displayedProducts);
        })
        .catch(() => showProductsError(IS_FA ? 'خطا در ارتباط با سرور' : 'Server connection error'));
}

// ===== ساخت چک‌باکس‌های فیلتر =====
function buildFilterCheckboxes(celebrities) {

    // مشاهیر — سایدبار و موبایل
    const celebHtml = celebrities.map((c, i) => `
        <div class="form-check">
            <input class="form-check-input prod-celeb-filter" type="checkbox"
                   value="${c.id}" id="celeb${i}" onchange="filterProducts()">
            <label class="form-check-label" for="celeb${i}">
                ${IS_FA ? c.name_fa : (c.name_en || c.name_fa)}
            </label>
        </div>
    `).join('');

    const sidebarCeleb = document.getElementById('sidebarCelebrityFilters');
    const mobileCeleb  = document.getElementById('mobileCelebrityFilters');
    if (sidebarCeleb) sidebarCeleb.innerHTML = celebHtml;

    // برای موبایل id‌های متفاوت
    const celebHtmlM = celebrities.map((c, i) => `
        <div class="form-check">
            <input class="form-check-input prod-celeb-filter" type="checkbox"
                   value="${c.id}" id="mceleb${i}" onchange="filterProducts()">
            <label class="form-check-label" for="mceleb${i}">
                ${IS_FA ? c.name_fa : (c.name_en || c.name_fa)}
            </label>
        </div>
    `).join('');
    if (mobileCeleb) mobileCeleb.innerHTML = celebHtmlM;

    // دسته‌بندی — سایدبار (در صورت وجود داده)
    const sidebarCat = document.getElementById('sidebarCategoryFilters');
    if (sidebarCat) sidebarCat.innerHTML = '';
}

// ===== فیلتر کردن =====
function filterProducts() {
    const searchDesktop = document.getElementById('productSearchInput');
    const searchMobile  = document.getElementById('productSearchMobile');
    const q = (searchDesktop ? searchDesktop.value : '') ||
              (searchMobile  ? searchMobile.value  : '');
    const query = q.toLowerCase().trim();

    // مشاهیر انتخاب‌شده
    const celebChecked = [...document.querySelectorAll('.prod-celeb-filter:checked')]
        .map(cb => parseInt(cb.value));

    // دسته‌بندی انتخاب‌شده
    const catChecked = [...document.querySelectorAll('.prod-cat-filter:checked')]
        .map(cb => parseInt(cb.value));

    // فیلترهای نوع
    const onlyFeatured = document.getElementById('filterFeatured')?.checked ||
                         document.getElementById('mFilterFeatured')?.checked;
    const onlySale     = document.getElementById('filterSale')?.checked ||
                         document.getElementById('mFilterSale')?.checked;

    displayedProducts = allProducts.filter(p => {
        // جستجوی متن
        if (query) {
            const titleFa = (p.titleFa || '').toLowerCase();
            const titleEn = (p.titleEn || '').toLowerCase();
            const author  = (p.authorFa || '').toLowerCase();
            const celeb   = (p.celebrityName || '').toLowerCase();
            const pub     = (p.publisher || '').toLowerCase();
            if (!titleFa.includes(query) &&
                !titleEn.includes(query) &&
                !author.includes(query)  &&
                !celeb.includes(query)   &&
                !pub.includes(query)) return false;
        }

        // فیلتر مشاهیر
        if (celebChecked.length > 0) {
            if (!celebChecked.includes(p.celebrityId)) return false;
        }

        // فیلتر نوع
        if (onlyFeatured && !p.isFeatured) return false;
        if (onlySale) {
            const hasSale = (p.salePricePrint && p.pricePrint && p.salePricePrint < p.pricePrint)
                         || (p.salePricePdf   && p.pricePdf   && p.salePricePdf   < p.pricePdf);
            if (!hasSale) return false;
        }

        return true;
    });

    sortProducts(false);
    updateFilterCount();
}

// ===== مرتب‌سازی =====
function sortProducts(reRender = true) {
    const select = document.getElementById('productSort');
    const mode   = select ? select.value : 'default';

    if (mode === 'price_asc') {
        displayedProducts.sort((a, b) => {
            const pa = a.salePricePrint || a.pricePrint || a.salePricePdf || a.pricePdf || 0;
            const pb = b.salePricePrint || b.pricePrint || b.salePricePdf || b.pricePdf || 0;
            return pa - pb;
        });
    } else if (mode === 'price_desc') {
        displayedProducts.sort((a, b) => {
            const pa = a.salePricePrint || a.pricePrint || a.salePricePdf || a.pricePdf || 0;
            const pb = b.salePricePrint || b.pricePrint || b.salePricePdf || b.pricePdf || 0;
            return pb - pa;
        });
    } else if (mode === 'featured') {
        displayedProducts.sort((a, b) => (b.isFeatured ? 1 : 0) - (a.isFeatured ? 1 : 0));
    }

    if (reRender) renderProducts(displayedProducts);
    else          renderProducts(displayedProducts);
}

// ===== پاک کردن فیلترها =====
function clearProductFilters() {
    document.querySelectorAll('.prod-celeb-filter, .prod-cat-filter').forEach(cb => cb.checked = false);
    ['filterFeatured','filterSale','mFilterFeatured','mFilterSale'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.checked = false;
    });
    ['productSearchInput','productSearchMobile'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    const select = document.getElementById('productSort');
    if (select) select.value = 'default';

    displayedProducts = [...allProducts];
    renderProducts(displayedProducts);
    updateFilterCount();
    const dd = document.getElementById('productFilterDropdown');
    if (dd) dd.classList.remove('show');
}

// ===== شمارش فیلترهای فعال =====
function updateFilterCount() {
    const count = document.querySelectorAll('.prod-celeb-filter:checked, .prod-cat-filter:checked').length
        + (document.getElementById('filterFeatured')?.checked ? 1 : 0)
        + (document.getElementById('filterSale')?.checked ? 1 : 0)
        + (document.getElementById('mFilterFeatured')?.checked ? 1 : 0)
        + (document.getElementById('mFilterSale')?.checked ? 1 : 0);

    const el = document.getElementById('productFilterCount');
    if (el) el.textContent = count;
}

// ===== نمایش/مخفی فیلتر موبایل =====
function toggleProductFilter() {
    const dd = document.getElementById('productFilterDropdown');
    if (dd) dd.classList.toggle('show');
}

// ===== رندر کارت‌ها =====
function renderProducts(products) {
    const grid = document.getElementById('productsGrid');
    const countEl = document.getElementById('productsCount');
    if (!grid) return;

    if (countEl) countEl.textContent = `(${products.length})`;

    if (products.length === 0) {
        grid.innerHTML = `
            <div class="no-results">
                <i class="fas fa-box-open"></i>
                <h4>${IS_FA ? 'محصولی یافت نشد' : 'No products found'}</h4>
                <p>${IS_FA ? 'لطفاً فیلترهای خود را تغییر دهید.' : 'Please adjust your filters.'}</p>
            </div>`;
        return;
    }

    grid.innerHTML = products.map(p => buildProductCard(p)).join('');
}

// ===== ساخت HTML یک کارت محصول =====
function buildProductCard(p) {
    // قیمت نمایشی: ارزان‌ترین گزینه موجود
    const candidates = [];
    if (p.salePricePrint) candidates.push({ final: p.salePricePrint, original: p.pricePrint });
    else if (p.pricePrint) candidates.push({ final: p.pricePrint, original: null });
    if (p.salePricePdf)   candidates.push({ final: p.salePricePdf, original: p.pricePdf });
    else if (p.pricePdf)   candidates.push({ final: p.pricePdf, original: null });

    const cheapest    = candidates.length ? candidates.reduce((a, b) => a.final <= b.final ? a : b) : null;
    const displayPrice = cheapest ? cheapest.final : null;
    const originalPrice = cheapest ? cheapest.original : null;
    const hasDiscount   = originalPrice && originalPrice > displayPrice;

    const discountPercent = hasDiscount
        ? Math.round((1 - displayPrice / originalPrice) * 100)
        : 0;

    const imgSrc = p.image || 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23ede5dc%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23a3846b%22 font-size=%2232%22%3E📚%3C/text%3E%3C/svg%3E';

    const productUrl = IS_FA
        ? `/Kavian/mashahir/persian/product.php?slug=${encodeURIComponent(p.slug)}`
        : `/Kavian/mashahir/english/product.php?slug=${encodeURIComponent(p.slug)}`;

    const featuredBadge = p.isFeatured
        ? `<span class="product-badge product-badge-featured"><i class="fas fa-star"></i> ${IS_FA ? 'ویژه' : 'Featured'}</span>`
        : '';

    const discountBadge = hasDiscount
        ? `<span class="product-badge product-badge-sale">%${discountPercent} ${IS_FA ? 'تخفیف' : 'OFF'}</span>`
        : '';

    const priceHtml = displayPrice
        ? `${hasDiscount ? `<span class="price-old">${formatPrice(originalPrice)}</span>` : ''}
           <span class="price-new">${formatPrice(displayPrice)}</span>
           <span class="price-unit" style="font-size:0.72rem;color:#7b6250;">${IS_FA ? 'تومان' : 'T'}</span>`
        : `<span class="price-new">${IS_FA ? 'رایگان' : 'Free'}</span>`;

    // نشانه‌های نوع خرید
    const typeBadges = [];
    if (p.pricePrint || p.salePricePrint) typeBadges.push(`<span class="type-badge"><i class="fas fa-print"></i> ${IS_FA ? 'چاپی' : 'Print'}</span>`);
    if (p.pricePdf   || p.salePricePdf)   typeBadges.push(`<span class="type-badge type-badge-pdf"><i class="fas fa-file-pdf"></i> PDF</span>`);

    return `
        <div class="prod-card-product">
            <a href="${productUrl}" class="pcp-image-link" aria-label="${p.title}">
                <div class="pcp-image-wrapper">
                    <img src="${imgSrc}" alt="${p.title}"
                         onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22300%22 height=%22200%22%3E%3Crect width=%22300%22 height=%22200%22 fill=%22%23ede5dc%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%23a3846b%22 font-size=%2232%22%3E📚%3C/text%3E%3C/svg%3E'">
                    <div class="pcp-badges">
                        ${featuredBadge}
                        ${discountBadge}
                    </div>
                    <div class="pcp-overlay">
                        <span class="pcp-view-btn">
                            <i class="fas fa-eye me-1"></i>
                            ${IS_FA ? 'مشاهده' : 'View'}
                        </span>
                    </div>
                </div>
            </a>
            <div class="pcp-body">
                <h4 class="pcp-title">
                    <a href="${productUrl}" class="pcp-title-link">${p.title}</a>
                </h4>
                ${p.author ? `<div class="pcp-author"><i class="fas fa-feather-alt me-1"></i>${p.author}</div>` : ''}
                ${p.publisher ? `<div class="pcp-publisher"><i class="fas fa-building me-1"></i>${p.publisher}</div>` : ''}
                <div class="pcp-meta">
                    ${p.celebrityName ? `<span class="badge-tag celebrity-tag"><i class="fas fa-user me-1"></i>${p.celebrityName}</span>` : ''}
                    ${typeBadges.join(' ')}
                </div>
                <div class="pcp-price-row">
                    <div class="pcp-price">${priceHtml}</div>
                    <a href="${productUrl}" class="pcp-cart-btn" aria-label="${IS_FA ? 'مشاهده محصول' : 'View product'}">
                        <i class="fas ${IS_FA ? 'fa-arrow-left' : 'fa-arrow-right'}"></i>
                    </a>
                </div>
            </div>
        </div>
    `;
}

// ===== فرمت قیمت =====
function formatPrice(price) {
    if (!price && price !== 0) return IS_FA ? 'رایگان' : 'Free';
    return Number(price).toLocaleString('fa-IR');
}

// ===== placeholder سبد خرید (آماده برای ادغام بعدی) =====
function addToCart(id, title) {
    // TODO: ادغام با سیستم سبد خرید
    console.log('addToCart:', id, title);
}

// ===== خطا =====
function showProductsError(msg) {
    const grid = document.getElementById('productsGrid');
    if (grid) grid.innerHTML = `
        <div class="no-results">
            <i class="fas fa-exclamation-triangle" style="color:#e74c3c"></i>
            <h4>${msg}</h4>
        </div>`;
}

// ===== اجرا =====
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
