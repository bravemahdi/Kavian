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
                        <i class="fas fa-sliders-h"></i> <?= t('filter') ?>
                    </div>

                    <div class="filter-group" id="catFilterGroup">
                        <span class="group-label"><i class="fas fa-tags me-1"></i> <?= $is_fa ? 'دسته‌بندی' : 'Category' ?></span>
                        <div id="catFilterItems">
                            <div class="filter-loading"><i class="fas fa-spinner fa-spin"></i></div>
                        </div>
                    </div>

                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-history me-1"></i> <?= $is_fa ? 'دوره تاریخی' : 'Historical Period' ?></span>
                        <?php
                        $eras = $is_fa ? [
                            'پیش از اسلام' => 'پیش از اسلام',
                            'اسلامی' => 'اسلامی',
                            'سلجوقی' => 'سلجوقی',
                            'صفوی' => 'صفوی',
                            'قاجار' => 'قاجار'
                        ] : [
                            'Pre-Islamic' => 'Pre-Islamic',
                            'Islamic' => 'Islamic',
                            'Seljuk' => 'Seljuk',
                            'Safavid' => 'Safavid',
                            'Qajar' => 'Qajar'
                        ];
                        $i = 1;
                        foreach ($eras as $key => $label): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $key ?>" id="era<?= $i ?>" onchange="filterCards()">
                                <label class="form-check-label" for="era<?= $i ?>"><?= $label ?></label>
                            </div>
                        <?php $i++; endforeach; ?>
                    </div>

                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-map-marker-alt me-1"></i> <?= $is_fa ? 'مکان' : 'Location' ?></span>
                        <?php
                        $locations = $is_fa ? [
                            'شیراز' => 'شیراز',
                            'خراسان' => 'خراسان',
                            'نیشابور' => 'نیشابور',
                            'همدان' => 'همدان',
                            'توس' => 'توس',
                            'ری' => 'ری',
                            'تهران' => 'تهران'
                        ] : [
                            'Shiraz' => 'Shiraz',
                            'Nishapur' => 'Nishapur',
                            'Hamadan' => 'Hamadan',
                            'Tus' => 'Tus',
                            'Tehran' => 'Tehran'
                        ];
                        $i = 1;
                        foreach ($locations as $key => $label): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $key ?>" id="loc<?= $i ?>" onchange="filterCards()">
                                <label class="form-check-label" for="loc<?= $i ?>"><?= $label ?></label>
                            </div>
                        <?php $i++; endforeach; ?>
                    </div>

                    <div class="filter-actions">
                        <button class="btn-apply" onclick="applyFilters()"><i class="fas fa-check me-1"></i> <?= t('apply') ?></button>
                        <button class="btn-clear" onclick="clearFilters()"><i class="fas fa-undo me-1"></i> <?= $is_fa ? 'پاک کردن' : 'Clear' ?></button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="filter-header-mobile">
                    <button class="filter-icon-btn" onclick="toggleFilter()">
                        <i class="fas fa-sliders-h"></i>
                        <span class="filter-count" id="filterCount">0</span>
                    </button>
                </div>

                <div class="filter-dropdown" id="filterDropdown">
                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-tags me-1"></i> <?= $is_fa ? 'دسته‌بندی' : 'Category' ?></span>
                        <div id="mobCatFilterItems">
                            <div class="filter-loading"><i class="fas fa-spinner fa-spin"></i></div>
                        </div>
                    </div>

                    <div class="filter-group">
                        <span class="group-label"><i class="fas fa-history me-1"></i> <?= $is_fa ? 'دوره تاریخی' : 'Historical Period' ?></span>
                        <?php
                        $i = 1;
                        foreach ($eras as $key => $label): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $key ?>" id="mera<?= $i ?>" onchange="updateFilterCount()">
                                <label class="form-check-label" for="mera<?= $i ?>"><?= $label ?></label>
                            </div>
                        <?php $i++; endforeach; ?>
                    </div>

                    <div class="filter-actions">
                        <button class="btn-apply" onclick="applyFilters()"><i class="fas fa-check me-1"></i> <?= t('apply') ?></button>
                        <button class="btn-clear" onclick="clearFilters()"><i class="fas fa-undo me-1"></i> <?= $is_fa ? 'پاک کردن' : 'Clear' ?></button>
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
            <i class="fas fa-chevron-right"></i> <?= SITE_NAME ?> <i class="fas fa-chevron-left"></i>
        </p>
    </div>
