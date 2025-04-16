<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('customer');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $user_id = $_SESSION['user']['id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Calculate total price
    $stmt = $pdo->prepare("SELECT price_per_day FROM vehicles WHERE id = :vehicle_id");
    $stmt->execute(['vehicle_id' => $vehicle_id]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vehicle) {
        $days = (strtotime($end_date) - strtotime($start_date)) / 86400;
        $total_price = $days * $vehicle['price_per_day'];

        // Insert booking
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, vehicle_id, start_date, end_date, total_price) VALUES (:user_id, :vehicle_id, :start_date, :end_date, :total_price)");
        $stmt->execute([
            'user_id' => $user_id,
            'vehicle_id' => $vehicle_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_price' => $total_price
        ]);

        // Mark vehicle as unavailable
        $stmt = $pdo->prepare("UPDATE vehicles SET availability = 0 WHERE id = :vehicle_id");
        $stmt->execute(['vehicle_id' => $vehicle_id]);

        echo "Booking successful!";
    } else {
        echo "Vehicle not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Vehicle</title>
</head>
<body>
    <h1>Book Vehicle</h1>
    <form method="POST" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required><br>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required><br>

        <input type="hidden" name="vehicle_id" value="<?= htmlspecialchars($_POST['vehicle_id']) ?>">
        <button type="submit">Confirm Booking</button>
    </form>
</body>
</html>