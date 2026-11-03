<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container" id="productPageContainer">

    <!-- نشانگر بارگذاری -->
    <div id="productLoading" class="text-center py-5">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden"><?= $is_fa ? 'در حال بارگذاری...' : 'Loading...' ?></span>
        </div>
    </div>

    <!-- محتوای محصول -->
    <div id="productContent" style="display:none;">

        <button class="btn-back" id="backBtn" onclick="history.back()">
            <i class="fas <?= $is_fa ? 'fa-arrow-right' : 'fa-arrow-left' ?>"></i>
            <?= $is_fa ? 'بازگشت' : 'Back' ?>
        </button>

        <!-- ===== نسخه دسکتاپ ===== -->
        <div class="product-desktop">

            <div class="pd-layout">

                <!-- ===== ستون چپ: تصویر + خرید (sticky) ===== -->
                <div class="pd-sidebar">

                    <!-- تصویر -->
                    <div class="pd-cover">
                        <div class="product-img-wrapper" id="d_imgWrapper">
                            <img id="d_img" src="" alt="" class="img-fluid">
                            <div class="product-img-badge" id="d_featuredBadge" style="display:none;">
                                <i class="fas fa-star me-1"></i>
                                <?= $is_fa ? 'ویژه' : 'Featured' ?>
                            </div>
                        </div>
                        <!-- نقل‌قول زیر تصویر -->
                        <div class="verse-box mt-3" id="d_quote" style="display:none;"></div>
                    </div>

                    <!-- بخش خرید -->
                    <div class="pd-purchase">
                        <div class="purchase-title">
                            <i class="fas fa-shopping-cart me-1"></i>
                            <?= $is_fa ? 'خرید اثر' : 'Purchase' ?>
                        </div>

                        <div id="d_purchasedBanner" style="display:none;"></div>

                        <div class="purchase-option" id="d_printOption" style="display:none;">
                            <div class="purchase-option-header">
                                <span class="option-icon"><i class="fas fa-print"></i></span>
                                <span class="option-label"><?= $is_fa ? 'نسخه چاپی' : 'Print Edition' ?></span>
                            </div>
                            <div class="purchase-option-price">
                                <span class="price-original" id="d_printOriginal" style="display:none;"></span>
                                <span class="price-final" id="d_printFinal"></span>
                                <span class="price-currency"><?= $is_fa ? 'تومان' : 'Toman' ?></span>
                            </div>
                            <div class="pdf-lang-selector" id="d_printLangBox">
                                <label class="pdf-lang-label" for="d_printLangSelect">
                                    <i class="fas fa-language me-1"></i><?= $is_fa ? 'زبان نسخه:' : 'Edition Language:' ?>
                                </label>
                                <select id="d_printLangSelect" class="pdf-lang-select">
                                    <option value=""><?= $is_fa ? '— انتخاب کنید —' : '— Select —' ?></option>
                                </select>
                                <div class="pdf-lang-error" id="d_printLangError" style="display:none;">
                                    <?= $is_fa ? 'لطفاً زبان نسخه را انتخاب کنید.' : 'Please select an edition language.' ?>
                                </div>
                            </div>
                            <button class="btn-buy btn-buy-print" onclick="addToCart('print')">
                                <i class="fas fa-cart-plus me-1"></i><?= $is_fa ? 'افزودن به سبد' : 'Add to Cart' ?>
                            </button>
                        </div>

                        <div class="purchase-option" id="d_pdfOption" style="display:none;">
                            <div class="purchase-option-header">
                                <span class="option-icon"><i class="fas fa-file-pdf"></i></span>
                                <span class="option-label"><?= $is_fa ? 'نسخه PDF' : 'PDF Edition' ?></span>
                            </div>
                            <div class="purchase-option-price">
                                <span class="price-original" id="d_pdfOriginal" style="display:none;"></span>
                                <span class="price-final" id="d_pdfFinal"></span>
                                <span class="price-currency"><?= $is_fa ? 'تومان' : 'Toman' ?></span>
                            </div>
                            <div class="pdf-lang-selector" id="d_pdfLangBox">
                                <label class="pdf-lang-label" for="d_pdfLangSelect">
                                    <i class="fas fa-language me-1"></i><?= $is_fa ? 'زبان نسخه:' : 'Edition Language:' ?>
                                </label>
                                <select id="d_pdfLangSelect" class="pdf-lang-select">
                                    <option value=""><?= $is_fa ? '— انتخاب کنید —' : '— Select —' ?></option>
                                </select>
                                <div class="pdf-lang-error" id="d_pdfLangError" style="display:none;">
                                    <?= $is_fa ? 'لطفاً زبان نسخه را انتخاب کنید.' : 'Please select an edition language.' ?>
                                </div>
                            </div>
                            <button class="btn-buy btn-buy-pdf" onclick="addToCart('pdf')">
                                <i class="fas fa-download me-1"></i><?= $is_fa ? 'دریافت PDF' : 'Get PDF' ?>
                            </button>
                        </div>

                        <div class="purchase-unavailable" id="d_noPurchase" style="display:none;">
                            <i class="fas fa-info-circle me-1"></i>
                            <?= $is_fa ? 'قیمت‌گذاری در دسترس نیست' : 'Pricing unavailable' ?>
                        </div>
                    </div>

                </div>

                <!-- ===== ستون راست: عنوان + تب‌ها ===== -->
                <div class="pd-main">

                    <!-- هدر اطلاعات -->
                    <div class="pd-header">
                        <span class="top-badge mb-2 d-inline-block">
                            <i class="fas fa-book me-1"></i>
                            <?= $is_fa ? 'اطلاعات کتاب' : 'Book Info' ?>
                        </span>
                        <div class="poet-name" id="d_title"></div>
                        <div class="profile-fullname" id="d_fullTitle"></div>

                        <!-- گرید اطلاعات سریع -->
                        <div class="pd-meta-grid" id="d_infoGrid"></div>

                        <!-- تگ‌ها -->
                        <div class="tag-group mt-3" id="d_tags"></div>
                    </div>

                    <!-- تب‌ها -->
                    <div class="pd-tabs">
                        <button class="pd-tab active" data-tab="about"><?= $is_fa ? 'معرفی' : 'About' ?></button>
                        <button class="pd-tab" data-tab="timeline" id="tabTimeline"><?= $is_fa ? 'تاریخچه' : 'History' ?></button>
                        <button class="pd-tab" data-tab="celebrity" id="tabCelebrity" style="display:none;"><?= $is_fa ? 'مشهور' : 'Celebrity' ?></button>
                    </div>

                    <!-- محتوای تب‌ها -->
                    <div class="pd-tab-content active" id="tab-about">
                        <div class="profile-intro-card" style="border:none; background:transparent; padding:0;">
                            <p class="intro-text" id="d_shortDesc"></p>
                            <p class="intro-full" id="d_description"></p>
                        </div>
                        <div class="profile-footer-info mt-3" id="d_footerTags"></div>
                    </div>

                    <div class="pd-tab-content" id="tab-timeline">
                        <div class="bio-timeline" id="d_timeline"></div>
                    </div>

                    <div class="pd-tab-content" id="tab-celebrity">
                        <div id="d_celebrityCard"></div>
                    </div>

                </div>

            </div>

        </div>

        <!-- ===== نسخه موبایل ===== -->
        <div class="profile-mobile">

            <!-- هدر با تصویر -->
            <div class="profile-header-mobile">
                <img id="m_img" src="" alt="">
                <div class="profile-overlay">
                    <h1 id="m_title"></h1>
                    <div class="subtitle" id="m_author"></div>
                    <div class="date-badge" id="m_year">
                        <i class="far fa-calendar-alt me-1"></i>
                        <span id="m_yearVal"></span>
                    </div>
                </div>
            </div>

            <!-- کارت ناشر -->
            <div class="profile-job-card">
                <p id="m_publisher">
                    <i class="fas fa-building" style="color:#00796B; margin-left:8px;"></i>
                    <span></span>
                </p>
            </div>

            <!-- گرید آمار -->
            <div class="stats-grid-mobile" id="m_stats"></div>

            <!-- گرید اطلاعات -->
            <div class="profile-info-grid-mobile" id="m_infoGrid"></div>

            <!-- بخش خرید موبایل -->
            <div class="purchase-box-mobile" id="m_purchaseBox">
                <div class="purchase-title-mobile">
                    <i class="fas fa-shopping-cart me-1"></i>
                    <?= $is_fa ? 'خرید اثر' : 'Purchase' ?>
                </div>
                <div id="m_purchasedBanner" style="display:none;"></div>
                <div class="purchase-options-mobile" id="m_purchaseOptions"></div>
            </div>

            <!-- نقل‌قول -->
            <div class="profile-quote-mobile" id="m_quote" style="display:none;"></div>

            <!-- توضیحات -->
            <div class="bio-card-mobile">
                <div class="bio-title">
                    <i class="fas fa-scroll"></i>
                    <?= $is_fa ? 'درباره اثر' : 'About the Work' ?>
                </div>
                <p id="m_description"></p>
            </div>

            <!-- تگ‌ها -->
            <div class="profile-tags-mobile" id="m_tags"></div>

            <!-- مشهور مرتبط موبایل -->
            <div id="m_celebrity" style="display:none;"></div>

        </div>

    </div>

    <!-- خطا -->
    <div id="productError" style="display:none;" class="text-center py-5">
        <div class="no-results">
            <i class="fas fa-exclamation-circle"></i>
            <p id="productErrorMsg"></p>
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
    <a href="products.php" class="bottom-nav-item active-btn">
        <img src="<?= IMG_PATH ?>icon1-m.png" alt="Products" class="bottom-nav-icon">
        <span><?= $is_fa ? 'محصولات' : 'Products' ?></span>
    </a>
    <a href="#" class="bottom-nav-item">
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
const LANG     = '<?= $current_lang ?>';
const IMG_BASE = '/Kavian/mashahir/public/img/';

