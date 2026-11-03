<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fa';
$is_fa = ($lang === 'fa');

// فیلترها
$celebrity_id  = isset($_GET['celebrity_id']) ? (int)$_GET['celebrity_id'] : 0;
$featured_only = isset($_GET['featured']) && $_GET['featured'] === '1';
$search        = isset($_GET['search']) ? trim($_GET['search']) : '';
$genre         = isset($_GET['genre']) ? trim($_GET['genre']) : '';
$era           = isset($_GET['era']) ? trim($_GET['era']) : '';

try {
    $params = [];

    $sql = "SELECT 
        p.id,
        p.celebrity_id,
        p.title_fa,
        p.title_en,
        p.slug,
        p.image,
        p.short_desc_fa,
        p.short_desc_en,
        p.author_fa,
        p.author_en,
        p.publisher_fa,
        p.publisher_en,
        p.publish_year,
        p.pages,
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
        mc.name_fa     AS celebrityNameFa,
        mc.name_en     AS celebrityNameEn,
        mc.slug        AS celebritySlug
    FROM mashahir_products p
    LEFT JOIN mashahir_celebrities mc ON p.celebrity_id = mc.id
    WHERE p.is_active = 1";

    if ($celebrity_id > 0) {
        $sql .= " AND p.celebrity_id = :celebrity_id";
        $params[':celebrity_id'] = $celebrity_id;
    }

    if ($featured_only) {
        $sql .= " AND p.is_featured = 1";
    }

    if ($genre !== '') {
        $sql .= " AND (p.genre_fa LIKE :genre OR p.genre_en LIKE :genre2)";
        $params[':genre']  = '%' . $genre . '%';
        $params[':genre2'] = '%' . $genre . '%';
    }

    if ($era !== '') {
        $sql .= " AND (p.era_fa LIKE :era OR p.era_en LIKE :era2)";
        $params[':era']  = '%' . $era . '%';
        $params[':era2'] = '%' . $era . '%';
    }

    if ($search !== '') {
        $sql .= " AND (p.title_fa LIKE :s1 OR p.title_en LIKE :s2
                    OR p.author_fa LIKE :s3 OR p.author_en LIKE :s4
                    OR mc.name_fa  LIKE :s5 OR mc.name_en  LIKE :s6
                    OR p.tags_fa   LIKE :s7 OR p.tags_en   LIKE :s8)";
        for ($i = 1; $i <= 8; $i++) {
            $params[":s$i"] = '%' . $search . '%';
        }
    }

    $sql .= " ORDER BY p.is_featured DESC, p.sort_order ASC, p.id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $products = [];
    foreach ($rows as $row) {
        $tagsRaw = $is_fa ? ($row['tags_fa'] ?: $row['tags_en']) : ($row['tags_en'] ?: $row['tags_fa']);
        $tags    = !empty($tagsRaw) ? array_map('trim', explode(',', $tagsRaw)) : [];

        $products[] = [
            'id'            => (int)$row['id'],
            'celebrityId'   => $row['celebrity_id'] ? (int)$row['celebrity_id'] : null,
            'slug'          => $row['slug'],
            'image'         => $row['image'] ? '/Kavian/mashahir/public/img/' . $row['image'] : null,
            'title'         => $is_fa ? $row['title_fa'] : ($row['title_en'] ?: $row['title_fa']),
            'titleFa'       => $row['title_fa'],
            'titleEn'       => $row['title_en'],
            'shortDesc'     => $is_fa ? $row['short_desc_fa'] : ($row['short_desc_en'] ?: $row['short_desc_fa']),
            'author'        => $is_fa ? $row['author_fa'] : ($row['author_en'] ?: $row['author_fa']),
            'publisher'     => $is_fa ? $row['publisher_fa'] : ($row['publisher_en'] ?: $row['publisher_fa']),
            'publishYear'   => $row['publish_year'],
            'pages'         => $row['pages'] ? (int)$row['pages'] : null,
            'genre'         => $is_fa ? $row['genre_fa'] : ($row['genre_en'] ?: $row['genre_fa']),
            'era'           => $is_fa ? $row['era_fa'] : ($row['era_en'] ?: $row['era_fa']),
            'tags'          => $tags,
            'pricePrint'    => $row['price_print']      ? (float)$row['price_print']      : null,
            'salePricePrint'=> $row['sale_price_print'] ? (float)$row['sale_price_print'] : null,
            'pricePdf'      => $row['price_pdf']        ? (float)$row['price_pdf']        : null,
            'salePricePdf'  => $row['sale_price_pdf']   ? (float)$row['sale_price_pdf']   : null,
            'isFeatured'    => (bool)$row['is_featured'],
            'celebrityName' => $is_fa ? $row['celebrityNameFa'] : ($row['celebrityNameEn'] ?: $row['celebrityNameFa']),
            'celebritySlug' => $row['celebritySlug'],
        ];
    }

    // لیست مشاهیر منحصربه‌فرد برای فیلتر
    $celebStmt = $pdo->query("
        SELECT DISTINCT mc.id, mc.name_fa, mc.name_en
        FROM mashahir_products p
        JOIN mashahir_celebrities mc ON p.celebrity_id = mc.id
        WHERE p.is_active = 1
        ORDER BY mc.name_fa
    ");
    $celebrities = $celebStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success'     => true,
        'language'    => $lang,
        'data'        => $products,
        'count'       => count($products),
        'celebrities' => $celebrities,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error'   => 'Database error: ' . $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
?>
