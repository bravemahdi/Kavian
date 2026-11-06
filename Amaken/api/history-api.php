<?php
/**
 * History API — mashahir
 *
 * GET  ?action=list              → آخرین ۳۰ بازدید کاربر
 * POST action=add&celebrity_id=X → ثبت یا به‌روزرسانی بازدید
 * POST action=clear              → پاک‌کردن تمام تاریخچه
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function ensureHistTable(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `user_history` (
        `id`           int(11)   NOT NULL AUTO_INCREMENT,
        `user_id`      int(11)   NOT NULL,
        `celebrity_id` int(11)   NOT NULL,
        `visited_at`   timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `uq_user_celebrity_hist` (`user_id`,`celebrity_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

if (empty($_SESSION['user_id'])) {
    // کاربر لاگین نیست — برای history بازگشت خالی کافیه
    echo json_encode(['success' => true, 'logged_in' => false, 'data' => [], 'count' => 0], JSON_UNESCAPED_UNICODE);
    exit;
}

$pdo    = require __DIR__ . '/../../app/config/config.php';
$userId = (int)$_SESSION['user_id'];
ensureHistTable($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = $method === 'GET' ? ($_GET['action'] ?? 'list') : ($_POST['action'] ?? '');

// ── GET list ─────────────────────────────────────────────────
if ($method === 'GET' && $action === 'list') {
    $lang  = isset($_GET['lang']) && $_GET['lang'] === 'fa' ? 'fa' : 'en';
    $limit = min((int)($_GET['limit'] ?? 30), 100);

    $stmt = $pdo->prepare("
        SELECT
            mc.id,
            mc.name_fa,
            mc.name_en,
            mc.image,
            mc.job_fa,
            mc.job_en,
            uh.visited_at
        FROM user_history uh
        JOIN mashahir_celebrities mc ON mc.id = uh.celebrity_id
        WHERE uh.user_id = ? AND mc.is_active = 1
        ORDER BY uh.visited_at DESC
        LIMIT " . $limit . "
    ");
    $stmt->execute([$userId]);
    $rows = $stmt->fetchAll();

    $data = array_map(function($r) use ($lang) {
        return [
            'id'         => (int)$r['id'],
            'name'       => $lang === 'fa' ? $r['name_fa'] : ($r['name_en'] ?: $r['name_fa']),
            'image'      => $r['image'] ? '/Kavian/mashahir/public/img/' . $r['image'] : '/Kavian/mashahir/public/img/s.png',
            'job'        => $lang === 'fa' ? $r['job_fa'] : ($r['job_en'] ?: $r['job_fa']),
            'visited_at' => $r['visited_at'],
        ];
    }, $rows);

    echo json_encode(['success' => true, 'logged_in' => true, 'data' => $data, 'count' => count($data)], JSON_UNESCAPED_UNICODE);
    exit;
}

// ── POST add ──────────────────────────────────────────────────
if ($method === 'POST' && $action === 'add') {
    $celebId = (int)($_POST['celebrity_id'] ?? 0);
    if (!$celebId) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'celebrity_id لازم است.'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // UPSERT — اگه قبلاً بازدید کرده visited_at رو آپدیت کن
    $pdo->prepare("
        INSERT INTO user_history (user_id, celebrity_id)
        VALUES (?, ?)
        ON DUPLICATE KEY UPDATE visited_at = current_timestamp()
    ")->execute([$userId, $celebId]);

    // نگه‌داری فقط آخرین ۵۰ بازدید
    $pdo->prepare("
        DELETE FROM user_history
        WHERE user_id = ?
          AND id NOT IN (
              SELECT id FROM (
                  SELECT id FROM user_history
                  WHERE user_id = ?
                  ORDER BY visited_at DESC
                  LIMIT 50
              ) AS t
          )
    ")->execute([$userId, $userId]);

    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

// ── POST clear ────────────────────────────────────────────────
if ($method === 'POST' && $action === 'clear') {
    $pdo->prepare("DELETE FROM user_history WHERE user_id=?")->execute([$userId]);
    echo json_encode(['success' => true], JSON_UNESCAPED_UNICODE);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'درخواست نامعتبر.'], JSON_UNESCAPED_UNICODE);
