<?php
/**
 * Roles API — مدیریت نقش‌ها
 */
require_once __DIR__ . '/auth.php';
admin_require_permission_api('special:roles');

$pdo    = require __DIR__ . '/../../app/config/config.php';
$method = $_SERVER['REQUEST_METHOD'];

// ── بررسی وضعیت migration و اجرای خودکار آن ──────────────
function ensure_migration(PDO $pdo): void
{
    // اگر نقش admin در جدول نبود بساز
    $adminExists = $pdo->query("SELECT id FROM roles WHERE name='admin'")->fetch();
    if (!$adminExists) {
        $pdo->exec("INSERT INTO `roles` (`name`,`label`,`description`) VALUES ('admin','مدیر کل','دسترسی کامل به همه بخش‌های پنل')");
        $adminId = (int)$pdo->lastInsertId();
        $pdo->prepare("INSERT IGNORE INTO role_permissions (role_id,permission_key) VALUES (?,?)")
            ->execute([$adminId, 'special:*']);
    }

    // اگر نقش user در جدول نبود بساز
    $userExists = $pdo->query("SELECT id FROM roles WHERE name='user'")->fetch();
    if (!$userExists) {
        $pdo->exec("INSERT INTO `roles` (`name`,`label`,`description`) VALUES ('user','کاربر عادی','فقط پروفایل شخصی — بدون دسترسی به پنل')");
    }

    // migrate کاربران قدیمی که custom_role_id ندارند
    $pdo->exec("UPDATE users u JOIN roles r ON r.name='admin' SET u.custom_role_id=r.id WHERE u.role='admin' AND (u.custom_role_id IS NULL OR u.custom_role_id=0)");
    $pdo->exec("UPDATE users u JOIN roles r ON r.name='user'  SET u.custom_role_id=r.id WHERE u.role='user'  AND (u.custom_role_id IS NULL OR u.custom_role_id=0)");
    $pdo->exec("UPDATE users u JOIN roles r ON r.name='user'  SET u.custom_role_id=r.id WHERE u.role='support' AND (u.custom_role_id IS NULL OR u.custom_role_id=0)");
}

// migration رو هر بار که API صدا زده می‌شه چک کن
try {
    ensure_migration($pdo);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'خطا در migration: ' . $e->getMessage()]);
    exit;
}

