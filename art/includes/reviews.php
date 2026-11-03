<?php
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$is_fa = ($current_lang === 'fa');

$reviews_data = [
    [
        'initial' => 'M', 'name' => 'Mohammad Rezaei', 'role' => 'Literature Lover',
        'text' => '"Shahnameh is incredible! Every Iranian should read this book. This great epic has kept Iranian identity alive."',
        'tags' => ['#Ferdowsi', '#Shahnameh', '#IranianLiterature'], 'likes' => 123, 'time' => '2 hours ago'
    ],
    [
        'initial' => 'S', 'name' => 'Sara Karimi', 'role' => 'Literature Student',
        'text' => '"I recommend Divan-e-Hafez to everyone. Every time I read it, I feel a fresh sensation."',
        'tags' => ['#Hafez', '#Ghazal', '#PersianPoetry'], 'likes' => 187, 'time' => '5 hours ago'
    ],
    [
        'initial' => 'A', 'name' => 'Ali Mousavi', 'role' => 'History Researcher',
        'text' => '"Rumi\'s Masnavi is an ocean of wisdom and mysticism. Every time I read it, I learn something new."',
        'tags' => ['#Rumi', '#Masnavi', '#Mysticism'], 'likes' => 210, 'time' => '8 hours ago'
    ],
    [
        'initial' => 'N', 'name' => 'Narges Ahmadi', 'role' => 'History Enthusiast',
        'text' => '"Avicenna\'s Canon of Medicine is truly fascinating. The advancement of science in that era is admirable."',
        'tags' => ['#Avicenna', '#Science', '#IranianHistory'], 'likes' => 54, 'time' => '12 hours ago'
    ],
    [
        'initial' => 'R', 'name' => 'Reza Karimi', 'role' => 'Poetry Enthusiast',
        'text' => '"Hafez is the master of poetry. Each of his ghazals carries a world of meaning."',
        'tags' => ['#Hafez', '#Poetry', '#Literature'], 'likes' => 98, 'time' => '1 day ago'
    ],
    [
        'initial' => 'Z', 'name' => 'Zahra Hosseini', 'role' => 'Art Lover',
        'text' => '"Iranian art is truly unique. Every time I look at Iranian artworks, I realize the depth of this land\'s culture."',
        'tags' => ['#IranianArt', '#Culture', '#IslamicArt'], 'likes' => 167, 'time' => '3 hours ago'
    ]
];

