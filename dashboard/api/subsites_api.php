<?php
require_once __DIR__ . '/auth.php';
admin_require_full_admin_api();

$pdo = require __DIR__ . '/../../app/config/config.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->query("SELECT * FROM subsites ORDER BY id DESC");
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $description = trim($_POST['description'] ?? '');
        if ($name === '' || $url === '') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'نام و آدرس الزامی است']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO subsites (name, url, description, status) VALUES (?, ?, ?, 'active')");
        $stmt->execute([$name, $url, $description]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'active';
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'شناسه نامعتبر']);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE subsites SET name=?, url=?, description=?, status=? WHERE id=?");
        $stmt->execute([$name, $url, $description, $status, $id]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM subsites WHERE id=?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'درخواست نامعتبر']);
