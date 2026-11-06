<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

try {
    $lang = $_GET['lang'] ?? 'en';
    $lang = in_array($lang, ['fa', 'en']) ? $lang : 'en';

    $sql = "SELECT 
        r.id,
        r.author_name,
        r.rating,
        r.content_en as content,
        r.hashtags,
        r.likes_count as likesCount,
        mc.name_en as celebrityName
    FROM mashahir_reviews r
    LEFT JOIN mashahir_celebrities mc ON r.celebrity_id = mc.id
    WHERE r.is_active = 1 AND r.lang = ?
    ORDER BY r.likes_count DESC, r.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($reviews as &$review) {
        if ($review['hashtags']) {
            $review['hashtags'] = json_decode($review['hashtags'], true);
        } else {
            $review['hashtags'] = [];
        }
        $review['rating'] = (int)$review['rating'];
        $review['likesCount'] = (int)$review['likesCount'];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $reviews,
        'count' => count($reviews)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>