<?php

$pdo = require __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email    = trim($_POST['email']    ?? '');
$password = $_POST['password']       ?? '';
$redirect = $_POST['redirect']       ?? '/Kavian/';

if (empty($redirect)) {
    $redirect = '/Kavian/';
}

// ── پیدا کردن کاربر به همراه custom_role_id ──────────────
$stmt = $pdo->prepare("
    SELECT id, name, email, role, password, custom_role_id
    FROM users
    WHERE email = ?
");
$stmt->execute([$email]);
$user = $stmt->fetch();

// ── Login موفق ────────────────────────────────────────────
if ($user && password_verify($password, $user['password'])) {

    session_regenerate_id(true);

    $_SESSION['logged_in']  = true;
    $_SESSION['user_id']    = (int) $user['id'];
    $_SESSION['user_name']  = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    // user_role را نگه می‌داریم فقط برای سازگاری با کدهای قدیمی
    $_SESSION['user_role']  = $user['role'];

    // ── بارگذاری مجوزها از DB ─────────────────────────────
    if (!empty($user['custom_role_id'])) {
        $permStmt = $pdo->prepare(
            "SELECT permission_key FROM role_permissions WHERE role_id = ?"
        );
        $permStmt->execute([$user['custom_role_id']]);
        $_SESSION['user_permissions']    = array_column($permStmt->fetchAll(), 'permission_key');
        $_SESSION['user_custom_role_id'] = (int) $user['custom_role_id'];

        $labelStmt = $pdo->prepare("SELECT label FROM roles WHERE id = ?");
        $labelStmt->execute([$user['custom_role_id']]);
        $labelRow = $labelStmt->fetch();
        $_SESSION['user_role_label']         = $labelRow ? $labelRow['label'] : '';
        $_SESSION['user_custom_role_label']  = $_SESSION['user_role_label'];
    } else {
        // کاربر هنوز نقشی ندارد (migration اجرا نشده)
        $_SESSION['user_permissions']    = [];
        $_SESSION['user_custom_role_id'] = null;
        $_SESSION['user_role_label']     = '';
        $_SESSION['user_custom_role_label'] = '';
    }

    // بروزرسانی زمان آخرین ورود
    $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")
        ->execute([$user['id']]);

    header('Location: ' . $redirect);
    exit;
}

// ── Login ناموفق ──────────────────────────────────────────
header(
    'Location: /Kavian/login.php?error=1&redirect=' .
    urlencode($redirect)
);
exit;
