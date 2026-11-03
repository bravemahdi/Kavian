<?php
/**
 * Favorites API — mashahir
 *
 * GET  ?action=list              → لیست مشاهیر محبوب کاربر
 * POST action=toggle&celebrity_id=X → افزودن/حذف
 * GET  ?action=check&celebrity_id=X → آیا محبوب است؟
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// اطمینان از وجود جدول
function ensureTable(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `user_favorites` (
        `id`           int(11)   NOT NULL AUTO_INCREMENT,
        `user_id`      int(11)   NOT NULL,
        `celebrity_id` int(11)   NOT NULL,
        `created_at`   timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `uq_user_celebrity` (`user_id`,`celebrity_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function notLoggedIn(): void {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'ابتدا وارد شوید.'], JSON_UNESCAPED_UNICODE);
    exit;
}

if (empty($_SESSION['user_id'])) {
    notLoggedIn();
}

$pdo    = require __DIR__ . '/../../app/config/config.php';
$userId = (int)$_SESSION['user_id'];
ensureTable($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = $method === 'GET' ? ($_GET['action'] ?? 'list') : ($_POST['action'] ?? '');

// ── GET list ─────────────────────────────────────────────────
if ($method === 'GET' && $action === 'list') {
    $lang = isset($_GET['lang']) && $_GET['lang'] === 'fa' ? 'fa' : 'en';

    $stmt = $pdo->prepare("
        SELECT
            mc.id,
            mc.name_fa,
            mc.name_en,
            mc.image,
            mc.job_fa,
            mc.job_en,
            uf.created_at AS favorited_at
        FROM user_favorites uf
        JOIN mashahir_celebrities mc ON mc.id = uf.celebrity_id
        WHERE uf.user_id = ? AND mc.is_active = 1
        ORDER BY uf.created_at DESC
    ");
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();

    $data = array_map(function($r) use ($lang) {
        return [
            'id'           => (int)$r['id'],
            'name'         => $lang === 'fa' ? $r['name_fa'] : ($r['name_en'] ?: $r['name_fa']),
            'image'        => $r['image'] ? '/Kavian/mashahir/public/img/' . $r['image'] : '/Kavian/mashahir/public/img/s.png',
            'job'          => $lang === 'fa' ? $r['job_fa'] : ($r['job_en'] ?: $r['job_fa']),
            'favorited_at' => $r['favorited_at'],
        ];
    }, $rows);

    echo json_encode(['success' => true, 'data' => $data, 'count' => count($data)], JSON_UNESCAPED_UNICODE);
    exit;
}

// ── GET check ────────────────────────────────────────────────
if ($method === 'GET' && $action === 'check') {
    $celebId = (int)($_GET['celebrity_id'] ?? 0);
    if (!$celebId) {
        echo json_encode(['success' => false, 'error' => 'celebrity_id لازم است.'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    $stmt = $pdo->prepare("SELECT id FROM user_favorites WHERE user_id=? AND celebrity_id=?");
    $stmt->execute([$userId, $celebId]);
    echo json_encode(['success' => true, 'is_favorite' => (bool)$stmt->fetch()], JSON_UNESCAPED_UNICODE);
    exit;
}

// ── POST toggle ───────────────────────────────────────────────
if ($method === 'POST' && $action === 'toggle') {
    $celebId = (int)($_POST['celebrity_id'] ?? 0);
    if (!$celebId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'celebrity_id لازم است.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // بررسی وجود مشهور
    $check = $pdo->prepare("SELECT id FROM mashahir_celebrities WHERE id=? AND is_active=1");
    $check->execute([$celebId]);
    if (!$check->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'مشهور یافت نشد.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $exists = $pdo->prepare("SELECT id FROM user_favorites WHERE user_id=? AND celebrity_id=?");
    $exists->execute([$userId, $celebId]);

    if ($exists->fetch()) {
        // حذف
        $pdo->prepare("DELETE FROM user_favorites WHERE user_id=? AND celebrity_id=?")->execute([$userId, $celebId]);
        $added = false;
    } else {
        // افزودن
        $pdo->prepare("INSERT INTO user_favorites (user_id, celebrity_id) VALUES (?,?)")->execute([$userId, $celebId]);
        $added = true;
    }

    // تعداد جدید
    $cnt = $pdo->prepare("SELECT COUNT(*) FROM user_favorites WHERE user_id=?");
    $cnt->execute([$userId]);

    echo json_encode([
        'success' => true,
        'added'   => $added,
        'count'   => (int)$cnt->fetchColumn(),
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'درخواست نامعتبر.'], JSON_UNESCAPED_UNICODE);
