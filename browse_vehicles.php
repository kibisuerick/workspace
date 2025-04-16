<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('customer');

$vehicles = $pdo->query("SELECT * FROM vehicles WHERE availability = 1")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Vehicles</title>
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
    <h1>Browse Vehicles</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price per Day</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $vehicle): ?>
                <tr>
                    <td><?= $vehicle['id'] ?></td>
                    <td><?= $vehicle['make'] ?></td>
                    <td><?= $vehicle['model'] ?></td>
                    <td><?= $vehicle['year'] ?></td>
                    <td><?= $vehicle['price_per_day'] ?></td>
                    <td>
                        <form method="POST" action="book_vehicle.php">
                            <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
                            <button type="submit">Book Now</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>