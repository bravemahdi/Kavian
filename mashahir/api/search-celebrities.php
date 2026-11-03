<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

$lang = 'en';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['fa', 'en'])) {
    $lang = $_GET['lang'];
} elseif (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/persian/') !== false) {
    $lang = 'fa';
}
$is_fa = ($lang === 'fa');

$q = trim($_GET['q'] ?? '');

if (mb_strlen($q) < 1) {
    echo json_encode(['success' => true, 'data' => [], 'count' => 0], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $search = '%' . $q . '%';

    $sql = "SELECT 
                id,
                name_fa, name_en,
                full_name_fa, full_name_en,
                job_fa, job_en,
                birth_year,
                image
            FROM mashahir_celebrities
            WHERE is_active = 1
              AND (
                  name_fa       LIKE :q1 OR
                  name_en       LIKE :q2 OR
                  full_name_fa  LIKE :q3 OR
                  full_name_en  LIKE :q4 OR
                  tags_fa       LIKE :q5 OR
                  tags_en       LIKE :q6
              )
            ORDER BY
                CASE
                    WHEN name_fa = :exact OR name_en = :exact THEN 0
                    WHEN name_fa LIKE :starts OR name_en LIKE :starts THEN 1
                    ELSE 2
                END,
                sort_order ASC,
                name_en ASC
            LIMIT 8";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':q1'     => $search,
        ':q2'     => $search,
        ':q3'     => $search,
        ':q4'     => $search,
        ':q5'     => $search,
        ':q6'     => $search,
        ':exact'  => $q,
        ':starts' => $q . '%',
    ]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results = array_map(function ($row) use ($is_fa) {
        return [
            'id'       => (int)$row['id'],
            'name'     => $is_fa ? ($row['name_fa'] ?: $row['name_en']) : $row['name_en'],
            'fullName' => $is_fa ? ($row['full_name_fa'] ?: $row['full_name_en']) : $row['full_name_en'],
            'job'      => $is_fa ? ($row['job_fa'] ?: $row['job_en']) : $row['job_en'],
            'birth'    => $row['birth_year'] ?? '',
            'img'      => '../public/img/' . $row['image'],
        ];
    }, $rows);

    echo json_encode([
        'success'  => true,
        'language' => $lang,
        'query'    => $q,
        'data'     => $results,
        'count'    => count($results),
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error'   => 'Database error: ' . $e->getMessage(),
    ], JSON_UNESCAPED_UNICODE);
}
?>
