<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('employee');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
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
            <h1 class="text-lg font-bold">CARNEX Employee Panel</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="manage_bookings.php" class="hover:underline">Manage Bookings</a></li>
                    <li><a href="assist_customers.php" class="hover:underline">Assist Customers</a></li>
                    <li><a href="logout.php" class="hover:underline">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <h1>Welcome, <?= $_SESSION['user']['full_name'] ?> (Employee)</h1>
    <nav>
        <ul>
            <li><a href="manage_bookings.php">Manage Bookings</a></li>
            <li><a href="assist_customers.php">Assist Customers</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>