// ═══════════════════════════════════════════════════════════
//  همه کلیدهای مجوز
// ═══════════════════════════════════════════════════════════
function get_all_permission_keys(): array
{
    return [
        'special:*'             => ['group' => 'مدیریت سایت', 'label' => 'دسترسی کامل (مدیر کل)'],
        'special:users'         => ['group' => 'مدیریت سایت', 'label' => 'کاربران سایت'],
        'special:logs'          => ['group' => 'مدیریت سایت', 'label' => 'مدیریت لاگ‌ها'],
        'special:subsites'      => ['group' => 'مدیریت سایت', 'label' => 'سایت‌های زیرمجموعه'],
        'special:roles'         => ['group' => 'مدیریت سایت', 'label' => 'مدیریت نقش‌ها'],
        'special:orders_global' => ['group' => 'مدیریت سایت', 'label' => 'مدیریت سفارش‌ها'],

        'section:celebrities'                  => ['group' => 'مدیریت سایت مشاهیر', 'label' => 'همه بخش‌های مشاهیر'],
        'section:celebrities:categories'       => ['group' => 'مدیریت سایت مشاهیر', 'label' => 'دسته‌بندی‌های مشاهیر'],
        'section:celebrities:celebrities'      => ['group' => 'مدیریت سایت مشاهیر', 'label' => 'مدیریت مشاهیر'],
        'section:celebrities:products'         => ['group' => 'مدیریت سایت مشاهیر', 'label' => 'نمایش محصولات'],
        'section:celebrities:product_register' => ['group' => 'مدیریت سایت مشاهیر', 'label' => 'ثبت محصول'],
        'section:celebrities:reviews'          => ['group' => 'مدیریت سایت مشاهیر', 'label' => 'نظرات مشاهیر'],
        'section:celebrities:tickets'          => ['group' => 'مدیریت سایت مشاهیر', 'label' => 'مدیریت تیکت‌ها'],

        'section:sport'           => ['group' => 'مدیریت سایت ورزش', 'label' => 'همه بخش‌های ورزش'],
        'section:sport:orders'    => ['group' => 'مدیریت سایت ورزش', 'label' => 'سفارشات ورزش'],
        'section:sport:comments'  => ['group' => 'مدیریت سایت ورزش', 'label' => 'کامنت‌های ورزش'],
        'section:sport:slides'    => ['group' => 'مدیریت سایت ورزش', 'label' => 'اسلایدهای ورزش'],
        'section:sport:products'  => ['group' => 'مدیریت سایت ورزش', 'label' => 'محصولات ورزش'],
        'section:sport:tickets'   => ['group' => 'مدیریت سایت ورزش', 'label' => 'مدیریت تیکت‌ها'],

        'section:art'             => ['group' => 'مدیریت سایت هنر', 'label' => 'همه بخش‌های هنر'],
        'section:art:orders'      => ['group' => 'مدیریت سایت هنر', 'label' => 'سفارشات هنر'],
        'section:art:comments'    => ['group' => 'مدیریت سایت هنر', 'label' => 'کامنت‌های هنر'],
        'section:art:slides'      => ['group' => 'مدیریت سایت هنر', 'label' => 'اسلایدهای هنر'],
        'section:art:products'    => ['group' => 'مدیریت سایت هنر', 'label' => 'محصولات هنر'],
        'section:art:tickets'     => ['group' => 'مدیریت سایت هنر', 'label' => 'مدیریت تیکت‌ها'],

        'section:places'          => ['group' => 'مدیریت سایت مکان‌ها', 'label' => 'همه بخش‌های مکان‌ها'],
        'section:places:orders'   => ['group' => 'مدیریت سایت مکان‌ها', 'label' => 'سفارشات مکان‌ها'],
        'section:places:comments' => ['group' => 'مدیریت سایت مکان‌ها', 'label' => 'کامنت‌های مکان‌ها'],
        'section:places:slides'   => ['group' => 'مدیریت سایت مکان‌ها', 'label' => 'اسلایدهای مکان‌ها'],
        'section:places:products' => ['group' => 'مدیریت سایت مکان‌ها', 'label' => 'محصولات مکان‌ها'],
        'section:places:tickets'  => ['group' => 'مدیریت سایت مکان‌ها', 'label' => 'مدیریت تیکت‌ها'],

        'section:exports'           => ['group' => 'مدیریت سایت صادرات', 'label' => 'همه بخش‌های صادرات'],
        'section:exports:orders'    => ['group' => 'مدیریت سایت صادرات', 'label' => 'سفارشات صادرات'],
        'section:exports:comments'  => ['group' => 'مدیریت سایت صادرات', 'label' => 'کامنت‌های صادرات'],
        'section:exports:slides'    => ['group' => 'مدیریت سایت صادرات', 'label' => 'اسلایدهای صادرات'],
        'section:exports:products'  => ['group' => 'مدیریت سایت صادرات', 'label' => 'محصولات صادرات'],
        'section:exports:tickets'   => ['group' => 'مدیریت سایت صادرات', 'label' => 'مدیریت تیکت‌ها'],

        'section:technology'           => ['group' => 'مدیریت سایت تکنولوژی', 'label' => 'همه بخش‌های تکنولوژی'],
        'section:technology:orders'    => ['group' => 'مدیریت سایت تکنولوژی', 'label' => 'سفارشات تکنولوژی'],
        'section:technology:comments'  => ['group' => 'مدیریت سایت تکنولوژی', 'label' => 'کامنت‌های تکنولوژی'],
        'section:technology:slides'    => ['group' => 'مدیریت سایت تکنولوژی', 'label' => 'اسلایدهای تکنولوژی'],
        'section:technology:products'  => ['group' => 'مدیریت سایت تکنولوژی', 'label' => 'محصولات تکنولوژی'],
        'section:technology:tickets'   => ['group' => 'مدیریت سایت تکنولوژی', 'label' => 'مدیریت تیکت‌ها'],
    ];
}

