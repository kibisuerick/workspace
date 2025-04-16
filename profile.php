<?php
// profile.php

// Include necessary files
include 'includes/auth.php';
include 'includes/db_connect.php';

// Logic for displaying and updating user profile
// Example: Fetch user profile details
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $pdo->query($query);

if ($result->rowCount() > 0) {
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo "Name: " . $row['full_name'] . "<br>Email: " . $row['email'] . "<br>Phone: " . $row['phone'] . "<br>";
} else {
    echo "Profile not found.";
}
?>