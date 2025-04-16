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
</head>
<body>
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