// ======== بارگذاری محصول ========

function loadProduct() {
    const params  = new URLSearchParams(window.location.search);
    const id      = params.get('id');
    const slug    = params.get('slug');

    if (!id && !slug) {
        showError(IS_FA ? 'شناسه محصول مشخص نشده است.' : 'No product identifier provided.');
        return;
    }

    let url = `/Kavian/mashahir/api/get-product.php?lang=${LANG}`;
    if (id)   url += `&id=${encodeURIComponent(id)}`;
    if (slug) url += `&slug=${encodeURIComponent(slug)}`;

    fetch(url)
        .then(r => r.json())
        .then(res => {
            if (!res.success || !res.data) {
                showError(IS_FA ? 'محصول یافت نشد.' : 'Product not found.');
                return;
            }
            renderProduct(res.data);
            checkPurchased(res.data.id);
        })
        .catch(() => showError(IS_FA ? 'خطا در بارگذاری محصول.' : 'Failed to load product.'));
}

// ======== رندر محصول ========

function renderProduct(p) {
    document.getElementById('productLoading').style.display = 'none';
    document.getElementById('productContent').style.display = 'block';

    // ذخیره ID محصول برای addToCart
    _currentProductId = p.id;

    // ---- دسکتاپ ----

    // تصویر
    const imgEl = document.getElementById('d_img');
    imgEl.src = p.image ? p.image.replace('../public/img/', IMG_BASE) : '';
    imgEl.alt = p.title;

    // badge ویژه
    if (p.isFeatured) document.getElementById('d_featuredBadge').style.display = 'block';

    // عناوین
    document.getElementById('d_title').textContent     = p.title;
    document.getElementById('d_fullTitle').textContent = p.fullTitle || '';

    // تصویر موبایل
    document.getElementById('m_img').src = imgEl.src;
    document.getElementById('m_img').alt = p.title;
    document.getElementById('m_title').textContent = p.title;
    if (p.author) document.getElementById('m_author').textContent = p.author;
    if (p.publishYear) document.getElementById('m_yearVal').textContent = p.publishYear;
    if (p.publisher) {
        document.querySelector('#m_publisher span').textContent = p.publisher;
    }

    // گرید اطلاعات دسکتاپ
    const infoItems = buildInfoItems(p);
    document.getElementById('d_infoGrid').innerHTML = infoItems.map(item => `
        <div class="col-6">
            <span class="info-label"><i class="${item.icon} me-1"></i>${item.label}</span>
            <br><span class="info-value">${item.value || '—'}</span>
        </div>`).join('');

    // تگ‌های دسکتاپ
    if (p.tags && p.tags.length) {
        document.getElementById('d_tags').innerHTML = p.tags
            .map(t => `<span class="badge"><i class="fas fa-tag"></i> ${t}</span>`).join(' ');
    }

    // نقل‌قول دسکتاپ
    if (p.quote) {
        const qEl = document.getElementById('d_quote');
        qEl.style.display = 'block';
        qEl.innerHTML = `<i class="fas fa-quote-right"></i> ${p.quote.replace(/\n/g, '<br><i class="fas fa-quote-left"></i> ')}`;
    }

    // معرفی
    document.getElementById('d_shortDesc').innerHTML   = `<strong>${p.fullTitle || p.title}</strong> · ${p.shortDesc || ''}`;
    document.getElementById('d_description').innerHTML = (p.description || '').replace(/\n/g, '<br>');

    // تگ‌های footer دسکتاپ
    const footerTags = [];
    if (p.author)      footerTags.push(`<i class="fas fa-pen-nib"></i> ${p.author}`);
    if (p.publisher)   footerTags.push(`<i class="fas fa-building"></i> ${p.publisher}`);
    if (p.publishYear) footerTags.push(`<i class="fas fa-calendar-alt"></i> ${p.publishYear}`);
    if (p.genre)       footerTags.push(`<i class="fas fa-feather"></i> ${p.genre}`);
    if (p.era)         footerTags.push(`<i class="fas fa-history"></i> ${p.era}`);
    if (p.pages)       footerTags.push(`<i class="fas fa-file-alt"></i> ${p.pages} ${IS_FA ? 'صفحه' : 'pp.'}`);
    document.getElementById('d_footerTags').innerHTML =
        footerTags.map(t => `<span class="info-tag">${t}</span>`).join('');

    // خط زمانی
    var timelineHtml = buildTimeline(p);
    document.getElementById('d_timeline').innerHTML = timelineHtml;
    // اگر خالی بود تب تاریخچه رو مخفی کن
    var tabTimeline = document.getElementById('tabTimeline');
    if (tabTimeline) tabTimeline.style.display = timelineHtml.trim() ? 'inline-flex' : 'none';

    // مشهور مرتبط
    if (p.celebrity) {
        const celCard = document.getElementById('d_celebrityCard');
        celCard.innerHTML = buildCelebrityCard(p.celebrity);
        // نمایش تب مشهور
        var tabCelebrity = document.getElementById('tabCelebrity');
        if (tabCelebrity) tabCelebrity.style.display = 'inline-flex';

        // موبایل
        const mCel = document.getElementById('m_celebrity');
        mCel.style.display = 'block';
        mCel.innerHTML = buildCelebrityCard(p.celebrity);
    }

    // ---- خرید ----
    renderPurchase(p, 'd');
    renderPurchaseMobile(p);

    // ---- گرید آمار موبایل ----
    document.getElementById('m_stats').innerHTML = `
        <div class="stat-item-mobile">
            <i class="fas fa-feather-alt stat-icon"></i>
            <div class="stat-label">${IS_FA ? 'ژانر' : 'Genre'}</div>
            <div class="stat-value">${p.genre || '—'}</div>
        </div>
        <div class="stat-item-mobile">
            <i class="fas fa-file-alt stat-icon"></i>
            <div class="stat-label">${IS_FA ? 'صفحات' : 'Pages'}</div>
            <div class="stat-value">${p.pages || '—'}</div>
        </div>
        <div class="stat-item-mobile">
            <i class="fas fa-globe stat-icon"></i>
            <div class="stat-label">${IS_FA ? 'زبان' : 'Language'}</div>
            <div class="stat-value">${p.language || '—'}</div>
        </div>
        <div class="stat-item-mobile">
            <i class="fas fa-clock stat-icon"></i>
            <div class="stat-label">${IS_FA ? 'دوره' : 'Era'}</div>
            <div class="stat-value">${p.era || '—'}</div>
        </div>`;

    // گرید اطلاعات موبایل
    document.getElementById('m_infoGrid').innerHTML = `
        <div class="info-box">
            <span class="label">${IS_FA ? 'نویسنده' : 'Author'}</span>
            <div class="value">${p.author || '—'}</div>
        </div>
        <div class="info-box">
            <span class="label">${IS_FA ? 'ناشر' : 'Publisher'}</span>
            <div class="value">${p.publisher || '—'}</div>
        </div>
        <div class="info-box">
            <span class="label">${IS_FA ? 'سال انتشار' : 'Published'}</span>
            <div class="value">${p.publishYear || '—'}</div>
        </div>
        <div class="info-box">
            <span class="label">${IS_FA ? 'زبان' : 'Language'}</span>
            <div class="value">${p.language || '—'}</div>
        </div>`;

    // نقل‌قول موبایل
    if (p.quote) {
        const mqEl = document.getElementById('m_quote');
        mqEl.style.display = 'block';
        mqEl.innerHTML = `<p><i class="fas fa-quote-right"></i> ${p.quote.replace(/\n/g, '<br>')}</p>`;
    }

    // توضیح موبایل
    document.getElementById('m_description').innerHTML = (p.description || '').replace(/\n/g, '<br>');

    // تگ‌ها موبایل
    if (p.tags && p.tags.length) {
        document.getElementById('m_tags').innerHTML = p.tags
            .map(t => `<span class="tag"><i class="fas fa-tag"></i> ${t}</span>`).join(' ');
    }
}

