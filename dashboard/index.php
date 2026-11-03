<?php
// بارگذاری helper صفحات خطا (باید قبل از auth باشد)
require_once __DIR__ . '/../app/errors_helper.php';

// شروع session و بررسی لاگین بودن کاربر
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    // کاربر لاگین نیست — نمایش صفحه 401 با لینک بازگشت بعد از لاگین
    $GLOBALS['_error_login_redirect'] = '/Kavian/dashboard/index.php';
    abort(401);
}

// اتصال به دیتابیس
$pdo = require '../app/config/config.php';

// بارگذاری توابع auth (admin_has_permission و ...) — errors_helper قبلاً لود شده
require_once __DIR__ . '/api/auth.php';

// دریافت اطلاعات کاربر — شامل custom_role_id
$user_id = (int) $_SESSION['user_id'];
$stmt = $pdo->prepare("
    SELECT id, name, family, email, phone, avatar, role, custom_role_id, status, email_verified,
           DATE_FORMAT(created_at, '%Y/%m/%d') as join_date,
           DATE_FORMAT(last_login, '%Y/%m/%d %H:%i') as last_login_date
    FROM users
    WHERE id = ?
");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    // حساب کاربری در DB پیدا نشد — 401 با لینک لاگین
    abort(401);
}

// ── migration خودکار — در هر بار لود index.php ──────────
function ensure_base_roles(PDO $pdo): void
{
    static $done = false;
    if ($done) return;
    $done = true;

    $adminExists = $pdo->query("SELECT id FROM roles WHERE name='admin'")->fetch();
    if (!$adminExists) {
        $pdo->exec("INSERT INTO `roles` (`name`,`label`,`description`) VALUES ('admin','مدیر کل','دسترسی کامل به همه بخش‌های پنل')");
        $adminId = (int)$pdo->lastInsertId();
        $pdo->prepare("INSERT IGNORE INTO role_permissions (role_id,permission_key) VALUES (?,?)")
            ->execute([$adminId, 'special:*']);
    }
    $userExists = $pdo->query("SELECT id FROM roles WHERE name='user'")->fetch();
    if (!$userExists) {
        $pdo->exec("INSERT INTO `roles` (`name`,`label`,`description`) VALUES ('user','کاربر عادی','فقط پروفایل شخصی')");
    }
    // migrate کاربران قدیمی
    $pdo->exec("UPDATE users u JOIN roles r ON r.name='admin' SET u.custom_role_id=r.id WHERE u.role='admin' AND (u.custom_role_id IS NULL OR u.custom_role_id=0)");
    $pdo->exec("UPDATE users u JOIN roles r ON r.name='user' SET u.custom_role_id=r.id WHERE u.role IN ('user','support') AND (u.custom_role_id IS NULL OR u.custom_role_id=0)");
}

// ── بارگذاری مجوزها از DB (هر بار) ──────────────────────────
ensure_base_roles($pdo);

// اگه session هنوز sentinel -1 داره، custom_role_id واقعی از DB بخون
if (($_SESSION['user_custom_role_id'] ?? null) === -1) {
    $freshUser = $pdo->prepare("SELECT custom_role_id FROM users WHERE id=?");
    $freshUser->execute([$user_id]);
    $freshRow = $freshUser->fetch();
    if (!empty($freshRow['custom_role_id'])) {
        $user['custom_role_id'] = (int)$freshRow['custom_role_id'];
        $_SESSION['user_custom_role_id'] = null; // reset تا admin_load_role_permissions بره
    }
}
// تغییرات نقش فوری منعکس می‌شوند
if (!empty($user['custom_role_id'])) {
    admin_load_role_permissions($pdo, (int)$user['custom_role_id']);
} elseif ($user['role'] === 'admin') {
    // fallback برای admin قدیمی — قبل از اجرای migration
    $_SESSION['user_permissions']       = ['special:*'];
    $_SESSION['user_custom_role_id']    = -1;
    $_SESSION['user_role_label']        = 'مدیر کل';
    $_SESSION['user_custom_role_label'] = 'مدیر کل';
} else {
    $_SESSION['user_permissions']    = [];
    $_SESSION['user_custom_role_id'] = null;
    $_SESSION['user_role_label']     = '';
    $_SESSION['user_custom_role_label'] = '';
}

// بارگذاری تنظیمات پنل ادمین
$config   = require __DIR__ . '/api/sections_config.php';
$SECTIONS = $config['sections'];
$PANELS   = $config['panels'];

// ── تعیین سطح دسترسی — همه از permissions ───────────────────
$isSuper = admin_is_super();                          // دارنده special:*
$isAdmin = !empty($_SESSION['user_custom_role_id']);  // هر نقشی = به پنل دسترسی دارد

// label نقش برای نمایش در sidebar
$roleLabel      = htmlspecialchars($_SESSION['user_role_label'] ?? 'کاربر');
$roleBadgeClass = $isSuper ? 'role-admin' : ($isAdmin ? 'role-custom' : 'role-user');

$adminName = $user['name'];

// آواتار
$avatarPath = '';
if (!empty($user['avatar'])) {
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/Kavian/' . ltrim($user['avatar'], '/');
    $avatarPath = file_exists($fullPath) ? $user['avatar'] : '';
}

// ── helper: آیا یک بخش/لینک برای کاربر قابل نمایش است؟ ─────
// همه چیز از permission_key — هیچ نقش hardcode نیست
function can_see(string $key): bool
{
    return admin_has_permission($key);
}

$pageTitle = 'داشبورد | میراث ایران';
include '../views/header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="/Kavian/dashboard/css/profile.css">
<link rel="stylesheet" href="/Kavian/dashboard/css/admin.css">

