<?php
/**
 * auth.php — لایه احراز هویت و authorization داشبورد
 *
 * همه کنترل دسترسی از جدول role_permissions می‌آید.
 * هیچ نقشی hardcode نیست — حتی admin.
 *
 * کلید ویژه:  special:*  → دسترسی کامل به همه چیز
 */

// بارگذاری helper صفحات خطا
require_once __DIR__ . '/../../app/errors_helper.php';

// ── توابع پایه ────────────────────────────────────────────

function admin_require_login(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // ذخیره URL مقصد برای redirect بعد از لاگین
        $redirectUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
            . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
            . ($_SERVER['REQUEST_URI'] ?? '/Kavian/dashboard/');
        $GLOBALS['_error_login_redirect'] = $redirectUrl;
        abort(401);
    }

    // کاربر باید custom_role_id داشته باشد
    // اگر نداشت ولی role=admin بود (قبل از migration) اجازه می‌دهیم
    if (empty($_SESSION['user_custom_role_id'])) {
        if (($_SESSION['user_role'] ?? '') === 'admin') {
            // fallback: admin قدیمی — مجوز کامل در session تنظیم می‌شود
            $_SESSION['user_permissions'] = ['special:*'];
            $_SESSION['user_custom_role_id'] = -1; // sentinel
            $_SESSION['user_role_label'] = 'مدیر کل';
            $_SESSION['user_custom_role_label'] = 'مدیر کل';
            return;
        }
        // کاربر عادی که به داشبورد دسترسی ندارد
        abort(403);
    }
}

/**
 * آیا کاربر جاری دسترسی کامل (special:*) دارد؟
 */
function admin_is_super(): bool
{
    $perms = $_SESSION['user_permissions'] ?? [];
    return in_array('special:*', $perms, true);
}

/**
 * آیا کاربر جاری به یک permission key خاص دسترسی دارد؟
 * دارنده special:* همیشه true می‌گیرد.
 */
function admin_has_permission(string $key): bool
{
    $perms = $_SESSION['user_permissions'] ?? [];

    // دسترسی کامل
    if (in_array('special:*', $perms, true)) {
        return true;
    }
    // دسترسی مستقیم
    if (in_array($key, $perms, true)) {
        return true;
    }

    $parts = explode(':', $key);

    // سلسله‌مراتب بالا به پایین: section:celebrities → true اگه section:celebrities:X وجود داشت
    // مثال: james داره section:celebrities:products → پس section:celebrities هم true
    $prefix = $key . ':';
    foreach ($perms as $perm) {
        if (str_starts_with($perm, $prefix)) {
            return true;
        }
    }

    // سلسله‌مراتب پایین به بالا: section:celebrities:products → section:celebrities
    if (count($parts) >= 3) {
        $parent = $parts[0] . ':' . $parts[1];
        if (in_array($parent, $perms, true)) {
            return true;
        }
    }

    return false;
}

/**
 * لیست همه permission key های کاربر جاری.
 */
function admin_get_permissions(): array
{
    return $_SESSION['user_permissions'] ?? [];
}

// ── API guards ────────────────────────────────────────────

function admin_require_login_api(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    header('Content-Type: application/json; charset=utf-8');

    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        // 401 = نیاز به احراز هویت
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'برای دسترسی به این بخش باید وارد حساب کاربری خود شوید']);
        exit;
    }

    // fallback برای admin قدیمی (قبل از migration)
    if (empty($_SESSION['user_custom_role_id'])) {
        if (($_SESSION['user_role'] ?? '') === 'admin') {
            $_SESSION['user_permissions']    = ['special:*'];
            $_SESSION['user_custom_role_id'] = -1;
            $_SESSION['user_role_label']     = 'مدیر کل';
            $_SESSION['user_custom_role_label'] = 'مدیر کل';
            return;
        }
        // 403 = لاگین است ولی دسترسی ندارد
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'شما دسترسی لازم برای این بخش را ندارید']);
        exit;
    }
}

/**
 * Guard برای بخش‌هایی که نیاز به special:* دارند (مدیریت سایت).
 */
function admin_require_super_api(): void
{
    admin_require_login_api();
    if (!admin_is_super()) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'این بخش فقط برای مدیر کل قابل دسترسی است']);
        exit;
    }
}

/**
 * Guard برای API هایی که نیاز به یک permission خاص دارند.
 * مثال: admin_require_permission_api('section:celebrities:products');
 */
function admin_require_permission_api(string $key): void
{
    admin_require_login_api();
    if (!admin_has_permission($key)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'شما دسترسی لازم برای این بخش را ندارید']);
        exit;
    }
}

// ── بارگذاری مجوزها از DB ─────────────────────────────────

/**
 * مجوزهای نقش کاربر را از DB می‌خواند و در session ذخیره می‌کند.
 * باید هر بار که صفحه لود می‌شود صدا زده شود.
 *
 * @param PDO $pdo
 * @param int $roleId — مقدار custom_role_id از جدول users
 */
function admin_load_role_permissions(PDO $pdo, int $roleId): void
{
    $stmt = $pdo->prepare(
        "SELECT permission_key FROM role_permissions WHERE role_id = ?"
    );
    $stmt->execute([$roleId]);
    $_SESSION['user_permissions']    = array_column($stmt->fetchAll(), 'permission_key');
    $_SESSION['user_custom_role_id'] = $roleId;

    // label نقش برای نمایش در sidebar
    $roleStmt = $pdo->prepare("SELECT label FROM roles WHERE id = ?");
    $roleStmt->execute([$roleId]);
    $roleRow = $roleStmt->fetch();
    $_SESSION['user_role_label'] = $roleRow ? $roleRow['label'] : '';
}

// alias برای سازگاری با کدهای قدیمی
function admin_load_custom_permissions(PDO $pdo, int $roleId): void
{
    admin_load_role_permissions($pdo, $roleId);
    // پر کردن کلیدهای قدیمی session برای سازگاری
    $_SESSION['user_custom_role_label'] = $_SESSION['user_role_label'];
}

// ── جایگزین admin_require_full_admin_api (سازگاری) ────────
function admin_require_full_admin_api(): void
{
    admin_require_super_api();
}