// ======== ساخت آیتم‌های اطلاعات ========

function buildInfoItems(p) {
    return [
        { icon: 'fas fa-pen-nib',   label: IS_FA ? 'نویسنده'      : 'Author',      value: p.author },
        { icon: 'fas fa-language',  label: IS_FA ? 'مترجم'         : 'Translator',  value: p.translator },
        { icon: 'fas fa-building',  label: IS_FA ? 'ناشر'          : 'Publisher',   value: p.publisher },
        { icon: 'fas fa-calendar',  label: IS_FA ? 'سال انتشار'    : 'Published',   value: p.publishYear },
        { icon: 'fas fa-file-alt',  label: IS_FA ? 'تعداد صفحات'   : 'Pages',       value: p.pages ? p.pages + (IS_FA ? ' صفحه' : ' pp.') : null },
        { icon: 'fas fa-globe',     label: IS_FA ? 'زبان'          : 'Language',    value: p.language },
        { icon: 'fas fa-feather',   label: IS_FA ? 'ژانر/نوع'      : 'Genre',       value: p.genre },
        { icon: 'fas fa-history',   label: IS_FA ? 'دوره تاریخی'   : 'Historical Period', value: p.era },
    ].filter(i => i.value);
}

// ======== ساخت خط زمانی ========

