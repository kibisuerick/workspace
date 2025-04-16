<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');

// Fetch reports data
$reports = $pdo->query("SELECT * FROM reports ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">Admin Panel</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="admin_dashboard.php" class="hover:underline">Dashboard</a></li>
                    <li><a href="manage_vehicles.php" class="hover:underline">Manage Vehicles</a></li>
                    <li><a href="manage_users.php" class="hover:underline">Manage Users</a></li>
                    <li><a href="logout.php" class="hover:underline">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto mt-8">
        <h1 class="text-3xl font-bold text-gray-800">View Reports</h1>
        <p class="text-gray-600">Browse and analyze system-generated reports.</p>

        <div class="mt-6 bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full table-auto border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Report ID</th>
                        <th class="border border-gray-300 px-4 py-2">Title</th>
                        <th class="border border-gray-300 px-4 py-2">Created At</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reports) > 0): ?>
                        <?php foreach ($reports as $report): ?>
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-4 py-2 text-center"><?= $report['id'] ?></td>
                                <td class="border border-gray-300 px-4 py-2 text-center"><?= htmlspecialchars($report['title']) ?></td>
                                <td class="border border-gray-300 px-4 py-2 text-center"><?= $report['created_at'] ?></td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <a href="generate_reports.php?report_id=<?= $report['id'] ?>" class="text-blue-500 hover:underline">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">No reports available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>