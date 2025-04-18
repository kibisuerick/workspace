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
            background-color: #2d3748;
            color: #e2e8f0;
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
            background-color: #4a5568;
        }
        .sidebar ul li.active {
            background-color: #4a5568;
            color: #ffffff;
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

        /* Improved styling for dashboard cards */
        .dashboard-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .dashboard-card h3 {
            font-size: 1.25rem;
            font-weight: bold;
            color: #2d3748;
        }

        .dashboard-card p {
            font-size: 2rem;
            font-weight: bold;
            color: #4a5568;
        }

        .dashboard-card button {
            background-color: #3182ce;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 0.875rem;
            font-weight: bold;
            transition: background-color 0.2s ease;
        }

        .dashboard-card button:hover {
            background-color: #2b6cb0;
        }

        /* Styling for priority alerts */
        .priority-alerts p {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .priority-alerts p.red {
            color: #e53e3e;
        }

        .priority-alerts p.yellow {
            color: #d69e2e;
        }

        /* Header improvements */
        header {
            background-color: #2d3748;
            color: #e2e8f0;
        }

        header h1 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        header input {
            background-color: #4a5568;
            color: #e2e8f0;
            border: none;
            padding: 10px;
            border-radius: 5px;
        }

        header input::placeholder {
            color: #a0aec0;
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
                <div class="dashboard-card cursor-pointer">
                    <h3>Total Vehicles</h3>
                    <p id="totalVehicles">123</p>
                    <div class="mt-2">
                        <button onclick="location.href='manage_vehicles.php'">Add Vehicle</button>
                    </div>
                </div>
                <div class="dashboard-card cursor-pointer">
                    <h3>Active Users</h3>
                    <p id="activeUsers">456</p>
                    <canvas id="activeUsersChart"></canvas>
                </div>
                <div class="dashboard-card cursor-pointer">
                    <h3>Reports Generated</h3>
                    <p id="reportsGenerated">789</p>
                    <div class="mt-2">
                        <button>Generate New</button>
                    </div>
                </div>
            </section>
            <section class="mt-8 priority-alerts">
                <h2 class="text-xl font-bold">Priority Alerts</h2>
                <div class="mt-4">
                    <p class="red" id="overdueVehicles">Overdue to Return: 5</p>
                    <p class="yellow" id="pendingApprovals">Pending Approvals: 3</p>
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

        document.addEventListener('DOMContentLoaded', function () {
            const totalVehiclesElement = document.getElementById('totalVehicles');
            const activeUsersElement = document.getElementById('activeUsers');
            const reportsGeneratedElement = document.getElementById('reportsGenerated');
            const overdueVehiclesElement = document.getElementById('overdueVehicles');
            const pendingApprovalsElement = document.getElementById('pendingApprovals');

            function fetchAdminDashboardData() {
                fetch('fetch_admin_dashboard_data.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error fetching admin dashboard data:', data.error);
                            return;
                        }

                        // Update dashboard elements
                        totalVehiclesElement.textContent = data.totalVehicles;
                        activeUsersElement.textContent = data.activeUsers;
                        reportsGeneratedElement.textContent = data.reportsGenerated;
                        overdueVehiclesElement.textContent = data.overdueVehicles;
                        pendingApprovalsElement.textContent = data.pendingApprovals;

                        // Update chart
                        const ctx = document.getElementById('activeUsersChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: data.userRoles.map(role => role.role),
                                datasets: [{
                                    data: data.userRoles.map(role => role.count),
                                    backgroundColor: ['#2b6cb0', '#68d391', '#f6ad55']
                                }]
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching admin dashboard data:', error);
                    });
            }

            // Fetch data on page load
            fetchAdminDashboardData();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportsGeneratedElement = document.getElementById('reportsGenerated');
            const generateReportButton = document.querySelector('.dashboard-card button');

            // Fetch the total reports count dynamically
            function fetchReportsCount() {
                fetch('fetch_admin_dashboard_data.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error fetching reports count:', data.error);
                            return;
                        }
                        reportsGeneratedElement.textContent = data.reportsGenerated;
                    })
                    .catch(error => {
                        console.error('Error fetching reports count:', error);
                    });
            }

            // Generate a new report
            generateReportButton.addEventListener('click', function () {
                fetch('generate_reports.php', {
                    method: 'POST',
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Report generated successfully!');
                            fetchReportsCount(); // Update the reports count
                        } else {
                            alert('Error generating report: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error generating report:', error);
                    });
            });

            // Fetch reports count on page load
            fetchReportsCount();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const overdueVehiclesElement = document.getElementById('overdueVehicles');
            const pendingApprovalsElement = document.getElementById('pendingApprovals');

            // Fetch priority alerts dynamically
            function fetchPriorityAlerts() {
                fetch('fetch_admin_dashboard_data.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            console.error('Error fetching priority alerts:', data.error);
                            return;
                        }

                        // Update priority alerts
                        overdueVehiclesElement.textContent = `Overdue Vehicles: ${data.overdueVehicles}`;
                        pendingApprovalsElement.textContent = `Pending Approvals: ${data.pendingApprovals}`;
                    })
                    .catch(error => {
                        console.error('Error fetching priority alerts:', error);
                    });
            }

            // Fetch priority alerts on page load
            fetchPriorityAlerts();
        });
    </script>
</body>
</html>