function buildTimeline(p) {
    const items = [];

    if (p.author) {
        items.push({
            icon: 'fas fa-pen-nib',
            title: IS_FA ? 'نوشته‌شده توسط' : 'Written by',
            text: p.author
        });
    }
    if (p.publishYear) {
        items.push({
            icon: 'fas fa-calendar-check',
            title: IS_FA ? 'سال انتشار' : 'Year of Publication',
            text: `${IS_FA ? 'این اثر در سال' : 'This work was published in'} ${p.publishYear} ${IS_FA ? 'منتشر شده است.' : '.'}`
        });
    }
    if (p.publisher) {
        items.push({
            icon: 'fas fa-building',
            title: IS_FA ? 'ناشر' : 'Publisher',
            text: `${IS_FA ? 'توسط' : 'Published by'} ${p.publisher} ${IS_FA ? 'چاپ و منتشر شده است.' : '.'}`
        });
    }
    if (p.era) {
        items.push({
            icon: 'fas fa-hourglass-half',
            title: IS_FA ? 'دوره تاریخی' : 'Historical Period',
            text: `${IS_FA ? 'این اثر متعلق به دوره' : 'This work belongs to the'} ${p.era} ${IS_FA ? 'است.' : 'period.'}`
        });
    }

    return items.map(item => `
        <div class="bio-item">
            <div class="bio-icon"><i class="${item.icon}"></i></div>
            <div class="bio-content">
                <h5>${item.title}</h5>
                <p>${item.text}</p>
            </div>
        </div>`).join('');
}

// ======== کارت مشهور مرتبط ========

function buildCelebrityCard(celebrity) {
    const img = celebrity.image ? celebrity.image.replace('../public/img/', IMG_BASE) : '';
    const profileUrl = IS_FA
        ? `/Kavian/mashahir/persian/profile.php?id=${celebrity.id}`
        : `/Kavian/mashahir/english/profile.php?id=${celebrity.id}`;
    return `
        <div class="celebrity-link-inner">
            <div class="celebrity-link-img">
                ${img ? `<img src="${img}" alt="${celebrity.name}">` : `<i class="fas fa-user-circle"></i>`}
            </div>
            <div class="celebrity-link-info">
                <div class="celebrity-link-label">
                    <i class="fas fa-link me-1"></i>
                    ${IS_FA ? 'مشهور مرتبط' : 'Related Celebrity'}
                </div>
                <div class="celebrity-link-name">${celebrity.name}</div>
            </div>
            <a href="${profileUrl}" class="btn-dark-custom">
                ${IS_FA ? 'مشاهده پروفایل' : 'View Profile'}
            </a>
        </div>`;
}

// ======== رندر قسمت خرید (دسکتاپ) ========

function renderPurchase(p, prefix) {
    const hasPrint = p.pricePrint || p.salePricePrint;
    const hasPdf   = p.pricePdf  || p.salePricePdf;

    if (!hasPrint && !hasPdf) {
        document.getElementById(`${prefix}_noPurchase`).style.display = 'block';
        return;
    }

    if (hasPrint) {
        document.getElementById(`${prefix}_printOption`).style.display = 'block';
        const finalPrint    = p.salePricePrint || p.pricePrint;
        const originalPrint = p.salePricePrint ? p.pricePrint : null;
        document.getElementById(`${prefix}_printFinal`).textContent = formatPrice(finalPrint);
        if (originalPrint) {
            const origEl = document.getElementById(`${prefix}_printOriginal`);
            origEl.style.display = 'inline';
            origEl.textContent = formatPrice(originalPrint);
        }

        // ── کشویی زبان چاپی ──
        const printVersions = p.printVersions || [];
        const printSel = document.getElementById(`${prefix}_printLangSelect`);
        if (printSel) {
            printSel.innerHTML = '';
            if (printVersions.length === 1) {
                printSel.appendChild(new Option(printVersions[0].label, printVersions[0].lang));
                printSel.disabled = true;
                printSel.value = printVersions[0].lang;
            } else if (printVersions.length > 1) {
                printSel.appendChild(new Option(IS_FA ? '— انتخاب کنید —' : '— Select —', ''));
                printVersions.forEach(function(v) { printSel.appendChild(new Option(v.label, v.lang)); });
                printSel.disabled = false;
            } else {
                const box = document.getElementById(`${prefix}_printLangBox`);
                if (box) box.style.display = 'none';
            }
        }
    }

    if (hasPdf) {
        document.getElementById(`${prefix}_pdfOption`).style.display = 'block';
        const finalPdf    = p.salePricePdf || p.pricePdf;
        const originalPdf = p.salePricePdf ? p.pricePdf : null;
        document.getElementById(`${prefix}_pdfFinal`).textContent = formatPrice(finalPdf);
        if (originalPdf) {
            const origEl = document.getElementById(`${prefix}_pdfOriginal`);
            origEl.style.display = 'inline';
            origEl.textContent = formatPrice(originalPdf);
        }

        // ── پر کردن کشویی زبان PDF (دسکتاپ) با DOM API تا encoding فارسی درست باشه ──
        const versions = p.pdfVersions || [];
        const selectEl = document.getElementById(`${prefix}_pdfLangSelect`);
        if (selectEl) {
            selectEl.innerHTML = '';
            if (versions.length === 1) {
                selectEl.appendChild(new Option(versions[0].label, versions[0].lang));
                selectEl.disabled = true;
                selectEl.value = versions[0].lang;
            } else if (versions.length > 1) {
                selectEl.appendChild(new Option(IS_FA ? '— انتخاب کنید —' : '— Select —', ''));
                versions.forEach(function(v) { selectEl.appendChild(new Option(v.label, v.lang)); });
                selectEl.disabled = false;
            } else {
                const box = document.getElementById(`${prefix}_pdfLangBox`);
                if (box) box.style.display = 'none';
            }
        }
    }
}

// ======== رندر قسمت خرید (موبایل) ========

