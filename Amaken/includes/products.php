<?php
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$is_fa = ($current_lang === 'fa');

global $pdo;

$productSql = "SELECT 
    p.id,
    p.slug,
    p.title_fa,
    p.title_en,
    p.image,
    p.short_desc_fa,
    p.short_desc_en,
    p.author_fa,
    p.author_en,
    p.price_print,
    p.sale_price_print,
    p.price_pdf,
    p.sale_price_pdf,
    p.is_featured,
    mc.name_fa as celebrity_name_fa,
    mc.name_en as celebrity_name_en
FROM mashahir_products p
LEFT JOIN mashahir_celebrities mc ON p.celebrity_id = mc.id
WHERE p.is_active = 1 
ORDER BY RAND()
LIMIT 6";

$productStmt = $pdo->query($productSql);
$products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

$title = $is_fa ? 'محصولات مشاهیر ایران' : 'Iranian Celebrities Products';
$desc  = $is_fa ? 'مجموعه‌ای از بهترین کتاب‌ها و محصولات فرهنگی' : 'A collection of the best books and cultural products';

function getBadge($index) {
    $badges = ['Bestseller', 'Popular', 'New', 'Special'];
    return $badges[$index % count($badges)];
}

function getDisplayPrice($product) {
    $prices = [];
    if ($product['sale_price_print']) $prices[] = (float)$product['sale_price_print'];
    elseif ($product['price_print'])  $prices[] = (float)$product['price_print'];
    if ($product['sale_price_pdf'])   $prices[] = (float)$product['sale_price_pdf'];
    elseif ($product['price_pdf'])    $prices[] = (float)$product['price_pdf'];
    return $prices ? min($prices) : null;
}
function getOriginalPrice($product) {
    if ($product['sale_price_print'] && $product['price_print']) return (float)$product['price_print'];
    if ($product['sale_price_pdf']   && $product['price_pdf'])   return (float)$product['price_pdf'];
    return null;
}

$chunks = array_chunk($products, 3);
$totalProducts = count($products);
$totalChunks   = count($chunks);
?>

