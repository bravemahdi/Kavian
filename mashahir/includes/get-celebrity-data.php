<?php
// This file provides celebrity data for JavaScript
header('Content-Type: application/json; charset=utf-8');

$pdo = require __DIR__ . '/../../app/config/config.php';

$current_lang = (strpos($_SERVER['REQUEST_URI'], '/persian/') !== false) ? 'fa' : 'en';
$is_fa = ($current_lang === 'fa');

try {
    // Fetch all celebrities
    $sql = "SELECT 
        id,
        name_en,
        name_fa,
        full_name_en,
        full_name_fa,
        birth_year,
        birth_place_en,
        birth_place_fa,
        death_year,
        death_place_en,
        death_place_fa,
        job_en,
        job_fa,
        era_en,
        era_fa,
        short_story_en,
        short_story_fa,
        biography_en,
        biography_fa,
        quote_en,
        quote_fa,
        image
    FROM mashahir_celebrities 
    WHERE is_active = 1 
    ORDER BY sort_order ASC, name_en ASC";
    
    $stmt = $pdo->query($sql);
    $celebrities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get categories for each celebrity
    foreach ($celebrities as &$celebrity) {
        $catSql = "SELECT mc.name_en, mc.name_fa 
                   FROM mashahir_categories mc
                   INNER JOIN celebrity_categories cc ON mc.id = cc.category_id
                   WHERE cc.celebrity_id = ? AND mc.is_active = 1";
        $catStmt = $pdo->prepare($catSql);
        $catStmt->execute([$celebrity['id']]);
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Build categories array based on language
        $celebrity['categories'] = array_map(function($cat) use ($is_fa) {
            return $is_fa ? $cat['name_fa'] : $cat['name_en'];
        }, $categories);
        
        // Build image path
        $celebrity['img'] = '../public/img/' . $celebrity['image'];
        
        // Create language-specific output
        $celebrity['name'] = $is_fa ? $celebrity['name_fa'] : $celebrity['name_en'];
        $celebrity['fullName'] = $is_fa ? $celebrity['full_name_fa'] : $celebrity['full_name_en'];
        $celebrity['birth'] = $celebrity['birth_year'];
        $celebrity['birthPlace'] = $is_fa ? $celebrity['birth_place_fa'] : $celebrity['birth_place_en'];
        $celebrity['death'] = $celebrity['death_year'];
        $celebrity['deathPlace'] = $is_fa ? $celebrity['death_place_fa'] : $celebrity['death_place_en'];
        $celebrity['job'] = $is_fa ? $celebrity['job_fa'] : $celebrity['job_en'];
        $celebrity['era'] = $is_fa ? $celebrity['era_fa'] : $celebrity['era_en'];
        $celebrity['shortStory'] = $is_fa ? $celebrity['short_story_fa'] : $celebrity['short_story_en'];
        $celebrity['biography'] = $is_fa ? $celebrity['biography_fa'] : $celebrity['biography_en'];
        $celebrity['quote'] = $is_fa ? $celebrity['quote_fa'] : $celebrity['quote_en'];
        
        // Remove raw fields
        unset(
            $celebrity['name_en'], $celebrity['name_fa'],
            $celebrity['full_name_en'], $celebrity['full_name_fa'],
            $celebrity['birth_year'], $celebrity['birth_place_en'], $celebrity['birth_place_fa'],
            $celebrity['death_year'], $celebrity['death_place_en'], $celebrity['death_place_fa'],
            $celebrity['job_en'], $celebrity['job_fa'],
            $celebrity['era_en'], $celebrity['era_fa'],
            $celebrity['short_story_en'], $celebrity['short_story_fa'],
            $celebrity['biography_en'], $celebrity['biography_fa'],
            $celebrity['quote_en'], $celebrity['quote_fa'],
            $celebrity['image']
        );
    }
    
    echo json_encode([
        'success' => true,
        'language' => $current_lang,
        'data' => $celebrities,
        'count' => count($celebrities)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
?>
