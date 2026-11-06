<?php
require_once __DIR__ . '/config.php';

/*
|--------------------------------------------------------------------------
| بررسی وضعیت ورود کاربر
|--------------------------------------------------------------------------
|
| Session باید توسط سیستم اصلی Kavian ساخته شود.
| چون تمام زیرسایت‌ها روی همان localhost/Kavian هستند،
| از همان Session استفاده می‌کنیم.
|
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



$current_url = $_SERVER['REQUEST_URI'];
$redirect_url = urlencode($current_url);
/*
|--------------------------------------------------------------------------
| اطلاعات صفحه فعلی
|--------------------------------------------------------------------------
*/

$request_uri = $_SERVER['REQUEST_URI'];

$current_file = basename(
    parse_url($request_uri, PHP_URL_PATH)
);

$current_query = isset($_GET['page'])
    ? $_GET['page']
    : '';

if (empty($current_file) || $current_file === '') {
    $current_file = 'index.php';
}


/*
|--------------------------------------------------------------------------
| آدرس فعلی برای برگشت بعد از Login/Register
|--------------------------------------------------------------------------
*/

$current_url = $_SERVER['REQUEST_URI'];

$redirect_url = urlencode($current_url);


/*
|--------------------------------------------------------------------------
| بررسی Login
|--------------------------------------------------------------------------
*/

$is_logged_in = isset($_SESSION['user_id']);

$username = '';

if ($is_logged_in) {

    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
    } elseif (isset($_SESSION['user_name'])) {
        $username = $_SESSION['user_name'];
    } elseif (isset($_SESSION['name'])) {
        $username = $_SESSION['name'];
    }

}
?>

<!DOCTYPE html>

<html lang="<?= $current_lang ?>" dir="<?= $dir ?>">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= SITE_NAME ?></title>


    <!-- Bootstrap RTL -->

    <link
        rel="stylesheet"
        href="/Kavian/mashahir/public/css/bootstrap.rtl.min.css"
    >


    <!-- Bootstrap Bundle JS -->

    <script
        src="/Kavian/mashahir/public/js/bootstrap.bundle.min.js"
        defer
    ></script>


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="/Kavian/mashahir/public/css/bootstrap-icons.min.css"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="/Kavian/mashahir/public/css/all.min.css"
    >


    <!-- Custom Styles -->

    <link
        rel="stylesheet"
        href="style.css"
    >


    <style>

        @font-face {

            font-display: block;

            font-family: bootstrap-icons;

            src:
                url("/Kavian/mashahir/public/fonts/bootstrap-icons.woff2")
                format("woff2"),

                url("/Kavian/mashahir/public/fonts/bootstrap-icons.woff")
                format("woff");

        }

    </style>

</head>



<body>


