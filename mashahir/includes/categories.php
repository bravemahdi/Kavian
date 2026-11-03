<?php
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$is_fa = ($current_lang === 'fa');

global $pdo;

$catSql = "SELECT 
    id,
    name_en,
    name_fa,
    image,
    sort_order
FROM mashahir_categories 
WHERE is_active = 1 
ORDER BY sort_order ASC";

$catStmt = $pdo->query($catSql);
$categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

$profileUrl = $is_fa
    ? '/Kavian/mashahir/persian/profile.php'
    : '/Kavian/mashahir/english/profile.php';
?>

<section class="category-section py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h1><?= $is_fa ? 'دسته‌بندی مشاهیر ایرانی' : 'Categories of Iranian Celebrities' ?></h1>
        </div>
        <div class="row g-4">
            <?php foreach ($categories as $category):
                $catName = $is_fa ? $category['name_fa'] : $category['name_en'];
                $catUrl  = $profileUrl . '?cat=' . urlencode($catName);
            ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="cat-item">
                        <a href="<?= htmlspecialchars($catUrl) ?>" class="category-card-link">
                            <div class="category-card card-ashpas">
                                <div class="category-icon-container">
                                    <img src="<?= IMG_PATH . $category['image'] ?>" 
                                         alt="<?= htmlspecialchars($catName) ?>" 
                                         class="img-fluid">
                                </div>
                                <h5><?= htmlspecialchars($catName) ?></h5>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>