$chunks = array_chunk($reviews_data, 3);
$title = $current_lang === 'fa' ? 'نظرات کاربران' : 'User Reviews';
$desc = $current_lang === 'fa' ? 'تجربیات و نظرات علاقه‌مندان به فرهنگ و ادبیات ایران' : 'Experiences and opinions of Iranian culture and literature enthusiasts';
?>
<section class="reviews-slider-section py-5">
    <div class="container">
        <div class="section-header text-center mb-5">
            <h2><?= $is_fa ? 'نظرات کاربران' : 'User Reviews' ?></h2>
            <p><?= $is_fa ? 'تجربیات و نظرات دوستداران فرهنگ و ادب ایران' : 'Experiences and opinions of Iranian culture and literature enthusiasts' ?></p>
            <span class="header-line"></span>
        </div>

        <div id="reviewsCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                $all_reviews = $is_fa ? [
                    ['avatar' => 'م', 'bg' => '#00796B', 'name' => 'محمد رضایی', 'role' => 'عاشق ادبیات', 'text' => '"شاهنامه فردوسی بی‌نظیره! هر ایرانی باید این کتاب رو خونده باشه. این اثر بزرگ حماسه‌ای است که هویت ایرانی رو زنده نگه داشته."', 'tags' => ['#فردوسی', '#شاهنامه', '#ادبیات_ایران'], 'likes' => '۱۲۳', 'time' => '۲ ساعت پیش', 'stars' => 5],
                    ['avatar' => 'س', 'bg' => '#8B1538', 'name' => 'سارا کریمی', 'role' => 'دانشجوی ادبیات', 'text' => '"دیوان حافظ رو به همه پیشنهاد می‌کنم. هر بار که می‌خونمش یه حس تازه دارم. غزل‌های این شاعر بزرگ واقعاً روح نواز هستند."', 'tags' => ['#حافظ', '#غزل', '#شعر_فارسی'], 'likes' => '۱۸۷', 'time' => '۵ ساعت پیش', 'stars' => 4],
                    ['avatar' => 'ع', 'bg' => '#1E4FA1', 'name' => 'علی موسوی', 'role' => 'پژوهشگر تاریخ', 'text' => '"مثنوی معنوی مولانا دریایی از معرفت و عرفانه. هر بار که می‌خونمش چیز تازه‌ای یاد می‌گیرم. واقعاً بی‌نظیره."', 'tags' => ['#مولانا', '#مثنوی', '#عرفان'], 'likes' => '۲۱۰', 'time' => '۸ ساعت پیش', 'stars' => 5]
                ] : [
                    ['avatar' => 'M', 'bg' => '#00796B', 'name' => 'Mohammad Rezaei', 'role' => 'Literature Lover', 'text' => '"Shahnameh is incredible! Every Iranian should read this book. This great epic has kept Iranian identity alive."', 'tags' => ['#Ferdowsi', '#Shahnameh', '#IranianLiterature'], 'likes' => '123', 'time' => '2 hours ago', 'stars' => 5],
                    ['avatar' => 'S', 'bg' => '#8B1538', 'name' => 'Sara Karimi', 'role' => 'Literature Student', 'text' => '"I recommend Divan-e-Hafez to everyone. Every time I read it, I feel a fresh sensation. The ghazals of this great poet are truly soul-soothing."', 'tags' => ['#Hafez', '#Ghazal', '#PersianPoetry'], 'likes' => '187', 'time' => '5 hours ago', 'stars' => 4],
                    ['avatar' => 'A', 'bg' => '#1E4FA1', 'name' => 'Ali Mousavi', 'role' => 'History Researcher', 'text' => '"Rumi\'s Masnavi is an ocean of wisdom and mysticism. Every time I read it, I learn something new. Truly amazing."', 'tags' => ['#Rumi', '#Masnavi', '#Mysticism'], 'likes' => '210', 'time' => '8 hours ago', 'stars' => 5]
                ];
                
                $chunks = array_chunk($all_reviews, 3);
                foreach ($chunks as $index => $chunk): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="row g-4">
                            <?php foreach ($chunk as $review): ?>
                                <div class="col-md-4">
                                    <div class="review-slider-card">
                                        <div class="review-slider-top">
                                            <div class="review-slider-user">
                                                <div class="review-slider-avatar" style="background: <?= $review['bg'] ?>;"><?= $review['avatar'] ?></div>
                                                <div>
                                                    <h4><?= $review['name'] ?></h4>
                                                    <span><?= $review['role'] ?></span>
                                                </div>
                                            </div>
                                            <div class="review-slider-rating">
                                                <?php for ($i = 0; $i < 5; $i++): ?>
                                                    <i class="bi bi-star<?= $i < $review['stars'] ? '-fill' : '' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        <p class="review-slider-text"><?= $review['text'] ?></p>
                                        <div class="review-slider-footer">
                                            <div class="review-slider-hashtags">
                                                <?php foreach ($review['tags'] as $tag): ?><span><?= $tag ?></span><?php endforeach; ?>
                                            </div>
                                            <div class="review-slider-actions">
                                                <button><i class="bi bi-heart"></i> <?= $review['likes'] ?></button>
                                                <span class="review-slider-time"><?= $review['time'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#reviewsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

            <div class="carousel-indicators">
                <?php for ($i = 0; $i < count($chunks); $i++): ?>
                    <button type="button" data-bs-target="#reviewsCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>"></button>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</section>