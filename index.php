<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    echo "<script>
        if (confirm('You are not signed in. Would you like to log in or sign up?')) {
            if (confirm('Do you want to sign up?')) {
                window.location.href = 'signup.php';
            } else {
                window.location.href = 'login.php';
            }
        }
    </script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        a:hover {
            transition: color 0.3s ease;
            color: #1d4ed8; /* Subtle hover effect for links */
        }

        button:hover {
            transition: background-color 0.3s ease;
        }

        /* Tailwind Enhancements */
        .hero-section {
            background-image: url('https://via.placeholder.com/1920x1080');
            background-size: cover;
            background-position: center;
        }

        .featured-car-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .featured-car-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .testimonial-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        /* Add smooth animations */
        .fade-in {
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Enhance button hover effects */
        .btn-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        /* Add subtle hover effects for cards */
        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Improve typography */
        h1, h2, h3 {
            font-family: 'Poppins', sans-serif;
        }

        p {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <!-- Top Navigation Bar -->
    <header class="bg-gray-800 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-2xl font-bold">CarRental</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="index.php" class="hover:underline">Home</a></li>
                    <li><a href="browse_vehicles.php" class="hover:underline">Cars</a></li>
                    <li><a href="#services" class="hover:underline">Services</a></li>
                    <li><a href="#about" class="hover:underline">About</a></li>
                    <li><a href="#contact" class="hover:underline">Contact</a></li>
                </ul>
            </nav>
            <div>
                <a href="login.php" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Login</a>
                <a href="signup.php" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded ml-2">Sign Up</a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section h-screen flex items-center justify-center bg-cover bg-center relative fade-in" style="background-image: url('https://via.placeholder.com/1920x1080');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="text-center text-white z-10">
            <h1 class="text-6xl font-extrabold mb-4 drop-shadow-lg">Drive Your Dreams</h1>
            <p class="text-2xl mb-6 drop-shadow-md">Affordable and reliable car rentals across Kenya</p>
            <a href="#search" class="bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-700 hover:to-green-700 text-white py-3 px-8 rounded-full text-lg shadow-lg btn-hover">Rent a Car Now</a>
        </div>
    </section>

    <!-- Search/Filter Section -->
    <section id="search" class="bg-white py-10 shadow-md fade-in">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold text-center mb-6">Find Your Perfect Car</h2>
            <form class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <input type="text" placeholder="Pickup Location" class="border rounded py-2 px-4">
                <input type="text" placeholder="Drop-off Location" class="border rounded py-2 px-4">
                <input type="date" placeholder="Pickup Date" class="border rounded py-2 px-4">
                <input type="date" placeholder="Return Date" class="border rounded py-2 px-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded col-span-full btn-hover">Search</button>
            </form>
        </div>
    </section>

    <!-- Featured Cars Section -->
    <section class="bg-gray-100 py-10 fade-in">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold text-center mb-6">Featured Cars</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="featured-car-card bg-white rounded shadow p-4 card-hover">
                    <img src="https://via.placeholder.com/300x200" alt="Car" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold">Toyota Corolla</h3>
                    <p class="text-gray-600">Specs: Automatic, 5 Seats, AC</p>
                    <p class="text-gray-800 font-bold">KES 5,000/day</p>
                    <button class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded mt-4 btn-hover">Rent Now</button>
                </div>
                <div class="featured-car-card bg-white rounded shadow p-4 card-hover">
                    <img src="https://via.placeholder.com/300x200" alt="Car" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold">Honda Fit</h3>
                    <p class="text-gray-600">Specs: Manual, 4 Seats, AC</p>
                    <p class="text-gray-800 font-bold">KES 4,500/day</p>
                    <button class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded mt-4 btn-hover">Rent Now</button>
                </div>
                <div class="featured-car-card bg-white rounded shadow p-4 card-hover">
                    <img src="https://via.placeholder.com/300x200" alt="Car" class="w-full h-48 object-cover rounded mb-4">
                    <h3 class="text-xl font-bold">Land Cruiser Prado</h3>
                    <p class="text-gray-600">Specs: Automatic, 7 Seats, 4WD</p>
                    <p class="text-gray-800 font-bold">KES 15,000/day</p>
                    <button class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded mt-4 btn-hover">Rent Now</button>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="services" class="bg-white py-10 fade-in">
        <div class="container mx-auto text-center">
            <h2 class="text-2xl font-bold mb-6">How It Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-xl font-bold">Step 1</h3>
                    <p>Choose your car and location</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Step 2</h3>
                    <p>Book your car online</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold">Step 3</h3>
                    <p>Pick up your car and enjoy</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Testimonials Section -->
    <section id="testimonials" class="bg-gray-100 py-10 fade-in">
        <div class="container mx-auto">
            <h2 class="text-2xl font-bold text-center mb-6">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="testimonial-card bg-white rounded shadow p-4 card-hover">
                    <p class="text-gray-600">"The car was in excellent condition and made my trip to Nairobi seamless!"</p>
                    <p class="text-right font-bold">- Jane Mwangi</p>
                </div>
                <div class="testimonial-card bg-white rounded shadow p-4 card-hover">
                    <p class="text-gray-600">"Affordable prices and a wide selection of cars. Perfect for my Mombasa vacation!"</p>
                    <p class="text-right font-bold">- Michael Otieno</p>
                </div>
                <div class="testimonial-card bg-white rounded shadow p-4 card-hover">
                    <p class="text-gray-600">"Booking was easy and the staff was very helpful. Highly recommend!"</p>
                    <p class="text-right font-bold">- Emily Wanjiru</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-white py-10 fade-in">
        <div class="container mx-auto text-center">
            <h2 class="text-2xl font-bold mb-6">Get in Touch</h2>
            <p>Contact us at <a href="mailto:mwaiflorence384@gmail.com" class="text-blue-500">mwaiflorence384@gmail.com</a> or call us at +254 712 345 678</p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 CarRental Kenya. All rights reserved.</p>
            <p>Designed and developed by CarRental Kenya Team</p>
        </div>
    </footer>
</body>
</html>