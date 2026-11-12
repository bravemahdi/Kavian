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
                        <i class="fas fa-sliders-h"></i>
                        <?= t('filter') ?>
                    </div>

                    <div class="filter-group" id="catFilterGroup">
                        <span class="group-label">
                            <i class="fas fa-tags me-1"></i>
                            <?= $is_fa ? 'دسته‌بندی مکان' : 'Place Category' ?>
                        </span>
                        <div id="catFilterItems">
                            <div class="filter-loading"><i class="fas fa-spinner fa-spin"></i></div>
                        </div>
                    </div>

                    <div class="filter-group">
                        <span class="group-label">
                            <i class="fas fa-history me-1"></i>
                            <?= $is_fa ? 'دوره تاریخی' : 'Historical Era' ?>
                        </span>
                        <div id="eraFilterItems"></div>
                    </div>

                    <div class="filter-group">
                        <span class="group-label">
                            <i class="fas fa-map-marked-alt me-1"></i>
                            <?= $is_fa ? 'استان' : 'Province' ?>
                        </span>
                        <div id="provinceFilterItems"></div>
                    </div>

                    <div class="filter-group">
                        <span class="group-label">
                            <i class="fas fa-city me-1"></i>
                            <?= $is_fa ? 'شهر' : 'City' ?>
                        </span>
                        <div id="cityFilterItems"></div>
                    </div>

                    <div class="filter-actions">
                        <button class="btn-apply" onclick="applyFilters()">
                            <i class="fas fa-check me-1"></i>
                            <?= t('apply') ?>
                        </button>
                        <button class="btn-clear" onclick="clearFilters()">
                            <i class="fas fa-undo me-1"></i>
                            <?= $is_fa ? 'پاک کردن فیلترها' : 'Clear filters' ?>
                        </button>
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
                        <span class="group-label">
                            <i class="fas fa-tags me-1"></i>
                            <?= $is_fa ? 'دسته‌بندی مکان' : 'Place Category' ?>
                        </span>
                        <div id="mobCatFilterItems"></div>
                    </div>
                    <div class="filter-group">
                        <span class="group-label">
                            <i class="fas fa-history me-1"></i>
                            <?= $is_fa ? 'دوره تاریخی' : 'Historical Era' ?>
                        </span>
                        <div id="mobEraFilterItems"></div>
                    </div>
                    <div class="filter-group">
                        <span class="group-label">
                            <i class="fas fa-map-marked-alt me-1"></i>
                            <?= $is_fa ? 'استان' : 'Province' ?>
                        </span>
                        <div id="mobProvinceFilterItems"></div>
                    </div>
                    <div class="filter-group">
                        <span class="group-label">
                            <i class="fas fa-city me-1"></i>
                            <?= $is_fa ? 'شهر' : 'City' ?>
                        </span>
                        <div id="mobCityFilterItems"></div>
                    </div>
                    <div class="filter-actions">
                        <button class="btn-apply" onclick="applyFilters()">
                            <i class="fas fa-check me-1"></i>
                            <?= t('apply') ?>
                        </button>
                        <button class="btn-clear" onclick="clearFilters()">
                            <i class="fas fa-undo me-1"></i>
                            <?= $is_fa ? 'پاک کردن فیلترها' : 'Clear filters' ?>
                        </button>
                    </div>
                </div>

                <div id="cardsContainer">
                    <div class="filter-loading text-center py-5">
                        <i class="fas fa-spinner fa-spin"></i>
                        <?= $is_fa ? 'در حال دریافت اطلاعات مکان‌ها...' : 'Loading places...' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="profilePage" style="display:none;">
        <button class="btn-back" onclick="showList()">
            <i class="fas <?= $is_fa ? 'fa-arrow-right' : 'fa-arrow-left' ?>"></i>
            <?= $is_fa ? 'بازگشت به فهرست مکان‌ها' : 'Back to Places' ?>
        </button>
        <div id="profileContent"></div>
    </div>

    <div class="text-center mt-4 py-3 border-top border-light" id="footerSection">
        <p class="text-muted small">
            <i class="fas fa-chevron-right"></i>
            <?= SITE_NAME ?>
            <i class="fas fa-chevron-left"></i>
        </p>
    </div>
