
<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

try {

    $sql = "
        SELECT
            hc.id,
            hc.place_id,
            hc.title_en AS title,
            hc.subtitle_en AS subtitle,
            hc.image,
            hc.link_url AS linkUrl,
            hc.sort_order,
            p.name_en AS placeName,
            p.name_fa AS placeNameFa
        FROM places_hero_carousel hc
        LEFT JOIN places p
            ON hc.place_id = p.id
        WHERE hc.is_active = 1
        ORDER BY hc.sort_order ASC
    ";

    $stmt = $pdo->query($sql);

    $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Build image paths
    foreach ($heroes as &$hero) {

        $hero['image'] = !empty($hero['image'])
            ? '../public/img/' . $hero['image']
            : null;

    }

    unset($hero);

    echo json_encode([
        'success' => true,
        'data' => $heroes,
        'count' => count($heroes)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ], JSON_UNESCAPED_UNICODE);

}

?>