// ═══════════════════════════════════════════════════════════
//  GET
// ═══════════════════════════════════════════════════════════
if ($method === 'GET') {
    $action = $_GET['action'] ?? 'list';

    if ($action === 'permissions_schema') {
        echo json_encode(['success' => true, 'data' => get_all_permission_keys()]);
        exit;
    }

    if ($action === 'get' && isset($_GET['id'])) {
        $id  = (int)$_GET['id'];
        $row = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
        $row->execute([$id]);
        $data = $row->fetch();
        if (!$data) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'نقش یافت نشد']);
            exit;
        }
        $perms = $pdo->prepare("SELECT permission_key FROM role_permissions WHERE role_id = ?");
        $perms->execute([$id]);
        $data['permissions'] = array_column($perms->fetchAll(), 'permission_key');
        echo json_encode(['success' => true, 'data' => $data]);
        exit;
    }

    // لیست همه نقش‌ها
    $stmt = $pdo->query("
        SELECT r.id, r.name, r.label, r.description,
               COUNT(DISTINCT rp.id) AS perm_count,
               COUNT(DISTINCT u.id)  AS user_count
        FROM roles r
        LEFT JOIN role_permissions rp ON rp.role_id = r.id
        LEFT JOIN users u             ON u.custom_role_id = r.id
        GROUP BY r.id
        ORDER BY r.id
    ");
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

// ═══════════════════════════════════════════════════════════
//  POST
// ═══════════════════════════════════════════════════════════
if ($method === 'POST') {
    $action  = $_POST['action'] ?? '';
    $allKeys = array_keys(get_all_permission_keys());

    // ── ساخت نقش جدید ───────────────────────────────────
    if ($action === 'create') {
        $name  = trim($_POST['name']  ?? '');
        $label = trim($_POST['label'] ?? '');

        if (!$name || !$label) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'نام و عنوان نقش الزامی است']);
            exit;
        }
        if (!preg_match('/^[a-z0-9\-_]+$/', $name)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'نام نقش فقط حروف کوچک، عدد، خط‌تیره و زیرخط']);
            exit;
        }
        if (in_array($name, ['admin', 'user'], true)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'این نام رزرو شده است']);
            exit;
        }

        try {
            $pdo->beginTransaction();
            $pdo->prepare("INSERT INTO roles (name,label,description) VALUES (?,?,?)")
                ->execute([$name, $label, trim($_POST['description'] ?? '')]);
            $roleId = (int)$pdo->lastInsertId();

            $perms = array_filter($_POST['permissions'] ?? [], fn($k) => in_array($k, $allKeys, true));
            if ($perms) {
                $ins = $pdo->prepare("INSERT IGNORE INTO role_permissions (role_id,permission_key) VALUES (?,?)");
                foreach ($perms as $key) $ins->execute([$roleId, $key]);
            }
            $pdo->commit();
            echo json_encode(['success' => true, 'id' => $roleId, 'message' => 'نقش با موفقیت ساخته شد']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            if ($e->getCode() === '23000') {
                http_response_code(409);
                echo json_encode(['success' => false, 'message' => 'این نام نقش قبلاً استفاده شده']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'خطا: ' . $e->getMessage()]);
            }
        }
        exit;
    }

    // ── ویرایش نقش ──────────────────────────────────────
    if ($action === 'update') {
        $id    = (int)($_POST['id']    ?? 0);
        $label = trim($_POST['label'] ?? '');
        $desc  = trim($_POST['description'] ?? '');

        if (!$id || !$label) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'شناسه و عنوان الزامی است']);
            exit;
        }

        $existing = $pdo->prepare("SELECT * FROM roles WHERE id = ?");
        $existing->execute([$id]);
        $roleRow = $existing->fetch();
        if (!$roleRow) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'نقش یافت نشد']);
            exit;
        }

        try {
            $pdo->beginTransaction();
            $pdo->prepare("UPDATE roles SET label=?, description=? WHERE id=?")
                ->execute([$label, $desc, $id]);

            // جایگزینی کامل مجوزها
            $pdo->prepare("DELETE FROM role_permissions WHERE role_id=?")->execute([$id]);
            $perms = array_filter($_POST['permissions'] ?? [], fn($k) => in_array($k, $allKeys, true));
            if ($perms) {
                $ins = $pdo->prepare("INSERT IGNORE INTO role_permissions (role_id,permission_key) VALUES (?,?)");
                foreach ($perms as $key) $ins->execute([$id, $key]);
            }
            $pdo->commit();
            echo json_encode(['success' => true, 'message' => 'نقش به‌روزرسانی شد']);
        } catch (PDOException $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'خطا: ' . $e->getMessage()]);
        }
        exit;
    }

    // ── حذف نقش ─────────────────────────────────────────
    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'شناسه الزامی است']);
            exit;
        }

        $roleRow = $pdo->prepare("SELECT * FROM roles WHERE id=?");
        $roleRow->execute([$id]);
        $role = $roleRow->fetch();
        if (!$role) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'نقش یافت نشد']);
            exit;
        }

        // نمی‌تونی نقشی که به خودت اختصاص داره رو حذف کنی
        $myRoleId = $_SESSION['user_custom_role_id'] ?? null;
        if ($myRoleId && (int)$myRoleId === $id) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'نمی‌توانید نقش فعلی خودتان را حذف کنید']);
            exit;
        }

        $cnt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE custom_role_id=?");
        $cnt->execute([$id]);
        if ((int)$cnt->fetchColumn() > 0) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'این نقش به کاربران فعال اختصاص دارد. ابتدا نقش آن کاربران را تغییر دهید.']);
            exit;
        }

        $pdo->prepare("DELETE FROM roles WHERE id=?")->execute([$id]);
        echo json_encode(['success' => true, 'message' => 'نقش حذف شد']);
        exit;
    }

    // ── اختصاص نقش به کاربر ─────────────────────────────
    if ($action === 'assign_user') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $roleId = (isset($_POST['role_id']) && $_POST['role_id'] !== '') ? (int)$_POST['role_id'] : null;
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'شناسه کاربر الزامی است']);
            exit;
        }
        if ($roleId !== null) {
            $e = $pdo->prepare("SELECT id FROM roles WHERE id=?");
            $e->execute([$roleId]);
            if (!$e->fetch()) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'نقش معتبر نیست']);
                exit;
            }
        }
        $pdo->prepare("UPDATE users SET custom_role_id=? WHERE id=?")->execute([$roleId, $userId]);
        echo json_encode(['success' => true, 'message' => 'نقش کاربر به‌روزرسانی شد']);
        exit;
    }
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'درخواست نامعتبر']);
