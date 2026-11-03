<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

$lang = isset($_GET['lang']) && $_GET['lang'] === 'fa' ? 'fa' : 'en';

try {
    // ── ۱. دسته‌بندی‌های رسمی از جدول mashahir_categories ──────
    $catStmt = $pdo->query("
        SELECT mc.id, mc.name_fa, mc.name_en, mc.image, mc.sort_order,
               COUNT(cc.celebrity_id) AS celebrity_count
        FROM mashahir_categories mc
        LEFT JOIN celebrity_categories cc ON cc.category_id = mc.id
        LEFT JOIN mashahir_celebrities cel ON cel.id = cc.celebrity_id AND cel.is_active = 1
        WHERE mc.is_active = 1
        GROUP BY mc.id
        ORDER BY mc.sort_order ASC
    ");
    $officialCats = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($officialCats as &$c) {
        $c['id']              = (int)$c['id'];
        $c['celebrity_count'] = (int)$c['celebrity_count'];
        $c['image']           = $c['image'] ? '/Kavian/mashahir/public/img/' . $c['image'] : null;
        $c['name']            = $lang === 'fa' ? $c['name_fa'] : ($c['name_en'] ?: $c['name_fa']);
    }
    unset($c);

    // ── ۲. تمام tags یکتا از فیلد tags_fa / tags_en مشاهیر ────
    $tagField = $lang === 'fa' ? 'tags_fa' : 'tags_en';
    $tagStmt  = $pdo->query("
        SELECT {$tagField} AS tags
        FROM mashahir_celebrities
        WHERE is_active = 1 AND {$tagField} IS NOT NULL AND {$tagField} != ''
    ");
    $allTags = [];
    foreach ($tagStmt->fetchAll(PDO::FETCH_COLUMN) as $row) {
        foreach (array_map('trim', explode(',', $row)) as $tag) {
            if ($tag !== '') $allTags[$tag] = true;
        }
    }
    $uniqueTags = array_keys($allTags);
    sort($uniqueTags);

    echo json_encode([
        'success'       => true,
        'lang'          => $lang,
        'categories'    => $officialCats,          // برای صفحه اصلی (grid تصویردار)
        'filter_tags'   => $uniqueTags,            // برای فیلتر profile.php
        'tags_count'    => count($uniqueTags),
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
