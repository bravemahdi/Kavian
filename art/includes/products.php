<?php

$title = $is_fa ? 'محصولات مشاهیر ایران' : 'Iranian Celebrities Products';
$desc = $is_fa ? 'مجموعه‌ای از بهترین کتاب‌ها و محصولات فرهنگی' : 'A collection of the best books and cultural products';

$all_products = $is_fa ? [
    ['img' => 'p1.png', 'title' => 'شاهنامه فردوسی', 'author' => 'فردوسی', 'old' => '۲۵۰,۰۰۰', 'new' => '۱۸۰,۰۰۰', 'badge' => 'پرفروش'],
    ['img' => 'p3.png', 'title' => 'دیوان حافظ', 'author' => 'حافظ', 'old' => '۱۹۰,۰۰۰', 'new' => '۱۴۰,۰۰۰', 'badge' => 'محبوب'],
    ['img' => 'prof2.png', 'title' => 'مثنوی معنوی', 'author' => 'مولانا', 'old' => '۳۲۰,۰۰۰', 'new' => '۲۵۰,۰۰۰', 'badge' => 'جدید'],
    ['img' => 'd2.png', 'title' => 'قانون ابن‌سینا', 'author' => 'ابن‌سینا', 'old' => '۴۵۰,۰۰۰', 'new' => '۳۵۰,۰۰۰', 'badge' => 'ویژه'],
    ['img' => 'p2.png', 'title' => 'رباعیات خیام', 'author' => 'خیام', 'old' => '۱۲۰,۰۰۰', 'new' => '۸۵,۰۰۰', 'badge' => 'پرفروش'],
    ['img' => 'prof16.png', 'title' => 'بوستان سعدی', 'author' => 'سعدی', 'old' => '۱۶۰,۰۰۰', 'new' => '۱۱۰,۰۰۰', 'badge' => 'محبوب']
] : [
    ['img' => 'p1.png', 'title' => 'Shahnameh', 'author' => 'Ferdowsi', 'old' => '250,000', 'new' => '180,000', 'badge' => 'Bestseller'],
    ['img' => 'p3.png', 'title' => 'Divan-e-Hafez', 'author' => 'Hafez', 'old' => '190,000', 'new' => '140,000', 'badge' => 'Popular'],
    ['img' => 'prof2.png', 'title' => 'Masnavi-e-Ma\'navi', 'author' => 'Rumi', 'old' => '320,000', 'new' => '250,000', 'badge' => 'New'],
    ['img' => 'd2.png', 'title' => 'Canon of Medicine', 'author' => 'Avicenna', 'old' => '450,000', 'new' => '350,000', 'badge' => 'Special'],
    ['img' => 'p2.png', 'title' => 'Rubaiyat of Khayyam', 'author' => 'Khayyam', 'old' => '120,000', 'new' => '85,000', 'badge' => 'Bestseller'],
    ['img' => 'prof16.png', 'title' => 'Bustan', 'author' => 'Saadi', 'old' => '160,000', 'new' => '110,000', 'badge' => 'Popular']
];

$chunks = array_chunk($all_products, 3);
$btn_label = $is_fa ? 'افزودن' : 'Add to Cart';
?>
<section class="products-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2><?= $title ?></h2>
            <p><?= $desc ?></p>
            <span class="header-line"></span>
        </div>

        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <?php foreach ($chunks as $index => $chunk): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row g-4">
                            <?php foreach ($chunk as $product): ?>
                                <div class="col-md-4">
                                    <div class="product-card">
                                        <div class="product-image">
                                            <img src="<?= IMG_PATH . $product['img'] ?>" alt="<?= $product['title'] ?>">
                                            <span class="product-badge"><?= $product['badge'] ?></span>
                                        </div>
                                        <div class="product-body">
                                            <h4><?= $product['title'] ?></h4>
                                            <p class="product-author"><?= $product['author'] ?></p>
                                            <div class="product-price">
                                                <span class="price-old"><?= $product['old'] ?></span>
                                                <span class="price-new"><?= $product['new'] ?></span>
                                            </div>
                                            <button class="btn-add-cart"><i class="bi bi-cart-plus"></i> <?= $btn_label ?></button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?= $is_fa ? 'قبلی' : 'Previous' ?></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden"><?= $is_fa ? 'بعدی' : 'Next' ?></span>
            </button>

            <div class="carousel-indicators">
                <?php for ($i = 0; $i < count($chunks); $i++): ?>
                    <button type="button" data-bs-target="#productCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>"></button>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>