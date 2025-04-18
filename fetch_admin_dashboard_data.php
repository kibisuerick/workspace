<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');

try {
    // Fetch total vehicles
    $totalVehicles = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();

    // Fetch active users
    $activeUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE role IN ('admin', 'employee', 'customer')")->fetchColumn();

    // Fetch reports generated
    $reportsGenerated = $pdo->query("SELECT COUNT(*) FROM reports")->fetchColumn();

    // Fetch priority alerts
    $overdueVehicles = $pdo->query("SELECT COUNT(*) FROM bookings WHERE return_date < CURDATE() AND booking_status = 'active'")->fetchColumn();
    $pendingApprovals = $pdo->query("SELECT COUNT(*) FROM bookings WHERE booking_status = 'pending'")->fetchColumn();

    // Fetch user role distribution
    $userRoles = $pdo->query(
        "SELECT role, COUNT(*) AS count FROM users GROUP BY role"
    )->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode([
        'totalVehicles' => $totalVehicles,
        'activeUsers' => $activeUsers,
        'reportsGenerated' => $reportsGenerated,
        'overdueVehicles' => $overdueVehicles,
        'pendingApprovals' => $pendingApprovals,
        'userRoles' => $userRoles
    ]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}