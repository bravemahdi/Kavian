<?php
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$is_fa = ($current_lang === 'fa');
$filters = ($current_lang === 'fa') ? [
    'category' => 'دسته‌بندی',
    'poetry' => 'شعر و ادبیات',
    'history' => 'تاریخ',
    'philosophy' => 'فلسفه',
    'art' => 'هنر',
    'science' => 'علوم',
    'celebrities' => 'مشاهیر',
    'all' => 'همه',
    'price' => 'قیمت',
    'min' => 'حداقل',
    'max' => 'حداکثر',
    'more' => 'بیشتر',
    'discount' => 'فقط تخفیف‌دار',
    'stock' => 'موجود در انبار'
] : [
    'category' => 'Category',
    'poetry' => 'Poetry & Literature',
    'history' => 'History',
    'philosophy' => 'Philosophy',
    'art' => 'Art',
    'science' => 'Science',
    'celebrities' => 'Celebrities',
    'all' => 'All',
    'price' => 'Price',
    'min' => 'Min',
    'max' => 'Max',
    'more' => 'More',
    'discount' => 'Only Discounted',
    'stock' => 'In Stock'
];
?>
<!-- بخش فیلترها که در سمت راست و ثابت قرار می‌گیرد -->
<div class="filters-sidebar-wrapper">
    <!-- دکمه نمایش فیلترها در موبایل -->
    <div class="mobile-filter-toggle">
        <button class="btn-filter-mobile" type="button" data-bs-toggle="offcanvas" data-bs-target="#filtersOffcanvas">
            <i class="bi bi-funnel"></i> <?= t('filter') ?>
        </button>
    </div>

    <!-- فیلترهای دسکتاپ -->
    <div class="filters-sidebar-desktop">
        <div class="filter-sidebar">
            <div class="filter-header">
                <h5><i class="bi bi-funnel-fill"></i> <?= t('filter') ?></h5>
                <button class="btn-filter-clear"><?= $is_fa ? 'پاک کردن' : 'Clear all' ?></button>
            </div>
            
            <div class="filter-content">
                <!-- دسته‌بندی -->
                <div class="filter-group">
                    <h6><?= $is_fa ? 'دسته‌بندی' : 'Category' ?></h6>
                    <?php
                    $cat_items = $is_fa ? ['شعر و ادبیات', 'تاریخ', 'فلسفه', 'هنر', 'علم'] : ['Poetry & Literature', 'History', 'Philosophy', 'Art', 'Science'];
                    foreach ($cat_items as $item): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cat_<?= str_replace(' ', '_', $item) ?>">
                            <label class="form-check-label" for="cat_<?= str_replace(' ', '_', $item) ?>">
                                <?= $item ?> <span class="badge-filter"><?= rand(3, 15) ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- مشاهیر -->
                <div class="filter-group">
                    <h6><?= $is_fa ? 'مشاهیر' : 'Celebrities' ?></h6>
                    <?php
                    $celeb_items = $is_fa ? ['همه', 'فردوسی', 'حافظ', 'مولانا', 'ابن‌سینا'] : ['All', 'Ferdowsi', 'Hafez', 'Rumi', 'Avicenna'];
                    foreach ($celeb_items as $index => $item): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="figure" id="fig_<?= $index ?>" <?= $index === 0 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="fig_<?= $index ?>"><?= $item ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- قیمت -->
                <div class="filter-group">
                    <h6><?= $is_fa ? 'قیمت' : 'Price' ?></h6>
                    <div class="range-group">
                        <label><?= $is_fa ? 'حداقل' : 'Min' ?></label>
                        <input type="range" class="form-range" min="0" max="500000" step="10000">
                    </div>
                    <div class="range-group">
                        <label><?= $is_fa ? 'حداکثر' : 'Max' ?></label>
                        <input type="range" class="form-range" min="0" max="1000000" step="10000">
                    </div>
                </div>
                
                <!-- گزینه‌های بیشتر -->
                <div class="filter-group">
                    <h6><?= $is_fa ? 'بیشتر' : 'More' ?></h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="discount_only">
                        <label class="form-check-label" for="discount_only"><?= $is_fa ? 'فقط تخفیف‌دار' : 'Only Discounted' ?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="in_stock">
                        <label class="form-check-label" for="in_stock"><?= $is_fa ? 'موجود در انبار' : 'In Stock' ?></label>
                    </div>
                </div>
                
                <button class="btn-filter-apply"><?= t('apply') ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas برای فیلترها در موبایل -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="filtersOffcanvas">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"><i class="bi bi-funnel-fill"></i> <?= t('filter') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <div class="filter-content">
            <!-- محتوای فیلترها در اینجا کپی می‌شود -->
            <div class="filter-group">
                <h6><?= $is_fa ? 'دسته‌بندی' : 'Category' ?></h6>
                <?php foreach ($cat_items as $item): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox">
                        <label><?= $item ?> <span class="badge-filter"><?= rand(3, 15) ?></span></label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="filter-group">
                <h6><?= $is_fa ? 'مشاهیر' : 'Celebrities' ?></h6>
                <?php foreach ($celeb_items as $item): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="figure_mobile">
                        <label><?= $item ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button class="btn-filter-apply w-100"><?= t('apply') ?></button>
        </div>
    </div>
</div>