function renderPurchaseMobile(p) {
    const hasPrint = p.pricePrint || p.salePricePrint;
    const hasPdf   = p.pricePdf  || p.salePricePdf;
    let html = '';

    if (!hasPrint && !hasPdf) {
        html = `<div class="purchase-unavailable">
            <i class="fas fa-info-circle me-1"></i>
            ${IS_FA ? 'قیمت‌گذاری در دسترس نیست' : 'Pricing unavailable'}
        </div>`;
    } else {
        if (hasPrint) {
            const finalPrint    = p.salePricePrint || p.pricePrint;
            const origPrint     = p.salePricePrint ? p.pricePrint : null;
            const printVersions = p.printVersions || [];

            const printSelHtml = printVersions.length > 0 ? `
                <div class="pdf-lang-selector">
                    <label class="pdf-lang-label">
                        <i class="fas fa-language me-1"></i>${IS_FA ? 'زبان نسخه:' : 'Edition:'}
                    </label>
                    <select id="m_printLangSelect" class="pdf-lang-select"></select>
                    <div class="pdf-lang-error" id="m_printLangError" style="display:none;">
                        ${IS_FA ? 'لطفاً زبان نسخه را انتخاب کنید.' : 'Please select an edition language.'}
                    </div>
                </div>` : '';

            html += `
            <div class="purchase-option-mobile">
                <div class="purchase-option-header">
                    <span class="option-icon"><i class="fas fa-print"></i></span>
                    <span class="option-label">${IS_FA ? 'نسخه چاپی' : 'Print Edition'}</span>
                </div>
                <div class="purchase-option-price">
                    ${origPrint ? `<span class="price-original">${formatPrice(origPrint)}</span>` : ''}
                    <span class="price-final">${formatPrice(finalPrint)}</span>
                    <span class="price-currency">${IS_FA ? 'تومان' : 'T'}</span>
                </div>
                ${printSelHtml}
                <button class="btn-buy btn-buy-print w-100" onclick="addToCart('print')">
                    <i class="fas fa-cart-plus me-1"></i>
                    ${IS_FA ? 'افزودن به سبد' : 'Add to Cart'}
                </button>
            </div>`;
        }
        if (hasPdf) {
            const finalPdf = p.salePricePdf || p.pricePdf;
            const origPdf  = p.salePricePdf ? p.pricePdf : null;
            const versions = p.pdfVersions || [];

            // placeholder کشویی — بعداً با DOM API پر می‌شه
            const hasSel = versions.length > 0;
            const selHtml = hasSel ? `
                <div class="pdf-lang-selector">
                    <label class="pdf-lang-label">
                        <i class="fas fa-language me-1"></i>${IS_FA ? 'زبان نسخه:' : 'Edition:'}
                    </label>
                    <select id="m_pdfLangSelect" class="pdf-lang-select"></select>
                    <div class="pdf-lang-error" id="m_pdfLangError" style="display:none;">
                        ${IS_FA ? 'لطفاً زبان نسخه را انتخاب کنید.' : 'Please select an edition language.'}
                    </div>
                </div>` : '';

            html += `
            <div class="purchase-option-mobile">
                <div class="purchase-option-header">
                    <span class="option-icon"><i class="fas fa-file-pdf"></i></span>
                    <span class="option-label">${IS_FA ? 'نسخه PDF' : 'PDF Edition'}</span>
                </div>
                <div class="purchase-option-price">
                    ${origPdf ? `<span class="price-original">${formatPrice(origPdf)}</span>` : ''}
                    <span class="price-final">${formatPrice(finalPdf)}</span>
                    <span class="price-currency">${IS_FA ? 'تومان' : 'T'}</span>
                </div>
                ${selHtml}
                <button class="btn-buy btn-buy-pdf w-100" onclick="addToCart('pdf')">
                    <i class="fas fa-download me-1"></i>
                    ${IS_FA ? 'دریافت PDF' : 'Get PDF'}
                </button>
            </div>`;
        }
    }

    document.getElementById('m_purchaseOptions').innerHTML = html;

    // ── پر کردن کشویی‌های موبایل با DOM API ──
    function fillSelect(selId, versions, isMulti) {
        var sel = document.getElementById(selId);
        if (!sel || !versions || versions.length === 0) return;
        sel.innerHTML = '';
        if (versions.length === 1) {
            sel.appendChild(new Option(versions[0].label, versions[0].lang));
            sel.disabled = true;
            sel.value = versions[0].lang;
        } else {
            sel.appendChild(new Option(IS_FA ? '— انتخاب کنید —' : '— Select —', ''));
            versions.forEach(function(v) { sel.appendChild(new Option(v.label, v.lang)); });
            sel.disabled = false;
        }
    }

    if (hasPrint) fillSelect('m_printLangSelect', p.printVersions || []);
    if (hasPdf)   fillSelect('m_pdfLangSelect',   p.pdfVersions   || []);
}

// ======== افزودن به سبد ========

let _currentProductId = null;