<nav class="navbar navbar-expand-lg navbar-dark fixed-top main-navbar">

    <div class="container">


        <!-- Logo -->

        <a
            class="navbar-brand flex-shrink-0"
            href="index.php"
        >

            <img
                src="<?= IMG_PATH ?>logo1.png"
                alt="Logo"
                class="logo-img"
            >

            <div class="brand-title d-none d-lg-flex">

                <span class="main-title">
                    <?= SITE_NAME ?>
                </span>

                <span class="sub-title">

                    <?= $current_lang === 'fa'
                        ? 'فرهنگ، تاریخ و افتخار'
                        : 'Culture, History & Pride'
                    ?>

                </span>

            </div>

        </a>


        <!-- موبایل: عنوان -->

        <div
            class="d-flex d-lg-none flex-column align-items-center mobile-brand-title"
        >

            <span class="main-title">
                <?= SITE_NAME ?>
            </span>

            <span class="sub-title">

                <?= $current_lang === 'fa'
                    ? 'فرهنگ، تاریخ و افتخار'
                    : 'Culture, History & Pride'
                ?>

            </span>

        </div>


        <!-- موبایل: سبد + منو -->

        <div
            class="d-flex d-lg-none align-items-center gap-2 ms-auto mobile-quick-actions"
        >

            <a
                href="cart.php"
                class="cart-btn position-relative"
                id="cartBtnMobile"
            >

                <i class="bi bi-cart3"></i>

                <span class="cart-badge" id="cartBadgeMobile" style="display:none;">
                    0
                </span>

            </a>


            <button
                class="menu-btn"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasMenu"
            >

                <i class="bi bi-grid-3x3-gap-fill"></i>

            </button>

        </div>


        <!-- همبرگر -->

        <button
            class="navbar-toggler d-lg-none flex-shrink-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarContent"
            aria-expanded="false"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <!-- Navbar Content -->

        <div
            class="collapse navbar-collapse"
            id="navbarContent"
        >

            <div
                class="d-flex flex-column flex-lg-row w-100 align-items-lg-center justify-content-between"
            >


                <!-- لینک‌های منو -->

                <ul
                    class="navbar-nav <?= $is_fa ? 'me-lg-auto' : 'ms-lg-0 me-lg-0' ?> mb-2 mb-lg-0 flex-nowrap"
                >

                    <li class="nav-item">

                        <a
                            class="nav-link"
                            id="navHome"
                            href="index.php"
                        >
                            <?= t('home') ?>
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            id="navCelebrities"
                            href="profile.php"
                        >
                            <?= t('celebrities') ?>
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            id="navProducts"
                            href="products.php"
                        >
                            <?= t('products') ?>
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            id="navAbout"
                            href="pages.php?page=about"
                        >
                            <?= t('about') ?>
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-link"
                            id="navContact"
                            href="pages.php?page=contact"
                        >
                            <?= t('contact') ?>
                        </a>

                    </li>


                    <li
                        class="nav-item"
                        id="langSwitcher"
                    ></li>

                </ul>


                <!-- سرچ + احراز هویت -->

                <div
                    class="d-flex flex-column flex-lg-row align-items-center gap-3 w-100 w-lg-auto"
                >


                    <!-- Search -->

                    <div
                        class="search-box-header position-relative flex-shrink-1"
                    >

                        <div class="search-frame-wrapper">
                            <img src="<?= IMG_PATH ?>search.png" alt="Search Frame" class="search-frame-img">
                            <input type="text" id="searchInput"
                                   placeholder="<?= t('search') ?>"
                                   onkeyup="handleSearchKeyup(event)"
                                   autocomplete="off"
                                   role="combobox"
                                   aria-autocomplete="list"
                                   aria-expanded="false"
                                   aria-controls="searchDropdown">
                        </div>
                        <div id="searchDropdown" class="search-dropdown" role="listbox" aria-label="<?= $is_fa ? 'پیشنهادات جستجو' : 'Search suggestions' ?>"></div>
                    </div>


                    <!-- Authentication -->

                    <div
                        class="d-flex gap-2 align-items-center"
                        id="authButtons"
                    >


                        <?php if (!$is_logged_in): ?>


                            <!-- ورود -->

                            <a href="/Kavian/login.php?redirect=<?= $redirect_url ?>" class="btn btn-outline-light px-3">
    <?= t('login') ?>
</a>


                            <!-- ثبت نام -->

                            <a href="/Kavian/login.php?redirect=<?= $redirect_url ?>&tab=signup" class="btn btn-accent px-3">
    <?= t('signup') ?>
</a>


                        <?php else: ?>


                            <!-- کاربر وارد شده -->

                            <div
                                class="d-flex align-items-center gap-2"
                            >

                                <span
                                    class="text-white"
                                    style="white-space: nowrap;"
                                >

                                    <?= $current_lang === 'fa'
                                        ? 'خوش آمدید '
                                        : 'Welcome '
                                    ?>

                                    <strong>
                                        <?= htmlspecialchars($username) ?>
                                    </strong>

                                </span>


                                <!-- خروج -->

                                <a href="/Kavian/app/auth/logout.php?redirect=<?= $redirect_url ?>" class="btn btn-outline-light px-3">
    <?= $current_lang === 'fa' ? 'خروج' : 'Log out' ?>