<div class="combined-dashboard-wrapper">

    <?php if ($isAdmin): ?>
    <!-- ===== ADMIN SIDEBAR ===== -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <img src="/Kavian/public/images/logo.png" alt="لوگو">
            <h2>پنل مدیریت</h2>
        </div>

        <div class="admin-user-box">
            <span>خوش آمدید،</span>
            <strong><?= htmlspecialchars($adminName) ?></strong>
            <span class="admin-role-badge <?= $roleBadgeClass ?>">
                <?= $roleLabel ?>
            </span>
        </div>

        <nav class="admin-nav">

            <!-- ===== پروفایل من ===== -->
            <div class="nav-group open">
                <button class="nav-group-toggle" type="button">
                    <span>👤 پروفایل من</span>
                    <i class="chevron">▾</i>
                </button>
                <div class="nav-group-items">
                    <a href="#" class="nav-link active" id="sidebarProfileLink"    data-user-tab="profile">ویرایش پروفایل</a>
                    <a href="#" class="nav-link"         id="sidebarPasswordLink"  data-user-tab="change-password">تغییر رمز عبور</a>
                    <a href="#" class="nav-link"         id="sidebarOrdersLink"    data-user-tab="my-orders">سفارش‌های من</a>
                    <a href="#" class="nav-link"         id="sidebarFavoritesLink" data-user-tab="my-favorites">مشاهیر محبوب</a>
                    <a href="#" class="nav-link"         id="sidebarHistoryLink"   data-user-tab="my-history">تاریخچه بازدید</a>
                </div>
            </div>

            <!-- ===== مدیریت ===== -->
            <?php
            $hasAnySiteManage = can_see('special:*') || can_see('special:users') || can_see('special:logs') || can_see('special:subsites') || can_see('special:roles') || can_see('special:orders_global');
            $hasAnySection    = false;
            foreach ($SECTIONS as $slug => $sec) {
                if (can_see("section:$slug")) { $hasAnySection = true; break; }
            }
            if ($hasAnySiteManage || $hasAnySection):
            ?>
            <div class="nav-group">
                <button class="nav-group-toggle" type="button">
                    <span>⚙️ مدیریت</span>
                    <i class="chevron">▾</i>
                </button>
                <div class="nav-group-items">

                    <?php if ($hasAnySiteManage): ?>
                    <!-- ── مدیریت سایت ── -->
                    <div class="nav-subgroup">
                        <button class="nav-subgroup-toggle" type="button">
                            <span>مدیریت سایت</span>
                            <i class="chevron">▾</i>
                        </button>
                        <div class="nav-subgroup-items">
                            <?php if (can_see('special:users')): ?>
                            <a href="#" class="nav-link nav-link-deep" data-special="users">👥 کاربران سایت</a>
                            <?php endif; ?>
                            <?php if (can_see('special:logs')): ?>
                            <a href="#" class="nav-link nav-link-deep" data-special="logs">📋 مدیریت لاگ‌های سایت</a>
                            <?php endif; ?>
                            <?php if (can_see('special:subsites')): ?>
                            <a href="#" class="nav-link nav-link-deep" data-special="subsites">🌐 مدیریت سایت‌های زیرمجموعه</a>
                            <?php endif; ?>
                            <?php if (can_see('special:roles')): ?>
                            <a href="#" class="nav-link nav-link-deep" data-special="roles">🎭 مدیریت نقش‌ها</a>
                            <?php endif; ?>
                            <?php if (can_see('special:orders_global')): ?>
                            <a href="#" class="nav-link nav-link-deep" data-special="wip">🛒 مدیریت سفارش‌ها</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php foreach ($SECTIONS as $slug => $sec):
                        // اگر کاربر هیچ دسترسی‌ای به این section ندارد نشان نده
                        if (!can_see("section:$slug")) continue;
                    ?>
                    <div class="nav-subgroup">
                        <button class="nav-subgroup-toggle" type="button">
                            <span><?= htmlspecialchars($sec['title']) ?></span>
                            <i class="chevron">▾</i>
                        </button>
                        <div class="nav-subgroup-items">
                            <?php if ($slug === 'celebrities'): ?>

                                <?php if (can_see('section:celebrities:categories')): ?>
                                <a href="#" class="nav-link nav-link-deep" data-special="mashahir_categories">دسته‌بندی‌های مشاهیر</a>
                                <?php endif; ?>

                                <?php if (can_see('section:celebrities:celebrities')): ?>
                                <a href="#" class="nav-link nav-link-deep" data-special="mashahir_celebrities">مدیریت مشاهیر</a>
                                <?php endif; ?>

                                <?php if (can_see('section:celebrities:products') || can_see('section:celebrities:product_register')): ?>
                                <div class="nav-subgroup nav-subgroup-l3">
                                    <button class="nav-subgroup-toggle nav-subgroup-toggle-l3" type="button">
                                        <span>محصولات مشاهیر (کتاب‌ها)</span>
                                        <i class="chevron">▾</i>
                                    </button>
                                    <div class="nav-subgroup-items">
                                        <?php if (can_see('section:celebrities:products')): ?>
                                        <a href="#" class="nav-link nav-link-deeper" data-special="mashahir_products">نمایش همه محصولات</a>
                                        <?php endif; ?>
                                        <?php if (can_see('section:celebrities:product_register')): ?>
                                        <a href="#" class="nav-link nav-link-deeper" data-special="mashahir_product_register">ثبت محصول</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if (can_see('section:celebrities:reviews')): ?>
                                <a href="#" class="nav-link nav-link-deep" data-special="mashahir_reviews">نظرات مشاهیر</a>
                                <?php endif; ?>

                                <?php if (can_see('section:celebrities:tickets')): ?>
                                <a href="#" class="nav-link nav-link-deep" data-special="wip">🎫 مدیریت تیکت‌ها</a>
                                <?php endif; ?>

                            <?php else: ?>

                                <?php if (can_see("section:$slug:orders")): ?>
                                <a href="#" class="nav-link nav-link-deep" data-section="<?= $slug ?>" data-entity="orders">مدیریت سفارشات</a>
                                <?php endif; ?>

                                <?php if (can_see("section:$slug:comments")): ?>
                                <a href="#" class="nav-link nav-link-deep" data-section="<?= $slug ?>" data-entity="comments">مدیریت کامنت‌ها</a>
                                <?php endif; ?>

                                <?php if (can_see("section:$slug:slides")): ?>
                                <a href="#" class="nav-link nav-link-deep" data-section="<?= $slug ?>" data-entity="slides">مدیریت اسلایدها</a>
                                <?php endif; ?>

                                <?php if (can_see("section:$slug:products")): ?>
                                <div class="nav-subgroup nav-subgroup-l3">
                                    <button class="nav-subgroup-toggle nav-subgroup-toggle-l3" type="button">
                                        <span>مدیریت محصولات</span>
                                        <i class="chevron">▾</i>
                                    </button>
                                    <div class="nav-subgroup-items">
                                        <a href="#" class="nav-link nav-link-deeper" data-section="<?= $slug ?>" data-entity="products">نمایش همه محصولات</a>
                                        <a href="#" class="nav-link nav-link-deeper" data-special="wip">ثبت محصول</a>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <a href="#" class="nav-link nav-link-deep" data-section="<?= $slug ?>" data-entity="celebrities">
                                    <?= htmlspecialchars($sec['sub5_label']) ?>
                                </a>

                                <?php if (can_see("section:$slug:tickets")): ?>
                                <a href="#" class="nav-link nav-link-deep" data-special="wip">🎫 مدیریت تیکت‌ها</a>
                                <?php endif; ?>

                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>
            <?php endif; ?>

        </nav>

        <div class="admin-sidebar-footer">
            <a href="/Kavian/" class="back-to-site">↩ بازگشت به سایت</a>
            <a href="/Kavian/app/auth/logout.php?redirect=/Kavian/" class="admin-logout">خروج</a>
        </div>
    </aside>
    <?php else: ?>

    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-brand">
            <img src="/Kavian/public/images/logo.png" alt="لوگو">
            <h2>حساب کاربری</h2>
        </div>

        <div class="admin-user-box">
            <span>خوش آمدید،</span>
            <strong><?= htmlspecialchars($adminName) ?></strong>
            <span class="admin-role-badge role-user">کاربر</span>
        </div>

        <nav class="admin-nav">

            <div class="nav-group open">
                <button class="nav-group-toggle" type="button">
                    <span>👤 پروفایل من</span>
                    <i class="chevron">▾</i>
                </button>
                <div class="nav-group-items">
                    <a href="#" class="nav-link active" id="sidebarProfileLink"   data-user-tab="profile">ویرایش پروفایل</a>
                    <a href="#" class="nav-link"         id="sidebarPasswordLink" data-user-tab="change-password">تغییر رمز عبور</a>
                    <a href="#" class="nav-link"         id="sidebarOrdersLink"   data-user-tab="my-orders">سفارش‌های من</a>
                </div>
            </div>
        </nav>

        <div class="admin-sidebar-footer">
            <a href="/Kavian/" class="back-to-site">↩ بازگشت به خانه</a>
            <a href="/Kavian/app/auth/logout.php?redirect=/Kavian/" class="admin-logout">خروج</a>
        </div>
    </aside>
    <?php endif; ?>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="admin-content combined-main">

        <!-- topbar پنل ادمین -->
        <div class="admin-topbar">
            <button id="sidebarToggle" class="sidebar-toggle-btn">☰</button>
            <h1 id="panelTitle"><?= $isAdmin ? 'داشبورد' : 'پروفایل من' ?></h1>        </div>

        <div id="adminMessage" class="admin-message"></div>

        <!-- محتوای پنل ادمین (پیش‌فرض مخفی) -->
        <div id="panelBody" class="panel-body" style="display:none;">
            <div class="welcome-box">
                <h2>به پنل مدیریت میراث ایران خوش آمدید</h2>
                <p>یکی از بخش‌ها را از سایدبار انتخاب کنید.</p>
            </div>
        </div>

        <!-- ===== داشبورد کاربر ===== -->
        <div id="userDashboardSection" class="panel-body">

            <!-- هدر داشبورد کاربر -->
            <div class="dashboard-container">
                <div class="dashboard-header">
                    <div class="dashboard-header-content">
                        <div class="dashboard-avatar">
                            <?php if (!empty($avatarPath)): ?>
                                <img src="/Kavian/<?= htmlspecialchars(ltrim($avatarPath, '/')) ?>" alt="آواتار">
                            <?php else: ?>
                                <div class="avatar-placeholder-dashboard">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="dashboard-info">
                            <h1><?= htmlspecialchars($user['name'] . ' ' . ($user['family'] ?? '')) ?></h1>
                            <p class="dashboard-email"><?= htmlspecialchars($user['email']) ?></p>
                            <span class="user-role-badge">
                                <?= htmlspecialchars($_SESSION['user_role_label'] ?? 'کاربر') ?>
                            </span>

                            <div class="dashboard-stats">
                                <div class="dashboard-stat-item">
                                    <span class="dashboard-stat-value"><?= htmlspecialchars($user['join_date']) ?></span>
                                    <span class="dashboard-stat-label">تاریخ عضویت</span>
                                </div>
                                <?php if ($user['email_verified']): ?>
                                <div class="dashboard-stat-item">
                                    <span class="dashboard-stat-value"><i class="fas fa-check-circle"></i></span>
                                    <span class="dashboard-stat-label">تایید ایمیل</span>
                                </div>
                                <?php endif; ?>
                                <?php if ($user['last_login_date']): ?>
                                <div class="dashboard-stat-item">
                                    <span class="dashboard-stat-value"><?= htmlspecialchars(explode(' ', $user['last_login_date'])[0]) ?></span>
                                    <span class="dashboard-stat-label">آخرین ورود</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php /* tabs همیشه نمایش داده می‌شود */ ?>
                <div class="user-tabs-bar">
                    <a href="#" class="nav-link user-tab-link active" data-user-tab="profile">
                        <i class="fas fa-user-edit"></i> ویرایش پروفایل
                    </a>
                    <a href="#" class="nav-link user-tab-link" data-user-tab="change-password">
                        <i class="fas fa-key"></i> تغییر رمز عبور
                    </a>
                    <a href="#" class="nav-link user-tab-link" data-user-tab="my-orders">
                        <i class="fas fa-shopping-bag"></i> سفارش‌های من
                    </a>
                    <a href="#" class="nav-link user-tab-link" data-user-tab="my-favorites">
                        <i class="fas fa-heart"></i> مشاهیر محبوب
                    </a>
                    <a href="#" class="nav-link user-tab-link" data-user-tab="my-history">
                        <i class="fas fa-history"></i> تاریخچه بازدید
                    </a>
                </div>

                <!-- محتوای تب‌ها -->
                <div class="dashboard-content">

                    <!-- ویرایش پروفایل -->
                    <div id="profile" class="dashboard-tab-content active">
                        <div class="tab-header-dashboard">
                            <h2><i class="fas fa-user-edit"></i> ویرایش پروفایل</h2>
                            <p>اطلاعات شخصی خود را به‌روزرسانی کنید</p>
                        </div>
                        <form class="dashboard-form" id="editProfileForm" method="POST"
                              action="/Kavian/app/profile/update.php" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="update_profile">

                            <div class="form-row-dashboard">
                                <div class="form-group-dashboard">
                                    <label for="name">نام <span class="required-dashboard">*</span></label>
                                    <input type="text" id="name" name="name"
                                           value="<?= htmlspecialchars($user['name']) ?>"
                                           required minlength="2" placeholder="نام خود را وارد کنید"
                                           class="dashboard-input">
                                </div>
                                <div class="form-group-dashboard">
                                    <label for="family">نام خانوادگی</label>
                                    <input type="text" id="family" name="family"
                                           value="<?= htmlspecialchars($user['family'] ?? '') ?>"
                                           placeholder="نام خانوادگی خود را وارد کنید"
                                           class="dashboard-input">
                                </div>
                            </div>

                            <div class="form-row-dashboard">
                                <div class="form-group-dashboard">
                                    <label for="email">ایمیل <span class="required-dashboard">*</span></label>
                                    <input type="email" id="email" name="email"
                                           value="<?= htmlspecialchars($user['email']) ?>"
                                           required placeholder="example@email.com"
                                           class="dashboard-input">
                                    <?php if ($user['email_verified']): ?>
                                        <div class="verified-indicator-dashboard">
                                            <i class="fas fa-check-circle"></i> ایمیل تایید شده است
                                        </div>
                                    <?php else: ?>
                                        <div class="unverified-indicator-dashboard">
                                            <i class="fas fa-exclamation-circle"></i> ایمیل تایید نشده است
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group-dashboard">
                                    <label for="phone">شماره تلفن</label>
                                    <input type="tel" id="phone" name="phone"
                                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                                           placeholder="09xxxxxxxxx" pattern="09[0-9]{9}"
                                           class="dashboard-input">
                                </div>
                            </div>

                            <div class="form-group-dashboard">
                                <label>آپلود آواتار</label>
                                <div class="avatar-upload-dashboard">
                                    <div class="avatar-preview-dashboard">
                                        <?php if (!empty($avatarPath)): ?>
                                            <img src="/Kavian/<?= htmlspecialchars(ltrim($avatarPath, '/')) ?>" alt="آواتار فعلی">
                                        <?php else: ?>
                                            <div class="avatar-preview-empty-dashboard">
                                                <i class="fas fa-user"></i>
                                                <small>بدون عکس</small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="upload-controls">
                                        <input type="file" id="avatar" name="avatar" accept="image/*" style="display:none;">
                                        <button type="button" class="btn-upload-dashboard"
                                                onclick="document.getElementById('avatar').click()">
                                            <i class="fas fa-camera"></i> انتخاب عکس جدید
                                        </button>
                                        <p class="upload-note-dashboard">
                                            <i class="fas fa-info-circle"></i> فرمت‌های مجاز: JPG, PNG, GIF | حداکثر: 2MB
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions-dashboard">
                                <button type="submit" class="btn-dashboard-primary">
                                    <i class="fas fa-save"></i> ذخیره تغییرات
                                </button>
                                <button type="reset" class="btn-dashboard-secondary">
                                    <i class="fas fa-redo"></i> بازنشانی فرم
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- تغییر رمز عبور -->
                    <div id="change-password" class="dashboard-tab-content">
                        <div class="tab-header-dashboard">
                            <h2><i class="fas fa-key"></i> تغییر رمز عبور</h2>
                            <p>برای امنیت بیشتر، رمز عبور خود را به‌طور منظم تغییر دهید</p>
                        </div>
                        <form class="dashboard-form" id="changePasswordForm" method="POST"
                              action="/Kavian/app/profile/update.php">
                            <input type="hidden" name="action" value="change_password">

                            <div class="form-group-dashboard">
                                <label for="current_password">رمز عبور فعلی <span class="required-dashboard">*</span></label>
                                <input type="password" id="current_password" name="current_password"
                                       required minlength="6" placeholder="رمز عبور فعلی خود را وارد کنید"
                                       class="dashboard-input">
                            </div>

                            <div class="password-strength-dashboard">
                                <div class="password-strength-meter-dashboard">
                                    <div class="strength-bar-dashboard">
                                        <div id="passwordStrength" class="strength-level-dashboard"></div>
                                    </div>
                                    <div id="strengthLabel" class="strength-label-dashboard">ضعیف</div>
                                </div>
                                <div id="passwordTips" class="password-tips-dashboard">
                                    برای رمز عبور قوی از ترکیب حروف بزرگ و کوچک، اعداد و نمادها استفاده کنید
                                </div>
                            </div>

                            <div class="form-row-dashboard">
                                <div class="form-group-dashboard">
                                    <label for="new_password">رمز عبور جدید <span class="required-dashboard">*</span></label>
                                    <input type="password" id="new_password" name="new_password"
                                           required minlength="6" placeholder="حداقل ۶ کاراکتر وارد کنید"
                                           class="dashboard-input">
                                </div>
                                <div class="form-group-dashboard">
                                    <label for="confirm_password">تکرار رمز عبور جدید <span class="required-dashboard">*</span></label>
                                    <input type="password" id="confirm_password" name="confirm_password"
                                           required minlength="6" placeholder="تکرار رمز عبور جدید"
                                           class="dashboard-input">
                                </div>
                            </div>

                            <div class="form-actions-dashboard">
                                <button type="submit" class="btn-dashboard-primary">
                                    <i class="fas fa-key"></i> تغییر رمز عبور
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- سفارش‌های من -->
                    <div id="my-orders" class="dashboard-tab-content">
                        <div class="tab-header-dashboard">
                            <h2><i class="fas fa-shopping-bag"></i> سفارش‌های من</h2>
                            <p>تاریخچه و وضعیت تمام سفارش‌های شما در یک نگاه</p>
                        </div>
                        <div class="orders-container-dashboard">

                            <!-- آمار خلاصه -->
                            <div class="orders-stats-dashboard">
                                <div class="stat-card-dashboard">
                                    <div class="stat-icon-dashboard"><i class="fas fa-list-alt"></i></div>
                                    <div class="stat-number-dashboard" id="statTotal">—</div>
                                    <div class="stat-text-dashboard">کل سفارش‌ها</div>
                                </div>
                                <div class="stat-card-dashboard">
                                    <div class="stat-icon-dashboard"><i class="fas fa-clock"></i></div>
                                    <div class="stat-number-dashboard" id="statPending">—</div>
                                    <div class="stat-text-dashboard">در انتظار</div>
                                </div>
                                <div class="stat-card-dashboard">
                                    <div class="stat-icon-dashboard" style="color:#27ae60;"><i class="fas fa-check-circle"></i></div>
                                    <div class="stat-number-dashboard" id="statPaid">—</div>
                                    <div class="stat-text-dashboard">پرداخت‌شده</div>
                                </div>
                                <div class="stat-card-dashboard">
                                    <div class="stat-icon-dashboard" style="color:#00796B;"><i class="fas fa-money-bill-wave"></i></div>
                                    <div class="stat-number-dashboard" id="statTotalPaid" style="font-size:0.95rem;">—</div>
                                    <div class="stat-text-dashboard">مجموع پرداخت (ت)</div>
                                </div>
                            </div>

                            <!-- فیلتر وضعیت -->
                            <div class="orders-filters-dashboard">
                                <div class="filter-group-dashboard">
                                    <label for="orderStatusFilter">وضعیت سفارش</label>
                                    <select id="orderStatusFilter" class="dashboard-input" onchange="loadMyOrders()">
                                        <option value="all">همه سفارش‌ها</option>
                                        <option value="pending">در انتظار پرداخت</option>
                                        <option value="paid">پرداخت‌شده</option>
                                        <option value="failed">ناموفق</option>
                                        <option value="cancelled">لغو‌شده</option>
                                    </select>
                                </div>
                            </div>

                            <!-- لودینگ -->
                            <div id="ordersLoading" style="text-align:center;padding:32px 0;">
                                <div class="spinner-border text-success" role="status"></div>
                            </div>

                            <!-- خطا -->
                            <div id="ordersError" style="display:none;" class="no-orders-dashboard">
                                <i class="fas fa-exclamation-circle" style="color:#e74c3c;"></i>
                                <h3>خطا در بارگذاری سفارش‌ها</h3>
                            </div>

                            <!-- سفارشی نیست -->
                            <div id="ordersEmpty" class="no-orders-dashboard" style="display:none;">
                                <i class="fas fa-shopping-bag"></i>
                                <h3>سفارشی یافت نشد</h3>
                                <p>پس از اولین خرید، لیست کامل سفارش‌های شما اینجا نمایش داده می‌شود.</p>
                                <a href="/Kavian/mashahir/persian/products.php" class="btn-dashboard-primary">
                                    <i class="fas fa-store"></i> مشاهده محصولات
                                </a>
                            </div>

                            <!-- لیست سفارش‌ها -->
                            <div id="ordersList" style="display:none;"></div>

                            <!-- مودال جزئیات -->
                            <div id="orderDetailModal" class="order-modal-overlay" style="display:none;" onclick="closeOrderDetail(event)">
                                <div class="order-modal-box">
                                    <div class="order-modal-header">
                                        <h4><i class="fas fa-receipt me-2"></i> جزئیات سفارش</h4>
                                        <button onclick="document.getElementById('orderDetailModal').style.display='none'" class="order-modal-close">✕</button>
                                    </div>
                                    <div id="orderDetailContent" class="order-modal-body"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- مشاهیر محبوب -->
                    <div id="my-favorites" class="dashboard-tab-content">
                        <div class="tab-header-dashboard">
                            <h2><i class="fas fa-heart"></i> مشاهیر محبوب</h2>
                            <p>مشاهیری که به علاقه‌مندی‌های خود اضافه کرده‌اید</p>
                        </div>
                        <div id="favLoading" style="display:none;text-align:center;padding:40px 0;">
                            <div class="spinner-border text-success" role="status"></div>
                        </div>
                        <div id="favEmpty" class="celeb-empty-state" style="display:none;">
                            <div class="celeb-empty-icon"><i class="fas fa-heart"></i></div>
                            <h3>هنوز مشهوری اضافه نکرده‌اید</h3>
                            <p>از صفحه مشاهیر، روی آیکون قلب کلیک کنید تا به اینجا اضافه شود.</p>
                            <a href="/Kavian/mashahir/persian/profile.php" class="btn-dashboard-primary">
                                <i class="fas fa-users"></i> مشاهده مشاهیر
                            </a>
                        </div>
                        <div id="favError" class="celeb-empty-state" style="display:none;">
                            <div class="celeb-empty-icon" style="color:#e74c3c;"><i class="fas fa-exclamation-circle"></i></div>
                            <h3>خطا در بارگذاری</h3>
                            <button class="btn-dashboard-primary" onclick="_favLoaded=false;loadMyFavorites()">
                                <i class="fas fa-redo"></i> تلاش مجدد
                            </button>
                        </div>
                        <div id="favList" class="celeb-grid" style="display:none;"></div>
                    </div>

                    <!-- تاریخچه بازدید -->
                    <div id="my-history" class="dashboard-tab-content">
                        <div class="tab-header-dashboard">
                            <h2><i class="fas fa-history"></i> تاریخچه بازدید</h2>
                            <p>آخرین مشاهیری که پروفایلشان را مشاهده کرده‌اید</p>
                        </div>
                        <div id="histLoading" style="display:none;text-align:center;padding:40px 0;">
                            <div class="spinner-border text-success" role="status"></div>
                        </div>
                        <div id="histEmpty" class="celeb-empty-state" style="display:none;">
                            <div class="celeb-empty-icon"><i class="fas fa-clock"></i></div>
                            <h3>هنوز صفحه‌ای بازدید نکرده‌اید</h3>
                            <p>هر بار که پروفایل یک مشهور را باز کنید اینجا ثبت می‌شود.</p>
                            <a href="/Kavian/mashahir/persian/profile.php" class="btn-dashboard-primary">
                                <i class="fas fa-users"></i> مشاهده مشاهیر
                            </a>
                        </div>
                        <div id="histError" class="celeb-empty-state" style="display:none;">
                            <div class="celeb-empty-icon" style="color:#e74c3c;"><i class="fas fa-exclamation-circle"></i></div>
                            <h3>خطا در بارگذاری</h3>
                            <button class="btn-dashboard-primary" onclick="_histLoaded=false;loadMyHistory()">
                                <i class="fas fa-redo"></i> تلاش مجدد
                            </button>
                        </div>
                        <div id="histList" style="display:none;">
                            <div class="celeb-toolbar">
                                <span id="histCount" class="celeb-count-badge"></span>
                                <button class="btn-danger-soft" onclick="clearHistory()">
                                    <i class="fas fa-trash-alt"></i> پاک‌کردن همه
                                </button>
                            </div>
                            <div id="histItems" class="celeb-grid"></div>
                        </div>
                    </div>

                </div><!-- /dashboard-content -->
            </div><!-- /dashboard-container -->
        </div><!-- /userDashboardSection -->

    </main><!-- /admin-content -->

    <div id="sidebarOverlay" class="sidebar-overlay"></div>
