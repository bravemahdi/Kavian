<?php
// فایل برای بروزرسانی پروفایل کاربر

ob_start(); // جلوگیری از خروجی ناخواسته (warnings/notices) قبل از JSON

header('Content-Type: application/json; charset=utf-8');

// شروع session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// بررسی لاگین بودن کاربر
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    jsonResponse(['success' => false, 'message' => 'لطفاً ابتدا وارد حساب کاربری خود شوید'], 401);
}

$pdo = require __DIR__ . '/../config/config.php';

$user_id = (int) $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// تابع کمکی برای ارسال JSON و خروج
function jsonResponse(array $data, int $code = 200): void {
    ob_clean(); // پاک کردن هر خروجی ناخواسته قبل از JSON
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// بر اساس action عملیات مناسب را انجام می‌دهیم
switch ($action) {
    case 'update_profile':
        updateProfile($pdo, $user_id);
        break;

    case 'change_password':
        changePassword($pdo, $user_id);
        break;

    default:
        jsonResponse(['success' => false, 'message' => 'عملیات نامعتبر'], 400);
}

// تابع بروزرسانی پروفایل
function updateProfile($pdo, $user_id) {
    try {
        // دریافت و اعتبارسنجی داده‌ها
        $name   = trim($_POST['name']   ?? '');
        $family = trim($_POST['family'] ?? '');
        $email  = trim($_POST['email']  ?? '');
        $phone  = trim($_POST['phone']  ?? '');

        // اعتبارسنجی اولیه
        if (empty($name) || strlen($name) < 2) {
            jsonResponse(['success' => false, 'message' => 'نام باید حداقل ۲ کاراکتر داشته باشد']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            jsonResponse(['success' => false, 'message' => 'ایمیل معتبر نیست']);
        }

        // بررسی تکراری نبودن ایمیل (به جز ایمیل فعلی کاربر)
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);

        if ($stmt->fetch()) {
            jsonResponse(['success' => false, 'message' => 'این ایمیل قبلاً استفاده شده است']);
        }

        // اعتبارسنجی شماره تلفن (اگر وارد شده)
        if (!empty($phone) && !preg_match('/^09[0-9]{9}$/', $phone)) {
            jsonResponse(['success' => false, 'message' => 'شماره تلفن معتبر نیست. فرمت صحیح: 09xxxxxxxxx']);
        }

        // مدیریت آپلود آواتار
        $avatar = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar = handleAvatarUpload($user_id);
        }

        // آماده کردن کوئری بروزرسانی
        $updateFields = [];
        $params = [];

        $updateFields[] = "name = ?";
        $params[] = $name;

        $updateFields[] = "family = ?";
        $params[] = $family ?: null;

        $updateFields[] = "email = ?";
        $params[] = $email;

        $updateFields[] = "phone = ?";
        $params[] = $phone ?: null;

        if ($avatar) {
            $updateFields[] = "avatar = ?";
            $params[] = $avatar;
        }

        $updateFields[] = "updated_at = NOW()";

        // اجرای کوئری
        $query = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = ?";
        $params[] = $user_id;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // بروزرسانی session
        $_SESSION['user_name']   = $name;
        $_SESSION['user_family'] = $family;
        $_SESSION['user_email']  = $email;
        $_SESSION['user_phone']  = $phone;
        if ($avatar) {
            $_SESSION['user_avatar'] = $avatar;
        }

        jsonResponse(['success' => true, 'message' => 'پروفایل با موفقیت بروزرسانی شد']);

    } catch (Exception $e) {
        error_log('Profile update error: ' . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'خطا در بروزرسانی پروفایل'], 500);
    }
}

// تابع تغییر رمز عبور
function changePassword($pdo, $user_id) {
    try {
        $current_password = $_POST['current_password'] ?? '';
        $new_password     = $_POST['new_password']     ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // اعتبارسنجی داده‌ها
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            jsonResponse(['success' => false, 'message' => 'لطفاً همه فیلدها را پر کنید']);
        }

        if ($new_password !== $confirm_password) {
            jsonResponse(['success' => false, 'message' => 'رمز عبور جدید و تکرار آن مطابقت ندارند']);
        }

        if (strlen($new_password) < 6) {
            jsonResponse(['success' => false, 'message' => 'رمز عبور جدید باید حداقل ۶ کاراکتر باشد']);
        }

        // دریافت رمز عبور فعلی کاربر
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if (!$user) {
            jsonResponse(['success' => false, 'message' => 'کاربر پیدا نشد']);
        }

        // بررسی صحت رمز عبور فعلی
        if (!password_verify($current_password, $user['password'])) {
            jsonResponse(['success' => false, 'message' => 'رمز عبور فعلی اشتباه است']);
        }

        // بررسی اینکه رمز عبور جدید با قدیم متفاوت باشد
        if (password_verify($new_password, $user['password'])) {
            jsonResponse(['success' => false, 'message' => 'رمز عبور جدید نباید با رمز عبور فعلی یکسان باشد']);
        }

        // هش کردن رمز عبور جدید
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // بروزرسانی رمز عبور
        $stmt = $pdo->prepare("UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$hashed_password, $user_id]);

        jsonResponse(['success' => true, 'message' => 'رمز عبور با موفقیت تغییر کرد']);

    } catch (Exception $e) {
        error_log('Password change error: ' . $e->getMessage());
        jsonResponse(['success' => false, 'message' => 'خطا در تغییر رمز عبور'], 500);
    }
}

// تابع مدیریت آپلود آواتار
function handleAvatarUpload($user_id) {
    $uploadDir = __DIR__ . '/../../public/uploads/avatars/';

    // ایجاد دایرکتوری اگر وجود ندارد
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $file = $_FILES['avatar'];

    // بررسی خطاهای آپلود
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    // بررسی حجم فایل (حداکثر 2MB)
    $maxSize = 2 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        return null;
    }

    // بررسی نوع فایل
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        return null;
    }

    // تولید نام فایل
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename  = 'avatar_' . $user_id . '_' . time() . '.' . strtolower($extension);
    $filepath  = $uploadDir . $filename;

    // جابجایی فایل
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // پاک کردن آواتار قبلی اگر وجود دارد
        $oldFiles = glob($uploadDir . 'avatar_' . $user_id . '_*');
        foreach ($oldFiles as $oldFile) {
            if ($oldFile !== $filepath) {
                unlink($oldFile);
            }
        }
        return 'public/uploads/avatars/' . $filename;
    }

    return null;
}