</a>

                            </div>


                        <?php endif; ?>


                        <!-- دسکتاپ: سبد -->

                        <a
                            href="cart.php"
                            class="cart-btn position-relative d-none d-lg-inline-flex"
                            id="cartBtnDesktop"
                        >

                            <i class="bi bi-cart3"></i>

                            <span class="cart-badge" id="cartBadgeDesktop" style="display:none;">
                                0
                            </span>

                        </a>


                        <!-- دسکتاپ: منو -->

                        <button
                            class="menu-btn d-none d-lg-inline-flex"
                            type="button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasMenu"
                        >

                            <i class="bi bi-grid-3x3-gap-fill"></i>

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

</nav>



<!-- Offcanvas Menu -->

<div
    class="offcanvas offcanvas-start"
    tabindex="-1"
    id="offcanvasMenu"
>

    <div class="offcanvas-header">

        <h5 class="offcanvas-title">
            <?= t('quick_access') ?>
        </h5>

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="offcanvas"
        ></button>

    </div>


    <div class="offcanvas-body">

        <ul class="menu-vertical">

            <li>

                <a href="/Kavian/dashboard/index.php">

                    <i class="bi bi-house-door"></i>

                    <?= t('dashboard') ?>

                </a>

            </li>


            <li>

                <a href="/Kavian/dashboard/index.php#my-orders" id="offcanvasLibraryLink">

                    <i class="bi bi-book"></i>

                    <?= t('my_library') ?>

                    <span class="badge-soft" id="offcanvasLibraryBadge" style="display:none;">0</span>

                </a>

            </li>


            <li>

                <a href="#" id="offcanvasFavoritesLink">

                    <i class="bi bi-heart"></i>

                    <?= t('favorites') ?>

                    <span class="badge-soft danger" id="offcanvasFavoritesBadge" style="display:none;">0</span>

                </a>

            </li>


            <li>

                <a href="#" id="offcanvasHistoryLink">

                    <i class="bi bi-clock-history"></i>

                    <?= t('history') ?>

                    <span class="badge-soft" id="offcanvasHistoryBadge" style="display:none;">0</span>

                </a>

            </li>


            <li>

                <a href="#">

                    <i class="bi bi-tags"></i>

                    <?= t('categories') ?>

                </a>

            </li>

        </ul>

        <!-- پانل مشاهیر محبوب -->
        <div id="offcanvasFavoritesPanel" style="display:none;" class="offcanvas-subpanel">
            <div class="offcanvas-subpanel-header">
                <button class="offcanvas-subpanel-back" onclick="closeOffcanvasPanel('favorites')">
                    <i class="bi bi-arrow-<?= $is_fa ? 'right' : 'left' ?>"></i>
                </button>
                <span><?= t('favorites') ?></span>
            </div>
            <div id="offcanvasFavoritesList" class="offcanvas-subpanel-list"></div>
        </div>

        <!-- پانل تاریخچه -->
        <div id="offcanvasHistoryPanel" style="display:none;" class="offcanvas-subpanel">
            <div class="offcanvas-subpanel-header">
                <button class="offcanvas-subpanel-back" onclick="closeOffcanvasPanel('history')">
                    <i class="bi bi-arrow-<?= $is_fa ? 'right' : 'left' ?>"></i>
                </button>
                <span><?= t('history') ?></span>
            </div>
            <div id="offcanvasHistoryList" class="offcanvas-subpanel-list"></div>
        </div>

    </div>

</div>



<script>

(function() {

    var path = window.location.pathname;

    var search = window.location.search;

    var fullUrl = path + search;


    var navHome =
        document.getElementById('navHome');

    var navCelebrities =
        document.getElementById('navCelebrities');

    var navProducts =
        document.getElementById('navProducts');

    var navAbout =
        document.getElementById('navAbout');

    var navContact =
        document.getElementById('navContact');


    document
        .querySelectorAll('.navbar-nav .nav-link')
        .forEach(function(el) {

            el.classList.remove('active');

        });


    if (
        fullUrl.indexOf('index.php') !== -1 ||
        path === '/' ||
        path === '/Kavian/mashahir/' ||
        path === '/Kavian/mashahir/persian/' ||
        path === '/Kavian/mashahir/english/'
    ) {

        if (navHome) {
            navHome.classList.add('active');
        }

    }


    else if (
        fullUrl.indexOf('profile.php') !== -1
    ) {

        if (navCelebrities) {
            navCelebrities.classList.add('active');
        }

    }


    else if (
        fullUrl.indexOf('products.php') !== -1
    ) {

        if (navProducts) {
            navProducts.classList.add('active');
        }

    }


    else if (
        fullUrl.indexOf('page=about') !== -1
    ) {

        if (navAbout) {
            navAbout.classList.add('active');
        }

    }


    else if (
        fullUrl.indexOf('page=contact') !== -1
    ) {

        if (navContact) {
            navContact.classList.add('active');
        }

    }


    var hasActive =
        document.querySelectorAll(
            '.navbar-nav .nav-link.active'
        ).length > 0;


    if (!hasActive) {

        if (navHome) {
            navHome.classList.add('active');
        }

    }

})();
</script>

