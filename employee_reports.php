<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

// Start session and check if user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header('Location: unauthorized.php');
    exit;
}

checkAuth('employee');
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
<body class="bg-gray-100 min-h-screen flex flex-col">
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
        <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="dateRange" class="block text-gray-700">Date Range</label>
                <input type="date" id="startDate" class="border rounded-lg px-4 py-2 w-full">
                <input type="date" id="endDate" class="border rounded-lg px-4 py-2 w-full mt-2">
            </div>
            <div>
                <label for="branch" class="block text-gray-700">Branch/Location</label>
                <select id="branch" class="border rounded-lg px-4 py-2 w-full">
                    <option value="all">All Locations</option>
                    <option value="branch1">Branch 1</option>
                    <option value="branch2">Branch 2</option>
                </select>
            </div>
            <div>
                <label for="reportType" class="block text-gray-700">Report Type</label>
                <select id="reportType" class="border rounded-lg px-4 py-2 w-full">
                    <option value="summary">Summary</option>
                    <option value="detailed">Detailed</option>
                </select>
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
                <tr>
                    <td class="border-b py-2 px-4">#12345</td>
                    <td class="border-b py-2 px-4">John Doe</td>
                    <td class="border-b py-2 px-4">Toyota Corolla</td>
                    <td class="border-b py-2 px-4">2025-04-15</td>
                    <td class="border-b py-2 px-4 text-green-500">Completed</td>
                </tr>
                <tr>
                    <td class="border-b py-2 px-4">#12346</td>
                    <td class="border-b py-2 px-4">Jane Smith</td>
                    <td class="border-b py-2 px-4">Honda Civic</td>
                    <td class="border-b py-2 px-4">2025-04-16</td>
                    <td class="border-b py-2 px-4 text-yellow-500">Pending</td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p>&copy; 2025 Car Rental Management System</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 15000, 10000, 20000],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                }]
            },
            options: {
                responsive: true,
            }
        });

        new Chart(utilizationCtx, {
            type: 'pie',
            data: {
                labels: ['Used', 'Available'],
                datasets: [{
                    data: [70, 30],
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