</div>

<div class="bottom-nav">
    <a href="#" class="bottom-nav-item" onclick="showList(); return false;">
        <img src="<?= IMG_PATH ?>icon-m.png" alt="Places" class="bottom-nav-icon">
        <span><?= $is_fa ? 'مکان‌ها' : 'Places' ?></span>
    </a>
    <a href="#catFilterGroup" class="bottom-nav-item" onclick="toggleFilter(); return false;">
        <img src="<?= IMG_PATH ?>icon2-m.png" alt="Categories" class="bottom-nav-icon">
        <span><?= $is_fa ? 'دسته‌بندی' : 'Categories' ?></span>
    </a>
    <a href="#cardsContainer" class="bottom-nav-item" onclick="showList(); return false;">
        <img src="<?= IMG_PATH ?>icon1-m.png" alt="Explore" class="bottom-nav-icon">
        <span><?= $is_fa ? 'اکتشاف' : 'Explore' ?></span>
    </a>
    <a href="#profilePage" class="bottom-nav-item">
        <img src="<?= IMG_PATH ?>icon3-m.png" alt="Details" class="bottom-nav-icon">
        <span><?= $is_fa ? 'جزئیات' : 'Details' ?></span>
    </a>
    <a href="#" class="bottom-nav-item active-btn" onclick="clearFilters(); return false;">
        <span><?= $is_fa ? 'نمایش همه' : 'Show All' ?></span>
        <img src="<?= IMG_PATH ?>icon5-m.png" alt="Show All" class="bottom-icon-btn">
    </a>
</div>

<script>
const IS_FA = <?= $is_fa ? 'true' : 'false' ?>;
const LANG = IS_FA ? 'fa' : 'en';

const PLACES_API = '/Kavian/places/api/get-places.php?lang=' + LANG;
const CATEGORIES_API = '/Kavian/places/api/get-categories.php?lang=' + LANG;

let placesData = [];
let categoryOptions = [];

const TEXT = {
    noPlaces: IS_FA ? 'هیچ مکانی یافت نشد' : 'No places found',
    tryAgain: IS_FA ? 'لطفاً فیلترها را تغییر دهید یا جستجوی دیگری انجام دهید.' : 'Please change your filters or try another search.',
    notFound: IS_FA ? 'مکان موردنظر پیدا نشد.' : 'Place not found.',
    info: IS_FA ? 'اطلاعات کلی' : 'General Information',
    established: IS_FA ? 'سال تأسیس / قدمت' : 'Established',
    province: IS_FA ? 'استان' : 'Province',
    city: IS_FA ? 'شهر' : 'City',
    address: IS_FA ? 'نشانی' : 'Address',
    era: IS_FA ? 'دوره تاریخی' : 'Historical Era',
    description: IS_FA ? 'معرفی مکان' : 'About This Place',
    history: IS_FA ? 'تاریخچه' : 'History',
    visitingHours: IS_FA ? 'ساعات بازدید' : 'Visiting Hours',
    bestSeason: IS_FA ? 'بهترین زمان بازدید' : 'Best Time to Visit',
    related: IS_FA ? 'اطلاعات تکمیلی' : 'Additional Information',
    view: IS_FA ? 'مشاهده جزئیات' : 'View Details',
    map: IS_FA ? 'مشاهده روی نقشه' : 'View on Map',
    categories: IS_FA ? 'دسته‌بندی‌ها' : 'Categories',
    quote: IS_FA ? 'نقل‌قول' : 'Quote',
    loadingError: IS_FA ? 'دریافت اطلاعات با خطا مواجه شد. لطفاً API اماکن را بررسی کنید.' : 'Could not load places. Please check the places API.'
};

function esc(value) {
    return String(value ?? '').replace(/[&<>"']/g, ch => ({
        '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#039;'
    }[ch]));
}

function normalizeArray(value) {
    if (Array.isArray(value)) return value.filter(Boolean);
    if (typeof value === 'string' && value.trim()) {
        return value.split(',').map(x => x.trim()).filter(Boolean);
    }
    return [];
}

function placeCategories(place) {
    return normalizeArray(place.categories || place.tags);
}


