<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('employee');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_status'])) {
        $booking_id = $_POST['booking_id'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE bookings SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $status, 'id' => $booking_id]);
    }
}

$bookings = $pdo->query("SELECT b.id, u.full_name, u.email, u.phone, v.make, v.model, b.start_date, b.end_date, b.total_price, b.status FROM bookings b JOIN users u ON b.user_id = u.id JOIN vehicles v ON b.vehicle_id = v.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
</head>
<body>
    <h1>Manage Bookings</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Vehicle</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= $booking['id'] ?></td>
                    <td><?= $booking['full_name'] ?></td>
                    <td><?= $booking['email'] ?></td>
                    <td><?= $booking['phone'] ?></td>
                    <td><?= $booking['make'] . ' ' . $booking['model'] ?></td>
                    <td><?= $booking['start_date'] ?></td>
                    <td><?= $booking['end_date'] ?></td>
                    <td><?= $booking['total_price'] ?></td>
                    <td><?= $booking['status'] ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                            <select name="status">
                                <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $booking['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $booking['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>