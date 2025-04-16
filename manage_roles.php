<?php
// manage_roles.php

// Include necessary files
include 'includes/auth.php';
include 'includes/db_connect.php';

// Logic for managing roles (CRUD operations)
// Example: Fetch all roles
$query = "SELECT * FROM roles";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Role: " . $row['name'] . "<br>";
    }
} else {
    echo "No roles found.";
}
?>