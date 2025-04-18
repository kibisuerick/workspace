<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

// Start session and check if user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header('Location: unauthorized.php');
    exit;
}

checkAuth('employee');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $startDate = $_GET['startDate'] ?? '';
    $endDate = $_GET['endDate'] ?? '';
    $branch = $_GET['branch'] ?? 'all';
    $reportType = $_GET['reportType'] ?? 'summary';

    $query = "SELECT b.booking_id, u.full_name, c.model, c.brand, b.pickup_date, b.return_date, b.booking_status 
              FROM bookings b
              JOIN users u ON b.user_id = u.id
              JOIN cars c ON b.car_id = c.car_id
              WHERE 1=1";

    $params = [];

    if (!empty($startDate)) {
        $query .= " AND b.pickup_date >= :startDate";
        $params['startDate'] = $startDate;
    }

    if (!empty($endDate)) {
        $query .= " AND b.return_date <= :endDate";
        $params['endDate'] = $endDate;
    }

    if ($branch !== 'all') {
        $query .= " AND b.pickup_location_id = :branch";
        $params['branch'] = $branch;
    }

    $query .= " ORDER BY b.pickup_date DESC";

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch revenue trends data
    $revenueQuery = "SELECT DATE_FORMAT(pickup_date, '%b %Y') AS month, SUM(total_amount) AS revenue 
                     FROM bookings 
                     WHERE pickup_date <= CURDATE() 
                     GROUP BY DATE_FORMAT(pickup_date, '%Y-%m') 
                     ORDER BY pickup_date ASC";
    $revenueStmt = $pdo->query($revenueQuery);
    $revenueData = $revenueStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch vehicle utilization data
    $utilizationQuery = "SELECT 
                            (SELECT COUNT(*) FROM cars WHERE status = 'unavailable') AS used,
                            (SELECT COUNT(*) FROM cars WHERE status = 'available') AS available";
    $utilizationStmt = $pdo->query($utilizationQuery);
    $utilizationData = $utilizationStmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Reports</title>
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
                <li><a href="manage_bookings.php" class="block py-2 px-4 hover:bg-gray-700">Bookings</a></li>
                <li><a href="manage_vehicles.php" class="block py-2 px-4 hover:bg-gray-700">Vehicles</a></li>
                <li><a href="manage_users.php" class="block py-2 px-4 hover:bg-gray-700">Customers</a></li>
                <li><a href="employee_reports.php" class="block py-2 px-4 bg-gray-700">Reports</a></li>
                <li><a href="employee_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a></li>
                <li><a href="logout.php" class="block py-2 px-4 hover:bg-gray-700">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Employee Reports</h1>
            <nav class="text-sm text-gray-600">
                <a href="employee_dashboard.php" class="hover:underline">Home</a> > <span>Reports</span>
            </nav>
        </header>

        <!-- Filters Section -->
        <section class="bg-white shadow-md rounded-lg p-6 m-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Filters</h2>
            <form class="grid grid-cols-1 md:grid-cols-3 gap-4" method="GET" action="">
                <div>
                    <label for="startDate" class="block text-gray-700">Start Date</label>
                    <input type="date" id="startDate" name="startDate" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div>
                    <label for="endDate" class="block text-gray-700">End Date</label>
                    <input type="date" id="endDate" name="endDate" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div>
                    <label for="branch" class="block text-gray-700">Branch/Location</label>
                    <select id="branch" name="branch" class="border rounded-lg px-4 py-2 w-full">
                        <option value="all">All Locations</option>
                        <option value="branch1">Branch 1</option>
                        <option value="branch2">Branch 2</option>
                    </select>
                </div>
                <div>
                    <label for="reportType" class="block text-gray-700">Report Type</label>
                    <select id="reportType" name="reportType" class="border rounded-lg px-4 py-2 w-full">
                        <option value="summary">Summary</option>
                        <option value="detailed">Detailed</option>
                    </select>
                </div>
                <div class="md:col-span-3">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Apply Filters</button>
                </div>
            </form>
        </section>

        <!-- Visualization Area -->
        <section class="bg-white shadow-md rounded-lg p-6 m-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Visual Insights</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-md font-bold text-gray-700">Revenue Trends</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
                <div>
                    <h3 class="text-md font-bold text-gray-700">Vehicle Utilization</h3>
                    <canvas id="utilizationChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Table Section -->
        <section class="bg-white shadow-md rounded-lg p-6 m-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Detailed Reports</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="border-b py-2 px-4">Rental ID</th>
                        <th class="border-b py-2 px-4">Customer</th>
                        <th class="border-b py-2 px-4">Vehicle</th>
                        <th class="border-b py-2 px-4">Date</th>
                        <th class="border-b py-2 px-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reports)): ?>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td class="border-b py-2 px-4">#<?= htmlspecialchars($report['booking_id']) ?></td>
                                <td class="border-b py-2 px-4"><?= htmlspecialchars($report['full_name']) ?></td>
                                <td class="border-b py-2 px-4"><?= htmlspecialchars($report['brand'] . ' ' . $report['model']) ?></td>
                                <td class="border-b py-2 px-4"><?= htmlspecialchars($report['pickup_date']) ?> - <?= htmlspecialchars($report['return_date']) ?></td>
                                <td class="border-b py-2 px-4"><?= htmlspecialchars($report['booking_status']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');

        const revenueLabels = <?= json_encode(array_column($revenueData, 'month')) ?>;
        const revenueValues = <?= json_encode(array_column($revenueData, 'revenue')) ?>;

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueLabels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueValues,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                }]
            },
            options: {
                responsive: true,
            }
        });

        const utilizationData = <?= json_encode($utilizationData) ?>;

        new Chart(utilizationCtx, {
            type: 'pie',
            data: {
                labels: ['Used', 'Available'],
                datasets: [{
                    data: [utilizationData.used, utilizationData.available],
                    backgroundColor: ['#4CAF50', '#FFC107']
                }]
            },
            options: {
                responsive: true,
            }
        });
    </script>
</body>
</html>