</div><!-- /combined-dashboard-wrapper -->

<!-- مودال پنل ادمین -->
<div id="adminModalOverlay" class="admin-modal-overlay">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <h3 id="modalTitle">افزودن مورد جدید</h3>
            <button id="modalClose" class="modal-close">✕</button>
        </div>
        <form id="modalForm" class="admin-modal-body"></form>
    </div>
</div>

<script>
    window.ADMIN_IS_SUPER       = <?= $isSuper ? 'true' : 'false' ?>;
    window.ADMIN_IS_FULL_ADMIN  = <?= $isSuper ? 'true' : 'false' ?>;  /* سازگاری */
    window.ADMIN_SECTIONS       = <?= json_encode($SECTIONS, JSON_UNESCAPED_UNICODE) ?>;
    window.ADMIN_PANELS         = <?= json_encode($PANELS,   JSON_UNESCAPED_UNICODE) ?>;
    window.ADMIN_PERMISSIONS    = <?= json_encode(admin_get_permissions(), JSON_UNESCAPED_UNICODE) ?>;
</script>

<script src="/Kavian/dashboard/js/profile.js"></script>
<script src="/Kavian/dashboard/js/admin.js"></script>
<script>
// ─── سفارش‌های من ─────────────────────────────────────────────
const STATUS_LABELS = {
    pending:   { text: 'در انتظار پرداخت', cls: 'badge-pending'   },
    paid:      { text: 'پرداخت‌شده',       cls: 'badge-paid'      },
    failed:    { text: 'ناموفق',            cls: 'badge-failed'    },
    cancelled: { text: 'لغوشده',           cls: 'badge-cancelled' },
};

