<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_vehicle'])) {
        $make = $_POST['make'];
        $model = $_POST['model'];
        $year = $_POST['year'];
        $price_per_day = $_POST['price_per_day'];

        $stmt = $pdo->prepare("INSERT INTO vehicles (make, model, year, price_per_day) VALUES (:make, :model, :year, :price_per_day)");
        $stmt->execute(['make' => $make, 'model' => $model, 'year' => $year, 'price_per_day' => $price_per_day]);
    }
}

$vehicles = $pdo->query("SELECT * FROM vehicles")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles</title>
</head>
<body>
    <h1>Manage Vehicles</h1>
    <form method="POST" action="">
        <h2>Add Vehicle</h2>
        <label for="make">Make:</label>
        <input type="text" id="make" name="make" required><br>

        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required><br>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required><br>

        <label for="price_per_day">Price per Day:</label>
        <input type="number" step="0.01" id="price_per_day" name="price_per_day" required><br>

        <button type="submit" name="add_vehicle">Add Vehicle</button>
    </form>

    <h2>Existing Vehicles</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Price per Day</th>
                <th>Availability</th>
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
                    <td><?= $vehicle['availability'] ? 'Available' : 'Not Available' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>