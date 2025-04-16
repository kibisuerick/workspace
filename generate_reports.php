<?php
// generate_reports.php

// Include necessary files
include 'includes/auth.php';
include 'includes/db_connect.php';

// Logic for generating reports
// Example: Generate a report of bookings
$query = "SELECT * FROM bookings";
$result = $pdo->query($query);

if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "Booking ID: " . $row['booking_id'] . " - Status: " . $row['booking_status'] . "<br>";
    }
} else {
    echo "No bookings found.";
}
?>