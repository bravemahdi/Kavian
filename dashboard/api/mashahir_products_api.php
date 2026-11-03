<?php
require_once __DIR__ . '/auth.php';
admin_require_login_api();
$pdo = require __DIR__ . '/../../app/config/config.php';

function mp_bad(string $msg): void
{
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $msg]);
    exit;
}

$COLUMNS = [
    'celebrity_id',
    'title_fa', 'title_en', 'full_title_fa', 'full_title_en',
    'slug', 'image',
    'short_desc_fa', 'short_desc_en',
    'description_fa', 'description_en',
    'quote_fa', 'quote_en',
    'author_fa', 'author_en',
    'translator_fa', 'translator_en',
    'publisher_fa', 'publisher_en',
    'publish_year', 'pages',
    'language_fa', 'language_en',
    'genre_fa', 'genre_en',
    'era_fa', 'era_en',
    'tags_fa', 'tags_en',
    'price_print', 'sale_price_print',
    'price_pdf', 'sale_price_pdf',
    'stock',
    'is_featured', 'is_active', 'sort_order'
];

$NULLABLE_ON_EMPTY = ['celebrity_id', 'price_print', 'sale_price_print', 'price_pdf', 'sale_price_pdf', 'publish_year', 'pages'];
$ZERO_ON_EMPTY = ['stock'];

function mp_current_role(): ?string
{
    return $_SESSION['user_role'] ?? null;
}

$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET['editions']) || (isset($_POST['action']) && $_POST['action'] === 'save_editions')) {

    if ($method === 'GET') {
        $productId = (int)($_GET['product_id'] ?? 0);
        if ($productId <= 0) mp_bad('شناسه محصول نامعتبر است');
        $stmt = $pdo->prepare("SELECT * FROM mashahir_product_editions WHERE product_id = ? ORDER BY edition_type ASC, sort_order ASC, id ASC");
        $stmt->execute([$productId]);
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        exit;
    }

    if ($method === 'POST' && ($_POST['action'] ?? '') === 'save_editions') {
        $productId = (int)($_POST['product_id'] ?? 0);
        if ($productId <= 0) mp_bad('شناسه محصول نامعتبر است');

        $editions = json_decode($_POST['editions_json'] ?? '[]', true);
        if (!is_array($editions)) $editions = [];

        $pdo->beginTransaction();
        try {

            $pdo->prepare("DELETE FROM mashahir_product_editions WHERE product_id = ?")->execute([$productId]);

            $ins = $pdo->prepare("INSERT INTO mashahir_product_editions
                (product_id, edition_type, lang, label_fa, label_en, file_path, file_size_kb, pages, sort_order, is_active)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            foreach ($editions as $i => $e) {
                $type = ($e['edition_type'] ?? 'pdf') === 'print' ? 'print' : 'pdf';
                $lang = ($e['lang'] ?? 'fa') === 'en' ? 'en' : 'fa';
                $labelFa = trim($e['label_fa'] ?? '');
                $labelEn = trim($e['label_en'] ?? '');
                if ($labelFa === '' && $labelEn === '') continue;
                $ins->execute([
                    $productId,
                    $type,
                    $lang,
                    $labelFa !== '' ? $labelFa : $labelEn,
                    $labelEn !== '' ? $labelEn : $labelFa,
                    $e['file_path'] ?? '',
                    isset($e['file_size_kb']) && $e['file_size_kb'] !== '' ? (int)$e['file_size_kb'] : null,
                    isset($e['pages']) && $e['pages'] !== '' ? (int)$e['pages'] : null,
                    $i,
                    isset($e['is_active']) && (int)$e['is_active'] === 0 ? 0 : 1,
                ]);
            }

            $pdo->commit();
        } catch (Throwable $ex) {
            $pdo->rollBack();
            mp_bad('خطا در ذخیره نسخه‌های محصول: ' . $ex->getMessage());
        }

        echo json_encode(['success' => true]);
        exit;
    }

    mp_bad('درخواست نامعتبر است');
}

if ($method === 'GET') {

    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT p.*, c.name_fa AS celebrity_name_fa
            FROM mashahir_products p
            LEFT JOIN mashahir_celebrities c ON c.id = p.celebrity_id
            WHERE p.id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        echo json_encode(['success' => true, 'data' => $row ?: null]);
        exit;
    }

    $sql = "SELECT p.*, c.name_fa AS celebrity_name_fa
            FROM mashahir_products p
            LEFT JOIN mashahir_celebrities c ON c.id = p.celebrity_id
            ORDER BY p.sort_order ASC, p.id DESC";
    echo json_encode(['success' => true, 'data' => $pdo->query($sql)->fetchAll()]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        if (empty($_POST['title_fa']) || empty($_POST['slug'])) mp_bad('عنوان فارسی و اسلاگ الزامی است');
        $cols = []; $ph = []; $vals = [];
        foreach ($COLUMNS as $c) {
            if (isset($_POST[$c]) && $_POST[$c] !== '') {
                $cols[] = "`$c`"; $ph[] = '?'; $vals[] = $_POST[$c];
            } elseif (isset($_POST[$c]) && in_array($c, $NULLABLE_ON_EMPTY, true)) {
                $cols[] = "`$c`"; $ph[] = '?'; $vals[] = null;
            }
        }

        $role = mp_current_role();
        if ($role !== null) {
            $cols[] = '`created_by`'; $ph[] = '?'; $vals[] = $role;
        }
        $pdo->prepare("INSERT INTO mashahir_products (" . implode(',', $cols) . ") VALUES (" . implode(',', $ph) . ")")->execute($vals);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) mp_bad('شناسه نامعتبر است');
        $sets = []; $vals = [];
        foreach ($COLUMNS as $c) {
            if (array_key_exists($c, $_POST)) {
                $sets[] = "`$c` = ?";
                if ($_POST[$c] === '') {
                    $vals[] = in_array($c, $ZERO_ON_EMPTY, true) ? 0 : null;
                } else {
                    $vals[] = $_POST[$c];
                }
            }
        }
        if (!$sets) mp_bad('هیچ فیلدی ارسال نشده');
        $vals[] = $id;
        $pdo->prepare("UPDATE mashahir_products SET " . implode(', ', $sets) . " WHERE id = ?")->execute($vals);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'adjust_stock') {
        $id = (int)($_POST['id'] ?? 0);
        $delta = (int)($_POST['delta'] ?? 0);
        if ($id <= 0) mp_bad('شناسه نامعتبر است');
        if ($delta === 0) mp_bad('مقدار تغییر موجودی نامعتبر است');
        $stmt = $pdo->prepare("UPDATE mashahir_products SET stock = GREATEST(0, stock + ?) WHERE id = ?");
        $stmt->execute([$delta, $id]);
        $stock = $pdo->prepare("SELECT stock FROM mashahir_products WHERE id = ?");
        $stock->execute([$id]);
        echo json_encode(['success' => true, 'stock' => (int)$stock->fetchColumn()]);
        exit;
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) mp_bad('شناسه نامعتبر است');
        $pdo->prepare("DELETE FROM mashahir_product_editions WHERE product_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM mashahir_products WHERE id = ?")->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }

    mp_bad('عملیات نامعتبر است');
}

mp_bad('متد نامعتبر است');
