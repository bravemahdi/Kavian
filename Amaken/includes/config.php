<?php

define('IMG_PATH', '/Kavian/mashahir/public/img/');

$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$dir = ($current_lang === 'fa') ? 'rtl' : 'ltr';
$is_fa = ($current_lang === 'fa');

define('SITE_NAME', $current_lang === 'fa' ? 'مشاهیر' : 'Mashahir');

$lang_texts = [
    'en' => [
        'home' => 'Home',
        'celebrities' => 'Celebrities',
        'products' => 'Products',
        'about' => 'About',
        'contact' => 'Contact',
        'login' => 'Login',
        'signup' => 'Sign Up',
        'search' => 'Search celebrities...',
        'footer' => 'All Rights Reserved for Iranian Celebrities.',
        'quick_access' => 'Quick Access',
        'categories' => 'Categories',
        'contact_us' => 'Contact Us',
        'filter' => 'Filters',
        'apply' => 'Apply Filters',
        'dashboard' => 'Dashboard',
        'my_library' => 'My Library',
        'favorites' => 'Favorite Celebrities',
        'history' => 'History',
        'settings' => 'Settings',
        'help' => 'Help'
    ],
    'fa' => [
        'home' => 'صفحه اصلی',
        'celebrities' => 'مشاهیر',
        'products' => 'محصولات',
        'about' => 'درباره ما',
        'contact' => 'تماس با ما',
        'login' => 'ورود',
        'signup' => 'ثبت نام',
        'search' => 'جستجوی مشاهیر...',
        'footer' => 'تمامی حقوق برای مشاهیر ایرانی محفوظ است.',
        'quick_access' => 'دسترسی سریع',
        'categories' => 'دسته‌بندی‌ها',
        'contact_us' => 'تماس با ما',
        'filter' => 'فیلترها',
        'apply' => 'اعمال فیلتر',
        'dashboard' => 'داشبورد',
        'my_library' => 'کتابخانه من',
        'favorites' => 'مشاهیر محبوب',
        'history' => 'تاریخچه',
        'settings' => 'تنظیمات',
        'help' => 'راهنما'
    ]
];

function t($key)
{
    global $current_lang, $lang_texts;
    return $lang_texts[$current_lang][$key] ?? $key;
}

// ===== Database Connection =====
$host = 'localhost';
$dbname = 'kavian';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>