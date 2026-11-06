<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

// تشخیص زبان
$lang = 'fa';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fa', 'en'])) {
    $lang = $_GET['lang'];
} elseif (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/persian/') !== false) {
    $lang = 'fa';
}
$is_fa = ($lang === 'fa');

// شناسه محصول — از id یا slug
$id   = isset($_GET['id'])   ? (int)$_GET['id']         : 0;
$slug = isset($_GET['slug']) ? trim($_GET['slug'])       : '';

if ($id <= 0 && $slug === '') {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'id یا slug الزامی است'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $sql = "SELECT 
        p.id,
        p.celebrity_id,
        p.title_fa,
        p.title_en,
        p.full_title_fa,
        p.full_title_en,
        p.slug,
        p.image,
        p.short_desc_fa,
        p.short_desc_en,
        p.description_fa,
        p.description_en,
        p.quote_fa,
        p.quote_en,
        p.author_fa,
        p.author_en,
        p.translator_fa,
        p.translator_en,
        p.publisher_fa,
        p.publisher_en,
        p.publish_year,
        p.pages,
        p.language_fa,
        p.language_en,
        p.genre_fa,
        p.genre_en,
        p.era_fa,
        p.era_en,
        p.tags_fa,
        p.tags_en,
        p.price_print,
        p.sale_price_print,
        p.price_pdf,
        p.sale_price_pdf,
        p.is_featured,
        p.sort_order,
        p.views_count,
        mc.id          AS celebrity_db_id,
        mc.name_fa     AS celebrity_name_fa,
        mc.name_en     AS celebrity_name_en,
        mc.image       AS celebrity_image,
        mc.slug        AS celebrity_slug
    FROM mashahir_products p
    LEFT JOIN mashahir_celebrities mc ON p.celebrity_id = mc.id
    WHERE p.is_active = 1 AND ";

    if ($id > 0) {
        $sql .= "p.id = :val";
        $param = [':val' => $id];
    } else {
        $sql .= "p.slug = :val";
        $param = [':val' => $slug];
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($param);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'محصول یافت نشد'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // افزایش بازدید
    $pdo->prepare("UPDATE mashahir_products SET views_count = views_count + 1 WHERE id = ?")
        ->execute([$row['id']]);

    // ── دریافت نسخه‌های موجود (PDF و چاپی) ──────────────────────
    $edStmt = $pdo->prepare(
        "SELECT id, edition_type, lang, label_fa, label_en, file_path, file_size_kb, pages, sort_order
         FROM mashahir_product_editions
         WHERE product_id = ? AND is_active = 1
         ORDER BY edition_type, sort_order ASC, id ASC"
    );
    $edStmt->execute([$row['id']]);
    $edRows = $edStmt->fetchAll(PDO::FETCH_ASSOC);

    $pdfVersions   = [];
    $printVersions = [];
    foreach ($edRows as $r) {
        $item = [
            'id'           => (int)$r['id'],
            'lang'         => $r['lang'],
            'label'        => $is_fa ? $r['label_fa'] : $r['label_en'],
            'label_fa'     => $r['label_fa'],
            'label_en'     => $r['label_en'],
            'file_path'    => $r['file_path'],
            'file_size_kb' => $r['file_size_kb'] ? (int)$r['file_size_kb'] : null,
            'pages'        => $r['pages']        ? (int)$r['pages']        : null,
        ];
        if ($r['edition_type'] === 'pdf') {
            $pdfVersions[] = $item;
        } else {
            $printVersions[] = $item;
        }
    }

    // پردازش تگ‌ها
    $tagsRaw = $is_fa ? ($row['tags_fa'] ?: $row['tags_en']) : ($row['tags_en'] ?: $row['tags_fa']);
    $tags = !empty($tagsRaw) ? array_map('trim', explode(',', $tagsRaw)) : [];

    $product = [
        'id'            => (int)$row['id'],
        'slug'          => $row['slug'],
        'image'         => $row['image'] ? '../public/img/' . $row['image'] : null,
        // عنوان
        'title'         => $is_fa ? $row['title_fa'] : ($row['title_en'] ?: $row['title_fa']),
        'titleFa'       => $row['title_fa'],
        'titleEn'       => $row['title_en'],
        'fullTitle'     => $is_fa ? ($row['full_title_fa'] ?: $row['title_fa'])
                                  : ($row['full_title_en'] ?: $row['title_en']),
        // توضیح
        'shortDesc'     => $is_fa ? $row['short_desc_fa'] : ($row['short_desc_en'] ?: $row['short_desc_fa']),
        'description'   => $is_fa ? $row['description_fa'] : ($row['description_en'] ?: $row['description_fa']),
        'quote'         => $is_fa ? $row['quote_fa'] : ($row['quote_en'] ?: $row['quote_fa']),
        // کتاب‌شناسی
        'author'        => $is_fa ? $row['author_fa'] : ($row['author_en'] ?: $row['author_fa']),
        'authorFa'      => $row['author_fa'],
        'authorEn'      => $row['author_en'],
        'translator'    => $is_fa ? $row['translator_fa'] : ($row['translator_en'] ?: $row['translator_fa']),
        'publisher'     => $is_fa ? $row['publisher_fa'] : ($row['publisher_en'] ?: $row['publisher_fa']),
        'publishYear'   => $row['publish_year'],
        'pages'         => $row['pages'] ? (int)$row['pages'] : null,
        'language'      => $is_fa ? $row['language_fa'] : ($row['language_en'] ?: $row['language_fa']),
        'genre'         => $is_fa ? $row['genre_fa'] : ($row['genre_en'] ?: $row['genre_fa']),
        'era'           => $is_fa ? $row['era_fa'] : ($row['era_en'] ?: $row['era_fa']),
        'tags'          => $tags,
        // قیمت‌گذاری
        'pricePrint'    => $row['price_print']      ? (float)$row['price_print']      : null,
        'salePricePrint'=> $row['sale_price_print'] ? (float)$row['sale_price_print'] : null,
        'pricePdf'      => $row['price_pdf']        ? (float)$row['price_pdf']        : null,
        'salePricePdf'  => $row['sale_price_pdf']   ? (float)$row['sale_price_pdf']   : null,
        // مشهور مرتبط
        'celebrity' => $row['celebrity_db_id'] ? [
            'id'    => (int)$row['celebrity_db_id'],
            'name'  => $is_fa ? $row['celebrity_name_fa'] : ($row['celebrity_name_en'] ?: $row['celebrity_name_fa']),
            'image' => $row['celebrity_image'] ? '../public/img/' . $row['celebrity_image'] : null,
            'slug'  => $row['celebrity_slug'],
        ] : null,
        // متا
        'isFeatured'    => (bool)$row['is_featured'],
        'viewsCount'    => (int)$row['views_count'],
        // نسخه‌های PDF و چاپی چندزبانه
        'pdfVersions'   => $pdfVersions,
        'printVersions' => $printVersions,
    ];

    echo json_encode([
        'success'  => true,
        'language' => $lang,
        'data'     => $product,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database error: ' . $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
?>
