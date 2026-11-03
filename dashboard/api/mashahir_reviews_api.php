<?php
require_once __DIR__ . '/auth.php';
admin_require_login_api();
$pdo = require __DIR__ . '/../../app/config/config.php';

function mr_bad(string $msg): void
{
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

$COLUMNS = ['celebrity_id', 'product_id', 'author_name', 'rating', 'content_fa', 'content_en', 'lang', 'status'];

function mr_is_active_for_status(string $status): int
{
    return $status === 'approved' ? 1 : 0;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {

    $where = '';
    $params = [];
    if (!empty($_GET['status']) && in_array($_GET['status'], ['pending', 'approved', 'rejected'], true)) {
        $where = 'WHERE r.status = ?';
        $params[] = $_GET['status'];
    }
    $sql = "SELECT r.*, c.name_fa AS celebrity_name_fa, p.title_fa AS product_title_fa
            FROM mashahir_reviews r
            LEFT JOIN mashahir_celebrities c ON c.id = r.celebrity_id
            LEFT JOIN mashahir_products p ON p.id = r.product_id
            $where
            ORDER BY (r.status = 'pending') DESC, r.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        if (empty($_POST['author_name']) || empty($_POST['content_fa'])) mr_bad('نام نویسنده و متن نظر الزامی است');
        $status = in_array($_POST['status'] ?? '', ['pending', 'approved', 'rejected'], true) ? $_POST['status'] : 'approved';

        $cols = ['is_active']; $ph = ['?']; $vals = [mr_is_active_for_status($status)];
        foreach ($COLUMNS as $c) {
            $v = $c === 'status' ? $status : ($_POST[$c] ?? '');
            if ($v !== '') { $cols[] = "`$c`"; $ph[] = '?'; $vals[] = $v; }
        }
        $pdo->prepare("INSERT INTO mashahir_reviews (" . implode(',', $cols) . ") VALUES (" . implode(',', $ph) . ")")->execute($vals);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) mr_bad('شناسه نامعتبر است');
        $sets = []; $vals = [];
        foreach ($COLUMNS as $c) {
            if (isset($_POST[$c])) { $sets[] = "`$c` = ?"; $vals[] = $_POST[$c]; }
        }

        if (isset($_POST['status']) && in_array($_POST['status'], ['pending', 'approved', 'rejected'], true)) {
            $sets[] = "`is_active` = ?"; $vals[] = mr_is_active_for_status($_POST['status']);
        }
        if (!$sets) mr_bad('هیچ فیلدی ارسال نشده');
        $vals[] = $id;
        $pdo->prepare("UPDATE mashahir_reviews SET " . implode(', ', $sets) . " WHERE id = ?")->execute($vals);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'moderate') {
        $id = (int)($_POST['id'] ?? 0);
        $decision = $_POST['decision'] ?? '';
        if ($id <= 0) mr_bad('شناسه نامعتبر است');
        if (!in_array($decision, ['approved', 'rejected', 'pending'], true)) mr_bad('وضعیت نامعتبر است');
        $pdo->prepare("UPDATE mashahir_reviews SET status = ?, is_active = ? WHERE id = ?")
            ->execute([$decision, mr_is_active_for_status($decision), $id]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) mr_bad('شناسه نامعتبر است');
        $pdo->prepare("DELETE FROM mashahir_reviews WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    mr_bad('عملیات نامعتبر است');
}

mr_bad('متد نامعتبر است');
