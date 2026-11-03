<?php
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$is_fa = ($current_lang === 'fa');

global $pdo;

$heroSql = "SELECT 
    hc.id,
    hc.celebrity_id,
    hc.title_en as title_en,
    hc.title_fa as title_fa,
    hc.subtitle_en as subtitle_en,
    hc.subtitle_fa as subtitle_fa,
    hc.image,
    hc.link_url
FROM hero_carousel hc
WHERE hc.is_active = 1 
ORDER BY hc.sort_order ASC";

$heroStmt = $pdo->query($heroSql);
$heroes = $heroStmt->fetchAll(PDO::FETCH_ASSOC);

// چک کن کدام celebrity_id ها واقعاً در دیتابیس وجود دارن
$existingIds = [];
if (!empty($heroes)) {
    $ids = array_filter(array_column($heroes, 'celebrity_id'));
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $checkStmt = $pdo->prepare("SELECT id FROM mashahir_celebrities WHERE id IN ($placeholders) AND is_active = 1");
        $checkStmt->execute(array_values($ids));
        $existingIds = $checkStmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

$profileBase = $is_fa ? '/Kavian/mashahir/persian/profile.php' : '/Kavian/mashahir/english/profile.php';
?>

<header class="hero-section">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <?php foreach ($heroes as $index => $hero): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= IMG_PATH . $hero['image'] ?>" class="d-block w-100 hero-img" alt="<?= $hero['title_en'] ?>">
                    <div class="hero-overlay"></div>
                    <div class="custom-caption fade-in">
                        <h1><?= $is_fa ? $hero['title_fa'] : $hero['title_en'] ?></h1>
                        <?php
                        $subtitle = $is_fa ? $hero['subtitle_fa'] : $hero['subtitle_en'];
                        $celId    = $hero['celebrity_id'];
                        $exists   = $celId && in_array($celId, $existingIds);
                        $notFoundMsg = $is_fa ? 'این شخص در پایگاه داده یافت نشد' : 'This person was not found in the database';
                        ?>
                        <?php if ($exists): ?>
                            <a href="<?= $profileBase ?>?id=<?= (int)$celId ?>" class="hero-subtitle-btn">
                                <?= htmlspecialchars($subtitle) ?>
                            </a>
                        <?php else: ?>
                            <button class="hero-subtitle-btn"
                                    onclick="heroNotFound(this)"
                                    data-msg="<?= htmlspecialchars($notFoundMsg) ?>">
                                <?= htmlspecialchars($subtitle) ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Navigation buttons -->
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?= $is_fa ? 'قبلی' : 'Previous' ?></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden"><?= $is_fa ? 'بعدی' : 'Next' ?></span>
        </button>
    </div>
</header>
