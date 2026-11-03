<?php
session_start();
header('Content-Type: application/json');

$pdo = require __DIR__ . '/../../app/config/config.php';

$response = ['success' => false];

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    if ($user) {
        $response['success'] = true;
        $response['user'] = $user;
    }
} catch (Exception $e) {

}

echo json_encode($response);
?>