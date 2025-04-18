<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('customer');

if (!isset($_SESSION['user']['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'User not authenticated']);
    exit();
}

$userId = $_SESSION['user']['id'];

try {
    // Check if the user has an active rental
    $activeRental = $pdo->query(
        "SELECT COUNT(*) FROM bookings WHERE user_id = $userId AND booking_status = 'active'"
    )->fetchColumn();

    $response = [
        'canBookNewRental' => true, // Always enabled
        'canExtendRental' => $activeRental > 0,
        'canContactSupport' => true // Always enabled
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}