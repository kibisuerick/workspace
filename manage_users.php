<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');

// Fetch users from the database
$users = $pdo->query("SELECT id, full_name, email, role, status, created_at FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 50;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Manage Users</h1>
        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Add User</button>
    </header>

    <!-- Breadcrumbs -->
    <nav class="text-sm text-gray-500 mt-5 px-6">
        <a href="index.php" class="hover:text-blue-600">Home</a> >
        <a href="admin_dashboard.php" class="hover:text-blue-600">Dashboard</a> >
        <span class="font-semibold text-gray-800">Manage Users</span>
    </nav>

    <!-- Main Content -->
    <main class="p-6 space-y-6">
        <!-- Search and Filter -->
        <div class="bg-white shadow-md rounded-lg p-4">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Search Users</h2>
            <form class="flex space-x-4">
                <input type="text" placeholder="Search by name or email" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select class="border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="employee">Employee</option>
                    <option value="customer">Customer</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Registered Users</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border-b py-2 px-4">ID</th>
                            <th class="border-b py-2 px-4">Full Name</th>
                            <th class="border-b py-2 px-4">Email</th>
                            <th class="border-b py-2 px-4">Role</th>
                            <th class="border-b py-2 px-4">Status</th>
                            <th class="border-b py-2 px-4">Created At</th>
                            <th class="border-b py-2 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border-b py-2 px-4">
                                    <input type="checkbox" class="mr-2">
                                    <?= $user['id'] ?>
                                </td>
                                <td class="border-b py-2 px-4"> <?= htmlspecialchars($user['full_name']) ?> </td>
                                <td class="border-b py-2 px-4"> <?= htmlspecialchars($user['email']) ?> </td>
                                <td class="border-b py-2 px-4">
                                    <span class="px-2 py-1 rounded text-white <?= $user['role'] === 'admin' ? 'bg-blue-500' : ($user['role'] === 'employee' ? 'bg-green-500' : 'bg-yellow-500') ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td class="border-b py-2 px-4">
                                    <span class="flex items-center">
                                        <span class="w-2 h-2 rounded-full mr-2 <?= $user['status'] === 'active' ? 'bg-green-500' : 'bg-red-500' ?>"></span>
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td class="border-b py-2 px-4"> <?= $user['created_at'] ?> </td>
                                <td class="border-b py-2 px-4">
                                    <button class="text-blue-500 hover:underline">✏️ Edit</button>
                                    <button class="text-red-500 hover:underline" onclick="confirmDelete(<?= $user['id'] ?>)">🗑️ Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center">
            <p>Showing 1-10 of 50</p>
            <div class="flex space-x-2">
                <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Previous</button>
                <button class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Next</button>
            </div>
        </div>
    </main>

    <!-- Floating Dashboard Button -->
    <button class="fab bg-blue-100 hover:bg-blue-200 px-3 py-1 rounded-lg" onclick="location.href='admin_dashboard.php'" aria-label="Return to dashboard">
        🏠 Dashboard
    </button>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 md:w-1/3">
            <h2 class="text-lg font-bold mb-4">Confirm Deletion</h2>
            <p>Are you sure you want to delete this user?</p>
            <div class="mt-4 flex justify-end space-x-4">
                <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="toggleModal('deleteModal')">Cancel</button>
                <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Delete</button>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('hidden');
        }

        function confirmDelete(userId) {
            toggleModal('deleteModal');
            // Add logic to handle deletion
        }
    </script>
</body>
</html>