function addToCart(type) {
    if (!_currentProductId) {
        showToast(IS_FA ? 'محصول هنوز بارگذاری نشده است.' : 'Product not loaded yet.');
        return;
    }

    // ── خواندن زبان انتخاب‌شده (برای pdf و print) ──
    let pdfLang = null;

    function getLangFromSelect(dId, mId, dErrId, mErrId) {
        const dSel = document.getElementById(dId);
        const mSel = document.getElementById(mId);
        const sel  = (dSel && dSel.closest('.purchase-option') &&
                      getComputedStyle(dSel.closest('.purchase-option')).display !== 'none')
                     ? dSel : mSel;
        if (!sel) return null;

        const errD = document.getElementById(dErrId);
        const errM = document.getElementById(mErrId);
        if (errD) errD.style.display = 'none';
        if (errM) errM.style.display = 'none';

        if (sel.disabled) return sel.options[0]?.value || null;

        const val = sel.value;
        if (!val) {
            const errEl = (sel === dSel) ? errD : errM;
            if (errEl) errEl.style.display = 'block';
            sel.focus();
            return undefined; // undefined = validation failed
        }
        return val;
    }

    if (type === 'pdf') {
        const lang = getLangFromSelect('d_pdfLangSelect','m_pdfLangSelect','d_pdfLangError','m_pdfLangError');
        if (lang === undefined) return;
        pdfLang = lang;
    } else if (type === 'print') {
        const lang = getLangFromSelect('d_printLangSelect','m_printLangSelect','d_printLangError','m_printLangError');
        if (lang === undefined) return;
        pdfLang = lang;
    }

    // غیرفعال موقت دکمه‌ها
    document.querySelectorAll('.btn-buy').forEach(function(b) {
        b.disabled = true;
    });

    fetch('/Kavian/mashahir/api/cart-api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action:       'add',
            product_id:   _currentProductId,
            product_type: type,
            pdf_lang:     pdfLang,
        })
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        document.querySelectorAll('.btn-buy').forEach(function(b) {
            b.disabled = false;
        });

        if (data.success) {
            // به‌روزرسانی badge
            document.querySelectorAll('.cart-badge').forEach(function(el) {
                el.textContent = data.count;
                el.style.display = 'flex';
            });

            const msg = IS_FA
                ? (type === 'pdf' ? 'نسخه PDF به سبد افزوده شد.' : 'نسخه چاپی به سبد افزوده شد.')
                : (type === 'pdf' ? 'PDF edition added to cart.' : 'Print edition added to cart.');
            showToast(msg, 'success');
        } else {
            if (data.already_purchased) {
                // دکمه همین نسخه را disable کن
                var prefix = document.getElementById('d_pdfOption') &&
                             getComputedStyle(document.getElementById('d_pdfOption')).display !== 'none'
                             ? 'd' : 'm';
                if (type === 'pdf') disablePdfBuyBtn(prefix);
                showToast(data.message, 'error');
            } else {
                showToast(data.message || (IS_FA ? 'خطا در افزودن به سبد.' : 'Error adding to cart.'), 'error');
            }
        }
    })
    .catch(function() {
        document.querySelectorAll('.btn-buy').forEach(function(b) {
            b.disabled = false;
        });
        showToast(IS_FA ? 'خطا در اتصال به سرور.' : 'Server connection error.', 'error');
    });
}

// ======== بررسی خرید قبلی ========

// ── زبان‌های PDF که کاربر خریداری کرده (برای block کردن خرید تکراری) ──
var _purchasedPdfLangs = [];

function checkPurchased(productId) {
    fetch('/Kavian/mashahir/api/check-purchased.php?product_id=' + productId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            console.log('[checkPurchased] response:', JSON.stringify(data));
            if (!data.purchased || !data.items || data.items.length === 0) return;

            var dashboardUrl = '/Kavian/dashboard/index.php';

            // ذخیره زبان‌های خریداری‌شده برای آیتم‌های PDF
            _purchasedPdfLangs = data.items
                .filter(function(i) { return i.product_type === 'pdf'; })
                .map(function(i) { return i.pdf_lang || '__any__'; });

            // ساخت HTML بنر برای هر نسخه خریداری‌شده
            var bannerHtml = data.items.map(function(item) {
                var isPdf = item.product_type === 'pdf';

                // برچسب نوع و زبان
                var langLabel = '';
                if (isPdf) {
                    langLabel = IS_FA
                        ? (item.pdf_label_fa || item.pdf_lang || 'PDF')
                        : (item.pdf_label_en || item.pdf_lang || 'PDF');
                } else {
                    langLabel = IS_FA ? 'نسخه چاپی' : 'Print Edition';
                }

                var msg = IS_FA
                    ? 'شما <strong>' + escHtml(langLabel) + '</strong> این محصول را خریداری کرده‌اید.'
                    : 'You have purchased the <strong>' + escHtml(langLabel) + '</strong> of this product.';

                // دکمه دانلود: فقط برای PDF و وقتی file_path یا order_item_id داریم
                var dlLink = '';
                if (isPdf) {
                    if (item.order_item_id) {
                        // اگه lang مشخصه (از editions) آن رو به URL اضافه کن
                        var dlUrl = '/Kavian/mashahir/api/download-pdf.php?order_item_id=' + item.order_item_id;
                        if (item.pdf_lang) dlUrl += '&lang=' + encodeURIComponent(item.pdf_lang);
                        dlLink = '<a href="' + dlUrl + '" class="purchased-banner-link" download>' +
                            '<i class="fas fa-download me-1"></i>' +
                            (IS_FA ? 'دانلود PDF' : 'Download PDF') +
                          '</a>';
                    } else {
                        dlLink = '<a href="' + dashboardUrl + '" class="purchased-banner-link">' +
                            '<i class="fas fa-shopping-bag me-1"></i>' +
                            (IS_FA ? 'مشاهده سفارشات' : 'View Orders') +
                          '</a>';
                    }
                } else {
                    // چاپی — فقط لینک داشبورد
                    dlLink = '<a href="' + dashboardUrl + '" class="purchased-banner-link">' +
                        '<i class="fas fa-shopping-bag me-1"></i>' +
                        (IS_FA ? 'مشاهده سفارش' : 'View Order') +
                      '</a>';
                }

                return '<div class="purchased-banner">' +
                    '<div class="purchased-banner-icon"><i class="fas fa-check-circle"></i></div>' +
                    '<div class="purchased-banner-text">' +
                        '<p>' + msg + '</p>' +
                        dlLink +
                    '</div>' +
                '</div>';
            }).join('');

            // نمایش در دسکتاپ
            var dBanner = document.getElementById('d_purchasedBanner');
            if (dBanner) { dBanner.innerHTML = bannerHtml; dBanner.style.display = 'block'; }

            // نمایش در موبایل
            var mBanner = document.getElementById('m_purchasedBanner');
            if (mBanner) { mBanner.innerHTML = bannerHtml; mBanner.style.display = 'block'; }

            // غیرفعال کردن دکمه خرید PDF برای نسخه‌های خریداری‌شده
            var hasPdfPurchased = data.items.some(function(i) { return i.product_type === 'pdf'; });
            if (hasPdfPurchased) {
                ['d_pdfLangSelect', 'm_pdfLangSelect'].forEach(function(selId) {
                    var sel = document.getElementById(selId);
                    if (!sel) return;
                    var prefix = selId === 'd_pdfLangSelect' ? 'd' : 'm';
                    // اگر تک زبان → فوری disable
                    if (sel.disabled) {
                        var val = sel.value || '__any__';
                        if (_purchasedPdfLangs.indexOf(val) !== -1 || _purchasedPdfLangs.indexOf('__any__') !== -1) {
                            disablePdfBuyBtn(prefix);
                        }
                    }
                    // برای چند زبان: وقتی کشویی تغییر کرد چک کن
                    sel.addEventListener('change', function() {
                        checkPdfLangPurchased(prefix, this.value);
                    });
                });
            }
        })
        .catch(function() {});
}

