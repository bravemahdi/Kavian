<?php
// فقط برای دیباگ — بعد از تست حذف کن
header('Content-Type: application/json; charset=utf-8');

if (session_status() === PHP_SESSION_NONE) session_start();

$pdo = require __DIR__ . '/../../app/config/config.php';

echo json_encode([
    'session_user_id' => $_SESSION['user_id'] ?? null,
    'session_logged_in' => $_SESSION['logged_in'] ?? null,
    'session_keys' => array_keys($_SESSION),
    'get_product_id' => $_GET['product_id'] ?? null,
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