let _ordersLoaded = false;

function loadMyOrders() {
    const status = document.getElementById('orderStatusFilter')?.value || 'all';

    document.getElementById('ordersLoading').style.display = 'block';
    document.getElementById('ordersError').style.display   = 'none';
    document.getElementById('ordersEmpty').style.display   = 'none';
    document.getElementById('ordersList').style.display    = 'none';

    fetch(`/Kavian/dashboard/api/orders_api.php?action=my-orders&status=${encodeURIComponent(status)}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('ordersLoading').style.display = 'none';

            if (!data.success) {
                document.getElementById('ordersError').style.display = 'block';
                return;
            }

            // آمار (همیشه از کل، نه فیلتر‌شده)
            if (data.stats) {
                const s = data.stats;
                document.getElementById('statTotal').textContent   = s.total;
                document.getElementById('statPending').textContent = s.pending_count;
                document.getElementById('statPaid').textContent    = s.paid_count;
                document.getElementById('statTotalPaid').textContent =
                    Number(s.total_paid).toLocaleString('fa-IR');
            }

            if (!data.data || data.data.length === 0) {
                document.getElementById('ordersEmpty').style.display = 'flex';
                return;
            }

            renderOrdersList(data.data);
        })
        .catch(() => {
            document.getElementById('ordersLoading').style.display = 'none';
            document.getElementById('ordersError').style.display   = 'block';
        });
}

function renderOrdersList(orders) {
    const listEl = document.getElementById('ordersList');
    listEl.style.display = 'block';

    listEl.innerHTML = orders.map(o => {
        const s = STATUS_LABELS[o.status] || { text: o.status, cls: '' };
        const date = o.created_at ? o.created_at.substring(0, 10) : '—';
        return `
        <div class="order-card-dashboard">
            <div class="order-card-top">
                <div class="order-code">
                    <i class="fas fa-hashtag"></i>
                    <span dir="ltr">${escHtml(o.order_code)}</span>
                </div>
                <span class="order-status-badge ${s.cls}">${s.text}</span>
            </div>
            <div class="order-card-mid">
                <div class="order-meta-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>${date}</span>
                </div>
                <div class="order-meta-item">
                    <i class="fas fa-box"></i>
                    <span>${o.item_count} قلم کالا</span>
                </div>
                <div class="order-meta-item order-amount">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>${Number(o.final_amount).toLocaleString('fa-IR')} تومان</span>
                </div>
            </div>
            <div class="order-card-bot">
                ${o.payment_ref
                    ? `<span class="order-ref"><i class="fas fa-check-circle me-1" style="color:#27ae60;"></i>کد پیگیری: <code dir="ltr">${escHtml(o.payment_ref)}</code></span>`
                    : ''
                }
                <button class="btn-order-detail" onclick="loadOrderDetail(${o.id})">
                    <i class="fas fa-eye me-1"></i> جزئیات
                </button>
            </div>
        </div>`;
    }).join('');
}

function loadOrderDetail(orderId) {
    const modal   = document.getElementById('orderDetailModal');
    const content = document.getElementById('orderDetailContent');
    content.innerHTML = '<div style="text-align:center;padding:24px;"><div class="spinner-border text-success"></div></div>';
    modal.style.display = 'flex';

    fetch(`/Kavian/dashboard/api/orders_api.php?action=detail&order_id=${orderId}`)
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                content.innerHTML = '<p style="color:#e74c3c;text-align:center;">خطا در بارگذاری جزئیات.</p>';
                return;
            }
            const o = data.order;
            const s = STATUS_LABELS[o.status] || { text: o.status, cls: '' };
            const items = data.items || [];

            content.innerHTML = `
            <div class="order-detail-section">
                <div class="order-detail-row">
                    <span>کد سفارش:</span>
                    <strong dir="ltr">${escHtml(o.order_code)}</strong>
                </div>
                <div class="order-detail-row">
                    <span>وضعیت:</span>
                    <span class="order-status-badge ${s.cls}">${s.text}</span>
                </div>
                <div class="order-detail-row">
                    <span>تاریخ ثبت:</span>
                    <span>${(o.created_at || '').substring(0, 16)}</span>
                </div>
                <div class="order-detail-row">
                    <span>نام:</span>
                    <span>${escHtml(o.full_name)}</span>
                </div>
                <div class="order-detail-row">
                    <span>ایمیل:</span>
                    <span dir="ltr">${escHtml(o.email)}</span>
                </div>
                ${o.payment_ref ? `
                <div class="order-detail-row">
                    <span>کد پیگیری:</span>
                    <strong dir="ltr">${escHtml(o.payment_ref)}</strong>
                </div>` : ''}
            </div>

            <div class="order-detail-items">
                <h5>محصولات خریداری‌شده</h5>
                ${items.map(item => {
                    const langBadge = item.product_type === 'pdf'
                        ? `<i class="fas fa-file-pdf me-1"></i>PDF${item.pdf_label_fa ? ' — ' + escHtml(item.pdf_label_fa) : ''}`
                        : `<i class="fas fa-print me-1"></i>چاپی`;
                    // دکمه دانلود برای همه آیتم‌های PDF نمایش داده می‌شه
                    // (بررسی دسترسی و وجود فایل در سمت سرور انجام می‌شه)
                    const dlBtn = (item.product_type === 'pdf')
                        ? `<a href="/Kavian/mashahir/api/download-pdf.php?order_item_id=${item.id}${item.pdf_lang ? '&lang=' + encodeURIComponent(item.pdf_lang) : ''}" download class="btn-order-download" target="_blank">
                               <i class="fas fa-download me-1"></i>دانلود PDF
                           </a>`
                        : '';
                    return `
                <div class="order-detail-item">
                    <div class="order-detail-item-info">
                        <span class="order-detail-item-title">${escHtml(item.product_title)}</span>
                        <span class="order-type-badge">${langBadge}</span>
                    </div>
                    <div class="order-detail-item-actions">
                        <div class="order-detail-item-price">
                            ${Number(item.total_price).toLocaleString('fa-IR')}
                            <small>ت</small>
                        </div>
                        ${dlBtn}
                    </div>
                </div>`;
                }).join('')}
            </div>

            <div class="order-detail-total">
                <span>مبلغ کل:</span>
                <strong>${Number(o.final_amount).toLocaleString('fa-IR')} تومان</strong>
            </div>
            `;
        })
        .catch(() => {
            content.innerHTML = '<p style="color:#e74c3c;text-align:center;">خطا در اتصال به سرور.</p>';
        });
}

function closeOrderDetail(e) {
    if (e.target === document.getElementById('orderDetailModal')) {
        document.getElementById('orderDetailModal').style.display = 'none';
    }
}

function escHtml(str) {
    const d = document.createElement('div');
    d.appendChild(document.createTextNode(str || ''));
    return d.innerHTML;
}
</script>
<script>
// ===== منطق سوئیچ بین داشبورد کاربر و پنل ادمین =====
(function () {
    const userSection  = document.getElementById('userDashboardSection');
    const adminSection = document.getElementById('panelBody');
    const panelTitle   = document.getElementById('panelTitle');

    // سوئیچ به تب مشخص داشبورد کاربر
    function showUserTab(tabId) {
        userSection.style.display  = 'block';
        adminSection.style.display = 'none';

        // عنوان topbar
        const titles = {
            'profile':         'ویرایش پروفایل',
            'change-password': 'تغییر رمز عبور',
            'my-orders':       'سفارش‌های من',
            'my-favorites':    'مشاهیر محبوب',
            'my-history':      'تاریخچه بازدید',
        };
        panelTitle.textContent = titles[tabId] || 'داشبورد';

        // فعال‌سازی تب محتوا
        document.querySelectorAll('.dashboard-tab-content').forEach(t => t.classList.remove('active'));
        const target = document.getElementById(tabId);
        if (target) target.classList.add('active');

        // فعال‌سازی لینک در sidebar
        document.querySelectorAll('#adminSidebar .nav-link').forEach(l => l.classList.remove('active'));
        const activeLink = document.querySelector(`#adminSidebar .nav-link[data-user-tab="${tabId}"]`);
        if (activeLink) activeLink.classList.add('active');

        // فعال‌سازی لینک در تب‌بار بالا
        document.querySelectorAll('.user-tab-link').forEach(l => l.classList.remove('active'));
        const activeTabLink = document.querySelector(`.user-tab-link[data-user-tab="${tabId}"]`);
        if (activeTabLink) activeTabLink.classList.add('active');

        if (window.innerWidth <= 860) closeMobileSidebar();

        // بار اول هر تب، داده را لود کن
        if (tabId === 'my-orders' && typeof loadMyOrders === 'function') {
            loadMyOrders();
        }
        if (tabId === 'my-favorites' && !_favLoaded) {
            _favLoaded = true;
            loadMyFavorites();
        }
        if (tabId === 'my-history' && !_histLoaded) {
            _histLoaded = true;
            loadMyHistory();
        }
    }

    // کلیک روی لینک‌های پروفایل در سایدبار
    document.querySelectorAll('#adminSidebar .nav-link[data-user-tab]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            showUserTab(this.dataset.userTab);
        });
    });

    // وقتی یک آیتم ادمین کلیک می‌شود، داشبورد کاربر مخفی شود
    document.querySelectorAll('#adminSidebar .nav-link:not([data-user-tab])').forEach(function (link) {
        link.addEventListener('click', function () {
            userSection.style.display  = 'none';
            adminSection.style.display = 'block';
        });
    });

    // پیش‌فرض اولیه — اگر hash در URL بود آن تب را باز کن
    var _allowedTabs = ['profile', 'change-password', 'my-orders', 'my-favorites', 'my-history'];
    var _hashTab = window.location.hash.replace('#', '');
    var _favLoaded  = false;
    var _histLoaded = false;
    if (_allowedTabs.indexOf(_hashTab) !== -1) {
        showUserTab(_hashTab);
    } else {
        showUserTab('profile');
    }

    // کلیک روی تب‌بار بالا
    document.querySelectorAll('.user-tab-link[data-user-tab]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            showUserTab(this.dataset.userTab);
        });
    });
})();

