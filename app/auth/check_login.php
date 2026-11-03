<?php
session_start();
header('Content-Type: application/json');

$response = ['logged_in' => false];

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $response['logged_in'] = true;
    $response['user'] = [
        'name'  => $_SESSION['user_name'],
        'email' => $_SESSION['user_email'],
        'role'  => $_SESSION['user_role'] ?? 'user',
    ];
}

echo json_encode($response);
?>
