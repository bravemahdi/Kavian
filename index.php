<?php
$pageTitle = 'میراث ایران | خانه';
include 'views/header.php';
?>

<section class="hero">
    <picture>
        <source media="(max-width: 600px)" srcset="/Kavian/public/images/iran-mobile.jpg">
        <img src="/Kavian/public/images/iran.png" alt="خلیج پارس - میراث ایران">
    </picture>
</section>

<section class="intro-section">
    <p class="intro-tag"><span class="diamond">&#10022;</span> گنجینه فرهنگ، تاریخ و افتخار ایران <span class="diamond">&#10022;</span></p>
    <h1 class="intro-title">میراث ایران</h1>
    <p class="intro-subtitle">هنر، ورزش، صادرات، فناوری و مشاهیر ایران</p>
    <p class="intro-desc">
        گذری بر مشاهیر، هنر، ورزش، صادرات و فناوری سرزمینی که هزاران سال تاریخ و افتخار در دل خود جای داده است.
    </p>
    <a href="#categories" class="intro-btn">کاوش در دسته‌بندی‌ها <span>&#8592;</span></a>
    <div class="intro-dots">
        <span></span><span></span><span class="active"></span><span></span>
    </div>
</section>

<section class="categories" id="categories">

    <a class="card" href="/Kavian/art/persian">
        <img class="icon" src="/Kavian/public/images/art.png" alt="هنر">
        <span class="card-label">هنر</span>
    </a>

    <a class="card" href="#">
        <img class="icon" src="/Kavian/public/images/sport.png" alt="ورزش">
        <span class="card-label">ورزش</span>
    </a>

    <a class="card" href="#">
        <img class="icon" src="/Kavian/public/images/tec.png" alt="فناوری">
        <span class="card-label">فناوری</span>
    </a>

    <a class="card" href="#">
        <img class="icon" src="/Kavian/public/images/saderat.png" alt="صادرات">
        <span class="card-label">صادرات</span>
    </a>

    <a class="card" href="/Kavian/mashahir">
        <img class="icon" src="/Kavian/public/images/mashhir.png" alt="مشاهیر">
        <span class="card-label">مشاهیر</span>
    </a>

    <a class="card" href="/Kavian/places">
        <img class="icon" src="/Kavian/public/images/amaken.png" alt="اماکن">
        <span class="card-label">اماکن</span>
    </a>

</section>

<?php include 'views/footer.php'; ?>
