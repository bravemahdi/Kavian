<?php

function mh_bad(string $msg): void
{
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

function mh_simple_crud(PDO $pdo, string $table, array $columns, array $required = [], string $orderBy = 'id DESC'): void
{
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $pdo->query("SELECT * FROM `$table` ORDER BY $orderBy");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        exit;
    }

    if ($method === 'POST') {
        $action = $_POST['action'] ?? '';

        if ($action === 'create') {
            foreach ($required as $f) {
                if (!isset($_POST[$f]) || $_POST[$f] === '') mh_bad("فیلد $f الزامی است");
            }
            $cols = []; $ph = []; $vals = [];
            foreach ($columns as $c) {
                if (isset($_POST[$c]) && $_POST[$c] !== '') { $cols[] = "`$c`"; $ph[] = '?'; $vals[] = $_POST[$c]; }
            }
            if (!$cols) mh_bad('هیچ فیلدی ارسال نشده');
            $pdo->prepare("INSERT INTO `$table` (" . implode(',', $cols) . ") VALUES (" . implode(',', $ph) . ")")->execute($vals);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            exit;
        }

        if ($action === 'update') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) mh_bad('شناسه نامعتبر است');
            $sets = []; $vals = [];
            foreach ($columns as $c) {
                if (isset($_POST[$c])) { $sets[] = "`$c` = ?"; $vals[] = $_POST[$c]; }
            }
            if (!$sets) mh_bad('هیچ فیلدی برای به‌روزرسانی ارسال نشده');
            $vals[] = $id;
            $pdo->prepare("UPDATE `$table` SET " . implode(', ', $sets) . " WHERE id = ?")->execute($vals);
            echo json_encode(['success' => true]);
            exit;
        }

        if ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) mh_bad('شناسه نامعتبر است');
            $pdo->prepare("DELETE FROM `$table` WHERE id = ?")->execute([$id]);
            echo json_encode(['success' => true]);
            exit;
        }

        mh_bad('عملیات نامعتبر است');
    }

    mh_bad('متد نامعتبر است');
}