</div>

<div class="bottom-nav">
    <a href="#" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon-m.png" alt="About" class="bottom-nav-icon">
        <span><?= $is_fa ? 'درباره' : 'About' ?></span>
    </a>
    <a href="#" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon2-m.png" alt="Works" class="bottom-nav-icon">
        <span><?= $is_fa ? 'آثار' : 'Works' ?></span>
    </a>
    <a href="#" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon1-m.png" alt="Gallery" class="bottom-nav-icon">
        <span><?= $is_fa ? 'گالری' : 'Gallery' ?></span>
    </a>
    <a href="#" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon3-m.png" alt="Videos" class="bottom-nav-icon">
        <span><?= $is_fa ? 'ویدیوها' : 'Videos' ?></span>
    </a>
    <a href="#" class="bottom-nav-item active-btn">
        <span><?= $is_fa ? 'بیشتر بخوانید' : 'Read More' ?></span>
        <img src="<?= IMG_PATH ?>icon5-m.png" alt="Read More" class="bottom-icon-btn">
    </a>
</div>

<script>
// ===== Language Setting from PHP =====
const IS_FA = <?= $is_fa ? 'true' : 'false' ?>;
const LANG  = IS_FA ? 'fa' : 'en';

// ===== Database API =====
let celebritiesData = [];

// ─── بارگذاری tags از API و رندر فیلتر ─────────────────────
function loadCategoryFilter(preselect) {
    fetch('/Kavian/mashahir/api/get-categories.php?lang=' + LANG)
        .then(r => r.json())
        .then(data => {
            if (!data.success || !data.filter_tags) return;
            renderCatCheckboxes('catFilterItems',    data.filter_tags, 'cat',  preselect, 'filterCards()');
            renderCatCheckboxes('mobCatFilterItems', data.filter_tags, 'mcat', preselect, 'updateFilterCount()');
        })
        .catch(() => {
            var el = document.getElementById('catFilterItems');
            if (el) el.innerHTML = '';
            var el2 = document.getElementById('mobCatFilterItems');
            if (el2) el2.innerHTML = '';
        });
}

function renderCatCheckboxes(containerId, tags, prefix, preselect, onchange) {
    var el = document.getElementById(containerId);
    if (!el) return;
    el.innerHTML = tags.map(function(tag, i) {
        var checked = preselect && preselect.toLowerCase() === tag.toLowerCase() ? ' checked' : '';
        return '<div class="form-check">' +
            '<input class="form-check-input" type="checkbox" value="' + escTag(tag) + '"' +
            ' id="' + prefix + i + '"' + checked + ' onchange="' + onchange + '">' +
            '<label class="form-check-label" for="' + prefix + i + '">' + escTag(tag) + '</label>' +
            '</div>';
    }).join('');
    if (preselect) updateFilterCount();
}

