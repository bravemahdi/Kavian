
<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

$lang = (isset($_GET['lang']) && $_GET['lang'] === 'fa')
    ? 'fa'
    : 'en';

try {

    // دریافت دسته‌بندی‌ها و تعداد مکان‌های هر دسته
    $catStmt = $pdo->query("
        SELECT
            pc.id,
            pc.name_fa,
            pc.name_en,
            pc.image,
            pc.sort_order,
            COUNT(DISTINCT p.id) AS place_count
        FROM places_categories pc

        LEFT JOIN places_place_categories ppc
            ON ppc.category_id = pc.id

        LEFT JOIN places p
            ON p.id = ppc.place_id
            AND p.is_active = 1

        WHERE pc.is_active = 1

        GROUP BY
            pc.id,
            pc.name_fa,
            pc.name_en,
            pc.image,
            pc.sort_order

        ORDER BY pc.sort_order ASC
    ");

    $officialCats = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($officialCats as &$category) {

        $category['id'] = (int) $category['id'];
        $category['place_count'] = (int) $category['place_count'];

        $category['image'] = !empty($category['image'])
            ? '/Kavian/places/public/img/' . $category['image']
            : null;

        $category['name'] = $lang === 'fa'
            ? $category['name_fa']
            : ($category['name_en'] ?: $category['name_fa']);
    }

    unset($category);


    // دریافت تگ‌های یکتا
    $tagField = ($lang === 'fa') ? 'tags_fa' : 'tags_en';

    $tagStmt = $pdo->query("
        SELECT {$tagField}
        FROM places
        WHERE is_active = 1
          AND {$tagField} IS NOT NULL
          AND {$tagField} != ''
    ");

    $allTags = [];

    foreach ($tagStmt->fetchAll(PDO::FETCH_COLUMN) as $row) {

        $tags = explode(',', $row);

        foreach ($tags as $tag) {

            $tag = trim($tag);

            if ($tag !== '') {
                $allTags[$tag] = true;
            }
        }
    }

    $uniqueTags = array_keys($allTags);

    sort($uniqueTags, SORT_NATURAL);


    // خروجی JSON
    echo json_encode([
        'success' => true,
        'lang' => $lang,
        'categories' => $officialCats,
        'filter_tags' => $uniqueTags,
        'tags_count' => count($uniqueTags)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);


} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ], JSON_UNESCAPED_UNICODE);

}