<script>
// متغیرهای سراسری برای search.js
window.SEARCH_CONFIG = {
    IS_FA:       <?= $is_fa ? 'true' : 'false' ?>,
    LANG:        '<?= $current_lang ?>',
    PROFILE_URL: '<?= $is_fa ? '/Kavian/mashahir/persian/profile.php' : '/Kavian/mashahir/english/profile.php' ?>',
    SEARCH_API:  '/Kavian/mashahir/api/search-celebrities.php',
    TEXT: {
        no_results:  '<?= $is_fa ? 'نتیجه‌ای یافت نشد' : 'No results found' ?>',
        more:        '<?= $is_fa ? 'مشاهده همه نتایج' : 'See all results' ?>',
        searching:   '<?= $is_fa ? 'در حال جستجو...' : 'Searching...' ?>',
    }
};
</script>
<script src="/Kavian/mashahir/public/js/search.js" defer></script>

<script>
// ─── badge سبد خرید ─────────────────────────────────────────
(function loadCartBadge() {
    fetch('/Kavian/mashahir/api/cart-api.php?action=count')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success && data.count > 0) {
                var badges = document.querySelectorAll('.cart-badge');
                badges.forEach(function(el) {
                    el.textContent = data.count;
                    el.style.display = 'flex';
                });
            }
        })
        .catch(function() { /* بی‌صدا نادیده گرفته می‌شود */ });
})();
</script>


