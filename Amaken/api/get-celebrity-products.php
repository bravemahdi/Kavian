<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

// تشخیص زبان
$lang = 'fa';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fa', 'en'])) {
    $lang = $_GET['lang'];
}
$is_fa = ($lang === 'fa');

// شناسه celebrity
$celebrity_id = isset($_GET['celebrity_id']) ? (int)$_GET['celebrity_id'] : 0;

if ($celebrity_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'celebrity_id الزامی است'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $sql = "SELECT
        p.id,
        p.slug,
        p.title_fa,
        p.title_en,
        p.image,
        p.short_desc_fa,
        p.short_desc_en,
        p.author_fa,
        p.author_en,
        p.price_print,
        p.sale_price_print,
        p.price_pdf,
        p.sale_price_pdf,
        p.is_featured,
        p.genre_fa,
        p.genre_en
    FROM mashahir_products p
    WHERE p.is_active = 1
      AND p.celebrity_id = :celebrity_id
    ORDER BY p.is_featured DESC, p.sort_order ASC, p.id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':celebrity_id' => $celebrity_id]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $result = [];
    foreach ($products as $p) {
        // محاسبه قیمت نمایشی
        $displayPrice = null;
        $originalPrice = null;

        if ($p['sale_price_print'])      $displayPrice = (float)$p['sale_price_print'];
        elseif ($p['price_print'])       $displayPrice = (float)$p['price_print'];
        if ($p['sale_price_pdf'])        $alt = (float)$p['sale_price_pdf'];
        elseif ($p['price_pdf'])         $alt = (float)$p['price_pdf'];
        else                             $alt = null;

        if ($alt !== null && ($displayPrice === null || $alt < $displayPrice)) {
            $displayPrice = $alt;
        }

        if ($p['sale_price_print'] && $p['price_print'])
            $originalPrice = (float)$p['price_print'];
        elseif ($p['sale_price_pdf'] && $p['price_pdf'])
            $originalPrice = (float)$p['price_pdf'];

        $result[] = [
            'id'            => (int)$p['id'],
            'slug'          => $p['slug'],
            'title'         => $is_fa ? $p['title_fa'] : $p['title_en'],
            'shortDesc'     => $is_fa ? $p['short_desc_fa'] : $p['short_desc_en'],
            'author'        => $is_fa ? $p['author_fa'] : $p['author_en'],
            'genre'         => $is_fa ? $p['genre_fa'] : $p['genre_en'],
            'image'         => $p['image'],
            'isFeatured'    => (bool)$p['is_featured'],
            'displayPrice'  => $displayPrice,
            'originalPrice' => $originalPrice,
        ];
    }

    echo json_encode([
        'success' => true,
        'count'   => count($result),
        'data'    => $result,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
