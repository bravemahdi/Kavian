<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_isLoggedIn = !empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miras-e Iran | Home</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="/Kavian/public/images/logo.png">
    <link rel="stylesheet" href="/Kavian/public/css/style.css">
    <style>
        .hero-mirrored { transform: scaleX(-1); }
    </style>
</head>
<body>

<header>
    <div class="logo">
        <div class="logo-text">
            <h2>Miras-e Iran</h2>
            <span class="logo-sub">Culture, History &amp; Pride</span>
        </div>
        <img src="/Kavian/public/images/logo.png" alt="Miras-e Iran logo" id="logoImg">
    </div>

    <nav class="main-nav" id="mainNav">
        <a href="/Kavian/english/index.php" class="nav-link active">Home</a>
        <a href="/Kavian/english/index.php#categories" class="nav-link">Categories</a>
        <a href="/Kavian/english/index.php#contact" class="nav-link">Contact Us</a>

        <?php if ($_isLoggedIn): ?>
            <a href="/Kavian/dashboard/index.php" class="nav-pill" title="Profile">Profile</a>
        <?php else: ?>
            <a href="/Kavian/login.php" class="nav-pill" title="Login">Login</a>
        <?php endif; ?>

        <div class="nav-extra">
            <div class="search-box">
                <button class="search-icon-btn" type="button" aria-label="Search">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                </button>
                <input type="text" placeholder="Search categories...">
            </div>
            <a href="/Kavian/index.php" class="en-btn">FA</a>
        </div>
    </nav>

    <div class="header-actions">
        <a href="/Kavian/index.php" class="en-btn desktop-only">FA</a>
        <div class="search-box desktop-only">
            <button class="search-icon-btn" type="button" aria-label="Search">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </button>
            <input type="text" placeholder="Search categories...">
        </div>
        <button class="menu-toggle" id="menuToggle" aria-label="Open menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
<div class="nav-backdrop" id="navBackdrop"></div>

<section class="hero hero-mirrored" style="transform: scaleX(-1)!important;">
    <picture>
        <source media="(max-width: 600px)" srcset="/Kavian/public/images/hero-en-mobile.jpg">
        <img src="/Kavian/public/images/hero-en.jpg" alt="Persian Gulf - Miras-e Iran">
    </picture>
</section>

<section class="intro-section">
    <p class="intro-tag"><span class="diamond">&#10022;</span> The Treasury of Iran's Culture, History &amp; Pride <span class="diamond">&#10022;</span></p>
    <h1 class="intro-title">Miras-e Iran</h1>
    <p class="intro-subtitle">Art, Sports, Exports, Technology &amp; Iranian Celebrities</p>
    <p class="intro-desc">
        A journey through the celebrities, art, sports, exports and technology of a land that carries thousands of years of history and pride.
    </p>
    <a href="#categories" class="intro-btn">Explore Categories <span>&#8594;</span></a>
    <div class="intro-dots">
        <span></span><span></span><span class="active"></span><span></span>
    </div>
</section>

<section class="categories" id="categories">

    <a class="card" href="/kavian/art/english">
        <img class="icon" src="/Kavian/public/images/art.png" alt="Art">
        <span class="card-label">Art</span>
    </a>

    <a class="card" href="#">
        <img class="icon" src="/Kavian/public/images/sport.png" alt="Sports">
        <span class="card-label">Sports</span>
    </a>

    <a class="card" href="#">
        <img class="icon" src="/Kavian/public/images/tec.png" alt="Technology">
        <span class="card-label">Technology</span>
    </a>

    <a class="card" href="#">
        <img class="icon" src="/Kavian/public/images/saderat.png" alt="Exports">
        <span class="card-label">Exports</span>
    </a>

    <a class="card" href="/kavian/mashahir/english">
        <img class="icon" src="/Kavian/public/images/mashhir.png" alt="Celebrities">
        <span class="card-label">Celebrities</span>
    </a>

    <a class="card" href="#">
        <img class="icon" src="/Kavian/public/images/amaken.png" alt="Landmarks">
        <span class="card-label">Landmarks</span>
    </a>

</section>

<footer id="contact">
    <div class="footer-main">
        <div class="footer-right">
            <h2>Miras-e Iran</h2>
            <p>Guardian of Iran's ancient heritage</p>
        </div>
        <div class="footer-left">
            <ul>
                <li><a href="/Kavian/english/index.php">Home</a></li>
                <li><a href="/Kavian/login.php">Login</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-contact">
            <h3>Contact Us</h3>
            <p>futureindustry@gmail.com</p>
            <p>051-12345678</p>
        </div>
        <div class="footer-copy">
            <p>&copy; 1405 All rights reserved</p>
        </div>
    </div>
</footer>

<script src="/Kavian/public/js/script.js"></script>
</body>
</html>