function placeImage(place) {
    const image = place.image || place.img;

    if (!image) {
        return '/Kavian/places/public/img/placeholder.png';
    }

    if (/^(https?:)?\/\//i.test(image) || image.startsWith('/')) {
        return image;
    }

    return '/Kavian/places/public/img/' + image.replace(/^\.?\//, '');
}


function getPlaceName(place) {
    return place.name || place.fullName || (IS_FA ? 'مکان بدون نام' : 'Unnamed place');
}

function loadPlaces() {
    fetch(PLACES_API)
        .then(response => {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(result => {
            if (!result.success || !Array.isArray(result.data)) {
                throw new Error(result.error || 'Invalid places response');
            }

            placesData = result.data;
            loadCategoryFilter();

            const params = new URLSearchParams(window.location.search);
            if (params.has('id')) {
                renderCards(placesData);
                const id = Number(params.get('id'));
                if (Number.isInteger(id)) showProfile(id);
                return;
            }

            if (params.has('search')) {
                const search = document.getElementById('searchInput');
                if (search) search.value = params.get('search');
            }

            renderCards(placesData);
            filterCards();
        })
        .catch(error => {
            console.error('Places API error:', error);
            const container = document.getElementById('cardsContainer');
            if (container) {
                container.innerHTML = `<div class="no-results">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4>${esc(TEXT.loadingError)}</h4>
                </div>`;
            }
        });
}

function loadCategoryFilter() {
    fetch(CATEGORIES_API)
        .then(response => {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(result => {
            let tags = [];
            if (Array.isArray(result.categories)) {
                result.categories.forEach(item => {
                    if (typeof item === 'string') tags.push(item);
                    else if (item && (item.name || item.name_fa || item.name_en)) {
                        tags.push(item.name || (IS_FA ? item.name_fa : item.name_en) || item.name_fa || item.name_en);
                    }
                });
            }
            if (Array.isArray(result.filter_tags)) tags = tags.concat(result.filter_tags);
            if (!tags.length) {
                placesData.forEach(place => tags.push(...placeCategories(place)));
            }
            categoryOptions = [...new Set(tags.map(x => String(x).trim()).filter(Boolean))];
            renderFilterGroup('catFilterItems', 'mobCatFilterItems', categoryOptions, 'category');
            buildDynamicFilters();
        })
        .catch(error => {
            console.warn('Categories API unavailable; deriving filters from places.', error);
            categoryOptions = [...new Set(placesData.flatMap(placeCategories))];
            renderFilterGroup('catFilterItems', 'mobCatFilterItems', categoryOptions, 'category');
            buildDynamicFilters();
        });
}

function uniqueValues(field) {
    return [...new Set(placesData.map(place => place[field]).filter(value => value && String(value).trim()).map(String))].sort((a,b) => a.localeCompare(b));
}

function buildDynamicFilters() {
    renderFilterGroup('eraFilterItems', 'mobEraFilterItems', uniqueValues('era'), 'era');
    renderFilterGroup('provinceFilterItems', 'mobProvinceFilterItems', uniqueValues('province'), 'province');
    renderFilterGroup('cityFilterItems', 'mobCityFilterItems', uniqueValues('city'), 'city');
}

function renderFilterGroup(desktopId, mobileId, values, type) {
    const render = (prefix) => values.map((value, index) => {
        const id = `${prefix}-${type}-${index}`;
        return `<div class="form-check">
            <input class="form-check-input place-filter" type="checkbox"
                data-filter-type="${esc(type)}" value="${esc(value)}" id="${id}"
                onchange="updateFilterCount()">
            <label class="form-check-label" for="${id}">${esc(value)}</label>
        </div>`;
    }).join('');

    const desktop = document.getElementById(desktopId);
    const mobile = document.getElementById(mobileId);
    if (desktop) desktop.innerHTML = render('d');
    if (mobile) mobile.innerHTML = render('m');
}

function selectedFilters() {
    const result = { category: [], era: [], province: [], city: [] };
    document.querySelectorAll('.place-filter:checked').forEach(input => {
        const type = input.dataset.filterType;
        if (result[type]) result[type].push(input.value);
    });
    // فیلترهای دسکتاپ و موبایل ممکن است هم‌زمان انتخاب شده باشند؛ مقدار تکراری حذف می‌شود.
    Object.keys(result).forEach(key => result[key] = [...new Set(result[key])]);
    return result;
}

function updateFilterCount() {
    const count = document.querySelectorAll('.place-filter:checked').length;
    const badge = document.getElementById('filterCount');
    if (badge) badge.textContent = count;
}

function filterCards() {
    const searchInput = document.getElementById('searchInput');
    const query = (searchInput?.value || '').trim().toLocaleLowerCase();
    const filters = selectedFilters();

    const filtered = placesData.filter(place => {
        const searchable = [
            place.name, place.fullName, place.province, place.city, place.address,
            place.era, place.shortStory, place.description, place.history,
            ...placeCategories(place)
        ].filter(Boolean).join(' ').toLocaleLowerCase();

        if (query && !searchable.includes(query)) return false;

        const categories = placeCategories(place);
        const categoryMatch = !filters.category.length || filters.category.some(value => categories.includes(value));
        const eraMatch = !filters.era.length || filters.era.includes(place.era);
        const provinceMatch = !filters.province.length || filters.province.includes(place.province);
        const cityMatch = !filters.city.length || filters.city.includes(place.city);

        return categoryMatch && eraMatch && provinceMatch && cityMatch;
    });

    renderCards(filtered);
    updateFilterCount();
    return filtered.length;
}

function applyFilters() {
    filterCards();
    document.getElementById('filterDropdown')?.classList.remove('show');
}

function clearFilters() {
    document.querySelectorAll('.place-filter').forEach(input => input.checked = false);
    const searchInput = document.getElementById('searchInput');
    if (searchInput) searchInput.value = '';
    renderCards(placesData);
    updateFilterCount();
    document.getElementById('filterDropdown')?.classList.remove('show');
}

function toggleFilter() {
    document.getElementById('filterDropdown')?.classList.toggle('show');
}

function renderCards(data) {
    const container = document.getElementById('cardsContainer');
    if (!container) return;

    container.innerHTML = '';
    if (!data.length) {
        container.innerHTML = `<div class="no-results">
            <i class="fas fa-map-marked-alt"></i>
            <h4>${esc(TEXT.noPlaces)}</h4>
            <p>${esc(TEXT.tryAgain)}</p>
        </div>`;
        return;
    }

    data.forEach(place => {
        const card = document.createElement('div');
        card.innerHTML = `
            <div class="prof-card" onclick="showProfile(${Number(place.id)})" style="position:relative;">
                <img src="${esc(placeImage(place))}" class="card-img-top" alt="${esc(getPlaceName(place))}"
                    onerror="this.onerror=null;this.src='/Kavian/places/public/img/placeholder.png'">
                <div class="card-body">
                    <h5 class="card-title">${esc(getPlaceName(place))}</h5>
                    <div class="card-subtitle">${esc([place.city, place.province].filter(Boolean).join('، '))}</div>
                    <p class="card-text">${esc(place.shortStory || place.description || '')}</p>
                    <div class="card-date">
                        <i class="fas fa-landmark"></i>
                        ${esc(place.era || place.established || '')}
                    </div>
                    <div>
                        ${placeCategories(place).slice(0,3).map(category => `<span class="badge-tag">${esc(category)}</span>`).join(' ')}
                    </div>
                </div>
                <div class="card-overlay">
                    <span><i class="fas fa-eye me-2"></i>${esc(TEXT.view)}</span>
                </div>
            </div>`;
        container.appendChild(card);
    });

    setTimeout(alignFilters, 50);
}

function showProfile(id) {
    const place = placesData.find(item => Number(item.id) === Number(id));
    if (!place) {
        alert(TEXT.notFound);
        return;
    }

    document.getElementById('listPage').style.display = 'none';
    document.getElementById('profilePage').style.display = 'block';
    document.getElementById('footerSection').style.display = 'none';
    document.getElementById('profilePage').scrollIntoView({ behavior: 'smooth', block: 'start' });

    const image = placeImage(place);
    const name = getPlaceName(place);
    const categories = placeCategories(place);
    const coordinatesAvailable = place.latitude !== null && place.latitude !== '' &&
        place.longitude !== null && place.longitude !== '';
    const mapUrl = coordinatesAvailable
        ? `https://www.google.com/maps?q=${encodeURIComponent(place.latitude + ',' + place.longitude)}`
        : '';

    const infoRows = [
        [TEXT.established, place.established || ''],
        [TEXT.era, place.era || ''],
        [TEXT.province, place.province || ''],
        [TEXT.city, place.city || ''],
        [TEXT.address, place.address || ''],
        [TEXT.visitingHours, place.visitingHours || ''],
        [TEXT.bestSeason, place.bestSeason || '']
    ].filter(row => row[1]);

    const infoHtml = infoRows.map(([label, value]) => `
        <div class="col-md-6">
            <span class="info-label">${esc(label)}</span><br>
            <span class="info-value">${esc(value)}</span>
        </div>`).join('');

    const description = place.description || place.shortStory || '';
    const history = place.history || '';

    const html = `
        <div class="profile-desktop">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card-shadow p-4 h-100">
                        <div class="profile-img-wrapper">
                            <img src="${esc(image)}" alt="${esc(name)}" class="img-fluid"
                                onerror="this.onerror=null;this.src='/Kavian/places/public/img/placeholder.png'">
                        </div>
                        <div class="mt-3">
                            <span class="top-badge"><i class="fas fa-map-marked-alt me-1"></i>${esc(TEXT.info)}</span>
                            <div class="poet-name mt-2">${esc(name)}</div>
                            <div class="profile-fullname">${esc([place.city, place.province].filter(Boolean).join('، '))}</div>
                        </div>
                        <div class="row g-2 mt-3">${infoHtml}</div>
                        <hr class="divider-light">
                        <div class="tag-group">
                            ${categories.map(category => `<span class="badge"><i class="fas fa-tag"></i> ${esc(category)}</span>`).join(' ')}
                        </div>
                        ${place.quote ? `<div class="verse-box mt-3"><i class="fas fa-quote-right"></i> ${esc(place.quote)}</div>` : ''}
                        ${mapUrl ? `<a class="btn-apply d-inline-block mt-3" href="${mapUrl}" target="_blank" rel="noopener"><i class="fas fa-map-marker-alt me-1"></i>${esc(TEXT.map)}</a>` : ''}
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card-shadow1 p-4 h-100">
                        <div class="profile-intro-card">
                            <div class="bio-title intro-title"><i class="fas fa-scroll"></i> ${esc(TEXT.description)}</div>
                            <p class="intro-text">${esc(place.shortStory || description)}</p>
                            ${description ? `<p class="intro-full">${esc(description).replace(/\n/g, '<br>')}</p>` : ''}
                        </div>

                        ${history ? `
                            <div class="mt-4">
                                <div class="bio-title"><i class="fas fa-landmark"></i> ${esc(TEXT.history)}</div>
                                <p class="intro-full">${esc(history).replace(/\n/g, '<br>')}</p>
                            </div>` : ''}

                        <div class="profile-footer-info">
                            ${place.era ? `<span class="info-tag"><i class="fas fa-history"></i> ${esc(place.era)}</span>` : ''}
                            ${place.city ? `<span class="info-tag"><i class="fas fa-city"></i> ${esc(place.city)}</span>` : ''}
                            ${place.province ? `<span class="info-tag"><i class="fas fa-map"></i> ${esc(place.province)}</span>` : ''}
                        </div>

                        <div class="bio-timeline">
                            ${place.established ? `
                                <div class="bio-item">
                                    <div class="bio-icon"><i class="fas fa-calendar-alt"></i></div>
                                    <div class="bio-content">
                                        <h5>${esc(TEXT.established)}</h5>
                                        <p>${esc(place.established)}</p>
                                    </div>
                                </div>` : ''}
                            ${place.visitingHours ? `
                                <div class="bio-item">
                                    <div class="bio-icon"><i class="fas fa-clock"></i></div>
                                    <div class="bio-content">
                                        <h5>${esc(TEXT.visitingHours)}</h5>
                                        <p>${esc(place.visitingHours)}</p>
                                    </div>
                                </div>` : ''}
                            ${place.bestSeason ? `
                                <div class="bio-item">
                                    <div class="bio-icon"><i class="fas fa-sun"></i></div>
                                    <div class="bio-content">
                                        <h5>${esc(TEXT.bestSeason)}</h5>
                                        <p>${esc(place.bestSeason)}</p>
                                    </div>
                                </div>` : ''}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-mobile">
            <div class="profile-header-mobile">
                <img src="${esc(image)}" alt="${esc(name)}"
                    onerror="this.onerror=null;this.src='/Kavian/places/public/img/placeholder.png'">
                <div class="profile-overlay">
                    <h1>${esc(name)}</h1>
                    <div class="subtitle">${esc([place.city, place.province].filter(Boolean).join('، '))}</div>
                    ${place.established ? `<div class="date-badge"><i class="fas fa-landmark me-1"></i>${esc(place.established)}</div>` : ''}
                </div>
            </div>

            <div class="profile-job-card">
                <p><i class="fas fa-map-marked-alt"></i> <strong>${esc(place.era || (IS_FA ? 'مکان گردشگری' : 'Tourist Attraction'))}</strong></p>
            </div>

            <div class="stats-grid-mobile">
                <div class="stat-item-mobile">
                    <i class="fas fa-map stat-icon"></i>
                    <div class="stat-label">${esc(TEXT.province)}</div>
                    <div class="stat-value">${esc(place.province || '-')}</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-city stat-icon"></i>
                    <div class="stat-label">${esc(TEXT.city)}</div>
                    <div class="stat-value">${esc(place.city || '-')}</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-history stat-icon"></i>
                    <div class="stat-label">${esc(TEXT.era)}</div>
                    <div class="stat-value">${esc(place.era || '-')}</div>
                </div>
                <div class="stat-item-mobile">
                    <i class="fas fa-tags stat-icon"></i>
                    <div class="stat-label">${esc(TEXT.categories)}</div>
                    <div class="stat-value">${esc(categories[0] || '-')}</div>
                </div>
            </div>

            <div class="profile-info-grid-mobile">
                ${infoRows.map(([label, value]) => `<div class="info-box"><span class="label">${esc(label)}</span><div class="value">${esc(value)}</div></div>`).join('')}
            </div>

            ${place.quote ? `<div class="profile-quote-mobile"><p><i class="fas fa-quote-right"></i> ${esc(place.quote)}</p></div>` : ''}

            <div class="bio-card-mobile">
                <div class="bio-title"><i class="fas fa-scroll"></i> ${esc(TEXT.description)}</div>
                <p>${esc(description).replace(/\n/g, '<br>')}</p>
                ${history ? `<div class="bio-title mt-4"><i class="fas fa-landmark"></i> ${esc(TEXT.history)}</div><p>${esc(history).replace(/\n/g, '<br>')}</p>` : ''}
            </div>

            <div class="profile-tags-mobile">
                ${categories.map(category => `<span class="tag"><i class="fas fa-tag"></i> ${esc(category)}</span>`).join(' ')}
            </div>

            ${mapUrl ? `<div class="px-3 mt-3"><a class="btn-apply d-block text-center" href="${mapUrl}" target="_blank" rel="noopener"><i class="fas fa-map-marker-alt me-1"></i>${esc(TEXT.map)}</a></div>` : ''}
        </div>
    `;

    document.getElementById('profileContent').innerHTML = html;
    const url = new URL(window.location.href);
    url.searchParams.set('id', id);
    window.history.replaceState({}, '', url);
}

function showList() {
    document.getElementById('profilePage').style.display = 'none';
    document.getElementById('listPage').style.display = 'block';
    document.getElementById('footerSection').style.display = 'block';

    const url = new URL(window.location.href);
    url.searchParams.delete('id');
    window.history.replaceState({}, '', url);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function alignFilters() {
    if (window.innerWidth < 992) return;
    const sidebar = document.querySelector('.filters-sidebar');
    const firstCard = document.querySelector('.prof-card');
    if (!sidebar || !firstCard) return;

    sidebar.style.paddingTop = '';
    const diff = firstCard.getBoundingClientRect().top - sidebar.getBoundingClientRect().top;
    if (diff > 1) sidebar.style.paddingTop = diff + 'px';
}

document.addEventListener('DOMContentLoaded', () => {
    loadPlaces();
    window.addEventListener('resize', alignFilters);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
