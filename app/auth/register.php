<?php

$pdo = require __DIR__ . '/../config/config.php';


// شروع Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// دریافت اطلاعات
$name = trim($_POST['name'] ?? '');
$family = trim($_POST['family'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm'] ?? '';


// آدرس بازگشت
$redirect = $_POST['redirect'] ?? '/Kavian/';


// جلوگیری از Redirect به سایت خارجی
if (
    empty($redirect) ||
    stripos($redirect, '/Kavian/') !== 0
) {
    $redirect = '/Kavian/';
}


/*
|--------------------------------------------------------------------------
| اعتبارسنجی اطلاعات
|--------------------------------------------------------------------------
*/

// بررسی رمز عبور
if ($password !== $confirm) {
    header(
        'Location: /Kavian/login.php?error=password_mismatch&redirect=' .
        urlencode($redirect)
    );
    exit;
}

// بررسی حداقل طول رمز عبور
if (strlen($password) < 6) {
    header(
        'Location: /Kavian/login.php?error=password_length&redirect=' .
        urlencode($redirect)
    );
    exit;
}

// بررسی ایمیل معتبر
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header(
        'Location: /Kavian/login.php?error=invalid_email&redirect=' .
        urlencode($redirect)
    );
    exit;
}

// بررسی نام کامل
if (strlen($name) < 2) {
    header(
        'Location: /Kavian/login.php?error=invalid_name&redirect=' .
        urlencode($redirect)
    );
    exit;
}


/*
|--------------------------------------------------------------------------
| بررسی ایمیل تکراری
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare(
    "SELECT id FROM users WHERE email = ?"
);

$stmt->execute([$email]);

if ($stmt->fetch()) {
    header(
        'Location: /Kavian/login.php?error=email_exists&redirect=' .
        urlencode($redirect)
    );
    exit;
}


/*
|--------------------------------------------------------------------------
| Hash کردن رمز
|--------------------------------------------------------------------------
*/

$hashed = password_hash(
    $password,
    PASSWORD_BCRYPT
);


/*
|--------------------------------------------------------------------------
| ایجاد کاربر جدید با تمام فیلدها
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
    INSERT INTO users 
    (name, family, email, password, phone, role, status, email_verified, created_at, updated_at)
    VALUES (?, ?, ?, ?, ?, 'user', 'active', 0, NOW(), NOW())
");

$stmt->execute([
    $name,
    $family ?: null,
    $email,
    $hashed,
    $phone ?: null
]);


/*
|--------------------------------------------------------------------------
| ورود خودکار بعد از ثبت نام
|--------------------------------------------------------------------------
*/

$user_id = $pdo->lastInsertId();

// تغییر Session ID برای امنیت
session_regenerate_id(true);

// ساخت Session
$_SESSION['logged_in']  = true;
$_SESSION['user_id']    = $user_id;
$_SESSION['user_name']  = $name;
$_SESSION['user_family'] = $family;
$_SESSION['user_email'] = $email;
$_SESSION['user_phone'] = $phone;
$_SESSION['user_role']  = 'user';


// برگشت به سایت قبلی
header(
    'Location: ' . $redirect
);

exit;