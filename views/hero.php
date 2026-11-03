<?php

$pdo = require __DIR__ . '/../app/config/config.php';
$heroLang = $heroLang ?? 'fa';
$titleKey = $heroLang === 'en' ? 'title_en' : 'title_fa';
$subtitleKey = $heroLang === 'en' ? 'subtitle_en' : 'subtitle_fa';
$prevLabel = $heroLang === 'en' ? 'Previous slide' : 'اسلاید قبلی';
$nextLabel = $heroLang === 'en' ? 'Next slide' : 'اسلاید بعدی';

$heroSql = "SELECT id, title_fa, title_en, subtitle_fa, subtitle_en, image, image_mobile, link_url
            FROM home_hero_carousel
            WHERE is_active = 1
            ORDER BY sort_order ASC";
$heroStmt = $pdo->query($heroSql);
$heroSlides = $heroStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="hero hero-carousel" id="heroCarousel">
    <div class="hero-slides">
        <?php foreach ($heroSlides as $index => $slide): ?>
            <?php $captionLeft = in_array($slide['image'], ['khalij-pars.png']) ? ' hero-caption-left' : ''; ?>
            <div class="hero-slide<?= $index === 0 ? ' active' : '' ?>">
                <picture>
                    <?php if (!empty($slide['image_mobile'])): ?>
                        <source media="(max-width: 600px)" srcset="/Kavian/public/images/<?= htmlspecialchars($slide['image_mobile']) ?>">
                    <?php endif; ?>
                    <img src="/Kavian/public/images/<?= htmlspecialchars($slide['image']) ?>" alt="<?= htmlspecialchars($slide[$titleKey] ?: ($heroLang === 'en' ? 'Miras-e Iran' : 'میراث ایران')) ?>">
                </picture>
                <?php if (!empty($slide[$titleKey])): ?>
                    <div class="hero-overlay"></div>
                    <div class="hero-caption<?= $captionLeft ?>">
                        <h2><?= htmlspecialchars($slide[$titleKey]) ?></h2>
                        <?php if (!empty($slide[$subtitleKey])): ?>
                            <p><?= htmlspecialchars($slide[$subtitleKey]) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($heroSlides) > 1): ?>
        <button class="hero-nav hero-prev" type="button" aria-label="<?= $prevLabel ?>">&#10094;</button>
        <button class="hero-nav hero-next" type="button" aria-label="<?= $nextLabel ?>">&#10095;</button>
        <div class="hero-dots">
            <?php foreach ($heroSlides as $index => $slide): ?>
                <span class="hero-dot<?= $index === 0 ? ' active' : '' ?>" data-slide="<?= $index ?>"></span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
