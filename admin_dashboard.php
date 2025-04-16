<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #1a202c;
            color: white;
            transition: transform 0.3s ease;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar ul li {
            display: flex;
            align-items: center;
            padding: 10px;
            transition: background-color 0.3s ease;
        }
        .sidebar ul li:hover {
            background-color: #2d3748;
        }
        .sidebar ul li.active {
            background-color: #ebf8ff;
            color: #2b6cb0;
            border-left: 4px solid #2b6cb0;
        }
        .sidebar ul li span {
            margin-left: 10px;
        }
        .content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }
        .content.collapsed {
            margin-left: 80px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="sidebar" id="sidebar">
        <div class="p-4">
            <h1 class="text-lg font-bold">CARNEX Admin Panel</h1>
            <nav>
                <ul class="space-y-4 mt-4">
                    <li class="active"><a href="manage_vehicles.php" class="flex items-center"><i class="fas fa-car"></i><span>Manage Vehicles</span></a></li>
                    <li><a href="manage_users.php" class="flex items-center"><i class="fas fa-users"></i><span>Manage Users</span></a></li>
                    <li><a href="view_reports.php" class="flex items-center"><i class="fas fa-chart-line"></i><span>View Reports</span></a></li>
                    <li><a href="logout.php" class="flex items-center"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="content" id="content">
        <header class="bg-gray-800 text-white py-4">
            <div class="container mx-auto flex justify-between items-center">
                <button id="toggleSidebar" class="text-white focus:outline-none">☰</button>
                <div class="flex items-center space-x-4">
                    <h1 class="text-lg font-bold">Welcome, <?= $_SESSION['user']['full_name'] ?> <span class="bg-blue-500 text-white px-2 py-1 rounded">Admin</span></h1>
                    <input type="text" placeholder="Search..." class="px-4 py-2 rounded bg-gray-700 text-white focus:outline-none">
                </div>
            </div>
        </header>
        <main class="mt-16 p-4">
            <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 shadow rounded cursor-pointer">
                    <h3 class="text-lg font-bold">Total Vehicles</h3>
                    <p class="text-2xl">123</p>
                    <div class="mt-2">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded">Add Vehicle</button>
                    </div>
                </div>
                <div class="bg-white p-4 shadow rounded cursor-pointer">
                    <h3 class="text-lg font-bold">Active Users</h3>
                    <p class="text-2xl">456</p>
                    <canvas id="activeUsersChart"></canvas>
                </div>
                <div class="bg-white p-4 shadow rounded cursor-pointer">
                    <h3 class="text-lg font-bold">Reports Generated</h3>
                    <p class="text-2xl">789</p>
                    <div class="mt-2">
                        <button class="bg-blue-500 text-white px-4 py-2 rounded">Generate New</button>
                    </div>
                </div>
            </section>
            <section class="mt-8">
                <h2 class="text-xl font-bold">Priority Alerts</h2>
                <div class="mt-4">
                    <p class="text-red-500">Overdue Vehicles: 5</p>
                    <p class="text-yellow-400">Pending Approvals: 3</p>
                </div>
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        const toggleSidebar = document.getElementById('toggleSidebar');

        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('collapsed');
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        });

        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('sidebar-collapsed') === 'true') {
                sidebar.classList.add('collapsed');
                content.classList.add('collapsed');
            }

            const ctx = document.getElementById('activeUsersChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Admin', 'Employee', 'Customer'],
                    datasets: [{
                        data: [10, 20, 30],
                        backgroundColor: ['#2b6cb0', '#68d391', '#f6ad55']
                    }]
                }
            });
        });
    </script>
</body>
</html>