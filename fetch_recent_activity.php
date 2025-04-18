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
    $recentActivities = $pdo->query(
        "SELECT 'Rental' AS activity_type, cars.model AS activity_detail, bookings.return_date AS activity_date 
        FROM bookings 
        JOIN cars ON bookings.car_id = cars.car_id 
        WHERE bookings.user_id = $userId AND bookings.booking_status = 'completed' 
        UNION ALL 
        SELECT 'Payment' AS activity_type, CONCAT('Payment of $', payments.amount_paid) AS activity_detail, payments.paid_at AS activity_date 
        FROM payments 
        JOIN bookings ON payments.booking_id = bookings.booking_id 
        WHERE bookings.user_id = $userId 
        ORDER BY activity_date DESC 
        LIMIT 10"
    )->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($recentActivities);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}