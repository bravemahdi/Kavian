<?php

define('SITE_NAME', 'Art');

$base_path = '/Kavian/art/';
define('IMG_PATH', $base_path . 'public/img/');

$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$dir = ($current_lang === 'fa') ? 'rtl' : 'ltr';
$is_fa = ($current_lang === 'fa');

$lang_texts = [
    'en' => [
        'home' => 'Home',
        'celebrities' => 'Celebrities',
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
        'celebrities' => 'هنر',
        'about' => 'درباره ما',
        'contact' => 'تماس با ما',
        'login' => 'ورود',
        'signup' => 'ثبت نام',
        'search' => 'جستجوی هنر...',
        'footer' => 'تمامی حقوق برای هنر های ایرانی محفوظ است.',
        'quick_access' => 'دسترسی سریع',
        'categories' => 'دسته‌بندی‌ها',
        'contact_us' => 'تماس با ما',
        'filter' => 'فیلترها',
        'apply' => 'اعمال فیلتر',
        'dashboard' => 'داشبورد',
        'my_library' => 'کتابخانه من',
        'favorites' => 'هنر های محبوب',
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
?>