<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

try {
    $sql = "SELECT 
        p.id,
        p.celebrity_id,
        p.title_en as title,
        p.slug,
        p.image,
        p.price,
        p.sale_price as salePrice,
        mc.name_en as celebrityName
    FROM mashahir_products p
    LEFT JOIN mashahir_celebrities mc ON p.celebrity_id = mc.id
    WHERE p.is_active = 1 
    ORDER BY p.is_featured DESC, p.id ASC";
    
    $stmt = $pdo->query($sql);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as &$product) {
        if ($product['image']) {
            $product['image'] = '../public/img/' . $product['image'];
        }
        $product['price'] = (float)$product['price'];
        $product['salePrice'] = $product['salePrice'] ? (float)$product['salePrice'] : null;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $products,
        'count' => count($products)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>