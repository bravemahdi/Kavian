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
<section class="filter-section py-4">
    <div class="container">
        <button class="btn-filter" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse">
            <i class="bi bi-layout-sidebar"></i> <?= t('filter') ?>
        </button>

        <div class="collapse mt-3" id="sidebarCollapse">
            <div class="filter-box">
                <div class="filter-row">
                    <div class="filter-col">
                        <h6><?= $is_fa ? 'دسته‌بندی' : 'Category' ?></h6>
                        <?php
                        $cat_items = $is_fa ? ['شعر و ادبیات', 'تاریخ', 'فلسفه', 'هنر', 'علم'] : ['Poetry & Literature', 'History', 'Philosophy', 'Art', 'Science'];
                        foreach ($cat_items as $item): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox">
                                <label><?= $item ?> <span class="badge-filter"><?= rand(3, 15) ?></span></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="filter-col">
                        <h6><?= $is_fa ? 'مشاهیر' : 'Celebrities' ?></h6>
                        <?php
                        $celeb_items = $is_fa ? ['همه', 'فردوسی', 'حافظ', 'مولانا', 'ابن‌سینا'] : ['All', 'Ferdowsi', 'Hafez', 'Rumi', 'Avicenna'];
                        foreach ($celeb_items as $item): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="figure">
                                <label><?= $item ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="filter-col">
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
                    <div class="filter-col">
                        <h6><?= $is_fa ? 'بیشتر' : 'More' ?></h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label><?= $is_fa ? 'فقط تخفیف‌دار' : 'Only Discounted' ?></label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label><?= $is_fa ? 'موجود در انبار' : 'In Stock' ?></label>
                        </div>
                        <button class="btn-filter-apply"><?= t('apply') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>