function checkPdfLangPurchased(prefix, lang) {
    var btn = document.querySelector('.btn-buy-pdf');
    var mobBtn = document.querySelector('.purchase-option-mobile .btn-buy-pdf');
    if (!lang) return;
    var alreadyBought = _purchasedPdfLangs.indexOf(lang) !== -1;
    if (alreadyBought) {
        disablePdfBuyBtn(prefix);
    } else {
        enablePdfBuyBtn(prefix);
    }
}

function disablePdfBuyBtn(prefix) {
    var selector = prefix === 'd' ? '#d_pdfOption .btn-buy-pdf' : '.purchase-option-mobile .btn-buy-pdf';
    var btn = document.querySelector(selector);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>' +
            (IS_FA ? 'خریداری شده' : 'Already Purchased');
        btn.style.background = '#81c784';
        btn.style.cursor = 'not-allowed';
    }
}

function enablePdfBuyBtn(prefix) {
    var selector = prefix === 'd' ? '#d_pdfOption .btn-buy-pdf' : '.purchase-option-mobile .btn-buy-pdf';
    var btn = document.querySelector(selector);
    if (btn) {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-download me-1"></i>' +
            (IS_FA ? 'دریافت PDF' : 'Get PDF');
        btn.style.background = '';
        btn.style.cursor = '';
    }
}

// ======== ابزارها ========

function escHtml(str) {
    var d = document.createElement('div');
    d.appendChild(document.createTextNode(str || ''));
    return d.innerHTML;
}

function formatPrice(val) {
    if (!val) return '—';
    return Number(val).toLocaleString('fa-IR');
}

function showError(msg) {
    document.getElementById('productLoading').style.display = 'none';
    document.getElementById('productError').style.display   = 'block';
    document.getElementById('productErrorMsg').textContent  = msg;
}

