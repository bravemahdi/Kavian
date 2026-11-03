<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'میراث ایران'; ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/Kavian/public/images/logo.png">
    <link rel="stylesheet" href="/Kavian/public/css/style.css">
</head>
<body>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_isLoggedIn = !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;

$_current = basename($_SERVER['SCRIPT_NAME'] ?? '');
$_currentPath = $_SERVER['SCRIPT_NAME'] ?? '';
function navActive($file, $current) {
    return $file === $current ? ' active' : '';
}
function navActivePath($file, $currentPath) {
    // مسیر کامل رو چک می‌کنه تا dashboard/index.php با /Kavian/index.php یکی نشه
    return $currentPath === '/Kavian/' . $file ? ' active' : '';
}
?>

<header>
    <div class="logo">
        <div class="logo-text">
            <h2>میراث ایران</h2>
            <span class="logo-sub">فرهنگ، تاریخ و افتخار</span>
        </div>
        <img src="/Kavian/public/images/logo.png" alt="لوگو میراث ایران" id="logoImg">
    </div>

    <nav class="main-nav" id="mainNav">
        <a href="/Kavian/index.php" class="nav-link<?php echo navActivePath('index.php', $_currentPath); ?>">خانه</a>
        <a href="/Kavian/index.php#categories" class="nav-link" onclick="if(location.pathname==='/Kavian/index.php'||location.pathname==='/Kavian/'){event.preventDefault();document.getElementById('categories')?.scrollIntoView({behavior:'smooth'});}">دسته‌بندی‌ها</a>
        <a href="/Kavian/index.php#contact" class="nav-link" onclick="if(location.pathname==='/Kavian/index.php'||location.pathname==='/Kavian/'){event.preventDefault();document.getElementById('contact')?.scrollIntoView({behavior:'smooth'});}">تماس با ما</a>

        <?php if ($_isLoggedIn): ?>
            <a href="/Kavian/dashboard/index.php" class="nav-pill" title="پروفایل">پروفایل</a>
        <?php else: ?>
            <a href="/Kavian/login.php" class="nav-pill" title="ورود">ورود</a>
        <?php endif; ?>

        <div class="nav-extra">
            <div class="search-box">
                <button class="search-icon-btn" type="button" aria-label="جستجو">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
                <input type="text" placeholder="جستجو در دسته‌بندی‌ها...">
            </div>
            <a href="/Kavian/english/index.php" class="en-btn">EN</a>
        </div>
    </nav>

    <div class="header-actions">
        <a href="/Kavian/english/index.php" class="en-btn desktop-only">EN</a>
        <div class="search-box desktop-only">
            <button class="search-icon-btn" type="button" aria-label="جستجو">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
            <input type="text" placeholder="جستجو در دسته‌بندی‌ها...">
        </div>
        <button class="menu-toggle" id="menuToggle" aria-label="باز کردن منو">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
<div class="nav-backdrop" id="navBackdrop"></div>
