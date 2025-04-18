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
    $upcomingReservations = $pdo->query(
        "SELECT cars.model AS car_model, bookings.pickup_date, locations.name AS pickup_location, bookings.booking_status 
        FROM bookings 
        JOIN cars ON bookings.car_id = cars.car_id 
        JOIN locations ON bookings.pickup_location_id = locations.location_id 
        WHERE bookings.user_id = $userId AND bookings.booking_status = 'upcoming'"
    )->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($upcomingReservations);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}