<?php


require_once __DIR__ . '/auth.php';
admin_require_login_api();

$pdo = require __DIR__ . '/../../backend/config.php';
$config = require __DIR__ . '/sections_config.php';
$validSections = array_keys($config['sections']);

$ENTITIES = [
    'products' => [
        'table'   => 'products',
        'columns' => ['name', 'description', 'price', 'image', 'status'],
        'required' => ['name'],
    ],
    'orders' => [
        'table'   => 'orders',
        'columns' => ['product_id', 'customer_name', 'customer_phone', 'quantity', 'status'],
        'required' => ['customer_name'],
    ],
    'comments' => [
        'table'   => 'comments',
        'columns' => ['product_id', 'author_name', 'content', 'status'],
        'required' => ['author_name', 'content'],
    ],
    'slides' => [
        'table'   => 'slides',
        'columns' => ['title', 'image', 'link', 'sort_order', 'status'],
        'required' => ['title'],
    ],
    'celebrities' => [
        'table'   => 'celebrities',
        'columns' => ['name', 'description', 'image', 'status'],
        'required' => ['name'],
    ],
];

function bad_request(string $msg): void
{
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

function write_log(PDO $pdo, string $action, string $description): void
{
    try {
        $stmt = $pdo->prepare("INSERT INTO site_logs (user_id, user_name, action, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'] ?? null, $_SESSION['user_name'] ?? 'ناشناس', $action, $description]);
    } catch (Exception $e) {

    }
}

$entityKey = $_GET['entity'] ?? $_POST['entity'] ?? '';
$section   = $_GET['section'] ?? $_POST['section'] ?? '';

if (!isset($ENTITIES[$entityKey])) {
    bad_request('موجودیت نامعتبر است');
}
if (!in_array($section, $validSections, true)) {
    bad_request('بخش سایت نامعتبر است');
}

$entity = $ENTITIES[$entityKey];
$table = $entity['table'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE section = ? ORDER BY id DESC");
    $stmt->execute([$section]);
    $rows = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        foreach ($entity['required'] as $field) {
            if (empty($_POST[$field])) {
                bad_request("فیلد $field الزامی است");
            }
        }
        $cols = ['section'];
        $placeholders = ['?'];
        $values = [$section];

        foreach ($entity['columns'] as $col) {
            if (isset($_POST[$col]) && $_POST[$col] !== '') {
                $cols[] = $col;
                $placeholders[] = '?';
                $values[] = $_POST[$col];
            }
        }

        $sql = "INSERT INTO `$table` (" . implode(',', $cols) . ") VALUES (" . implode(',', $placeholders) . ")";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        write_log($pdo, 'create', "افزودن یک مورد جدید در $table (بخش: $section)");
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) bad_request('شناسه نامعتبر است');

        $sets = [];
        $values = [];
        foreach ($entity['columns'] as $col) {
            if (isset($_POST[$col])) {
                $sets[] = "`$col` = ?";
                $values[] = $_POST[$col];
            }
        }
        if (!$sets) bad_request('هیچ فیلدی برای به‌روزرسانی ارسال نشده');

        $values[] = $id;
        $values[] = $section;
        $sql = "UPDATE `$table` SET " . implode(', ', $sets) . " WHERE id = ? AND section = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        write_log($pdo, 'update', "ویرایش مورد #$id در $table (بخش: $section)");
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) bad_request('شناسه نامعتبر است');

        $stmt = $pdo->prepare("DELETE FROM `$table` WHERE id = ? AND section = ?");
        $stmt->execute([$id, $section]);

        write_log($pdo, 'delete', "حذف مورد #$id از $table (بخش: $section)");
        echo json_encode(['success' => true]);
        exit;
    }

    bad_request('عملیات نامعتبر است');
}

bad_request('متد نامعتبر است');
