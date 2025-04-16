<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            <h1 class="text-lg font-bold">CARNEX Admin Panel</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="manage_vehicles.php" class="hover:underline">Manage Vehicles</a></li>
                    <li><a href="manage_users.php" class="hover:underline">Manage Users</a></li>
                    <li><a href="view_reports.php" class="hover:underline">View Reports</a></li>
                    <li><a href="logout.php" class="hover:underline">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <h1>Welcome, <?= $_SESSION['user']['full_name'] ?> (Admin)</h1>
    <nav>
        <ul>
            <li><a href="manage_vehicles.php">Manage Vehicles</a></li>
            <li><a href="manage_users.php">Manage Users</a></li>
            <li><a href="view_reports.php">View Reports</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>