<?php
require_once __DIR__ . '/auth.php';
admin_require_login_api();
$pdo = require __DIR__ . '/../../app/config/config.php';

function mcel_bad(string $msg): void
{
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

$COLUMNS = [
    'name_fa', 'name_en', 'full_name_fa', 'full_name_en', 'slug',
    'birth_year', 'birth_place_fa', 'birth_place_en',
    'death_year', 'death_place_fa', 'death_place_en',
    'job_fa', 'job_en', 'era_fa', 'era_en',
    'short_story_fa', 'short_story_en', 'biography_fa', 'biography_en',
    'quote_fa', 'quote_en', 'tags_fa', 'tags_en', 'image',
    'is_featured', 'is_active', 'sort_order',
];

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $sql = "SELECT c.*, mc.name_fa AS category_name_fa,
                   (SELECT category_id FROM celebrity_categories WHERE celebrity_id = c.id AND is_primary = 1 LIMIT 1) AS category_id
            FROM mashahir_celebrities c
            LEFT JOIN celebrity_categories cc ON cc.celebrity_id = c.id AND cc.is_primary = 1
            LEFT JOIN mashahir_categories mc ON mc.id = cc.category_id
            ORDER BY c.sort_order ASC, c.id DESC";
    echo json_encode(['success' => true, 'data' => $pdo->query($sql)->fetchAll()]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    $categoryId = isset($_POST['category_id']) && $_POST['category_id'] !== '' ? (int)$_POST['category_id'] : null;

    if ($action === 'create') {
        if (empty($_POST['name_fa']) || empty($_POST['slug'])) mcel_bad('نام و اسلاگ الزامی است');
        $cols = []; $ph = []; $vals = [];
        foreach ($COLUMNS as $c) {
            if (isset($_POST[$c]) && $_POST[$c] !== '') { $cols[] = "`$c`"; $ph[] = '?'; $vals[] = $_POST[$c]; }
        }
        $pdo->prepare("INSERT INTO mashahir_celebrities (" . implode(',', $cols) . ") VALUES (" . implode(',', $ph) . ")")->execute($vals);
        $newId = $pdo->lastInsertId();

        if ($categoryId) {
            $pdo->prepare("INSERT INTO celebrity_categories (celebrity_id, category_id, is_primary) VALUES (?, ?, 1)")
                ->execute([$newId, $categoryId]);
        }
        echo json_encode(['success' => true, 'id' => $newId]);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) mcel_bad('شناسه نامعتبر است');

        $sets = []; $vals = [];
        foreach ($COLUMNS as $c) {
            if (isset($_POST[$c])) { $sets[] = "`$c` = ?"; $vals[] = $_POST[$c]; }
        }
        if ($sets) {
            $vals[] = $id;
            $pdo->prepare("UPDATE mashahir_celebrities SET " . implode(', ', $sets) . " WHERE id = ?")->execute($vals);
        }

        if (array_key_exists('category_id', $_POST)) {
            $pdo->prepare("DELETE FROM celebrity_categories WHERE celebrity_id = ? AND is_primary = 1")->execute([$id]);
            if ($categoryId) {
                $pdo->prepare("INSERT INTO celebrity_categories (celebrity_id, category_id, is_primary) VALUES (?, ?, 1)
                               ON DUPLICATE KEY UPDATE is_primary = 1")->execute([$id, $categoryId]);
            }
        }
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) mcel_bad('شناسه نامعتبر است');
        $pdo->prepare("DELETE FROM celebrity_categories WHERE celebrity_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM mashahir_celebrities WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    mcel_bad('عملیات نامعتبر است');
}

mcel_bad('متد نامعتبر است');
