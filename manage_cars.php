<?php
// manage_cars.php

// Include necessary files
include 'includes/auth.php';
include 'includes/db_connect.php';

// Logic for managing cars (CRUD operations)
// Example: Fetch all cars
$query = "SELECT * FROM cars";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Car: " . $row['brand'] . " " . $row['model'] . " - Status: " . $row['status'] . "<br>";
    }
} else {
    echo "No cars found.";
}
?>