<?php
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$is_fa = ($current_lang === 'fa');

global $pdo;

$lang_filter = $is_fa ? 'fa' : 'en';

$reviewSql = "SELECT 
    r.id,
    r.author_name,
    r.rating,
    r.content_en,
    r.content_fa,
    r.hashtags,
    r.likes_count,
    r.created_at,
    mc.name_en as celebrity_name_en,
    mc.name_fa as celebrity_name_fa
FROM mashahir_reviews r
LEFT JOIN mashahir_celebrities mc ON r.celebrity_id = mc.id
WHERE r.is_active = 1 AND r.lang = ?
ORDER BY RAND()
LIMIT 6";

$reviewStmt = $pdo->prepare($reviewSql);
$reviewStmt->execute([$lang_filter]);
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

$title = $is_fa ? 'نظرات کاربران' : 'User Reviews';
$desc  = $is_fa ? 'تجربیات و نظرات دوستداران فرهنگ و ادب ایران' : 'Experiences and opinions of Iranian culture and literature enthusiasts';

$colors = ['#00796B', '#8B1538', '#1E4FA1', '#FF5722', '#4CAF50', '#9C27B0'];

$chunks       = array_chunk($reviews, 3);
$totalReviews = count($reviews);
$totalChunks  = count($chunks);
?>

<section class="reviews-slider-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2><?= $title ?></h2>
            <p><?= $desc ?></p>
            <span class="header-line"></span>
        </div>

        <!-- ============ دسکتاپ: هر slide سه کارت ============ -->
        <div id="reviewsCarouselDesktop" class="carousel slide d-none d-md-block" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($chunks as $index => $chunk): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="row g-4">
                        <?php foreach ($chunk as $rIndex => $review):
                            $hashtags = json_decode($review['hashtags'], true) ?? [];
                            $initial  = mb_substr($review['author_name'], 0, 1);
                        ?>
                        <div class="col-md-4">
                            <div class="review-slider-card">
                                <div class="review-slider-top">
                                    <div class="review-slider-user">
                                        <div class="review-slider-avatar"
                                             style="background: <?= $colors[$rIndex % count($colors)] ?>;">
                                            <?= $initial ?>
                                        </div>
                                        <div>
                                            <h4><?= htmlspecialchars($review['author_name']) ?></h4>
                                            <span><?= $is_fa ? ($review['celebrity_name_fa'] ?? 'کاربر') : ($review['celebrity_name_en'] ?? 'User') ?></span>
                                        </div>
                                    </div>
                                    <div class="review-slider-rating">
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="bi bi-star<?= $i < $review['rating'] ? '-fill' : '' ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="review-slider-text">
                                    "<?= htmlspecialchars($is_fa ? $review['content_fa'] : $review['content_en']) ?>"
                                </p>
                                <div class="review-slider-footer">
                                    <div class="review-slider-hashtags">
                                        <?php foreach ($hashtags as $tag): ?>
                                            <span><?= htmlspecialchars($tag) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="review-slider-actions">
                                        <button><i class="bi bi-heart"></i> <?= $review['likes_count'] ?></button>
                                        <span class="review-slider-time">
                                            <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarouselDesktop" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarouselDesktop" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

            <div class="carousel-indicators">
                <?php for ($i = 0; $i < $totalChunks; $i++): ?>
                <button type="button" data-bs-target="#reviewsCarouselDesktop" data-bs-slide-to="<?= $i ?>"
                        class="<?= $i === 0 ? 'active' : '' ?>"></button>
                <?php endfor; ?>
            </div>
        </div>

        <!-- ============ موبایل: هر slide یک کارت ============ -->
        <div id="reviewsCarouselMobile" class="carousel slide d-md-none" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($reviews as $rIndex => $review):
                    $hashtags = json_decode($review['hashtags'], true) ?? [];
                    $initial  = mb_substr($review['author_name'], 0, 1);
                ?>
                <div class="carousel-item <?= $rIndex === 0 ? 'active' : '' ?>">
                    <div class="review-slider-card">
                        <div class="review-slider-top">
                            <div class="review-slider-user">
                                <div class="review-slider-avatar"
                                     style="background: <?= $colors[$rIndex % count($colors)] ?>;">
                                    <?= $initial ?>
                                </div>
                                <div>
                                    <h4><?= htmlspecialchars($review['author_name']) ?></h4>
                                    <span><?= $is_fa ? ($review['celebrity_name_fa'] ?? 'کاربر') : ($review['celebrity_name_en'] ?? 'User') ?></span>
                                </div>
                            </div>
                            <div class="review-slider-rating">
                                <?php for ($i = 0; $i < 5; $i++): ?>
                                    <i class="bi bi-star<?= $i < $review['rating'] ? '-fill' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <p class="review-slider-text">
                            "<?= htmlspecialchars($is_fa ? $review['content_fa'] : $review['content_en']) ?>"
                        </p>
                        <div class="review-slider-footer">
                            <div class="review-slider-hashtags">
                                <?php foreach ($hashtags as $tag): ?>
                                    <span><?= htmlspecialchars($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="review-slider-actions">
                                <button><i class="bi bi-heart"></i> <?= $review['likes_count'] ?></button>
                                <span class="review-slider-time">
                                    <?= date('M d, Y', strtotime($review['created_at'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($is_fa): ?>
            <!-- RTL: swap prev/next تا جهت حرکت با دکمه match کنه -->
            <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarouselMobile" data-bs-slide="next">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarouselMobile" data-bs-slide="prev">
                <span class="carousel-control-next-icon"></span>
            </button>
            <!-- custom indicators — نه Bootstrap، تا RTL درست کار کنه -->
            <div class="custom-indicators" id="reviewsMobileIndicators">
                <?php for ($i = 0; $i < $totalReviews; $i++): ?>
                <button type="button" class="<?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>"></button>
                <?php endfor; ?>
            </div>
            <?php else: ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarouselMobile" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarouselMobile" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
            <div class="carousel-indicators">
                <?php for ($i = 0; $i < $totalReviews; $i++): ?>
                <button type="button" data-bs-target="#reviewsCarouselMobile" data-bs-slide-to="<?= $i ?>"
                        class="<?= $i === 0 ? 'active' : '' ?>"></button>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>
