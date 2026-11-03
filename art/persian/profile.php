<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="container">
    <div id="listPage">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="filters-sidebar">
                    <div class="filter-title">
                        <i class="fas fa-sliders-h"></i> فیلترها
                    </div>

                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-tags me-1"></i> دسته‌بندی</span>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="شاعر" id="cat1" onchange="filterCards()">
                            <label class="form-check-label" for="cat1">شاعران</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="پزشک" id="cat2" onchange="filterCards()">
                            <label class="form-check-label" for="cat2">پزشکان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="فیلسوف" id="cat3" onchange="filterCards()">
                            <label class="form-check-label" for="cat3">فیلسوفان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="سیاستمدار" id="cat4" onchange="filterCards()">
                            <label class="form-check-label" for="cat4">سیاستمداران</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ریاضیدان" id="cat5" onchange="filterCards()">
                            <label class="form-check-label" for="cat5">ریاضیدانان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ستاره‌شناس" id="cat6" onchange="filterCards()">
                            <label class="form-check-label" for="cat6">ستاره‌شناسان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="عارف" id="cat7" onchange="filterCards()">
                            <label class="form-check-label" for="cat7">عرفا</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="حماسه‌سرا" id="cat8" onchange="filterCards()">
                            <label class="form-check-label" for="cat8">حماسه‌سرایان</label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-history me-1"></i> دوره تاریخی</span>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="پیش از اسلام" id="era1" onchange="filterCards()">
                            <label class="form-check-label" for="era1">پیش از اسلام</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="اسلامی" id="era2" onchange="filterCards()">
                            <label class="form-check-label" for="era2">اسلامی</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="سلجوقی" id="era3" onchange="filterCards()">
                            <label class="form-check-label" for="era3">سلجوقی</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="صفوی" id="era4" onchange="filterCards()">
                            <label class="form-check-label" for="era4">صفوی</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="قاجار" id="era5" onchange="filterCards()">
                            <label class="form-check-label" for="era5">قاجار</label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-map-marker-alt me-1"></i> مکان</span>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="شیراز" id="loc1" onchange="filterCards()">
                            <label class="form-check-label" for="loc1">شیراز</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="خراسان" id="loc2" onchange="filterCards()">
                            <label class="form-check-label" for="loc2">خراسان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="نیشابور" id="loc3" onchange="filterCards()">
                            <label class="form-check-label" for="loc3">نیشابور</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="همدان" id="loc4" onchange="filterCards()">
                            <label class="form-check-label" for="loc4">همدان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="توس" id="loc6" onchange="filterCards()">
                            <label class="form-check-label" for="loc6">توس</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ری" id="loc5" onchange="filterCards()">
                            <label class="form-check-label" for="loc5">ری</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="تهران" id="loc7" onchange="filterCards()">
                            <label class="form-check-label" for="loc7">تهران</label>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button class="btn-apply" onclick="applyFilters()"><i class="fas fa-check me-1"></i> اعمال فیلترها</button>
                        <button class="btn-clear" onclick="clearFilters()"><i class="fas fa-undo me-1"></i> پاک کردن</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="filter-header-mobile">
                    <button class="filter-icon-btn" onclick="toggleFilter()">
                        <i class="fas fa-sliders-h"></i>
                        <span class="filter-count" id="filterCount">۰</span>
                    </button>
                </div>

                <div class="filter-dropdown" id="filterDropdown">
                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-tags me-1"></i> دسته‌بندی</span>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="شاعر" id="mcat1" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mcat1">شاعران</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="پزشک" id="mcat2" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mcat2">پزشکان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="فیلسوف" id="mcat3" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mcat3">فیلسوفان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="سیاستمدار" id="mcat4" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mcat4">سیاستمداران</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ریاضیدان" id="mcat5" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mcat5">ریاضیدانان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="ستاره‌شناس" id="mcat6" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mcat6">ستاره‌شناسان</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="عارف" id="mcat7" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mcat7">عرفا</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="حماسه‌سرا" id="mcat8" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mcat8">حماسه‌سرایان</label>
                        </div>
                    </div>

                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-history me-1"></i> دوره تاریخی</span>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="پیش از اسلام" id="mera1" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mera1">پیش از اسلام</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="اسلامی" id="mera2" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mera2">اسلامی</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="سلجوقی" id="mera3" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mera3">سلجوقی</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="صفوی" id="mera4" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mera4">صفوی</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="قاجار" id="mera5" onchange="updateFilterCount()">
                            <label class="form-check-label" for="mera5">قاجار</label>
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button class="btn-apply" onclick="applyFilters()"><i class="fas fa-check me-1"></i> اعمال</button>
                        <button class="btn-clear" onclick="clearFilters()"><i class="fas fa-undo me-1"></i> پاک</button>
                    </div>
                </div>

                <div id="cardsContainer"></div>
            </div>
        </div>
    </div>

    <div id="profilePage">
        <button class="btn-back" onclick="showList()">
            <i class="fas <?= $is_fa ? 'fa-arrow-right' : 'fa-arrow-left' ?>"></i> <?= $is_fa ? 'بازگشت به لیست' : 'Back to List' ?>
        </button>
        <div id="profileContent"></div>
    </div>

    <div class="text-center mt-4 py-3 border-top border-light" id="footerSection">
        <p class="text-muted small">
            <i class="fas fa-chevron-right"></i> مشاهیر ایران <i class="fas fa-chevron-left"></i>
        </p>
    </div>
</div>

<div class="bottom-nav">
    <a href="#" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon-m.png" alt="درباره استاد" class="bottom-nav-icon">
        <span>درباره استاد</span>
    </a>
    <a href="#" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon2-m.png" alt="آثار" class="bottom-nav-icon">
        <span>آثار</span>
    </a>
    <a href="#" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon1-m.png" alt="گالری" class="bottom-nav-icon">
        <span>گالری</span>
    </a>
    <a href="#" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon3-m.png" alt="ویدیوها" class="bottom-nav-icon">
        <span>ویدیوها</span>
    </a>
    <a href="#" class="bottom-nav-item active-btn">
        <span>ادامه مطلب</span>
        <img src="<?= IMG_PATH ?>icon5-m.png" alt="ادامه مطلب" class="bottom-icon-btn">
    </a>
</div>

<!-- ===== اسکریپت‌ها باید اینجا باشند ===== -->
<script>
console.log('✅ profile.php loaded successfully!');
</script>

<script src="script.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>