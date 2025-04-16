<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('customer');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
</head>
<body>
    <h1>Welcome, <?= $_SESSION['user']['full_name'] ?> (Customer)</h1>
    <nav>
        <ul>
            <li><a href="browse_vehicles.php">Browse Vehicles</a></li>
            <li><a href="view_bookings.php">View Bookings</a></li>
            <li><a href="update_profile.php">Update Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>