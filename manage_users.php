<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">Manage Users</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:underline">Home</a></li>
                    <li><a href="admin_dashboard.php" class="hover:underline">Dashboard</a></li>
                    <li><a href="logout.php" class="hover:underline">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container mx-auto py-10">
        <h1 class="text-3xl font-bold text-center mb-6">User Management</h1>
        <table class="w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="py-2 px-4">ID</th>
                    <th class="py-2 px-4">Username</th>
                    <th class="py-2 px-4">Email</th>
                    <th class="py-2 px-4">Role</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="py-2 px-4">1</td>
                    <td class="py-2 px-4">johndoe</td>
                    <td class="py-2 px-4">johndoe@example.com</td>
                    <td class="py-2 px-4">Admin</td>
                    <td class="py-2 px-4">
                        <a href="#" class="text-blue-500 hover:underline">Edit</a> |
                        <a href="#" class="text-red-500 hover:underline">Delete</a>
                    </td>
                </tr>
                <!-- Repeat similar rows for more users -->
            </tbody>
        </table>
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-10">
        <div class="container mx-auto text-center">
            <p class="text-sm">&copy; 2025 CARNEX. All rights reserved.</p>
            <p class="text-sm">Designed and developed by CARNEX</p>
        </div>
    </footer>
</body>
</html>