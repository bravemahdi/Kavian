<style>
    .category-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 15px;
        padding: 10px 0;
    }
    .category-grid .cat-item {
        flex: 0 0 170px;
        max-width: 170px;
    }
    .category-grid .category-card {
        padding: 12px 8px;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        text-align: center;
        transition: all 0.3s ease;
    }
    .category-grid .category-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
   .category-grid .category-icon-container {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    width: auto !important;
    height: auto !important;
    min-height: 200px !important;
}

.category-grid .category-icon-container img {
    width: 150px !important;
    height: 150px !important;
    object-fit: contain !important;
    max-width: 150px !important;
    max-height: 150px !important;
    min-width: 150px !important;
    min-height: 150px !important;
    display: block !important;
}
    .category-grid h5 {
    font-size: 22px !important;
    margin: 0 !important;  /* حذف فاصله‌های اضافی */
    padding: 5px 0 !important;  /* فاصله از بالا و پایین */
    color: #333;
    font-weight: 500;
    text-align: center;
    word-wrap: break-word;
    line-height: 1.3;
    position: relative;
    top: -10px;  /* ← با این خط متن رو بالاتر میبری */
}
</style>
<?php
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';

$cat_names = ($current_lang === 'fa') ? [
    'نقاشی','مجسمه سازی','معماری','موسیقی','ادبیات','تیاتر','سینما'
] : [
    'painting','sclupture','architecture','music','literature','theater','cinema'
];

$cat_icons = [
    'naghashi.png','mojasame.png','memari.png','mosighi.png','adabiat.png','teatr.png','sinama.png'
];
?>
<section class="category-section py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <h1><?= $current_lang === 'fa' ? 'دسته‌بندی هنرهای ایرانی' : 'Categories of Iranian Arts' ?></h1>
        </div>
        <div class="category-grid">
    <?php foreach ($cat_names as $index => $name): ?>
        <div class="cat-item">
            <div class="category-card card-ashpas">
                <div class="category-icon-container">
                    <img src="<?= IMG_PATH . $cat_icons[$index] ?>" alt="<?= $name ?>" class="img-fluid">
                </div>
                <h5><?= $name ?></h5>
            </div>
        </div>
    <?php endforeach; ?>
</div>
    </div>
</section>