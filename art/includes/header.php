<?php require_once __DIR__ . '/config.php'; 
$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$dir = ($current_lang === 'fa') ? 'rtl' : 'ltr';
$request_uri = $_SERVER['REQUEST_URI'];
$current_file = basename(parse_url($request_uri, PHP_URL_PATH));
$current_query = isset($_GET['page']) ? $_GET['page'] : '';

if (empty($current_file) || $current_file === '') {
    $current_file = 'index.php';
}
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>" dir="<?= $dir ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    
    <link href="/Kavian/art/public/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script src="/Kavian/art/public/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="/Kavian/art/public/css/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/Kavian/art/public/css/all.min.css">
    
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top main-navbar">
    <div class="container">
        <img src="<?= IMG_PATH ?>logo.png" alt="Logo" class="logo-img">
        <a class="navbar-brand d-flex align-items-center gap-2" href="#">
            <div class="brand-title">
                <span class="main-title"><?= SITE_NAME ?></span>
                <span class="sub-title"><?= $current_lang === 'fa' ? 'میراث ، زیبایی و خلاقیت' : 'Culture, History & Pride' ?></span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" id="navHome" href="index.php"><?= t('home') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="navCelebrities" href="profile.php"><?= t('celebrities') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="navAbout" href="pages.php?page=about"><?= t('about') ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="navContact" href="pages.php?page=contact"><?= t('contact') ?></a>
                </li>
                <li class="nav-item" id="langSwitcher"></li>
            </ul>
            <div class="search-box-header position-relative">
                <div class="search-frame-wrapper">
                    <img src="<?= IMG_PATH ?>search.png" alt="Search Frame" class="search-frame-img">
                    <input type="text" id="searchInput" placeholder="<?= t('search') ?>" onkeyup="filterCards()">
                </div>
            </div>

            <div class="d-flex gap-2 align-items-center" id="authButtons">
                <button class="btn btn-outline-light px-3" onclick="openLoginModal()"><?= t('login') ?></button>
                <button class="btn btn-accent px-3" onclick="openRegisterModal()"><?= t('signup') ?></button>
                <a href="#" class="cart-btn">
                    <i class="bi bi-cart3"></i>
                    <span class="cart-badge">5</span>
                </a>
                <button class="menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </button>
            </div>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"><?= t('quick_access') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="menu-vertical">
            <li><a href="#" class="active"><i class="bi bi-house-door"></i> <?= t('dashboard') ?></a></li>
            <li><a href="#"><i class="bi bi-book"></i> <?= t('my_library') ?> <span class="badge-soft">12</span></a></li>
            <li><a href="#"><i class="bi bi-heart"></i> <?= t('favorites') ?> <span class="badge-soft danger">8</span></a></li>
            <li><a href="#"><i class="bi bi-clock-history"></i> <?= t('history') ?></a></li>
            <li><a href="#"><i class="bi bi-tags"></i> <?= t('categories') ?></a></li>
            <li><a href="#"><i class="bi bi-gear"></i> <?= t('settings') ?></a></li>
            <li><a href="#"><i class="bi bi-question-circle"></i> <?= t('help') ?></a></li>
        </ul>
    </div>
</div>

<script>
(function() {
    var path = window.location.pathname;
    var search = window.location.search;
    var fullUrl = path + search;
    
    var navHome = document.getElementById('navHome');
    var navCelebrities = document.getElementById('navCelebrities');
    var navAbout = document.getElementById('navAbout');
    var navContact = document.getElementById('navContact');

    document.querySelectorAll('.navbar-nav .nav-link').forEach(function(el) {
        el.classList.remove('active');
    });
    
    if (fullUrl.indexOf('index.php') !== -1 || path === '/' || path === '/Kavian/mashahir/' || path === '/Kavian/mashahir/persian/' || path === '/Kavian/mashahir/english/') {
        if (navHome) navHome.classList.add('active');
    }
    else if (fullUrl.indexOf('profile.php') !== -1) {
        if (navCelebrities) navCelebrities.classList.add('active');
    }

    else if (fullUrl.indexOf('page=about') !== -1) {
        if (navAbout) navAbout.classList.add('active');
    }

    else if (fullUrl.indexOf('page=contact') !== -1) {
        if (navContact) navContact.classList.add('active');
    }
    
    var hasActive = document.querySelectorAll('.navbar-nav .nav-link.active').length > 0;
    if (!hasActive) {
        if (navHome) navHome.classList.add('active');
    }
})();
</script>