<?php
require_once __DIR__ . '/auth.php';
admin_require_login_api();
$pdo = require __DIR__ . '/../../app/config/config.php';

function mc_bad(string $msg): void
{
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM mashahir_categories ORDER BY sort_order ASC, id ASC");
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    $columns = ['name_fa', 'name_en', 'image', 'sort_order', 'is_active'];

    if ($action === 'create') {
        if (empty($_POST['name_fa'])) mc_bad('نام فارسی الزامی است');
        $cols = []; $ph = []; $vals = [];
        foreach ($columns as $c) {
            if (isset($_POST[$c]) && $_POST[$c] !== '') { $cols[] = "`$c`"; $ph[] = '?'; $vals[] = $_POST[$c]; }
        }
        $pdo->prepare("INSERT INTO mashahir_categories (" . implode(',', $cols) . ") VALUES (" . implode(',', $ph) . ")")->execute($vals);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) mc_bad('شناسه نامعتبر است');
        $sets = []; $vals = [];
        foreach ($columns as $c) {
            if (isset($_POST[$c])) { $sets[] = "`$c` = ?"; $vals[] = $_POST[$c]; }
        }
        if (!$sets) mc_bad('هیچ فیلدی ارسال نشده');
        $vals[] = $id;
        $pdo->prepare("UPDATE mashahir_categories SET " . implode(', ', $sets) . " WHERE id = ?")->execute($vals);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) mc_bad('شناسه نامعتبر است');
        $pdo->prepare("DELETE FROM mashahir_categories WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    mc_bad('عملیات نامعتبر است');
}

mc_bad('متد نامعتبر است');
