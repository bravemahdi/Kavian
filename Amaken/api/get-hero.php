<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

try {
    $sql = "SELECT 
        hc.id,
        hc.celebrity_id,
        hc.title_en as title,
        hc.subtitle_en as subtitle,
        hc.image,
        hc.link_url as linkUrl,
        hc.sort_order,
        mc.name_en as celebrityName,
        mc.job_en as celebrityJob
    FROM hero_carousel hc
    LEFT JOIN mashahir_celebrities mc ON hc.celebrity_id = mc.id
    WHERE hc.is_active = 1 
    ORDER BY hc.sort_order ASC";
    
    $stmt = $pdo->query($sql);
    $heroes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Build image paths
    foreach ($heroes as &$hero) {
        $hero['image'] = '../public/img/' . $hero['image'];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $heroes,
        'count' => count($heroes)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
