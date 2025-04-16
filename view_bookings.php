<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('customer');

$user_id = $_SESSION['user']['id'];
$bookings = $pdo->prepare("SELECT b.id, v.make, v.model, b.start_date, b.end_date, b.total_price, b.status FROM bookings b JOIN vehicles v ON b.vehicle_id = v.id WHERE b.user_id = :user_id");
$bookings->execute(['user_id' => $user_id]);
$bookings = $bookings->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bookings</title>
</head>
<body>
    <h1>Your Bookings</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vehicle</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= $booking['id'] ?></td>
                    <td><?= $booking['make'] . ' ' . $booking['model'] ?></td>
                    <td><?= $booking['start_date'] ?></td>
                    <td><?= $booking['end_date'] ?></td>
                    <td><?= $booking['total_price'] ?></td>
                    <td><?= $booking['status'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>