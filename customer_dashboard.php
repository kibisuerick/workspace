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
    <style>
        header {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">CARNEX Customer Panel</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="browse_vehicles.php" class="hover:underline">Browse Vehicles</a></li>
                    <li><a href="view_bookings.php" class="hover:underline">View Bookings</a></li>
                    <li><a href="update_profile.php" class="hover:underline">Update Profile</a></li>
                    <li><a href="logout.php" class="hover:underline">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
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