<?php
/**
 * errors_helper.php
 * توابع مرکزی برای redirect به صفحات خطا
 *
 * استفاده:
 *   require_once '/path/to/app/errors_helper.php';
 *   abort(403);        // نمایش صفحه 403 و توقف اجرا
 *   abort(401);        // نمایش صفحه 401 (نیاز به لاگین) و توقف اجرا
 *   abort(404);        // نمایش صفحه 404 و توقف اجرا
 */

/**
 * HTTP status code را ست می‌کند و صفحه خطای مناسب را نمایش می‌دهد.
 *
 * @param int    $code    کد خطا (400, 401, 403, 404, 429, 500, 502, 503, 504)
 * @param string $message پیام اختیاری — اگر داده شود روی پیام پیش‌فرض صفحه override می‌شود
 */
function abort(int $code, string $message = ''): never
{
    // جلوگیری از ارسال output قبل از header
    if (ob_get_level()) {
        ob_end_clean();
    }

    $allowed = [400, 401, 403, 404, 429, 500, 502, 503, 504];
    if (!in_array($code, $allowed, true)) {
        $code = 500;
    }

    // مسیر مطلق صفحه خطا
    $errorFile = __DIR__ . '/../errors/' . $code . '.php';

    // ست کردن HTTP status code واقعی
    http_response_code($code);

    if (file_exists($errorFile)) {
        // اگر پیام سفارشی داده شده، در قالب متغیر به صفحه پاس می‌دهیم
        if ($message !== '') {
            $GLOBALS['_error_custom_message'] = $message;
        }
        include $errorFile;
    } else {
        // fallback ساده اگر فایل وجود نداشت
        echo "<h1>خطای $code</h1>";
        if ($message) {
            echo "<p>" . htmlspecialchars($message) . "</p>";
        }
    }

    exit;
}

/**
 * بررسی لاگین بودن کاربر — در صورت عدم لاگین به صفحه 401 هدایت می‌کند.
 * برای صفحات HTML (غیر API) استفاده می‌شود.
 *
 * @param string $redirectAfterLogin URL که بعد از لاگین به آن redirect شود (پیش‌فرض: صفحه جاری)
 */
function require_login(string $redirectAfterLogin = ''): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // ذخیره URL مقصد برای redirect بعد از لاگین
        if ($redirectAfterLogin === '') {
            $redirectAfterLogin = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
                . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
                . ($_SERVER['REQUEST_URI'] ?? '/');
        }
        // پاس دادن به صفحه 401 از طریق query string
        $GLOBALS['_error_login_redirect'] = $redirectAfterLogin;
        abort(401);
    }
}

/**
 * بررسی دسترسی کاربر — در صورت نداشتن دسترسی به صفحه 403 هدایت می‌کند.
 * باید بعد از require_login صدا زده شود.
 *
 * @param callable $permissionCheck تابعی که true/false برمی‌گرداند
 * @param string   $message         پیام اختیاری
 */
function require_permission(callable $permissionCheck, string $message = ''): void
{
    if (!$permissionCheck()) {
        abort(403, $message);
    }
}
