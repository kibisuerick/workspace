<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('customer');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $user_id = $_SESSION['user']['id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Calculate total price
    $stmt = $pdo->prepare("SELECT price_per_day FROM cars WHERE id = :car_id");
    $stmt->execute(['car_id' => $vehicle_id]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vehicle) {
        $days = (strtotime($end_date) - strtotime($start_date)) / 86400;
        $total_price = $days * $vehicle['price_per_day'];

        // Insert booking
        $stmt = $pdo->prepare("INSERT INTO bookings (user_id, vehicle_id, start_date, end_date, total_price) VALUES (:user_id, :vehicle_id, :start_date, :end_date, :total_price)");
        $stmt->execute([
            'user_id' => $user_id,
            'vehicle_id' => $vehicle_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_price' => $total_price
        ]);

        // Mark vehicle as unavailable
        $stmt = $pdo->prepare("UPDATE cars SET availability = 0 WHERE id = :car_id");
        $stmt->execute(['car_id' => $vehicle_id]);

        echo "Booking successful!";
    } else {
        echo "Vehicle not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Your Vehicle</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .sticky-sidebar {
            position: sticky;
            top: 1rem;
        }

        .vehicle-card {
            transition: transform 0.2s ease-in-out;
        }

        .vehicle-card:hover {
            transform: scale(1.02);
        }

        .map-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .map-modal.active {
            display: flex;
        }

        .map-container {
            width: 80%;
            height: 80%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header Section -->
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-bold">CARNEX</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="index.php" class="hover:underline">Home</a></li>
                    <li><a href="customer_dashboard.php" class="hover:underline">Dashboard</a></li>
                    <li><a href="browse_vehicles.php" class="hover:underline">Browse Vehicles</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Breadcrumbs -->
    <div class="container mx-auto mt-4">
        <nav class="text-gray-600 text-sm">
            <a href="index.php" class="hover:underline">Home</a> &gt; <span>Book Vehicle</span>
        </nav>
    </div>

    <!-- Page Title -->
    <div class="container mx-auto mt-6">
        <h1 class="text-3xl font-bold text-gray-800">Reserve Your Vehicle</h1>
        <p class="text-gray-600">Choose your vehicle and complete your booking in just a few steps.</p>
    </div>

    <!-- Booking Form -->
    <div class="container mx-auto mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Filters and Search -->
        <div class="md:col-span-2">
            <form id="searchForm" class="bg-white shadow-md rounded-lg p-6 space-y-4">
                <div>
                    <label for="location" class="block text-gray-700 font-medium">Pickup Location</label>
                    <button type="button" id="openMapModal" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">Select Location on Map</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-gray-700 font-medium">Start Date</label>
                        <input type="text" id="start_date" name="start_date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Select start date">
                    </div>
                    <div>
                        <label for="end_date" class="block text-gray-700 font-medium">End Date</label>
                        <input type="text" id="end_date" name="end_date" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Select end date">
                    </div>
                </div>

                <div>
                    <label for="vehicle_type" class="block text-gray-700 font-medium">Vehicle Type</label>
                    <div class="flex space-x-4">
                        <button type="button" class="vehicle-type-filter bg-gray-200 rounded-lg px-4 py-2 hover:bg-gray-300">SUV</button>
                        <button type="button" class="vehicle-type-filter bg-gray-200 rounded-lg px-4 py-2 hover:bg-gray-300">Sedan</button>
                        <button type="button" class="vehicle-type-filter bg-gray-200 rounded-lg px-4 py-2 hover:bg-gray-300">Luxury</button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600">Search Vehicles</button>
            </form>

            <!-- Vehicle Display Grid -->
            <div id="vehicleGrid" class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Example Vehicle Card -->
                <div class="vehicle-card bg-white shadow-md rounded-lg p-4">
                    <div class="relative">
                        <img src="https://via.placeholder.com/300" alt="Vehicle Image" class="w-full h-40 object-cover rounded-md">
                        <button class="absolute top-2 right-2 bg-gray-800 text-white rounded-full p-2 hover:bg-gray-700">360°</button>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mt-2">Toyota Corolla</h3>
                    <p class="text-gray-600">Seats: 5 | Bags: 3 | Transmission: Automatic</p>
                    <p class="text-gray-800 font-bold mt-2">$50/day</p>
                    <button class="w-full bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-600 mt-4">Reserve Now</button>
                </div>
            </div>
        </div>

        <!-- Booking Summary Sidebar -->
        <div class="sticky-sidebar bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800">Booking Summary</h2>
            <p class="text-gray-600 mt-2">Dates: <span id="summaryDates">-</span></p>
            <p class="text-gray-600">Location: <span id="summaryLocation">-</span></p>
            <p class="text-gray-600">Vehicle: <span id="summaryVehicle">-</span></p>
            <div class="mt-4">
                <div class="timeline bg-gray-200 rounded-lg h-2 relative">
                    <div class="progress bg-blue-500 h-2 absolute top-0 left-0" style="width: 50%;"></div>
                </div>
            </div>
            <p class="text-gray-800 font-bold mt-4">Total: <span id="summaryTotal">$0.00</span></p>
            <button class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-600 mt-4">Proceed to Payment</button>
        </div>
    </div>

    <!-- Map Modal -->
    <div id="mapModal" class="map-modal">
        <div class="map-container">
            <button id="closeMapModal" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-2 hover:bg-red-600">Close</button>
            <iframe src="https://www.google.com/maps/embed" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </div>

    <script>
        // Initialize Flatpickr for date pickers
        flatpickr("#start_date", { minDate: "today" });
        flatpickr("#end_date", { minDate: "today" });

        // Map Modal functionality
        const mapModal = document.getElementById('mapModal');
        const openMapModal = document.getElementById('openMapModal');
        const closeMapModal = document.getElementById('closeMapModal');

        openMapModal.addEventListener('click', () => {
            mapModal.classList.add('active');
        });

        closeMapModal.addEventListener('click', () => {
            mapModal.classList.remove('active');
        });

        // Example AJAX search functionality
        document.getElementById('searchForm').addEventListener('submit', function (e) {
            e.preventDefault();
            // Simulate search results
            alert('Search functionality not implemented yet.');
        });
    </script>
</body>
</html>