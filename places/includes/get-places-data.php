<?php
// This file provides place data for JavaScript
header('Content-Type: application/json; charset=utf-8');

$pdo = require __DIR__ . '/../../app/config/config.php';

// زبان: اول از ?lang، وگرنه از آدرس (مثل مشاهیر)
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fa', 'en'], true)) {
    $current_lang = $_GET['lang'];
} else {
    $current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
}
$is_fa = ($current_lang === 'fa');
$pick  = function ($fa, $en) use ($is_fa) { return $is_fa ? $fa : $en; };
$tags  = function ($str) {
    return $str === null || $str === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $str))));
};

try {
    // اختیاری: فقط یک مکان
    $where  = 'WHERE is_active = 1';
    $params = [];
    if (isset($_GET['id']) && ctype_digit($_GET['id'])) {
        $where .= ' AND id = ?';
        $params[] = (int)$_GET['id'];
    }

    $sql = "SELECT
        id, slug,
        name_en, name_fa,
        full_name_en, full_name_fa,
        province_en, province_fa,
        city_en, city_fa,
        address_en, address_fa,
        latitude, longitude,
        established_year,
        era_en, era_fa,
        short_story_en, short_story_fa,
        description_en, description_fa,
        history_en, history_fa,
        quote_en, quote_fa,
        visiting_hours_en, visiting_hours_fa,
        best_season_en, best_season_fa,
        tags_en, tags_fa,
        image, is_featured
    FROM amaken_places
    $where
    ORDER BY sort_order ASC, name_en ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // دسته‌ها: یک کوئری برای همه
    $catsByPlace = [];
    $catStmt = $pdo->query(
        "SELECT pc.place_id, ac.name_en, ac.name_fa
         FROM amaken_place_categories pc
         INNER JOIN amaken_categories ac ON ac.id = pc.category_id
         WHERE ac.is_active = 1
         ORDER BY pc.is_primary DESC, ac.sort_order ASC"
    );
    foreach ($catStmt->fetchAll(PDO::FETCH_ASSOC) as $c) {
        $catsByPlace[$c['place_id']][] = $is_fa ? $c['name_fa'] : $c['name_en'];
    }

    // معرفی‌ها (رستوران و...): یک کوئری برای همه
    $recsByPlace = [];
    $recStmt = $pdo->query(
        "SELECT id, place_id, type, slug, image,
                title_en, title_fa,
                short_desc_en, short_desc_fa,
                description_en, description_fa,
                address_en, address_fa,
                phone, website, map_url, price_level,
                tags_en, tags_fa
         FROM amaken_recommendations
         WHERE is_active = 1
         ORDER BY is_featured DESC, sort_order ASC, id ASC"
    );
    foreach ($recStmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $recsByPlace[$r['place_id']][] = [
            'id'          => (int)$r['id'],
            'type'        => $r['type'],
            'slug'        => $r['slug'],
            'title'       => $pick($r['title_fa'], $r['title_en']),
            'shortDesc'   => $pick($r['short_desc_fa'], $r['short_desc_en']),
            'description' => $pick($r['description_fa'], $r['description_en']),
            'address'     => $pick($r['address_fa'], $r['address_en']),
            'phone'       => $r['phone'],
            'website'     => $r['website'],
            'mapUrl'      => $r['map_url'],
            'priceLevel'  => $r['price_level'] !== null ? (int)$r['price_level'] : null,
            'tags'        => $tags($pick($r['tags_fa'], $r['tags_en'])),
            'img'         => $r['image'] ? '../public/img/' . $r['image'] : null,
        ];
    }

    // خروجی نهایی (همان الگوی مشاهیر)
    $places = [];
    foreach ($rows as $p) {
        $places[] = [
            'id'            => (int)$p['id'],
            'slug'          => $p['slug'],
            'name'          => $pick($p['name_fa'], $p['name_en']),
            'fullName'      => $pick($p['full_name_fa'], $p['full_name_en']),
            'province'      => $pick($p['province_fa'], $p['province_en']),
            'city'          => $pick($p['city_fa'], $p['city_en']),
            'address'       => $pick($p['address_fa'], $p['address_en']),
            'latitude'      => $p['latitude'] !== null ? (float)$p['latitude'] : null,
            'longitude'     => $p['longitude'] !== null ? (float)$p['longitude'] : null,
            'established'   => $p['established_year'],
            'era'           => $pick($p['era_fa'], $p['era_en']),
            'shortStory'    => $pick($p['short_story_fa'], $p['short_story_en']),
            'description'   => $pick($p['description_fa'], $p['description_en']),
            'history'       => $pick($p['history_fa'], $p['history_en']),
            'quote'         => $pick($p['quote_fa'], $p['quote_en']),
            'visitingHours' => $pick($p['visiting_hours_fa'], $p['visiting_hours_en']),
            'bestSeason'    => $pick($p['best_season_fa'], $p['best_season_en']),
            'tags'          => $tags($pick($p['tags_fa'], $p['tags_en'])),
            'isFeatured'    => (bool)$p['is_featured'],
            'img'           => '../public/img/' . $p['image'],
            'categories'    => $catsByPlace[$p['id']] ?? [],
            'recommendations' => $recsByPlace[$p['id']] ?? [],
        ];
    }

    echo json_encode([
        'success'  => true,
        'language' => $current_lang,
        'data'     => $places,
        'count'    => count($places)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    error_log('get-place-data: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error'   => 'Database error'
    ], JSON_UNESCAPED_UNICODE);
}