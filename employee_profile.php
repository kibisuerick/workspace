<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validate session data
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'employee') {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];

    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $newPassword = $_POST['password'] ?? '';
    $currentPassword = $_POST['currentPassword'] ?? '';

    try {
        // Validate current password
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->execute(['id' => $_SESSION['user']['id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception("User not found in the database.");
        }

        // Debugging: Log the provided and hashed passwords
        file_put_contents('debug_session.log', "[DEBUG] Provided Password: $currentPassword\n", FILE_APPEND);
        file_put_contents('debug_session.log', "[DEBUG] Hashed Password from DB: {$user['password']}\n", FILE_APPEND);

        // Rehash the provided password for comparison
        $rehash = password_hash($currentPassword, PASSWORD_DEFAULT);
        file_put_contents('debug_session.log', "[DEBUG] Rehashed Password for Comparison: $rehash\n", FILE_APPEND);

        // Compare the rehashed password with the stored hash
        if ($rehash === $user['password']) {
            file_put_contents('debug_session.log', "[DEBUG] Rehashed password matches the stored hash.\n", FILE_APPEND);
        } else {
            file_put_contents('debug_session.log', "[DEBUG] Rehashed password does NOT match the stored hash.\n", FILE_APPEND);
        }

        // Check if the password is empty
        if (empty($currentPassword)) {
            throw new Exception("Current password cannot be empty.");
        }

        // Verify the current password
        if (!password_verify($currentPassword, $user['password'])) {
            file_put_contents('debug_session.log', "[DEBUG] Password verification failed.\n", FILE_APPEND);
            throw new Exception("Current password is incorrect.");
        } else {
            file_put_contents('debug_session.log', "[DEBUG] Password verification succeeded.\n", FILE_APPEND);
        }

        // Update employee details
        $updateQuery = "UPDATE employees SET email = :email, phone = :phone, address = :address WHERE id = :id";
        $updateParams = [
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'id' => $_SESSION['user']['id']
        ];

        $pdo->prepare($updateQuery)->execute($updateParams);

        // Update password if provided
        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE users SET password = :password WHERE id = :id")
                ->execute(['password' => $hashedPassword, 'id' => $_SESSION['user']['id']]);
        }

        $response['success'] = true;
        $response['message'] = "Profile updated successfully.";
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
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
                <li><a href="employee_reports.php" class="block py-2 px-4 hover:bg-gray-700">Reports</a></li>
                <li><a href="employee_profile.php" class="block py-2 px-4 bg-gray-700">Profile</a></li>
                <li><a href="logout.php" class="block py-2 px-4 hover:bg-gray-700">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col">
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
                <form class="space-y-4" method="POST">
                    <div>
                        <label for="email" class="block text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>" class="border rounded-lg px-4 py-2 w-full">
                    </div>
                    <div>
                        <label for="phone" class="block text-gray-700">Phone</label>
                        <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>" class="border rounded-lg px-4 py-2 w-full">
                    </div>
                    <div>
                        <label for="address" class="block text-gray-700">Address</label>
                        <input type="text" id="address" name="address" value="<?= htmlspecialchars($employee['address'] ?? '') ?>" class="border rounded-lg px-4 py-2 w-full">
                    </div>
                    <div>
                        <label for="password" class="block text-gray-700">New Password</label>
                        <input type="password" id="password" name="password" class="border rounded-lg px-4 py-2 w-full">
                    </div>
                    <div>
                        <label for="currentPassword" class="block text-gray-700">Current Password</label>
                        <input type="password" id="currentPassword" name="currentPassword" class="border rounded-lg px-4 py-2 w-full">
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const saveButton = form.querySelector('button[type="submit"]');
            const successMessage = document.createElement('div');
            successMessage.className = 'bg-green-100 text-green-800 p-4 rounded mb-4 hidden';
            successMessage.textContent = 'Profile updated successfully!';
            form.parentNode.insertBefore(successMessage, form);

            form.addEventListener('submit', function (event) {
                event.preventDefault();

                // Disable the save button to prevent multiple submissions
                saveButton.disabled = true;
                saveButton.textContent = 'Saving...';

                const formData = new FormData(form);

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            successMessage.classList.remove('hidden');
                            setTimeout(() => {
                                successMessage.classList.add('hidden');
                            }, 3000);
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the profile.');
                    })
                    .finally(() => {
                        // Re-enable the save button
                        saveButton.disabled = false;
                        saveButton.textContent = 'Save Changes';
                    });
            });
        });
    </script>
</body>
</html>