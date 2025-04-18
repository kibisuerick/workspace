<?php
// generate_reports.php

// Ensure the user is authenticated as an admin
include 'includes/auth.php';
include 'includes/db_connect.php';
checkAuth('admin');

header('Content-Type: application/json');

try {
    // Example: Generate a summary report of bookings
    $query = "SELECT COUNT(*) AS total_bookings, SUM(total_amount) AS total_revenue FROM bookings";
    $result = $pdo->query($query)->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $totalBookings = $result['total_bookings'];
        $totalRevenue = $result['total_revenue'];

        // Save the report details in the reports table
        $stmt = $pdo->prepare("INSERT INTO reports (report_name, report_data, generated_by) VALUES (:report_name, :report_data, :generated_by)");
        $stmt->execute([
            'report_name' => 'Bookings Summary',
            'report_data' => json_encode(['total_bookings' => $totalBookings, 'total_revenue' => $totalRevenue]),
            'generated_by' => $_SESSION['user']['id']
        ]);

        echo json_encode(['success' => true, 'message' => 'Report generated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to generate report.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>