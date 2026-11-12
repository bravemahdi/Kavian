
<?php

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$pdo = require __DIR__ . '/../../app/config/config.php';

try {

    $lang = $_GET['lang'] ?? 'en';
    $lang = in_array($lang, ['fa', 'en']) ? $lang : 'en';

    $contentField = ($lang === 'fa') ? 'content_fa' : 'content_en';

    $sql = "
        SELECT
            r.id,
            r.place_id,
            r.recommendation_id,
            r.author_name,
            r.rating,
            r.{$contentField} AS content,
            r.hashtags,
            r.likes_count AS likesCount,
            r.lang,
            p.name_en AS placeNameEn,
            p.name_fa AS placeNameFa,
            pr.title_en AS recommendationNameEn,
            pr.title_fa AS recommendationNameFa
        FROM places_reviews r

        LEFT JOIN places p
            ON r.place_id = p.id

        LEFT JOIN places_recommendations pr
            ON r.recommendation_id = pr.id

        WHERE
            r.is_active = 1
            AND r.status = 'approved'
            AND r.lang = ?

        ORDER BY
            r.likes_count DESC,
            r.created_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$lang]);

    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reviews as &$review) {

        // تبدیل هشتگ‌ها به آرایه
        if (!empty($review['hashtags'])) {

            $hashtags = json_decode($review['hashtags'], true);

            $review['hashtags'] = is_array($hashtags)
                ? $hashtags
                : [];

        } else {

            $review['hashtags'] = [];

        }

        // تبدیل اعداد
        $review['id'] = (int) $review['id'];
        $review['rating'] = (int) $review['rating'];
        $review['likesCount'] = (int) $review['likesCount'];

        // نام مکان متناسب با زبان
        $review['placeName'] = $lang === 'fa'
            ? ($review['placeNameFa'] ?: $review['placeNameEn'])
            : ($review['placeNameEn'] ?: $review['placeNameFa']);

        // نام پیشنهاد مرتبط، در صورت وجود
        $review['recommendationName'] = $lang === 'fa'
            ? ($review['recommendationNameFa'] ?: $review['recommendationNameEn'])
            : ($review['recommendationNameEn'] ?: $review['recommendationNameFa']);

        // حذف فیلدهای اضافی
        unset(
            $review['placeNameEn'],
            $review['placeNameFa'],
            $review['recommendationNameEn'],
            $review['recommendationNameFa']
        );

    }

    unset($review);

    echo json_encode([
        'success' => true,
        'language' => $lang,
        'data' => $reviews,
        'count' => count($reviews)
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'error' => 'Database error'
    ], JSON_UNESCAPED_UNICODE);

}

?>
