<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

// Start session and check if user is logged in
//session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header('Location: unauthorized.php');
    exit;
}

$fullName = $_SESSION['user']['full_name'];

checkAuth('employee');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar collapsible for mobile -->
    <aside id="sidebar" class="bg-gray-800 text-white w-64 hidden md:block md:relative">
        <div class="p-4 text-center font-bold text-lg border-b border-gray-700">Employee Dashboard</div>
        <nav class="mt-4">
            <ul class="space-y-2">
                <li><a href="#" class="block py-2 px-4 hover:bg-gray-700">Dashboard</a></li>
                <li><a href="manage_bookings.php" class="block py-2 px-4 hover:bg-gray-700">Bookings</a></li>
                <li><a href="manage_vehicles.php" class="block py-2 px-4 hover:bg-gray-700">Vehicles</a></li>
                <li><a href="manage_users.php" class="block py-2 px-4 hover:bg-gray-700">Customers</a></li>
                <li><a href="employee_reports.php" class="block py-2 px-4 hover:bg-gray-700">Reports</a></li>
                <li><a href="employee_profile.php" class="block py-2 px-4 hover:bg-gray-700">Profile</a></li>
                <li><a href="logout.php" class="block py-2 px-4 hover:bg-gray-700">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <button id="toggleSidebar" class="md:hidden bg-gray-800 text-white p-2 fixed top-4 left-4 z-50">☰</button>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
        <!-- Navbar -->
        <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">Welcome, <?= htmlspecialchars($fullName) ?></h1>
            <div class="flex items-center space-x-4">
                <input type="text" placeholder="Search..." class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="relative">
                    <button class="flex items-center space-x-2 focus:outline-none" onclick="toggleModal('editProfileModal')">
                        <img src="https://via.placeholder.com/40" alt="Profile" class="w-10 h-10 rounded-full">
                        <span class="hidden md:block">Profile</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-6 space-y-6">
            <!-- Summary Panel -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h2 class="text-lg font-bold text-gray-800">Total Bookings</h2>
                    <p class="text-2xl font-bold text-blue-500">120</p>
                </div>
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h2 class="text-lg font-bold text-gray-800">Available Cars</h2>
                    <p class="text-2xl font-bold text-green-500">45</p>
                </div>
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h2 class="text-lg font-bold text-gray-800">Pending Returns</h2>
                    <p class="text-2xl font-bold text-red-500">8</p>
                </div>
            </div>

            <!-- Recent Activity Table -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Recent Bookings</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="border-b py-2 px-4">Booking ID</th>
                            <th class="border-b py-2 px-4">Customer</th>
                            <th class="border-b py-2 px-4">Vehicle</th>
                            <th class="border-b py-2 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr onclick="toggleModal('viewBookingModal')">
                            <td class="border-b py-2 px-4">#12345</td>
                            <td class="border-b py-2 px-4">John Doe</td>
                            <td class="border-b py-2 px-4">Toyota Corolla</td>
                            <td class="border-b py-2 px-4 text-green-500">Completed</td>
                        </tr>
                        <tr onclick="toggleModal('viewBookingModal')">
                            <td class="border-b py-2 px-4">#12346</td>
                            <td class="border-b py-2 px-4">Jane Smith</td>
                            <td class="border-b py-2 px-4">Honda Civic</td>
                            <td class="border-b py-2 px-4 text-yellow-500">Pending</td>
                        </tr>
                        <tr onclick="toggleModal('viewBookingModal')">
                            <td class="border-b py-2 px-4">#12347</td>
                            <td class="border-b py-2 px-4">Alice Brown</td>
                            <td class="border-b py-2 px-4">Ford Focus</td>
                            <td class="border-b py-2 px-4 text-red-500">Cancelled</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Performance Stats with Chart.js -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-bold text-gray-800">Monthly Revenue</h2>
                    <canvas id="revenueChart"></canvas>
                </div>
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-bold text-gray-800">Customer Satisfaction</h2>
                    <canvas id="satisfactionChart"></canvas>
                </div>
            </div>
        </main>
    </div>

    <!-- Add modals for viewing booking details and editing profile -->
    <div id="viewBookingModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 md:w-1/2">
            <h2 class="text-lg font-bold mb-4">Booking Details</h2>
            <p>Details about the booking will go here.</p>
            <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600" onclick="toggleModal('viewBookingModal')">Close</button>
        </div>
    </div>

    <div id="editProfileModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 md:w-1/2">
            <h2 class="text-lg font-bold mb-4">Edit Profile</h2>
            <form>
                <div class="mb-4">
                    <label for="fullName" class="block text-gray-700">Full Name</label>
                    <input type="text" id="fullName" class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" class="w-full border rounded px-3 py-2">
                </div>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save</button>
                <button type="button" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="toggleModal('editProfileModal')">Cancel</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        const sidebar = document.getElementById('sidebar');
        const toggleSidebar = document.getElementById('toggleSidebar');

        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });

        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');

        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 15000, 10000, 20000, 18000],
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        new Chart(satisfactionCtx, {
            type: 'pie',
            data: {
                labels: ['Satisfied', 'Neutral', 'Dissatisfied'],
                datasets: [{
                    label: 'Customer Satisfaction',
                    data: [70, 20, 10],
                    backgroundColor: ['#4CAF50', '#FFC107', '#F44336']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });
    </script>
</body>
</html>