function showToast(msg, type) {
    let toast = document.getElementById('productToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'productToast';
        toast.className = 'product-toast';
        document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.className = 'product-toast show';
    if (type === 'error') {
        toast.style.background = '#c0392b';
    } else {
        toast.style.background = '#00796B';
    }
    clearTimeout(toast._timer);
    toast._timer = setTimeout(function() {
        toast.classList.remove('show');
    }, 3000);
}

// ======== تب‌های product detail ========

document.addEventListener('DOMContentLoaded', function() {
    document.addEventListener('click', function(e) {
        var tab = e.target.closest('.pd-tab');
        if (!tab) return;
        var key = tab.dataset.tab;
        document.querySelectorAll('.pd-tab').forEach(function(t) { t.classList.remove('active'); });
        document.querySelectorAll('.pd-tab-content').forEach(function(c) { c.classList.remove('active'); });
        tab.classList.add('active');
        var content = document.getElementById('tab-' + key);
        if (content) content.classList.add('active');
    });
});

// ======== اجرا ========
document.addEventListener('DOMContentLoaded', loadProduct);
</script>

<!-- ===== بخش نظرات محصول ===== -->
<div class="container" id="productReviewsSection" style="display:none;">
    <div class="product-reviews-section">

        <div class="product-reviews-header">
            <i class="fas fa-comments" style="color:#00796B; font-size:1.4rem;"></i>
            <h3><?= $is_fa ? 'نظرات کاربران' : 'User Reviews' ?></h3>
            <span class="reviews-count-badge" id="reviewsCountBadge">۰</span>
        </div>

        <!-- لیست نظرات -->
        <div id="productReviewsList">
            <div class="text-center py-4">
                <div class="spinner-border text-success spinner-border-sm" role="status"></div>
            </div>
        </div>

        <!-- فرم ثبت نظر -->
        <div class="product-review-form-box">
            <div class="product-review-form-title">
                <i class="fas fa-pen"></i>
                <?= $is_fa ? 'ثبت نظر شما' : 'Write a Review' ?>
            </div>

            <div class="review-form-group">
                <label><?= $is_fa ? 'نام شما' : 'Your Name' ?></label>
                <input type="text" id="reviewAuthorName" placeholder="<?= $is_fa ? 'مثلاً: علی محمدی' : 'e.g. John Smith' ?>">
            </div>

            <div class="review-form-group">
                <label><?= $is_fa ? 'امتیاز' : 'Rating' ?></label>
                <div class="review-stars-input" id="reviewStarsInput">
                    <input type="radio" name="reviewRating" id="star5" value="5">
                    <label for="star5" title="5">&#9733;</label>
                    <input type="radio" name="reviewRating" id="star4" value="4">
                    <label for="star4" title="4">&#9733;</label>
                    <input type="radio" name="reviewRating" id="star3" value="3">
                    <label for="star3" title="3">&#9733;</label>
                    <input type="radio" name="reviewRating" id="star2" value="2">
                    <label for="star2" title="2">&#9733;</label>
                    <input type="radio" name="reviewRating" id="star1" value="1">
                    <label for="star1" title="1">&#9733;</label>
                </div>
            </div>

            <div class="review-form-group">
                <label><?= $is_fa ? 'متن نظر' : 'Review Text' ?></label>
                <textarea id="reviewContent" rows="4"
                    placeholder="<?= $is_fa ? 'نظر خود را درباره این محصول بنویسید...' : 'Write your thoughts about this product...' ?>"></textarea>
            </div>

            <button class="btn-submit-review" onclick="submitReview()">
                <i class="fas fa-paper-plane"></i>
                <?= $is_fa ? 'ثبت نظر' : 'Submit Review' ?>
            </button>
            <div id="reviewFormMsg" class="review-form-msg" style="display:none;"></div>
        </div>

    </div>
</div>

<!-- مودال "در دست توسعه" -->
<div class="wip-modal-overlay" id="wipModalOverlay" onclick="closeWipModal(event)">
    <div class="wip-modal">
        <span class="wip-modal-icon">🚧</span>
        <h4><?= $is_fa ? 'در دست توسعه' : 'Coming Soon' ?></h4>
        <p><?= $is_fa
            ? 'امکان ثبت نظر در حال توسعه است و به زودی در دسترس قرار می‌گیرد.'
            : 'The review submission feature is under development and will be available soon.' ?>
        </p>
        <button class="btn-wip-close" onclick="closeWipModal()">
            <?= $is_fa ? 'متوجه شدم' : 'Got it' ?>
        </button>
    </div>
</div>

<script>
// ===== نظرات محصول =====

var _reviewProductId = null;
var _reviewColors = ['#00796B','#8B1538','#1E4FA1','#FF5722','#4CAF50','#9C27B0'];

function loadProductReviews(productId) {
    _reviewProductId = productId;
    document.getElementById('productReviewsSection').style.display = 'block';

    fetch('/Kavian/mashahir/api/get-product-reviews.php?product_id=' + productId + '&lang=<?= $current_lang ?>')
        .then(function(r) { return r.json(); })
        .then(function(res) {
            renderReviews(res.data || [], res.count || 0);
        })
        .catch(function() {
            document.getElementById('productReviewsList').innerHTML =
                '<div class="product-reviews-empty"><i class="fas fa-exclamation-circle"></i>' +
                (IS_FA ? 'خطا در بارگذاری نظرات.' : 'Failed to load reviews.') + '</div>';
        });
}

function renderReviews(reviews, count) {
    // به‌روزرسانی badge تعداد
    var badge = document.getElementById('reviewsCountBadge');
    if (badge) badge.textContent = IS_FA ? toPersianNum(count) : count;

    var container = document.getElementById('productReviewsList');
    if (!reviews || reviews.length === 0) {
        container.innerHTML =
            '<div class="product-reviews-empty">' +
            '<i class="fas fa-comment-slash"></i>' +
            (IS_FA ? 'هنوز نظری ثبت نشده است.' : 'No reviews yet. Be the first!') +
            '</div>';
        return;
    }

    container.innerHTML = reviews.map(function(r, i) {
        var hashtags = [];
        try { hashtags = JSON.parse(r.hashtags || '[]'); } catch(e) {}
        var initial = r.author_name ? r.author_name.charAt(0) : '؟';
        var color   = _reviewColors[i % _reviewColors.length];
        var content = IS_FA ? (r.content_fa || r.content_en || '') : (r.content_en || r.content_fa || '');
        var date    = r.created_at ? r.created_at.split(' ')[0] : '';

        var starsHtml = '';
        for (var s = 1; s <= 5; s++) {
            starsHtml += '<i class="' + (s <= r.rating ? 'fas' : 'far') + ' fa-star"></i>';
        }

        var tagsHtml = hashtags.map(function(t) {
            return '<span>' + escHtml('#' + t) + '</span>';
        }).join('');

        return '<div class="product-review-card">' +
            '<div class="product-review-top">' +
                '<div class="product-review-user">' +
                    '<div class="product-review-avatar" style="background:' + color + '">' + escHtml(initial) + '</div>' +
                    '<div>' +
                        '<h5>' + escHtml(r.author_name || '') + '</h5>' +
                        '<span>' + date + '</span>' +
                    '</div>' +
                '</div>' +
                '<div class="product-review-stars">' + starsHtml + '</div>' +
            '</div>' +
            '<p class="product-review-text">"' + escHtml(content) + '"</p>' +
            '<div class="product-review-footer">' +
                '<div class="product-review-hashtags">' + tagsHtml + '</div>' +
                '<div class="product-review-meta">' +
                    '<div class="product-review-likes"><i class="fas fa-heart"></i> ' + (r.likes_count || 0) + '</div>' +
                '</div>' +
            '</div>' +
        '</div>';
    }).join('');
}

function submitReview() {
    var name    = (document.getElementById('reviewAuthorName').value || '').trim();
    var content = (document.getElementById('reviewContent').value || '').trim();
    var ratingEl = document.querySelector('input[name="reviewRating"]:checked');
    var rating  = ratingEl ? ratingEl.value : '5';

    // اعتبارسنجی
    if (!name) {
        showReviewMsg(IS_FA ? 'لطفاً نام خود را وارد کنید.' : 'Please enter your name.', 'error');
        document.getElementById('reviewAuthorName').focus();
        return;
    }
    if (!content) {
        showReviewMsg(IS_FA ? 'لطفاً متن نظر را بنویسید.' : 'Please write your review.', 'error');
        document.getElementById('reviewContent').focus();
        return;
    }
    if (!_reviewProductId) {
        showReviewMsg(IS_FA ? 'خطا: شناسه محصول نامعتبر است.' : 'Error: invalid product ID.', 'error');
        return;
    }

    var btn = document.querySelector('.btn-submit-review');
    if (btn) { btn.disabled = true; }

    var fd = new FormData();
    fd.append('product_id',  _reviewProductId);
    fd.append('author_name', name);
    fd.append('rating',      rating);
    fd.append('content',     content);
    fd.append('lang',        IS_FA ? 'fa' : 'en');

    fetch('/Kavian/mashahir/api/submit-review.php', { method: 'POST', body: fd })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (btn) { btn.disabled = false; }
            if (data.success) {
                showReviewMsg(data.message, 'success');
                document.getElementById('reviewAuthorName').value = '';
                document.getElementById('reviewContent').value    = '';
                var checked = document.querySelector('input[name="reviewRating"]:checked');
                if (checked) checked.checked = false;
            } else {
                showReviewMsg(data.error || (IS_FA ? 'خطا در ارسال نظر.' : 'Error submitting review.'), 'error');
            }
        })
        .catch(function() {
            if (btn) { btn.disabled = false; }
            showReviewMsg(IS_FA ? 'خطا در اتصال به سرور.' : 'Server connection error.', 'error');
        });
}

function showReviewMsg(msg, type) {
    var el = document.getElementById('reviewFormMsg');
    if (!el) return;
    el.textContent = msg;
    el.className = 'review-form-msg review-form-msg--' + type;
    el.style.display = 'block';
    setTimeout(function() { el.style.display = 'none'; }, 6000);
}

function showWipModal() {
    document.getElementById('wipModalOverlay').classList.add('show');
}

function closeWipModal(e) {
    if (!e || e.target === document.getElementById('wipModalOverlay') || e.currentTarget === null) {
        document.getElementById('wipModalOverlay').classList.remove('show');
    }
}

function toPersianNum(n) {
    return String(n).replace(/\d/g, function(d) { return '۰۱۲۳۴۵۶۷۸۹'[d]; });
}

// بارگذاری نظرات بعد از لود محصول
var _origRenderProduct = renderProduct;
renderProduct = function(p) {
    _origRenderProduct(p);
    loadProductReviews(p.id);
};
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
