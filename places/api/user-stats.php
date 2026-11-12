<?php
/**
 * User Stats API — mashahir
 * GET /Kavian/mashahir/api/user-stats.php
 *
 * خروجی:
 *   library_count    — تعداد محصولات خریداری‌شده (paid)
 *   favorites_count  — تعداد مشاهیر محبوب
 *   history_count    — تعداد بازدیدها
 *   logged_in        — آیا لاگین است
 */
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    echo json_encode([
        'success'         => true,
        'logged_in'       => false,
        'library_count'   => 0,
        'favorites_count' => 0,
        'history_count'   => 0,
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

$pdo    = require __DIR__ . '/../../app/config/config.php';
$userId = (int)$_SESSION['user_id'];

// اطمینان از وجود جداول
$pdo->exec("CREATE TABLE IF NOT EXISTS `user_favorites` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `celebrity_id` int(11) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_user_celebrity` (`user_id`,`celebrity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$pdo->exec("CREATE TABLE IF NOT EXISTS `user_history` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `user_id` int(11) NOT NULL,
    `celebrity_id` int(11) NOT NULL,
    `visited_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_user_celebrity_hist` (`user_id`,`celebrity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

try {
    // تعداد محصولات خریداری‌شده
    $s1 = $pdo->prepare("SELECT COUNT(DISTINCT oi.product_id) FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE o.user_id=? AND o.status='paid'");
    $s1->execute([$userId]);
    $libraryCount = (int)$s1->fetchColumn();

    // تعداد مشاهیر محبوب
    $s2 = $pdo->prepare("SELECT COUNT(*) FROM user_favorites WHERE user_id=?");
    $s2->execute([$userId]);
    $favsCount = (int)$s2->fetchColumn();

    // تعداد تاریخچه
    $s3 = $pdo->prepare("SELECT COUNT(*) FROM user_history WHERE user_id=?");
    $s3->execute([$userId]);
    $histCount = (int)$s3->fetchColumn();

    echo json_encode([
        'success'         => true,
        'logged_in'       => true,
        'library_count'   => $libraryCount,
        'favorites_count' => $favsCount,
        'history_count'   => $histCount,
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'success'         => false,
        'logged_in'       => true,
        'library_count'   => 0,
        'favorites_count' => 0,
        'history_count'   => 0,
    ], JSON_UNESCAPED_UNICODE);
}
