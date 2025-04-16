<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (loginUser($username, $password)) {
        if ($_SESSION['user']['role'] === 'admin') {
            header('Location: admin_dashboard.php');
        } elseif ($_SESSION['user']['role'] === 'employee') {
            header('Location: employee_dashboard.php');
        } elseif ($_SESSION['user']['role'] === 'customer') {
            header('Location: customer_dashboard.php');
        }
        exit();
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Ensure dropdown visibility */
        .group .group-hover\:block {
            display: block;
        }

        /* Remove hover-based visibility logic */
        .group:hover .group-hover\:block {
            display: block;
        }

        /* Ensure the dropdown stays open when hovering over it */
        .group:hover .group-hover\:block,
        .group-hover\:block:hover {
            display: block;
        }

        /* Fix positioning issues and prevent gaps */
        .dropdown-menu {
            position: absolute;
            top: 100%; /* Align directly below the parent */
            left: 0;
            z-index: 50;
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 12rem; /* Ensure dropdown has a minimum width */
        }

        .dropdown-menu li a {
            color: #374151;
            text-decoration: none;
            display: block;
            padding: 0.5rem 1rem;
        }

        .dropdown-menu li a:hover {
            background-color: #f3f4f6;
            color: #1f2937;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<header class="bg-gray-800 text-white py-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-lg font-bold">CARNEX Login</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="index.php" class="hover:underline">Home</a></li>
                <li><a href="blog.php" class="hover:underline">Blog</a></li>
                <li><a href="browse_vehicles.php" class="hover:underline">Browse Vehicles</a></li>
                <li class="relative group">
                    <a href="#" class="hover:underline flex items-center">Pages <svg class="w-4 h-4 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg></a>
                    <ul class="absolute hidden group-hover:block bg-white shadow-md mt-2 space-y-2 py-2 w-48">
                        <li><a href="team.php" class="block px-4 py-2 hover:bg-gray-100">Team</a></li>
                        <li><a href="pricing.php" class="block px-4 py-2 hover:bg-gray-100">Pricing</a></li>
                        <li><a href="faq.php" class="block px-4 py-2 hover:bg-gray-100">FAQ</a></li>
                        <li><a href="testimonials.php" class="block px-4 py-2 hover:bg-gray-100">Testimonials</a></li>
                        <li><a href="404.php" class="block px-4 py-2 hover:bg-gray-100">404 Page</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</header>

<div class="bg-gray-100 flex items-center justify-center flex-grow">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h1 class="text-3xl font-bold text-center mb-4">Welcome Back!</h1>
        <p class="text-gray-600 text-center mb-6">Please log in to access your account and manage your bookings.</p>

        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"> <?= $error ?> </p>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 font-medium mb-2">Username</label>
                <input type="text" id="username" name="username" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 px-3 text-gray-500">Show</button>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Login</button>
        </form>

        <div class="text-center mt-4">
            <a href="forgot_password.php" class="text-blue-500 hover:underline">Forgot Password?</a>
        </div>

        <p class="text-center text-gray-600 mt-4">Don't have an account? <a href="signup.php" class="text-blue-500 hover:underline">Sign Up</a></p>
    </div>
</div>

<footer class="bg-gray-800 text-white py-4 mt-auto w-full">
    <div class="container mx-auto text-center">
        <p class="text-sm">&copy; 2025 CARNEX. All rights reserved.</p>
        <p class="text-sm">Designed and developed by CARNEX</p>
    </div>
</footer>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const type = passwordField.type === 'password' ? 'text' : 'password';
        passwordField.type = type;
        this.textContent = type === 'password' ? 'Show' : 'Hide';
    });

    // Ensure the dropdown remains stable when clicked
    document.querySelectorAll('.group > a').forEach(function (dropdownToggle) {
        dropdownToggle.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent default link behavior
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu) {
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
            }
        });
    });

    // Close dropdown if clicked outside
    document.addEventListener('click', function (event) {
        const isClickInside = event.target.closest('.group');
        if (!isClickInside) {
            document.querySelectorAll('.dropdown-menu').forEach(function (dropdownMenu) {
                dropdownMenu.style.display = 'none';
            });
        }
    });
</script>
</body>
</html>