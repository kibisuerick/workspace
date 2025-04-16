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
</head>
<body>
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