<!-- مدیریت منوهای موبایل: فقط یک منو می‌تواند باز باشد -->
<script>
document.addEventListener('DOMContentLoaded', function() {

    var IS_FA = <?= $is_fa ? 'true' : 'false' ?>;
    var PROFILE_URL = '<?= $is_fa ? '/Kavian/mashahir/persian/profile.php' : '/Kavian/mashahir/english/profile.php' ?>';
    var LANG = IS_FA ? 'fa' : 'en';

    // ─── بستن navbar ───────────────────────────────────────
    function closeNavbar() {
        var el = document.getElementById('navbarContent');
        if (el && el.classList.contains('show')) {
            var tog = document.querySelector('.navbar-toggler[data-bs-target="#navbarContent"]');
            if (tog) tog.click();
        }
    }

    // ─── بستن offcanvas ────────────────────────────────────
    function closeOffcanvas() {
        var el = document.getElementById('offcanvasMenu');
        if (el && el.classList.contains('show')) {
            var btn = el.querySelector('.btn-close[data-bs-dismiss="offcanvas"]');
            if (btn) btn.click();
        }
    }

    // ─── باز/بستن subpanel ─────────────────────────────────
    window.closeOffcanvasPanel = function(name) {
        var panel = document.getElementById('offcanvas' + capitalize(name) + 'Panel');
        var menu  = document.querySelector('#offcanvasMenu .menu-vertical');
        if (panel) panel.style.display = 'none';
        if (menu)  menu.style.display  = '';
    };

    function openOffcanvasPanel(name) {
        var panel = document.getElementById('offcanvas' + capitalize(name) + 'Panel');
        var menu  = document.querySelector('#offcanvasMenu .menu-vertical');
        if (panel) panel.style.display = 'block';
        if (menu)  menu.style.display  = 'none';
    }

    function capitalize(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

    function escHtmlOC(str) {
        return String(str || '')
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ─── badge به‌روزرسانی ─────────────────────────────────
    function setBadge(id, count) {
        var badge = document.getElementById(id);
        if (!badge) return;
        if (count > 0) { badge.textContent = count; badge.style.display = ''; }
        else            { badge.style.display = 'none'; }
    }

    // ─── بارگذاری badge ها از API ─────────────────────────
    fetch('/Kavian/mashahir/api/user-stats.php')
        .then(function(r) { return r.json(); })
        .then(function(d) {
            if (!d.success || !d.logged_in) return;
            setBadge('offcanvasLibraryBadge',   d.library_count);
            setBadge('offcanvasFavoritesBadge', d.favorites_count);
            setBadge('offcanvasHistoryBadge',   d.history_count);
        })
        .catch(function() {});

    // ─── ثبت بازدید (تاریخچه) — از profile.php صدا میشه ──
    window.mashahirTrackHistory = function(celebrity) {
        var fd = new FormData();
        fd.append('action',       'add');
        fd.append('celebrity_id', celebrity.id);
        fetch('/Kavian/mashahir/api/history-api.php', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    // badge رو یک واحد بالا ببر (بدون re-fetch)
                    var badge = document.getElementById('offcanvasHistoryBadge');
                    if (badge) {
                        var cur = parseInt(badge.textContent) || 0;
                        setBadge('offcanvasHistoryBadge', cur + 1);
                    }
                }
            })
            .catch(function() {});
    };

    // ─── toggle علاقه‌مندی — از profile.php صدا میشه ──────
    window.mashahirToggleFavorite = function(celebrity, callback) {
        var fd = new FormData();
        fd.append('action',       'toggle');
        fd.append('celebrity_id', celebrity.id);
        fetch('/Kavian/mashahir/api/favorites-api.php', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    setBadge('offcanvasFavoritesBadge', d.count);
                    if (typeof callback === 'function') callback(d.added);
                }
            })
            .catch(function() {});
    };

    // ─── check favorite — از profile.php صدا میشه ─────────
    window.mashahirIsFavorite = function(id, callback) {
        fetch('/Kavian/mashahir/api/favorites-api.php?action=check&celebrity_id=' + id)
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (typeof callback === 'function') callback(d.is_favorite || false);
            })
            .catch(function() { if (typeof callback === 'function') callback(false); });
    };

    // ─── رندر لیست مشاهیر محبوب ──────────────────────────
    function renderFavoritesList() {
        var container = document.getElementById('offcanvasFavoritesList');
        if (!container) return;
        container.innerHTML = '<div class="offcanvas-subpanel-loading"><div class="spinner-border spinner-border-sm text-success"></div></div>';

        fetch('/Kavian/mashahir/api/favorites-api.php?action=list&lang=' + LANG)
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (!d.success || d.data.length === 0) {
                    container.innerHTML = '<div class="offcanvas-subpanel-empty">' +
                        '<i class="bi bi-heart"></i>' +
                        '<p>' + (IS_FA ? 'هنوز مشهوری اضافه نکرده‌اید.' : 'No favorites yet.') + '</p>' +
                        '</div>';
                    return;
                }
                container.innerHTML = d.data.map(function(c) {
                    return '<a class="offcanvas-subpanel-item" href="' + PROFILE_URL + '?id=' + c.id + '">' +
                        '<img src="' + escHtmlOC(c.image) + '" onerror="this.src=\'/Kavian/mashahir/public/img/s.png\'">' +
                        '<span>' + escHtmlOC(c.name) + '</span>' +
                        '<button class="offcanvas-fav-remove" onclick="removeFavorite(event,' + c.id + ')" title="' + (IS_FA ? 'حذف' : 'Remove') + '">' +
                            '<i class="bi bi-x"></i>' +
                        '</button>' +
                    '</a>';
                }).join('');
            })
            .catch(function() {
                container.innerHTML = '<div class="offcanvas-subpanel-empty"><p>' +
                    (IS_FA ? 'خطا در بارگذاری.' : 'Failed to load.') + '</p></div>';
            });
    }

    // ─── رندر تاریخچه ─────────────────────────────────────
    function renderHistoryList() {
        var container = document.getElementById('offcanvasHistoryList');
        if (!container) return;
        container.innerHTML = '<div class="offcanvas-subpanel-loading"><div class="spinner-border spinner-border-sm text-success"></div></div>';

        fetch('/Kavian/mashahir/api/history-api.php?action=list&lang=' + LANG + '&limit=20')
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (!d.logged_in) {
                    container.innerHTML = '<div class="offcanvas-subpanel-empty">' +
                        '<i class="bi bi-person-lock"></i>' +
                        '<p>' + (IS_FA ? 'برای مشاهده تاریخچه وارد شوید.' : 'Login to see your history.') + '</p>' +
                        '</div>';
                    return;
                }
                if (!d.data || d.data.length === 0) {
                    container.innerHTML = '<div class="offcanvas-subpanel-empty">' +
                        '<i class="bi bi-clock-history"></i>' +
                        '<p>' + (IS_FA ? 'هنوز صفحه‌ای بازدید نکرده‌اید.' : 'No history yet.') + '</p>' +
                        '</div>';
                    return;
                }
                container.innerHTML = d.data.map(function(c) {
                    return '<a class="offcanvas-subpanel-item" href="' + PROFILE_URL + '?id=' + c.id + '">' +
                        '<img src="' + escHtmlOC(c.image) + '" onerror="this.src=\'/Kavian/mashahir/public/img/s.png\'">' +
                        '<span>' + escHtmlOC(c.name) + '</span>' +
                    '</a>';
                }).join('');
            })
            .catch(function() {
                container.innerHTML = '<div class="offcanvas-subpanel-empty"><p>' +
                    (IS_FA ? 'خطا در بارگذاری.' : 'Failed to load.') + '</p></div>';
            });
    }

    // ─── حذف از علاقه‌مندی‌ها ────────────────────────────
    window.removeFavorite = function(e, id) {
        e.preventDefault();
        e.stopPropagation();
        var fd = new FormData();
        fd.append('action', 'toggle');
        fd.append('celebrity_id', id);
        fetch('/Kavian/mashahir/api/favorites-api.php', { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                if (d.success) {
                    setBadge('offcanvasFavoritesBadge', d.count);
                    renderFavoritesList();
                }
            })
            .catch(function() {});
    };

    // ─── کلیک روی favorites ───────────────────────────────
    var favLink = document.getElementById('offcanvasFavoritesLink');
    if (favLink) {
        favLink.addEventListener('click', function(e) {
            e.preventDefault();
            renderFavoritesList();
            openOffcanvasPanel('favorites');
        });
    }

    // ─── کلیک روی history ────────────────────────────────
    var histLink = document.getElementById('offcanvasHistoryLink');
    if (histLink) {
        histLink.addEventListener('click', function(e) {
            e.preventDefault();
            renderHistoryList();
            openOffcanvasPanel('history');
        });
    }

    // ─── وقتی offcanvas باز می‌شود navbar را ببند ─────────
    var offcanvasEl = document.getElementById('offcanvasMenu');
    if (offcanvasEl) {
        offcanvasEl.addEventListener('show.bs.offcanvas', function() {
            closeNavbar();
            closeOffcanvasPanel('favorites');
            closeOffcanvasPanel('history');
        });
    }

    // ─── وقتی navbar باز می‌شود offcanvas را ببند ─────────
    var navbarContent = document.getElementById('navbarContent');
    if (navbarContent) {
        navbarContent.addEventListener('show.bs.collapse', function() {
            closeOffcanvas();
        });
    }

    // ─── لینک‌های ساده # در offcanvas ─────────────────────
    setTimeout(function() {
        // فقط لینک‌هایی که id مخصوص ندارند (نه favorites و نه history)
        var simpleLinks = document.querySelectorAll(
            '#offcanvasMenu .menu-vertical a[href="#"]:not(#offcanvasFavoritesLink):not(#offcanvasHistoryLink)'
        );
        simpleLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                closeNavbar();
                closeOffcanvas();
                setTimeout(function() { window.scrollTo({ top: 0, behavior: 'smooth' }); }, 200);
            });
        });
    }, 300);
});
</script>


