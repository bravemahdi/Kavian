<div dir="rtl">

# 🏛️ Kavian — میراث ایران

> پورتال فرهنگی دوزبانه (فارسی / انگلیسی) برای معرفی میراث، هنر، ورزش، فناوری، صادرات و اماکن ایران.

---

## 📋 فهرست مطالب

- [نمای کلی](#-نمای-کلی)
- [وضعیت زیرسایت‌ها](#-وضعیت-زیرسایت‌ها)
- [ساختار پروژه](#-ساختار-پروژه)
- [تکنولوژی‌ها](#️-تکنولوژی‌ها)
- [دیتابیس](#️-دیتابیس)
- [سیستم احراز هویت](#-سیستم-احراز-هویت)
- [سیستم مجوزها (RBAC)](#-سیستم-مجوزها-rbac)
- [پنل مدیریت (داشبورد)](#-پنل-مدیریت-داشبورد)
- [API‌های داشبورد](#-apiهای-داشبورد)
- [زیرسایت مشاهیر](#-زیرسایت-مشاهیر)
- [صفحات خطا](#-صفحات-خطا)
- [راه‌اندازی محلی](#-راه‌اندازی-محلی)

---

## 🌐 نمای کلی

**Kavian** یک اپلیکیشن وب دوزبانه (فارسی / انگلیسی) است که با PHP، JavaScript خالص، MySQL و Bootstrap 5 ساخته شده. هدف آن ارائه یک مرجع فرهنگی جامع از میراث ایران در قالب یک پورتال اصلی و **۶ زیرسایت تخصصی** است.

</div>

| لایه | مسیر | توضیح |
|---|---|---|
| **پورتال** | `/Kavian/` | صفحه اصلی با احراز هویت و ناوبری دسته‌بندی‌ها |
| **مشاهیر** | `/Kavian/mashahir/` | ✅ مشاهیر ایرانی — کامل |
| **هنر** | `/Kavian/art/` | 🚧 هنر و فرهنگ ایران — در حال توسعه |
| **ورزش** | `/Kavian/sports/` | 🔲 ورزش ایران — برنامه‌ریزی شده |
| **فناوری** | `/Kavian/technology/` | 🔲 فناوری و نوآوری ایران — برنامه‌ریزی شده |
| **صادرات** | `/Kavian/exports/` | 🔲 صادرات ایران — برنامه‌ریزی شده |
| **اماکن** | `/Kavian/places/` | 🔲 اماکن تاریخی ایران — برنامه‌ریزی شده |

<div dir="rtl">

---

## 🗺️ وضعیت زیرسایت‌ها

### ✅ مشاهیر — `/mashahir/`
کامل‌ترین زیرسایت. دوزبانه (فارسی/انگلیسی) با ۸ صفحه در هر نسخه، فروشگاه کتاب، سبد خرید، پرداخت شبیه‌سازی‌شده، دانلود PDF، سیستم نظرات، علاقه‌مندی‌ها و تاریخچه بازدید. تمام داده‌ها DB-driven هستند.

### 🚧 هنر — `/art/`
layout فارسی کامل دارد (header، hero، filters، categories، reviews، footer). نسخه انگلیسی ساخته نشده. هنوز به دیتابیس وصل نیست.

### 🔲 ورزش / فناوری / صادرات / اماکن
فقط آیکون و کارت در صفحه اصلی. محتوا و صفحات هنوز ساخته نشده‌اند.

---

## 📁 ساختار پروژه

</div>

```
Kavian/
├── index.php                      ← صفحه اصلی پورتال (فارسی، RTL)
├── login.php                      ← صفحه ورود + ثبت‌نام (تب‌دار)
├── terms.php                      ← شرایط و ضوابط
├── kavian.sql                     ← اسکمای دیتابیس + داده اولیه
├── ERD.png                        ← دیاگرام روابط موجودیت‌ها
│
├── app/                           ← بک‌اند مشترک
│   ├── config/config.php          ← اتصال PDO (بازگشت $pdo)
│   ├── auth/
│   │   ├── login.php              ← احراز هویت + بارگذاری permissions
│   │   ├── register.php           ← ثبت‌نام + ورود خودکار
│   │   ├── logout.php             ← نابودی session + ریدایرکت
│   │   └── check_login.php        ← JSON: وضعیت session کاربر
│   ├── user/get_user.php          ← JSON: اطلاعات کاربر از DB
│   ├── profile/update.php         ← بروزرسانی پروفایل و تغییر رمز
│   └── errors_helper.php          ← تابع abort($code) مرکزی
│
├── views/                         ← layout مشترک پورتال
│   ├── header.php
│   ├── hero.php
│   └── footer.php
│
├── public/                        ← استاتیک‌های پورتال
│   ├── css/style.css
│   ├── js/script.js
│   ├── images/                    ← لوگو، آیکون‌ها، hero images
│   └── uploads/avatars/           ← آواتار کاربران (آپلود)
│
├── english/                       ← نسخه LTR پورتال اصلی
│   └── index.php
│
├── dashboard/                     ← پنل مدیریت یک‌پارچه (SPA)
│   ├── index.php                  ← SPA داشبورد (ادمین + کاربر)
│   ├── api/
│   │   ├── auth.php               ← توابع RBAC و guard های API
│   │   ├── sections_config.php    ← تنظیمات ۶ بخش + پنل‌های ثابت
│   │   ├── roles_api.php          ← CRUD نقش‌ها و مجوزها
│   │   ├── users_api.php          ← مدیریت کاربران + اختصاص نقش
│   │   ├── logs_api.php           ← مدیریت لاگ‌های سایت
│   │   ├── subsites_api.php       ← مدیریت زیرسایت‌ها
│   │   ├── orders_api.php         ← سفارش‌های کاربر
│   │   ├── mashahir_categories_api.php
│   │   ├── mashahir_celebrities_api.php
│   │   ├── mashahir_products_api.php
│   │   ├── mashahir_reviews_api.php
│   │   ├── mashahir_helpers.php
│   │   ├── generic_api.php
│   │   ├── roles_migration.sql    ← Migration نقش‌ها (v1)
│   │   └── roles_v2_migration.sql ← Migration نقش‌ها (v2)
│   ├── css/admin.css, profile.css
│   └── js/admin.js, profile.js
│
├── mashahir/                      ← ✅ زیرسایت مشاهیر (کامل)
│   ├── index.php                  ← روتر زبان (کوکی preferred_language)
│   ├── persian/                   ← نسخه RTL فارسی (۸ صفحه)
│   │   ├── index.php, profile.php, pages.php
│   │   ├── cart.php, checkout.php, payment-result.php
│   │   ├── product.php, products.php
│   │   └── script.js, style.css
│   ├── english/                   ← نسخه LTR انگلیسی (۸ صفحه)
│   ├── includes/                  ← کامپوننت‌های مشترک
│   │   ├── config.php             ← تشخیص زبان + تابع t()
│   │   ├── header.php, footer.php
│   │   ├── hero.php, filters.php
│   │   ├── categories.php
│   │   └── products.php, reviews.php
│   ├── api/                       ← ۲۰+ فایل API مشاهیر
│   ├── public/                    ← تصاویر و فونت‌های محلی
│   ├── sendmessage.php            ← ارسال ایمیل با PHPMailer
│   └── PHPMailer-master/          ← کتابخانه PHPMailer
│
├── art/                           ← 🚧 زیرسایت هنر
│   ├── persian/index.php          ← صفحه اصلی فارسی (layout کامل)
│   ├── includes/                  ← config, header, hero, filters, ...
│   └── public/                    ← استاتیک‌ها
│
├── errors/                        ← صفحات خطای سفارشی
│   ├── 400.php ... 504.php        ← ۹ صفحه خطا
│   └── nginx_errors.conf          ← پیکربندی Nginx
│
└── .htaccess                      ← پیکربندی Apache
```

<div dir="rtl">

---

## 🛠️ تکنولوژی‌ها

</div>

| لایه | تکنولوژی |
|---|---|
| سرور | PHP 8.2 |
| دیتابیس | MySQL / MariaDB (via PDO) |
| فریم‌ورک CSS | Bootstrap 5.3 (RTL-capable) |
| آیکون‌ها | Bootstrap Icons، Font Awesome 6 |
| فونت‌ها (پورتال) | Vazirmatn (Google Fonts) |
| فونت‌ها (مشاهیر) | ASuls، W_laleh، W_thulth1 (محلی) |
| کلاینت‌ساید | JavaScript خالص (بدون فریم‌ورک) |
| ایمیل | PHPMailer (فرم تماس مشاهیر) |
| وب‌سرور | Nginx / Apache |

<div dir="rtl">

---

## 🗄️ دیتابیس

نام دیتابیس: `kavian`

### جدول `users`

</div>

| ستون | نوع | توضیح |
|---|---|---|
| `id` | `INT` PK, AUTO_INCREMENT | |
| `name` | `VARCHAR(100)` | نام |
| `family` | `VARCHAR(100)` | نام خانوادگی |
| `email` | `VARCHAR(100)` UNIQUE | |
| `phone` | `VARCHAR(20)` | |
| `password` | `VARCHAR(255)` | هش bcrypt |
| `avatar` | `VARCHAR(255)` | مسیر نسبی آواتار |
| `role` | `VARCHAR(20)` | `'admin'` یا `'user'` (legacy) |
| `custom_role_id` | `INT` FK | ارجاع به جدول `roles` |
| `status` | `TINYINT` | وضعیت حساب |
| `email_verified` | `TINYINT` | تأیید ایمیل |
| `last_login` | `DATETIME` | آخرین ورود |
| `created_at` | `TIMESTAMP` | تاریخ ثبت‌نام |
| `updated_at` | `TIMESTAMP` | آخرین بروزرسانی |

<div dir="rtl">

### جدول `roles`

</div>

| ستون | نوع | توضیح |
|---|---|---|
| `id` | `INT` PK | |
| `name` | `VARCHAR(50)` | کلید نقش (مثل `admin`) |
| `label` | `VARCHAR(100)` | برچسب نمایشی |
| `description` | `TEXT` | توضیح |

<div dir="rtl">

### جدول `role_permissions`

</div>

| ستون | نوع | توضیح |
|---|---|---|
| `role_id` | `INT` FK | ارجاع به `roles.id` |
| `permission_key` | `VARCHAR(100)` | کلید مجوز (مثل `section:celebrities:products`) |

<div dir="rtl">

---

## 🔐 سیستم احراز هویت

همه لاجیک احراز هویت در `app/auth/` قرار دارد.

### جریان ورود

</div>

```
POST /app/auth/login.php
    ↓
SELECT users WHERE email = ?
    ↓
password_verify($input, $hash_bcrypt)
    ↓
موفق → session_regenerate_id(true)
      → SET SESSION: logged_in, user_id, user_name, user_permissions
      → UPDATE users SET last_login = NOW()
      → redirect به مقصد (یا /)
    ↓
ناموفق → redirect login.php?error=1
```

<div dir="rtl">

### جریان ثبت‌نام

</div>

```
POST /app/auth/register.php
    ↓
بررسی تطابق رمز عبور
    ↓
چک تکراری بودن ایمیل
    ↓
password_hash($password, PASSWORD_BCRYPT)
    ↓
INSERT users → ورود خودکار → redirect /
```

<div dir="rtl">

### Endpoint‌ها

</div>

| فایل | متد | عملکرد |
|---|---|---|
| `app/auth/login.php` | POST | احراز هویت با bcrypt |
| `app/auth/register.php` | POST | ثبت‌نام + ورود خودکار |
| `app/auth/logout.php` | GET | نابودی session |
| `app/auth/check_login.php` | GET | JSON: `{ logged_in, user }` |
| `app/user/get_user.php` | GET | JSON: `{ success, user }` |
| `app/profile/update.php` | POST | بروزرسانی پروفایل / تغییر رمز |

<div dir="rtl">

---

## 🎭 سیستم مجوزها (RBAC)

پروژه یک سیستم نقش‌بندی مبتنی بر دیتابیس (RBAC) کامل دارد. **هیچ نقشی در کد hardcode نیست** — همه چیز از جدول `role_permissions` می‌آید.

### ساختار permission keyها

</div>

```
special:*                          ← دسترسی کامل (مدیر کل)
special:users                      ← مدیریت کاربران
special:logs                       ← مدیریت لاگ‌ها
special:subsites                   ← مدیریت زیرسایت‌ها
special:roles                      ← مدیریت نقش‌ها
special:orders_global              ← مدیریت سفارشات کل

section:celebrities                ← همه بخش‌های مشاهیر
section:celebrities:categories     ← دسته‌بندی‌ها
section:celebrities:celebrities    ← مدیریت مشاهیر
section:celebrities:products       ← نمایش محصولات
section:celebrities:product_register ← ثبت محصول
section:celebrities:reviews        ← نظرات
section:celebrities:tickets        ← تیکت‌ها

section:art / section:sport / section:places
section:exports / section:technology   ← هر کدام با زیرمجموعه‌های
    :orders / :comments / :slides / :products / :tickets
```

<div dir="rtl">

### قوانین سلسله‌مراتب

- کلید `special:*` → دسترسی به همه چیز
- داشتن `section:celebrities` → دسترسی به همه زیرمجموعه‌هایش
- داشتن `section:celebrities:products` → `section:celebrities` هم true می‌شود

### توابع اصلی (`dashboard/api/auth.php`)

</div>

| تابع | عملکرد |
|---|---|
| `admin_has_permission($key)` | بررسی دسترسی با سلسله‌مراتب دوطرفه |
| `admin_is_super()` | آیا `special:*` دارد؟ |
| `admin_load_role_permissions($pdo, $roleId)` | بارگذاری مجوزها از DB به session |
| `admin_require_login_api()` | Guard: باید لاگین باشد |
| `admin_require_permission_api($key)` | Guard: باید مجوز خاص داشته باشد |
| `admin_require_super_api()` | Guard: باید `special:*` داشته باشد |

<div dir="rtl">

---

## 🖥️ پنل مدیریت (داشبورد)

`dashboard/index.php` یک **SPA یک‌پارچه** است که:
- برای **کاربران عادی**: تب‌های ویرایش پروفایل، تغییر رمز، سفارشات، مشاهیر محبوب، تاریخچه بازدید
- برای **مدیران**: sidebar کامل مدیریتی + پنل‌های مدیریت

### ساختار sidebar ادمین (۳ سطح)

</div>

```
📁 پروفایل من
    ├── ویرایش پروفایل
    ├── تغییر رمز عبور
    ├── سفارش‌های من
    ├── مشاهیر محبوب
    └── تاریخچه بازدید

⚙️ مدیریت
    ├── مدیریت سایت (special:*)
    │   ├── کاربران سایت
    │   ├── لاگ‌ها
    │   ├── زیرسایت‌ها
    │   ├── نقش‌ها
    │   └── سفارشات کلی
    │
    ├── مدیریت سایت مشاهیر
    │   ├── دسته‌بندی‌ها
    │   ├── مدیریت مشاهیر
    │   ├── محصولات (نمایش / ثبت)
    │   ├── نظرات
    │   └── تیکت‌ها
    │
    └── مدیریت سایت‌های دیگر (هنر، ورزش، ...)
        ├── سفارشات
        ├── کامنت‌ها
        ├── اسلایدها
        ├── محصولات
        └── تیکت‌ها
```

<div dir="rtl">

هر آیتم sidebar تنها در صورتی نمایش داده می‌شود که کاربر permission key مربوطه را داشته باشد (`can_see()`).

---

## 📡 API‌های داشبورد

</div>

| فایل | Guard | عملکرد |
|---|---|---|
| `roles_api.php` | `special:roles` | CRUD نقش‌ها، اختصاص به کاربر، schema مجوزها |
| `users_api.php` | `special:users` | لیست/ویرایش/حذف کاربران، اختصاص نقش |
| `logs_api.php` | `special:*` | خواندن و پاک‌کردن `site_logs` |
| `subsites_api.php` | `special:*` | CRUD زیرسایت‌ها |
| `orders_api.php` | login | سفارشات کاربر جاری |
| `mashahir_categories_api.php` | login | CRUD دسته‌بندی‌های مشاهیر |
| `mashahir_celebrities_api.php` | login | CRUD مشاهیر + دسته‌بندی‌ها |
| `mashahir_products_api.php` | login | CRUD کتاب‌ها (عنوان، ناشر، قیمت، موجودی، PDF) |
| `mashahir_reviews_api.php` | login | مدیریت نظرات (pending/approved/rejected) |

<div dir="rtl">

---

## ⭐ زیرسایت مشاهیر

### روتر زبان

`mashahir/index.php` کوکی `preferred_language` را می‌خواند:
- `en` ← `english/index.php`
- هر مقدار دیگر ← `persian/index.php` (پیش‌فرض)

### صفحات (هر نسخه)

</div>

| صفحه | عملکرد |
|---|---|
| `index.php` | صفحه اصلی با hero، فیلترها، دسته‌بندی‌ها، محصولات، نظرات |
| `profile.php` | مرور مشاهیر — لیست + جزئیات کامل |
| `products.php` | فروشگاه کتاب‌های مشاهیر |
| `product.php` | صفحه یک محصول + نظرات + Add to Cart |
| `cart.php` | سبد خرید |
| `checkout.php` | تسویه حساب |
| `payment-result.php` | نتیجه پرداخت |
| `pages.php` | صفحات استاتیک (درباره ما، تماس) |

<div dir="rtl">

### API‌های مشاهیر (`mashahir/api/`)

۲۰+ فایل شامل: `get-celebrities`، `get-products`، `cart`، `checkout`، `payment-simulate`، `download-pdf`، `favorites`، `history`، `search`، `submit-review`، `get-product-reviews`، `user-stats`، `sendmessage` و غیره.

### Includes مشترک

</div>

| فایل | عملکرد |
|---|---|
| `config.php` | تشخیص زبان از URI، متغیر `$dir`، تابع `t()` |
| `header.php` | Navbar Bootstrap 5 با جستجو، auth buttons، منوی offcanvas |
| `hero.php` | کاروسل خودگردان با چهره‌های برجسته |
| `categories.php` | گرید ۱۲ کارت دسته‌بندی |
| `filters.php` | فیلتربار تاشو با چک‌باکس و رنج قیمت |
| `products.php` | کاروسل کتاب‌های معرفی‌شده |
| `reviews.php` | کاروسل نظرات کاربران با ستاره و لایک |

<div dir="rtl">

---

## 🚨 صفحات خطا

صفحات خطای سفارشی در `errors/` با طراحی یکپارچه:

</div>

| کد | فایل | توضیح |
|---|---|---|
| 400 | `400.php` | درخواست نامعتبر |
| 401 | `401.php` | نیاز به ورود (لینک ریدایرکت بعد از لاگین) |
| 403 | `403.php` | دسترسی ممنوع |
| 404 | `404.php` | صفحه پیدا نشد |
| 429 | `429.php` | درخواست بیش از حد |
| 500 | `500.php` | خطای داخلی سرور |
| 502 | `502.php` | Bad Gateway |
| 503 | `503.php` | سرویس در دسترس نیست |
| 504 | `504.php` | Gateway Timeout |

<div dir="rtl">

تابع `abort(int $code)` در `app/errors_helper.php` از همه جای پروژه برای ریدایرکت به این صفحات استفاده می‌شود.

---

## 🚀 راه‌اندازی محلی

**پیش‌نیازها:** PHP 8.x، MySQL / MariaDB، Apache یا Nginx

</div>

```bash
# ۱. پروژه را زیر root وب‌سرور قرار بده
#    مثال: /srv/http/Kavian  یا  C:\wamp64\www\Kavian

# ۲. دیتابیس را ایمپورت کن
#    phpMyAdmin → دیتابیس kavian بساز → kavian.sql را ایمپورت کن

# ۳. اتصال DB را در app/config/config.php تأیید کن
$host     = 'localhost';
$dbname   = 'kavian';
$username = 'root';
$password = '';

# ۴. Migration نقش‌ها را اجرا کن (اختیاری — در اولین لود داشبورد خودکار اجرا می‌شود)
#    dashboard/api/roles_migration.sql
#    dashboard/api/roles_v2_migration.sql

# ۵. به پروژه دسترسی داشته باش
http://localhost/Kavian/               ← پورتال فارسی
http://localhost/Kavian/english/       ← پورتال انگلیسی
http://localhost/Kavian/mashahir/      ← ✅ مشاهیر (کامل)
http://localhost/Kavian/art/persian/   ← 🚧 هنر (در حال توسعه)
http://localhost/Kavian/dashboard/     ← پنل مدیریت
```

<div dir="rtl">

### مجوز پوشه آپلود

</div>

```bash
chmod 755 public/uploads/
chmod 755 public/uploads/avatars/
```

<div dir="rtl">

---

<div align="center">
  <sub>ساخته‌شده با ❤️ برای فرهنگ و میراث ایران · © ۱۴۰۵</sub>
</div>

</div>

---

## 👥 مشارکت‌کنندگان

<br />

<p align="center">افرادی که این پروژه را با هم ساختند.</p>

<br />

<table align="center">
  <tr>
    <td align="center" style="padding: 16px;">
      <a href="https://github.com/bootless64">
        <img src="https://github.com/bootless64.png?size=120" width="120" style="border-radius:50%" /><br /><br />
        <b>bootless64</b><br />
        <sub>🛠️ توسعه‌دهنده اصلی</sub>
      </a>
    </td>
    <td align="center" style="padding: 16px;">
      <a href="https://github.com/sajdavy-hub">
        <img src="https://github.com/sajdavy-hub.png?size=120" width="120" style="border-radius:50%" /><br /><br />
        <b>sajdavy-hub</b><br />
        <sub>🩹 توسعه‌دهنده</sub>
      </a>
    </td>
    <td align="center" style="padding: 16px;">
      <a href="https://github.com/niloufar-kashefi">
        <img src="https://github.com/niloufar-kashefi.png?size=120" width="120" style="border-radius:50%" /><br /><br />
        <b>niloufar-kashefi</b><br />
        <sub>👑 مالک پروژه</sub>
      </a>
    </td>
  </tr>
</table>

<br />
