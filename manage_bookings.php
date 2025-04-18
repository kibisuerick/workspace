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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $statusFilter = $_GET['status'] ?? '';
    $dateFilter = $_GET['date'] ?? '';
    $carFilter = $_GET['car'] ?? '';

    $query = "SELECT b.booking_id, u.full_name, c.model, c.brand, b.pickup_date, b.return_date, b.booking_status 
              FROM bookings b
              JOIN users u ON b.user_id = u.id
              JOIN cars c ON b.car_id = c.car_id
              WHERE 1=1";

    $params = [];

    if (!empty($statusFilter)) {
        $query .= " AND b.booking_status = :status";
        $params['status'] = $statusFilter;
    }

    if (!empty($dateFilter)) {
        $query .= " AND DATE(b.pickup_date) <= :date AND DATE(b.return_date) >= :date";
        $params['date'] = $dateFilter;
    }

    if (!empty($carFilter)) {
        $query .= " AND c.model = :car";
        $params['car'] = $carFilter;
    }

    $query .= " ORDER BY b.created_at DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
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
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <aside id="sidebar" class="bg-gray-800 text-white w-64 hidden md:block md:relative">
        <div class="p-4 text-center font-bold text-lg border-b border-gray-700">Employee Dashboard</div>
        <nav class="mt-4">
            <ul class="space-y-2">
                <li><a href="employee_dashboard.php" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a></li>
                <li><a href="manage_bookings.php" class="block py-2 px-4 bg-gray-700">Bookings</a></li>
                <li><a href="manage_vehicles.php" class="block py-2 px-4 hover:bg-gray-700">Vehicles</a></li>
                <li><a href="manage_users.php" class="block py-2 px-4 hover:bg-gray-700">Customers</a></li>
                <li><a href="employee_reports.php" class="block py-2 px-4 hover:bg-gray-700">Reports</a></li>
                <li><a href="employee_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a></li>
                <li><a href="logout.php" class="block py-2 px-4 hover:bg-gray-700">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col">
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
                <form class="grid grid-cols-1 md:grid-cols-3 gap-4" method="GET" action="">
                    <div>
                        <label for="status" class="block text-gray-700">Status</label>
                        <select id="status" name="status" class="w-full border rounded px-3 py-2">
                            <option value="">All</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label for="date" class="block text-gray-700">Date Range</label>
                        <input type="date" id="date" name="date" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="car" class="block text-gray-700">Car Type</label>
                        <select id="car" name="car" class="w-full border rounded px-3 py-2">
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
                                    <td class="border-b py-2 px-4">#<?= $booking['booking_id'] ?></td>
                                    <td class="border-b py-2 px-4"><?= $booking['full_name'] ?></td>
                                    <td class="border-b py-2 px-4"><?= $booking['brand'] . ' ' . $booking['model'] ?></td>
                                    <td class="border-b py-2 px-4"><?= $booking['pickup_date'] ?></td>
                                    <td class="border-b py-2 px-4"><?= $booking['return_date'] ?></td>
                                    <td class="border-b py-2 px-4">
                                        <span class="px-2 py-1 rounded text-white 
                                            <?= $booking['booking_status'] === 'pending' ? 'bg-yellow-500' : '' ?>
                                            <?= $booking['booking_status'] === 'approved' ? 'bg-green-500' : '' ?>
                                            <?= $booking['booking_status'] === 'rejected' ? 'bg-red-500' : '' ?>">
                                            <?= ucfirst($booking['booking_status']) ?>
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
    </div>
</body>
</html>