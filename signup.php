<?php
require 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role, full_name, email, phone) VALUES (:username, :password, :role, :full_name, :email, :phone)");
        $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword,
            'role' => $role,
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone
        ]);
        $success = "User registered successfully. You can now log in.";
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        body > *:not(footer) {
            flex: 1;
        }

        header {
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 10;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-start" style="padding-top: 5rem; min-height: 100vh;">
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">CARNEX Sign Up</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="login.php" class="hover:underline">Login</a></li>
                    <li><a href="browse_vehicles.php" class="hover:underline">Browse Vehicles</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md my-10">
        <h1 class="text-2xl font-bold text-center mb-6">Sign Up</h1>
        <p class="text-center text-gray-600 mb-4">Welcome! Please fill out the form below to create your account.</p>
        <?php if (isset($success)): ?>
            <p class="text-green-500 text-center mb-4"> <?= $success ?> </p>
        <?php elseif (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"> <?= $error ?> </p>
        <?php endif; ?>
        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username:</label>
                <input type="text" id="username" name="username" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 text-gray-500">Show</button>
                </div>
            </div>

            <div>
                <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password:</label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 px-3 text-gray-500">Show</button>
                </div>
            </div>

            <div>
                <label for="full_name" class="block text-gray-700 text-sm font-bold mb-2">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
                <input type="email" id="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
                <input type="tel" id="phone" name="phone" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div>
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                <select id="role" name="role" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="customer">Customer</option>
                    <option value="employee">Employee</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">Sign Up</button>
            </div>
        </form>
        <p class="text-center text-gray-600 mt-4">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Log In</a></p>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Check if passwords match
            if (password !== confirmPassword) {
                event.preventDefault();
                alert('Passwords do not match.');
                return;
            }

            // Check password strength
            const passwordStrengthRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordStrengthRegex.test(password)) {
                event.preventDefault();
                alert('Password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a number, and a special character.');
            }
        });

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirm_password');
            const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
            confirmPasswordField.type = type;
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    </script>

    <footer class="bg-gray-800 text-white py-4 mt-auto w-full">
        <div class="container mx-auto text-center">
            <p class="text-sm">&copy; 2025 CARNEX. All rights reserved.</p>
            <p class="text-sm">Designed and developed by CARNEX</p>
        </div>
    </footer>
</body>
</html>