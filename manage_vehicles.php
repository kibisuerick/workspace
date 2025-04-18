<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');

// Add feedback mechanism for form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_vehicle'])) {
        $model = $_POST['model'];
        $brand = $_POST['brand'];
        $year = $_POST['year'];
        $registration_number = $_POST['registration_number'];
        $price_per_day = $_POST['price_per_day'];
        $status = $_POST['status'];
        $image_url = $_POST['image_url'];

        try {
            $stmt = $pdo->prepare("INSERT INTO cars (model, brand, year, registration_number, price_per_day, status, image_url) VALUES (:model, :brand, :year, :registration_number, :price_per_day, :status, :image_url)");
            $stmt->execute([
                'model' => $model,
                'brand' => $brand,
                'year' => $year,
                'registration_number' => $registration_number,
                'price_per_day' => $price_per_day,
                'status' => $status,
                'image_url' => $image_url
            ]);
            $message = 'Vehicle added successfully!';
        } catch (Exception $e) {
            $message = 'Error adding vehicle: ' . $e->getMessage();
        }
    }
}

$vehicles = $pdo->query("SELECT car_id, model, brand, year, registration_number, price_per_day, status, image_url FROM cars")->fetchAll(PDO::FETCH_ASSOC);
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

    <!-- Display feedback message -->
    <?php if (!empty($message)): ?>
        <p style="color: <?= strpos($message, 'Error') === 0 ? 'red' : 'green' ?>; font-weight: bold;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">
        <h2>Add Vehicle</h2>
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required><br>

        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" required><br>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required><br>

        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" required><br>

        <label for="price_per_day">Price per Day:</label>
        <input type="number" step="0.01" id="price_per_day" name="price_per_day" required><br>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required><br>

        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url" required><br>

        <button type="submit" name="add_vehicle">Add Vehicle</button>
    </form>

    <h2>Existing Vehicles</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Model</th>
                <th>Brand</th>
                <th>Year</th>
                <th>Registration Number</th>
                <th>Price per Day</th>
                <th>Status</th>
                <th>Image</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($vehicles as $vehicle): ?>
                <tr>
                    <td><?= $vehicle['car_id'] ?></td>
                    <td><?= $vehicle['model'] ?></td>
                    <td><?= $vehicle['brand'] ?></td>
                    <td><?= $vehicle['year'] ?></td>
                    <td><?= $vehicle['registration_number'] ?></td>
                    <td><?= $vehicle['price_per_day'] ?></td>
                    <td><?= $vehicle['status'] ?></td>
                    <td><img src="<?= $vehicle['image_url'] ?>" alt="Vehicle Image" width="100"></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>