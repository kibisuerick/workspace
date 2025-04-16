<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

// Start session and check if user is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'employee') {
    header('Location: unauthorized.php');
    exit;
}

// Ensure all required keys exist in the session user array
$employee = [
    'id' => $_SESSION['user']['id'] ?? 'N/A',
    'full_name' => $_SESSION['user']['full_name'] ?? 'N/A',
    'email' => $_SESSION['user']['email'] ?? 'N/A',
    'phone' => $_SESSION['user']['phone'] ?? 'N/A',
    'address' => $_SESSION['user']['address'] ?? 'N/A',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
        <h1 class="text-xl font-bold text-gray-800">My Profile</h1>
        <nav class="text-sm text-gray-600">
            <a href="employee_dashboard.php" class="hover:underline">Home</a> > <span>Profile</span>
        </nav>
    </header>

    <!-- Profile Section -->
    <main class="flex-1 p-6 space-y-6">
        <div class="bg-white shadow-md rounded-lg p-6 flex flex-col md:flex-row">
            <!-- Profile Picture -->
            <div class="flex flex-col items-center md:w-1/3">
                <img src="https://via.placeholder.com/150" alt="Profile Picture" class="w-32 h-32 rounded-full mb-4">
                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit Picture</button>
            </div>

            <!-- Personal Details -->
            <div class="md:w-2/3 md:pl-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Personal Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700">Name</label>
                        <p class="text-gray-900"><?= htmlspecialchars($employee['full_name']) ?></p>
                    </div>
                    <div>
                        <label class="block text-gray-700">Role</label>
                        <p class="text-gray-900">Employee</p>
                    </div>
                    <div>
                        <label class="block text-gray-700">Employee ID</label>
                        <p class="text-gray-900">#<?= htmlspecialchars($employee['id']) ?></p>
                    </div>
                    <div>
                        <label class="block text-gray-700">Contact Info</label>
                        <p class="text-gray-900">Email: <?= htmlspecialchars($employee['email']) ?></p>
                        <p class="text-gray-900">Phone: <?= htmlspecialchars($employee['phone'] ?? 'N/A') ?></p>
                    </div>
                    <div>
                        <label class="block text-gray-700">Hire Date</label>
                        <p class="text-gray-900">2023-01-15</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Editable Fields -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Edit Profile</h2>
            <form class="space-y-4">
                <div>
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" value="<?= htmlspecialchars($employee['email']) ?>" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div>
                    <label for="phone" class="block text-gray-700">Phone</label>
                    <input type="text" id="phone" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div>
                    <label for="address" class="block text-gray-700">Address</label>
                    <input type="text" id="address" value="<?= htmlspecialchars($employee['address'] ?? '') ?>" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div>
                    <label for="password" class="block text-gray-700">New Password</label>
                    <input type="password" id="password" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div>
                    <label for="currentPassword" class="block text-gray-700">Current Password</label>
                    <input type="password" id="currentPassword" class="border rounded-lg px-4 py-2 w-full">
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Recent Activity</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="border-b py-2 px-4">Date</th>
                        <th class="border-b py-2 px-4">Action</th>
                        <th class="border-b py-2 px-4">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-b py-2 px-4">2025-04-15</td>
                        <td class="border-b py-2 px-4">Processed Rental</td>
                        <td class="border-b py-2 px-4">Rental #1234</td>
                    </tr>
                    <tr>
                        <td class="border-b py-2 px-4">2025-04-14</td>
                        <td class="border-b py-2 px-4">Updated Profile</td>
                        <td class="border-b py-2 px-4">Changed phone number</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p>&copy; 2025 Car Rental Management System</p>
    </footer>
</body>
</html>