function escTag(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function loadCelebrities() {
    const url = `/Kavian/mashahir/api/get-celebrities.php?lang=${LANG}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                celebritiesData = data.data;

                const params = new URLSearchParams(window.location.search);

                if (params.has('id')) {
                    renderCards(celebritiesData);
                    updateFilterCount();
                    const id = parseInt(params.get('id'));
                    if (!isNaN(id)) showProfile(id);

                } else if (params.has('cat')) {
                    // ?cat=شاعر — فیلتر دسته‌بندی رو فعال کن
                    const catParam = decodeURIComponent(params.get('cat'));
                    loadCategoryFilter(catParam);
                    // صبر کن filter بارگذاری بشه، بعد filterCards صدا بزن
                    setTimeout(function() {
                        const matched = filterCardsByCat(catParam);
                        if (matched === 0) {
                            showCatNotFound(catParam);
                        }
                        updateFilterCount();
                    }, 600);

                } else if (params.has('search')) {
                    loadCategoryFilter(null);
                    const q = params.get('search');
                    const searchInput = document.getElementById('searchInput');
                    if (searchInput) searchInput.value = q;
                    filterCards();
                    updateFilterCount();
                    const matched = celebritiesData.filter(f =>
                        (f.name || '').toLowerCase().includes(q.toLowerCase()) ||
                        (f.fullName || '').toLowerCase().includes(q.toLowerCase())
                    );
                    if (matched.length === 1) showProfile(matched[0].id);

                } else {
                    loadCategoryFilter(null);
                    renderCards(celebritiesData);
                    updateFilterCount();
                }

            } else {
                console.error('Failed to load celebrities:', data.error);
            }
        })
        .catch(error => {
            console.error('Error loading celebrities:', error);
        });
}

// فیلتر بر اساس یک دسته‌بندی مشخص — بازگشت تعداد نتیجه
function filterCardsByCat(catValue) {
    const q = catValue.toLowerCase();
    const filtered = celebritiesData.filter(f => {
        // ۱. جستجو در tags
        const inTags = (f.categories || []).some(c => c.toLowerCase().includes(q) || q.includes(c.toLowerCase()));
        // ۲. جستجو در job
        const inJob  = (f.job || '').toLowerCase().includes(q);
        // ۳. جستجو در era
        const inEra  = (f.era || '').toLowerCase().includes(q);
        return inTags || inJob || inEra;
    });
    renderCards(filtered);
    return filtered.length;
}

// نمایش پیام "دسته‌بندی موجود نیست"
function showCatNotFound(catName) {
    const container = document.getElementById('cardsContainer');
    if (!container) return;
    container.innerHTML = `
        <div class="cat-not-found">
            <div class="cat-not-found-icon"><i class="fas fa-folder-open"></i></div>
            <h4>${IS_FA ? 'محتوایی برای این دسته‌بندی ایجاد نشده' : 'No content for this category yet'}</h4>
            <p class="cat-not-found-name">${escTag(catName)}</p>
            <button class="btn-back-cats" onclick="clearFilters()">
                <i class="fas fa-arrow-right"></i> ${IS_FA ? 'مشاهده همه مشاهیر' : 'View all celebrities'}
            </button>
        </div>
    `;
}

function showProfile(id) {
    const figure = celebritiesData.find(f => f.id === id);
    if (!figure) {
        alert(IS_FA ? 'مشاهیر یافت نشد!' : 'Celebrity not found!');
        return;
    }

    // Hide list, show profile
    const listPage = document.getElementById('listPage');
    const profilePage = document.getElementById('profilePage');
    const footerSection = document.getElementById('footerSection');
    
    if (listPage) listPage.style.display = 'none';
    if (profilePage) {
        profilePage.style.display = 'block';
        profilePage.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    if (footerSection) footerSection.style.display = 'none';

    const container = document.getElementById('profileContent');
    if (!container) return;

    // Build HTML using IS_FA constant
    const desktopHTML = `
        <div class="profile-desktop">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card-shadow p-4 h-100"> 
                        <div class="profile-img-wrapper">
                            <img src="${figure.img}" alt="${figure.name}" class="img-fluid">
                        </div>
                        <div class="mt-3">
                            <span class="top-badge"><i class="fas fa-chess-queen me-1"></i> ${IS_FA ? 'اطلاعات کلی' : 'General Info'}</span>
                            <div class="poet-name mt-2">${figure.name}</div>
                            <div class="profile-fullname">${figure.fullName}</div>
                        </div>
                        <div class="row g-2 mt-3">
                            <div class="col-6"><span class="info-label"><i class="far fa-calendar-alt me-1"></i> ${IS_FA ? 'تاریخ تولد' : 'Birth Date'}</span><br><span class="info-value">${figure.birth}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-map-pin me-1"></i> ${IS_FA ? 'محل تولد' : 'Birth Place'}</span><br><span class="info-value">${figure.birthPlace}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-skull me-1"></i> ${IS_FA ? 'تاریخ وفات' : 'Death Date'}</span><br><span class="info-value">${figure.death}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-map-pin me-1"></i> ${IS_FA ? 'محل وفات' : 'Death Place'}</span><br><span class="info-value">${figure.deathPlace}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-briefcase me-1"></i> ${IS_FA ? 'حرفه' : 'Profession'}</span><br><span class="info-value">${figure.job}</span></div>
                            <div class="col-6"><span class="info-label"><i class="fas fa-history me-1"></i> ${IS_FA ? 'دوره تاریخی' : 'Historical Period'}</span><br><span class="info-value">${figure.era}</span></div>
                        </div>
                        <hr class="divider-light">
                        <div class="tag-group">
                            ${(figure.categories || []).map(cat => `<span class="badge"><i class="fas fa-tag"></i> ${cat}</span>`).join(' ')}
                        </div>
                        <div class="verse-box mt-3">
                            <i class="fas fa-quote-right"></i> ${(figure.quote || '').replace(/\n/g, '<br><i class="fas fa-quote-left"></i> ')}
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card-shadow1 p-4 h-100">
                        <div class="profile-intro-card">
                            <div class="bio-title intro-title"><i class="fas fa-scroll"></i> ${IS_FA ? 'معرفی' : 'Introduction'}</div>
                            <p class="intro-text"><strong>${figure.fullName}</strong> · ${figure.shortStory}</p>
                            <p class="intro-full">${figure.biography}</p>
                        </div>

                        <div class="profile-footer-info">
                            <span class="info-tag"><i class="fas fa-map-marker-alt"></i> ${figure.birthPlace}</span>
                            <span class="info-tag"><i class="fas fa-calendar-alt"></i> ${figure.birth} – ${figure.death}</span>
                            <span class="info-tag"><i class="fas fa-feather"></i> ${figure.job}</span>
                            <span class="info-tag"><i class="fas fa-tag"></i> ${figure.era}</span>
                        </div>

                        <div class="bio-timeline">
                            <div class="bio-item">
                                <div class="bio-icon"><i class="fas fa-birthday-cake"></i></div>
                                <div class="bio-content">
                                    <h5>${IS_FA ? 'تولد در' : 'Born in'} ${figure.birthPlace}</h5>
                                    <p>${figure.fullName} ${IS_FA ? 'در سال' : 'was born in'} ${figure.birth} ${IS_FA ? 'در' : 'in'} ${figure.birthPlace}.</p>
                                </div>
                            </div>
                            <div class="bio-item">
                                <div class="bio-icon"><i class="fas fa-pen-nib"></i></div>
                                <div class="bio-content">
                                    <h5>${IS_FA ? 'آغاز فعالیت' : 'Beginning of Career'}</h5>
                                    <p>${IS_FA ? 'از جوانی به علم و دانش علاقه‌مند شد و در مسیر تخصص خود گام برداشت.' : 'From a young age, he/she became interested in knowledge and took steps in the path of their expertise.'}</p>
                                </div>
                            </div>
                            <div class="bio-item">
                                <div class="bio-icon"><i class="fas fa-crown"></i></div>
                                <div class="bio-content">
                                    <h5>${IS_FA ? 'شهرت و استقبال' : 'Fame and Recognition'}</h5>
                                    <p>${IS_FA ? 'با آثار ارزشمند خود به شهرت رسید و مورد احترام مردم و بزرگان زمان شد.' : 'With his/her valuable works, he/she gained fame and was respected by the people and the greats of their time.'}</p>
                                </div>
                            </div>
                            <div class="bio-item">
                                <div class="bio-icon"><i class="fas fa-dove"></i></div>
                                <div class="bio-content">
                                    <h5>${IS_FA ? 'وفات در' : 'Passed away in'} ${figure.deathPlace}</h5>
                                    <p>${IS_FA ? 'در سال' : 'In'} ${figure.death} ${IS_FA ? 'در' : 'in'} ${figure.deathPlace} ${IS_FA ? 'دیده از جهان فرو بست.' : 'passed away.'}</p>
                                </div>
                            </div>
                        </div>

                        <!-- محصولات مرتبط — دسکتاپ -->
                        <div id="d_celebrityProducts" class="mt-3" style="display:none;"></div>

                    </div>
                </div>
            </div>
        </div>
    `;

    const mobileHTML = `
        <div class="profile-mobile">
            <div class="profile-header-mobile">
                <img src="${figure.img}" alt="${figure.name}">
                <div class="profile-overlay">
                    <h1>${figure.name}</h1>
                    <div class="subtitle">${figure.fullName}</div>
                    <div class="date-badge">
                        <i class="far fa-calendar-alt me-1"></i> ${figure.birth} - ${figure.death}
                    </div>
                </div>
            </div>

            <div class="profile-job-card">
                <p><i class="fas fa-briefcase"></i> <strong>${figure.job}</strong></p>
            </div>

            <div class="stats-grid-mobile">
                <div class="stat-item-mobile">
                    <i class="fas fa-feather-alt stat-icon"></i>
                    <div class="stat-label">${IS_FA ? 'سبک' : 'Style'}</div>
                    <div class="stat-value">${figure.categories[0] || (IS_FA ? 'سنتی' : 'Traditional')}</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-globe stat-icon"></i>
                    <div class="stat-label">${IS_FA ? 'شهرت' : 'Fame'}</div>
                    <div class="stat-value">${IS_FA ? 'جهانی' : 'Global'}</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-award stat-icon"></i>
                    <div class="stat-label">${IS_FA ? 'آثار' : 'Works'}</div>
                    <div class="stat-value">${IS_FA ? 'ارزشمند' : 'Valuable'}</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-clock stat-icon"></i>
                    <div class="stat-label">${IS_FA ? 'میراث' : 'Legacy'}</div>
                    <div class="stat-value">${IS_FA ? 'ماندگار' : 'Enduring'}</div>
                </div>
            </div>

            <div class="profile-info-grid-mobile">
                <div class="info-box">
                    <span class="label">${IS_FA ? 'تاریخ تولد' : 'Birth Date'}</span>
                    <div class="value">${figure.birth}</div>
                </div>
                <div class="info-box">
                    <span class="label">${IS_FA ? 'محل تولد' : 'Birth Place'}</span>
                    <div class="value">${figure.birthPlace}</div>
                </div>
                <div class="info-box">
                    <span class="label">${IS_FA ? 'تاریخ وفات' : 'Death Date'}</span>
                    <div class="value">${figure.death}</div>
                </div>
                <div class="info-box">
                    <span class="label">${IS_FA ? 'محل وفات' : 'Death Place'}</span>
                    <div class="value">${figure.deathPlace}</div>
                </div>
            </div>

            <div class="profile-quote-mobile">
                <p><i class="fas fa-quote-right"></i> ${figure.quote.replace(/\n/g, '<br>')}</p>
            </div>

            <div class="bio-card-mobile">
                <div class="bio-title"><i class="fas fa-scroll"></i> ${IS_FA ? 'زندگینامه' : 'Biography'}</div>
                <p><strong>${figure.fullName}</strong></p>
                <p>${figure.biography}</p>
            </div>

            <div class="profile-tags-mobile">
                ${figure.categories.map(cat => `
                    <span class="tag"><i class="fas fa-tag"></i> ${cat}</span>
                `).join(' ')}
            </div>

            <!-- محصولات مرتبط — موبایل -->
            <div id="m_celebrityProducts" class="mt-3 px-3" style="display:none;"></div>

        </div>
    `;

    container.innerHTML = desktopHTML + mobileHTML;

    // بارگذاری محصولات مرتبط با این مشهور
    loadAndRenderProducts(figure.id, 'd_celebrityProducts', 'm_celebrityProducts');

    // ─── ثبت در تاریخچه ───────────────────────────────────
    if (typeof window.mashahirTrackHistory === 'function') {
        window.mashahirTrackHistory({
            id:    figure.id,
            name:  figure.name,
            image: figure.img,
        });
    }

    // ─── دکمه علاقه‌مندی ─────────────────────────────────
    _renderFavBtn(figure);
}

function _renderFavBtn(figure) {
    var existingBtn = document.getElementById('profileFavBtn');
    if (existingBtn) existingBtn.remove();

    var btn = document.createElement('button');
    btn.id = 'profileFavBtn';
    btn.className = 'profile-fav-btn';
    btn.innerHTML = '<i class="far fa-heart"></i>';

    // وضعیت اولیه رو از API بگیر
    if (typeof window.mashahirIsFavorite === 'function') {
        window.mashahirIsFavorite(figure.id, function(isFav) {
            _updateFavBtnState(btn, isFav);
        });
    }

    btn.addEventListener('click', function() {
        if (typeof window.mashahirToggleFavorite !== 'function') return;
        btn.disabled = true;
        window.mashahirToggleFavorite({ id: figure.id, name: figure.name, image: figure.img }, function(added) {
            btn.disabled = false;
            _updateFavBtnState(btn, added);
        });
    });

    var cardShadow = document.querySelector('#profileContent .card-shadow');
    if (cardShadow) {
        cardShadow.style.position = 'relative';
        cardShadow.appendChild(btn);
    }
}

function _updateFavBtnState(btn, isFav) {
    btn.className = 'profile-fav-btn' + (isFav ? ' active' : '');
    btn.querySelector('i').className = (isFav ? 'fas' : 'far') + ' fa-heart';
    btn.title = IS_FA
        ? (isFav ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها')
        : (isFav ? 'Remove from favorites'  : 'Add to favorites');
}

function renderCards(data) {
    const container = document.getElementById('cardsContainer');
    if (!container) return;
    
    container.innerHTML = '';

    if (data.length === 0) {
        container.innerHTML = `
            <div class="no-results">
                <i class="fas fa-search"></i>
                <h4>${IS_FA ? 'هیچ مشاهیری یافت نشد' : 'No celebrities found'}</h4>
                <p>${IS_FA ? 'لطفاً فیلترهای خود را تغییر دهید یا جستجوی دیگری انجام دهید.' : 'Please change your filters or try a different search.'}</p>
            </div>
        `;
        return;
    }

    data.forEach(figure => {
        const imgPath = figure.img || '../public/img/placeholder.png';
        const name = figure.name || '';
        const fullName = figure.fullName || '';
        const shortStory = figure.shortStory || '';
        const birth = figure.birth || '';
        const death = figure.death || '';
        const categories = figure.categories || [];
        
        const card = document.createElement('div');
        card.innerHTML = `
            <div class="prof-card" onclick="showProfile(${figure.id})" style="position:relative;">
                <button class="card-fav-btn" id="cardfav-${figure.id}"
                        onclick="event.stopPropagation(); toggleCardFav(${figure.id}, '${(name||'').replace(/'/g,"\\'")}', '${(imgPath||'').replace(/'/g,"\\'")}', this)"
                        title="${IS_FA ? 'افزودن به علاقه‌مندی‌ها' : 'Add to favorites'}">
                    <i class="far fa-heart"></i>
                </button>
                <img src="${imgPath}" class="card-img-top" alt="${name}" 
                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22220%22%3E%3Crect width=%22400%22 height=%22220%22 fill=%22%23d6c9bd%22/%3E%3Ctext x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22 dy=%22.3em%22 fill=%22%237b6250%22 font-size=%2224%22 font-family=%22sans-serif%22%3E${name}%3C/text%3E%3C/svg%3E'">
                <div class="card-body">
                    <h5 class="card-title">${name}</h5>
                    <div class="card-subtitle">${fullName}</div>
                    <p class="card-text">${shortStory ? shortStory.substring(0, 60) + '...' : ''}</p>
                    <div class="card-date">
                        <i class="far fa-calendar-alt"></i> ${birth} - ${death}
                    </div>
                    <div>
                        ${categories.slice(0, 3).map(cat => `<span class="badge-tag">${cat}</span>`).join(' ')}
                        ${categories.length > 3 ? `<span class="badge-tag">+${categories.length - 3}</span>` : ''}
                    </div>
                </div>
                <div class="card-overlay">
                    <span><i class="fas fa-eye me-2"></i> ${IS_FA ? 'مشاهده پروفایل' : 'View Profile'}</span>
                </div>
            </div>
        `;
        container.appendChild(card);
    });

    data.forEach(figure => {
        const card = document.createElement('div');
        card.className = 'celebrity-card';
        card.onclick = function () { showProfile(figure.id); };
        card.innerHTML = `
            <img src="${figure.img}" class="avatar" alt="${figure.name}">
            <div class="info">
                <div class="name">${figure.name}</div>
                <div class="title">${figure.fullName}</div>
                <div class="date"><i class="far fa-calendar-alt me-1"></i> ${figure.birth} - ${figure.death}</div>
            </div>
            <button class="view-btn" onclick="event.stopPropagation(); showProfile(${figure.id})">
                <i class="fas fa-eye me-1"></i> ${IS_FA ? 'مشاهده' : 'View'}
            </button>
        `;
        container.appendChild(card);
    });

    // بعد از رندر کارت‌ها، فیلترها را تراز کن
    setTimeout(alignFilters, 50);

    // بارگذاری وضعیت قلب‌ها از API
    if (typeof window.mashahirIsFavorite === 'function') {
        data.forEach(function(figure) {
            window.mashahirIsFavorite(figure.id, function(isFav) {
                var btn = document.getElementById('cardfav-' + figure.id);
                if (!btn) return;
                if (isFav) {
                    btn.classList.add('active');
                    btn.querySelector('i').className = 'fas fa-heart';
                    btn.title = IS_FA ? 'حذف از علاقه‌مندی‌ها' : 'Remove from favorites';
                }
            });
        });
    }
}

function toggleCardFav(id, name, image, btn) {
    if (typeof window.mashahirToggleFavorite !== 'function') return;
    btn.disabled = true;
    window.mashahirToggleFavorite({ id: id, name: name, image: image }, function(added) {
        btn.disabled = false;
        btn.classList.toggle('active', added);
        btn.querySelector('i').className = (added ? 'fas' : 'far') + ' fa-heart';
        btn.title = IS_FA
            ? (added ? 'حذف از علاقه‌مندی‌ها' : 'افزودن به علاقه‌مندی‌ها')
            : (added ? 'Remove from favorites'  : 'Add to favorites');
        // پروفایل‌باز هم sync بشه
        var profBtn = document.getElementById('profileFavBtn');
        if (profBtn) {
            profBtn.classList.toggle('active', added);
            profBtn.querySelector('i').className = (added ? 'fas' : 'far') + ' fa-heart';
        }
    });
}

function getSelectedFilters() {
    const checked = document.querySelectorAll('.form-check-input:checked');
    const selected = [];
    checked.forEach(cb => {
        if (cb.value) selected.push(cb.value);
    });
    return selected;
}

function updateFilterCount() {
    const filterCount = document.getElementById('filterCount');
    if (!filterCount) return;
    const count = document.querySelectorAll('.form-check-input:checked').length;
    filterCount.textContent = count;
}

function filterCards() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;
    
    const searchQuery = searchInput.value.toLowerCase();
    const selectedFilters = getSelectedFilters();

    const filtered = celebritiesData.filter(figure => {
        const matchSearch =
            (figure.name || '').toLowerCase().includes(searchQuery) ||
            (figure.fullName || '').toLowerCase().includes(searchQuery) ||
            (figure.categories || []).some(c => c.toLowerCase().includes(searchQuery)) ||
            (figure.birthPlace || '').toLowerCase().includes(searchQuery);

        let matchFilters = true;
        if (selectedFilters.length > 0) {
            matchFilters = (figure.categories || []).some(cat => selectedFilters.includes(cat)) ||
                selectedFilters.includes(figure.era) ||
                selectedFilters.includes(figure.birthPlace);
        }

        return matchSearch && matchFilters;
    });

    renderCards(filtered);
    updateFilterCount();
}

function applyFilters() {
    filterCards();
    const dropdown = document.getElementById('filterDropdown');
    if (dropdown) dropdown.classList.remove('show');
}

function clearFilters() {
    document.querySelectorAll('.form-check-input').forEach(cb => cb.checked = false);
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.value = '';
    renderCards(celebritiesData);
    updateFilterCount();
    const dropdown = document.getElementById('filterDropdown');
    if (dropdown) dropdown.classList.remove('show');
}

function toggleFilter() {
    const dropdown = document.getElementById('filterDropdown');
    if (dropdown) dropdown.classList.toggle('show');
}

function showList() {
    const listPage = document.getElementById('listPage');
    const profilePage = document.getElementById('profilePage');
    const footerSection = document.getElementById('footerSection');
    
    if (profilePage) profilePage.style.display = 'none';
    if (listPage) listPage.style.display = 'block';
    if (footerSection) footerSection.style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ===== محصولات مرتبط با مشهور =====

function buildCelebrityProducts(products) {
    const lang  = IS_FA ? 'fa' : 'en';
    const base  = IS_FA ? '/Kavian/mashahir/persian/' : '/Kavian/mashahir/english/';
    const imgBase = '/Kavian/mashahir/public/img/';

    const cards = products.map(p => {
        const url   = `${base}product.php?slug=${encodeURIComponent(p.slug)}`;
        const img   = p.image ? `${imgBase}${p.image}` : '';
        const badge = p.isFeatured
            ? `<span class="cp-badge cp-badge-featured"><i class="fas fa-star"></i> ${IS_FA ? 'ویژه' : 'Featured'}</span>`
            : '';
        const priceHtml = p.displayPrice
            ? `${p.originalPrice ? `<span class="cp-price-old">${Number(p.originalPrice).toLocaleString()}</span>` : ''}
               <span class="cp-price-new">${Number(p.displayPrice).toLocaleString()}</span>
               <span class="cp-price-unit">${IS_FA ? 'تومان' : 'T'}</span>`
            : `<span class="cp-price-free">${IS_FA ? 'رایگان' : 'Free'}</span>`;

        return `
            <a href="${url}" class="cp-card">
                <div class="cp-img-wrap">
                    ${img ? `<img src="${img}" alt="${p.title}">` : `<i class="fas fa-book cp-img-placeholder"></i>`}
                    ${badge}
                </div>
                <div class="cp-body">
                    <div class="cp-title">${p.title}</div>
                    ${p.genre ? `<div class="cp-genre"><i class="fas fa-feather-alt"></i> ${p.genre}</div>` : ''}
                    <div class="cp-price">${priceHtml}</div>
                </div>
            </a>`;
    }).join('');

    return `
        <div class="celebrity-products-section">
            <div class="cp-header">
                <i class="fas fa-book-open me-2"></i>
                ${IS_FA ? 'آثار موجود در فروشگاه' : 'Available Works in Store'}
            </div>
            <div class="cp-grid">${cards}</div>
        </div>`;
}

function loadAndRenderProducts(celebrityId, desktopTarget, mobileTarget) {
    const lang = IS_FA ? 'fa' : 'en';
    const url  = `/Kavian/mashahir/api/get-celebrity-products.php?celebrity_id=${celebrityId}&lang=${lang}`;

    fetch(url)
        .then(r => r.json())
        .then(res => {
            if (!res.success || res.count === 0) return;
            const html = buildCelebrityProducts(res.data);
            const d = document.getElementById(desktopTarget);
            const m = document.getElementById(mobileTarget);
            if (d) { d.innerHTML = html; d.style.display = 'block'; }
            if (m) { m.innerHTML = html; m.style.display = 'block'; }
        })
        .catch(() => {});   // در صورت خطا بخش مخفی می‌ماند
}

// ===== LOAD DATA =====
document.addEventListener('DOMContentLoaded', function() {
    loadCelebrities();
    window.addEventListener('resize', alignFilters);
});

// ===== تراز فیلترها با اولین کارت =====
function alignFilters() {
    if (window.innerWidth < 992) return;

    const sidebar  = document.querySelector('.filters-sidebar');
    const firstCard = document.querySelector('.prof-card');
    if (!sidebar || !firstCard) return;

    // Reset قبل از محاسبه تا getBoundingClientRect دقیق باشد
    sidebar.style.paddingTop = '';

    // هر دو مقدار نسبت به viewport هستند — تفاوتشان همیشه دقیق است
    const cardTop    = firstCard.getBoundingClientRect().top;
    const sidebarTop = sidebar.getBoundingClientRect().top;
    const diff = cardTop - sidebarTop;

    if (diff > 1) {
        sidebar.style.paddingTop = diff + 'px';
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>