<section class="products-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2><?= $title ?></h2>
            <p><?= $desc ?></p>
            <span class="header-line"></span>
        </div>

        <!-- ============ دسکتاپ: هر slide سه کارت ============ -->
        <div id="productCarouselDesktop" class="carousel slide d-none d-md-block" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <?php foreach ($chunks as $index => $chunk): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="row g-4">
                        <?php foreach ($chunk as $pIndex => $product):
                            $displayPrice  = getDisplayPrice($product);
                            $originalPrice = getOriginalPrice($product);
                            $hasDiscount   = $originalPrice !== null;
                            $productUrl    = ($is_fa ? '/Kavian/mashahir/persian/' : '/Kavian/mashahir/english/')
                                             . 'product.php?slug=' . urlencode($product['slug']);
                        ?>
                        <div class="col-md-4">
                            <div class="product-card">
                                <a href="<?= $productUrl ?>" class="product-image-link">
                                    <div class="product-image">
                                        <img src="<?= IMG_PATH . $product['image'] ?>"
                                             alt="<?= htmlspecialchars($is_fa ? $product['title_fa'] : $product['title_en']) ?>">
                                        <?php if ($product['is_featured']): ?>
                                            <span class="product-badge product-badge-featured">
                                                <i class="fas fa-star"></i> <?= $is_fa ? 'ویژه' : 'Featured' ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="product-badge"><?= getBadge($pIndex) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </a>
                                <div class="product-body">
                                    <h4>
                                        <a href="<?= $productUrl ?>" class="product-title-link">
                                            <?= htmlspecialchars($is_fa ? $product['title_fa'] : $product['title_en']) ?>
                                        </a>
                                    </h4>
                                    <p class="product-author">
                                        <?php
                                        $author = $is_fa
                                            ? ($product['author_fa'] ?: $product['celebrity_name_fa'])
                                            : ($product['author_en'] ?: $product['celebrity_name_en']);
                                        echo htmlspecialchars($author ?: '');
                                        ?>
                                    </p>
                                    <div class="product-price">
                                        <?php if ($hasDiscount && $originalPrice): ?>
                                            <span class="price-old"><?= number_format($originalPrice) ?></span>
                                        <?php endif; ?>
                                        <?php if ($displayPrice !== null): ?>
                                            <span class="price-new"><?= number_format($displayPrice) ?></span>
                                            <span class="price-unit"><?= $is_fa ? 'تومان' : 'T' ?></span>
                                        <?php else: ?>
                                            <span class="price-free"><?= $is_fa ? 'رایگان' : 'Free' ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= $productUrl ?>" class="btn-add-cart">
                                        <i class="fas fa-eye me-1"></i> <?= $is_fa ? 'مشاهده' : 'View' ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#productCarouselDesktop" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarouselDesktop" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>

            <div class="carousel-indicators">
                <?php for ($i = 0; $i < $totalChunks; $i++): ?>
                <button type="button" data-bs-target="#productCarouselDesktop" data-bs-slide-to="<?= $i ?>"
                        class="<?= $i === 0 ? 'active' : '' ?>"></button>
                <?php endfor; ?>
            </div>
        </div>

        <!-- ============ موبایل: هر slide یک کارت ============ -->
        <div id="productCarouselMobile" class="carousel slide d-md-none" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <?php foreach ($products as $pIndex => $product):
                    $displayPrice  = getDisplayPrice($product);
                    $originalPrice = getOriginalPrice($product);
                    $hasDiscount   = $originalPrice !== null;
                    $productUrl    = ($is_fa ? '/Kavian/mashahir/persian/' : '/Kavian/mashahir/english/')
                                     . 'product.php?slug=' . urlencode($product['slug']);
                ?>
                <div class="carousel-item <?= $pIndex === 0 ? 'active' : '' ?>">
                    <div class="product-card">
                        <a href="<?= $productUrl ?>" class="product-image-link">
                            <div class="product-image">
                                <img src="<?= IMG_PATH . $product['image'] ?>"
                                     alt="<?= htmlspecialchars($is_fa ? $product['title_fa'] : $product['title_en']) ?>">
                                <?php if ($product['is_featured']): ?>
                                    <span class="product-badge product-badge-featured">
                                        <i class="fas fa-star"></i> <?= $is_fa ? 'ویژه' : 'Featured' ?>
                                    </span>
                                <?php else: ?>
                                    <span class="product-badge"><?= getBadge($pIndex) ?></span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="product-body">
                            <h4>
                                <a href="<?= $productUrl ?>" class="product-title-link">
                                    <?= htmlspecialchars($is_fa ? $product['title_fa'] : $product['title_en']) ?>
                                </a>
                            </h4>
                            <p class="product-author">
                                <?php
                                $author = $is_fa
                                    ? ($product['author_fa'] ?: $product['celebrity_name_fa'])
                                    : ($product['author_en'] ?: $product['celebrity_name_en']);
                                echo htmlspecialchars($author ?: '');
                                ?>
                            </p>
                            <div class="product-price">
                                <?php if ($hasDiscount && $originalPrice): ?>
                                    <span class="price-old"><?= number_format($originalPrice) ?></span>
                                <?php endif; ?>
                                <?php if ($displayPrice !== null): ?>
                                    <span class="price-new"><?= number_format($displayPrice) ?></span>
                                    <span class="price-unit"><?= $is_fa ? 'تومان' : 'T' ?></span>
                                <?php else: ?>
                                    <span class="price-free"><?= $is_fa ? 'رایگان' : 'Free' ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?= $productUrl ?>" class="btn-add-cart">
                                <i class="fas fa-eye me-1"></i> <?= $is_fa ? 'مشاهده' : 'View' ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($is_fa): ?>
            <!-- RTL: swap prev/next تا جهت حرکت با دکمه match کنه -->
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarouselMobile" data-bs-slide="next">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarouselMobile" data-bs-slide="prev">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
            <!-- custom indicators — نه Bootstrap، تا RTL درست کار کنه -->
            <div class="custom-indicators" id="productMobileIndicators">
                <?php for ($i = 0; $i < $totalProducts; $i++): ?>
                <button type="button" class="<?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>"></button>
                <?php endfor; ?>
            </div>
            <?php else: ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#productCarouselMobile" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarouselMobile" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
            <div class="carousel-indicators">
                <?php for ($i = 0; $i < $totalProducts; $i++): ?>
                <button type="button" data-bs-target="#productCarouselMobile" data-bs-slide-to="<?= $i ?>"
                        class="<?= $i === 0 ? 'active' : '' ?>"></button>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>
