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
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Manage Bookings</h1>
        <nav>
            <ol class="flex space-x-2 text-gray-600">
                <li><a href="employee_dashboard.php" class="hover:underline">Dashboard</a></li>
                <li>/</li>
                <li>Bookings</li>
            </ol>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="p-6 space-y-6">
        <!-- Filter/Search Section -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Bookings</h2>
            <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-gray-700">Status</label>
                    <select id="status" class="w-full border rounded px-3 py-2">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div>
                    <label for="date" class="block text-gray-700">Date Range</label>
                    <input type="date" id="date" class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label for="car" class="block text-gray-700">Car Type</label>
                    <select id="car" class="w-full border rounded px-3 py-2">
                        <option value="">All</option>
                        <option value="sedan">Sedan</option>
                        <option value="suv">SUV</option>
                        <option value="truck">Truck</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Apply Filters</button>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Bookings</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border-b py-2 px-4">Booking ID</th>
                            <th class="border-b py-2 px-4">Customer Name</th>
                            <th class="border-b py-2 px-4">Car Model</th>
                            <th class="border-b py-2 px-4">Pickup Date</th>
                            <th class="border-b py-2 px-4">Return Date</th>
                            <th class="border-b py-2 px-4">Status</th>
                            <th class="border-b py-2 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="border-b py-2 px-4">#<?= $booking['id'] ?></td>
                                <td class="border-b py-2 px-4"><?= $booking['full_name'] ?></td>
                                <td class="border-b py-2 px-4"><?= $booking['make'] . ' ' . $booking['model'] ?></td>
                                <td class="border-b py-2 px-4"><?= $booking['start_date'] ?></td>
                                <td class="border-b py-2 px-4"><?= $booking['end_date'] ?></td>
                                <td class="border-b py-2 px-4">
                                    <span class="px-2 py-1 rounded text-white 
                                        <?= $booking['status'] === 'pending' ? 'bg-yellow-500' : '' ?>
                                        <?= $booking['status'] === 'approved' ? 'bg-green-500' : '' ?>
                                        <?= $booking['status'] === 'rejected' ? 'bg-red-500' : '' ?>">
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                </td>
                                <td class="border-b py-2 px-4">
                                    <button class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">View</button>
                                    <button class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">Edit</button>
                                    <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Cancel</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>