// ─── مشاهیر محبوب ────────────────────────────────────────────
function loadMyFavorites() {
    document.getElementById('favLoading').style.display = 'block';
    document.getElementById('favEmpty').style.display   = 'none';
    document.getElementById('favError').style.display   = 'none';
    document.getElementById('favList').style.display    = 'none';

    fetch('/Kavian/mashahir/api/favorites-api.php?action=list&lang=fa')
        .then(r => r.json())
        .then(data => {
            document.getElementById('favLoading').style.display = 'none';
            if (!data.success) { document.getElementById('favError').style.display = 'flex'; return; }
            if (!data.data || data.data.length === 0) { document.getElementById('favEmpty').style.display = 'flex'; return; }
            const list = document.getElementById('favList');
            list.innerHTML = data.data.map(c => celebCard(c, true)).join('');
            list.style.display = '';
        })
        .catch(() => {
            document.getElementById('favLoading').style.display = 'none';
            document.getElementById('favError').style.display   = 'flex';
        });
}

// ─── تاریخچه بازدید ──────────────────────────────────────────
function loadMyHistory() {
    document.getElementById('histLoading').style.display = 'block';
    document.getElementById('histEmpty').style.display   = 'none';
    document.getElementById('histError').style.display   = 'none';
    document.getElementById('histList').style.display    = 'none';

    fetch('/Kavian/mashahir/api/history-api.php?action=list&lang=fa&limit=50')
        .then(r => r.json())
        .then(data => {
            document.getElementById('histLoading').style.display = 'none';
            if (!data.success) { document.getElementById('histError').style.display = 'flex'; return; }
            if (!data.data || data.data.length === 0) { document.getElementById('histEmpty').style.display = 'flex'; return; }
            const badge = document.getElementById('histCount');
            if (badge) badge.textContent = data.data.length + ' بازدید';
            document.getElementById('histItems').innerHTML = data.data.map(c => celebCard(c, false)).join('');
            document.getElementById('histList').style.display = 'block';
        })
        .catch(() => {
            document.getElementById('histLoading').style.display = 'none';
            document.getElementById('histError').style.display   = 'flex';
        });
}

