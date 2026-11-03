<?php
require_once __DIR__ . '/auth.php';
admin_require_full_admin_api();

$pdo = require __DIR__ . '/../../app/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
    $limit = max(10, min($limit, 500));

    $stmt = $pdo->prepare("SELECT id, user_name, action, description, created_at FROM site_logs ORDER BY id DESC LIMIT $limit");
    $stmt->execute();
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'clear') {
    $pdo->exec("TRUNCATE TABLE site_logs");
    echo json_encode(['success' => true]);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'درخواست نامعتبر']);
