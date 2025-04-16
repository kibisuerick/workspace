<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('customer');

// Add dynamic greeting and quick stats
$greeting = "Welcome back, " . htmlspecialchars($_SESSION['user']['full_name']) . "!";
$loyaltyPoints = 120; // Example value
$rentalStats = [
    'upcoming' => 2,
    'active' => 1,
    'completed' => 15,
];

// Example upcoming reservations
$upcomingReservations = [
    [
        'car_model' => 'Toyota Corolla',
        'pickup_date' => '2025-04-20',
        'pickup_location' => 'Downtown Office',
        'status' => 'Confirmed',
    ],
    [
        'car_model' => 'Honda Civic',
        'pickup_date' => '2025-04-25',
        'pickup_location' => 'Airport Terminal',
        'status' => 'Pending',
    ],
];

$activeRental = [
    'car_model' => 'Ford Focus',
    'return_time' => '2025-04-22 10:00 AM',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navigation Bar -->
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">CARNEX Dashboard</h1>
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

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 h-screen bg-gray-800 text-white transition-all duration-300 w-64 md:w-20 overflow-hidden">
        <!-- Header Section -->
        <div class="flex items-center justify-between p-4 border-b border-gray-700">
            <div class="flex items-center space-x-2">
                <img src="https://via.placeholder.com/40" alt="Avatar" class="rounded-full w-10 h-10">
                <span id="customerName" class="text-lg font-bold md:hidden">John Doe</span>
            </div>
            <button id="collapseButton" class="text-gray-400 hover:text-white md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Menu Items -->
        <nav class="mt-4 space-y-2">
            <!-- Top Section -->
            <a href="customer_dashboard.php" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700">
                <span class="text-xl">📊</span>
                <span class="md:hidden">Dashboard</span>
            </a>
            <a href="book_vehicle.php" class="flex items-center space-x-2 px-4 py-2 bg-blue-500 hover:bg-blue-600">
                <span class="text-xl">🚗</span>
                <span class="md:hidden">Book Vehicle</span>
            </a>
            <div class="group relative">
                <button class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700 w-full">
                    <span class="text-xl">📅</span>
                    <span class="md:hidden">My Rentals</span>
                    <span class="ml-auto">▶️</span>
                </button>
                <div class="hidden group-hover:block bg-gray-700 rounded mt-1">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-600">Upcoming (3)</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-600">Past</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-600">Cancelled</a>
                </div>
            </div>

            <!-- Middle Section -->
            <a href="profile.php" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700">
                <span class="text-xl">👤</span>
                <span class="md:hidden">Profile Settings</span>
            </a>
            <a href="#" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700">
                <span class="text-xl">💳</span>
                <span class="md:hidden">Payment Methods</span>
            </a>
            <div class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700">
                <span class="text-xl">🏆</span>
                <span class="md:hidden">Loyalty Rewards</span>
                <div class="w-full bg-gray-600 rounded-full h-2 ml-2">
                    <div class="bg-yellow-400 h-2 rounded-full" style="width: 60%;"></div>
                </div>
            </div>

            <!-- Bottom Section -->
            <a href="faq.php" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700">
                <span class="text-xl">🛟</span>
                <span class="md:hidden">Support Center</span>
            </a>
            <div class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700">
                <span class="text-xl">🌓</span>
                <span class="md:hidden">Dark Mode</span>
                <button id="darkModeToggle" class="ml-auto">Toggle</button>
            </div>
            <a href="logout.php" class="flex items-center space-x-2 px-4 py-2 hover:bg-gray-700">
                <span class="text-xl">⎋</span>
                <span class="md:hidden">Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="ml-64 md:ml-20 transition-all duration-300">
        <!-- Sticky Active Rental Alert -->
        <?php if (isset($activeRental)): ?>
        <div class="bg-yellow-100 text-yellow-800 px-4 py-3 sticky top-12 z-40 flex justify-between items-center">
            <p>You’re currently renting a <strong><?= htmlspecialchars($activeRental['car_model']) ?></strong> — Return by <strong><?= htmlspecialchars($activeRental['return_time']) ?></strong></p>
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Extend Rental</button>
        </div>
        <?php endif; ?>

        <!-- Main Content -->
        <main class="container mx-auto mt-6 p-4 space-y-6">
            <!-- Welcome Header -->
            <section class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-2"><?= $greeting ?></h2>
                <p class="text-gray-600">Loyalty Points: <span class="font-bold text-green-600"><?= $loyaltyPoints ?></span></p>
                <p class="text-gray-600">Upcoming Rentals: <span class="font-bold text-blue-600"><?= $rentalStats['upcoming'] ?></span></p>
            </section>

            <!-- Upcoming Reservations -->
            <section class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Upcoming Reservations</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <?php foreach ($upcomingReservations as $reservation): ?>
                    <div class="border rounded-lg p-4 shadow-sm">
                        <h3 class="text-md font-bold text-gray-800">Car: <?= htmlspecialchars($reservation['car_model']) ?></h3>
                        <p class="text-gray-600">Pickup Date: <?= htmlspecialchars($reservation['pickup_date']) ?></p>
                        <p class="text-gray-600">Location: <?= htmlspecialchars($reservation['pickup_location']) ?></p>
                        <p class="text-gray-600">Status: <span class="font-bold text-<?= $reservation['status'] === 'Confirmed' ? 'green' : 'orange' ?>-600"><?= htmlspecialchars($reservation['status']) ?></span></p>
                        <div class="mt-2 flex space-x-2">
                            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Modify</button>
                            <button class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Cancel</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Quick Actions -->
            <section class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Book New Rental</button>
                    <button class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Extend Current Rental</button>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Contact Support</button>
                </div>
            </section>

            <!-- Rental History & Activity -->
            <section class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Rental History & Activity</h2>
                <div class="tabs">
                    <button class="tab-button active">Active Rentals</button>
                    <button class="tab-button">Past Rentals</button>
                    <button class="tab-button">Favorites</button>
                </div>
                <!-- Tab Content Placeholder -->
            </section>
        </main>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4">
        <p>&copy; 2025 Car Rental Management System</p>
    </footer>

    <script>
        const sidebar = document.getElementById('sidebar');
        const collapseButton = document.getElementById('collapseButton');
        const darkModeToggle = document.getElementById('darkModeToggle');

        // Toggle Sidebar Collapse
        collapseButton.addEventListener('click', () => {
            sidebar.classList.toggle('w-64');
            sidebar.classList.toggle('w-20');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('w-20'));
        });

        // Remember Sidebar State
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('w-20');
            sidebar.classList.remove('w-64');
        }

        // Dark Mode Toggle
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        });

        // Remember Dark Mode State
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }

        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobileMenu');

        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
</body>
</html>