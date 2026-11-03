<?php
require_once __DIR__ . '/auth.php';
admin_require_permission_api('special:users');

$pdo    = require __DIR__ . '/../../app/config/config.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->query("
        SELECT u.id, u.name, u.email, u.role, u.custom_role_id,
               r.label AS current_role_label,
               u.created_at, u.last_login
        FROM users u
        LEFT JOIN roles r ON r.id = u.custom_role_id
        ORDER BY (u.last_login IS NULL), u.last_login DESC, u.id DESC
    ");
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    // اختصاص نقش به کاربر (همه از ID)
    if ($action === 'assign_custom_role') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $roleId = (isset($_POST['role_id']) && $_POST['role_id'] !== '')
                    ? (int)$_POST['role_id']
                    : null;

        if (!$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'شناسه کاربر الزامی است']);
            exit;
        }
        if ($userId === (int)($_SESSION['user_id'] ?? 0)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'نمی‌توانید نقش خودتان را تغییر دهید']);
            exit;
        }

        if ($roleId !== null) {
            $exists = $pdo->prepare("SELECT id FROM roles WHERE id = ?");
            $exists->execute([$roleId]);
            if (!$exists->fetch()) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'نقش انتخاب‌شده معتبر نیست']);
                exit;
            }
        }

        $pdo->prepare("UPDATE users SET custom_role_id = ? WHERE id = ?")
            ->execute([$roleId, $userId]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id === (int)($_SESSION['user_id'] ?? 0)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'نمی‌توانید حساب خودتان را حذف کنید']);
            exit;
        }
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'درخواست نامعتبر']);