function celebCard(c, showRemove) {
    return `<div class="celeb-card-db">
        <div class="celeb-card-db-img">
            <img src="${escHtml(c.image)}" alt="${escHtml(c.name)}"
                 onerror="this.src='/Kavian/mashahir/public/img/s.png'">
        </div>
        <div class="celeb-card-db-body">
            <h4>${escHtml(c.name)}</h4>
            <p>${escHtml(c.job || '')}</p>
            <div class="celeb-card-db-actions">
                <a href="/Kavian/mashahir/persian/profile.php?id=${c.id}" class="cdb-btn-view">
                    <i class="fas fa-eye"></i> مشاهده
                </a>
                ${showRemove ? `<button class="cdb-btn-remove" onclick="removeFavItem(${c.id},this)">
                    <i class="fas fa-heart-broken"></i> حذف
                </button>` : ''}
            </div>
        </div>
    </div>`;
}

function renderCelebGrid(containerId, items, showRemove) {
    const el = document.getElementById(containerId);
    if (!el) return;
    el.innerHTML = items.map(c => celebCard(c, showRemove)).join('');
    el.style.display = '';
}

function removeFavItem(id, btn) {
    btn.disabled = true;
    const fd = new FormData();
    fd.append('action', 'toggle');
    fd.append('celebrity_id', id);
    fetch('/Kavian/mashahir/api/favorites-api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // کارت رو از DOM حذف کن
                const card = btn.closest('.celeb-card-dashboard');
                if (card) card.remove();
                // اگه لیست خالی شد empty state نشون بده
                const remaining = document.querySelectorAll('#favList .celeb-card-dashboard').length;
                if (remaining === 0) {
                    document.getElementById('favList').style.display  = 'none';
                    document.getElementById('favEmpty').style.display = 'flex';
                }
            }
        })
        .catch(() => { btn.disabled = false; });
}

function clearHistory() {
    if (!confirm('آیا مطمئن هستید؟ تمام تاریخچه پاک می‌شود.')) return;
    const fd = new FormData();
    fd.append('action', 'clear');
    fetch('/Kavian/mashahir/api/history-api.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('histList').style.display  = 'none';
                document.getElementById('histEmpty').style.display = 'flex';
            }
        })
        .catch(() => {});
}
</script>

<?php include '../views/footer.php'; ?>
