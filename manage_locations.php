<?php
// manage_locations.php

// Include necessary files
include 'includes/auth.php';
include 'includes/db_connect.php';

// Logic for managing locations (CRUD operations)
// Example: Fetch all locations
$query = "SELECT * FROM locations";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Location: " . $row['name'] . " - Address: " . $row['address'] . "<br>";
    }
} else {
    echo "No locations found.";
}
?>