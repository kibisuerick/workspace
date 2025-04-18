<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');

// Add feedback mechanism for form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_vehicle'])) {
        $model = $_POST['model'];
        $brand = $_POST['brand'];
        $year = $_POST['year'];
        $registration_number = $_POST['registration_number'];
        $price_per_day = $_POST['price_per_day'];
        $status = $_POST['status'];
        $image_url = $_POST['image_url'];

        try {
            $stmt = $pdo->prepare("INSERT INTO cars (model, brand, year, registration_number, price_per_day, status, image_url) VALUES (:model, :brand, :year, :registration_number, :price_per_day, :status, :image_url)");
            $stmt->execute([
                'model' => $model,
                'brand' => $brand,
                'year' => $year,
                'registration_number' => $registration_number,
                'price_per_day' => $price_per_day,
                'status' => $status,
                'image_url' => $image_url
            ]);
            $message = 'Vehicle added successfully!';
        } catch (Exception $e) {
            $message = 'Error adding vehicle: ' . $e->getMessage();
        }
    }
}

$vehicles = $pdo->query("SELECT car_id, model, brand, year, registration_number, price_per_day, status, image_url FROM cars")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles</title>
    <style>
        /* General styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
            font-size: 2rem;
        }

        form {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 20px auto;
        }

        form h2 {
            margin-bottom: 20px;
            color: #555;
            font-size: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        button {
            background-color: #007bff;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            font-size: 1rem;
        }

        table th {
            background-color: #f4f4f4;
            color: #333;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        img {
            max-width: 100px;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            form {
                padding: 15px;
            }

            table {
                width: 100%;
            }

            table th, table td {
                padding: 8px;
            }

            button {
                width: 100%;
                padding: 12px;
            }
        }

        .back-to-dashboard {
            display: inline-block;
            margin: 20px auto;
            text-align: center;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .back-to-dashboard:hover {
            background-color: #0056b3;
        }

        header {
            background-color: #2d3748; /* Custom color */
            color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }

        header h1 {
            font-size: 1.25rem;
            font-weight: bold;
        }

        header nav ul {
            display: flex;
            gap: 1.5rem;
        }

        header nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        header nav ul li a:hover {
            color: #BFDBFE; /* Blue-200 */
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header class="bg-blue-600 text-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-4">
            <h1 class="text-xl font-bold">Admin Panel</h1>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="admin_dashboard.php" class="hover:underline hover:text-gray-200 transition">Dashboard</a></li>
                    <!--<li><a href="manage_vehicles.php" class="hover:underline hover:text-gray-200 transition">Manage Vehicles</a></li>-->
                    <li><a href="manage_users.php" class="hover:underline hover:text-gray-200 transition">Manage Users</a></li>
                    <li><a href="logout.php" class="hover:underline hover:text-gray-200 transition">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <h1>Manage Vehicles</h1>

    <!-- Display feedback message -->
    <?php if (!empty($message)): ?>
        <p class="text-center font-bold <?= strpos($message, 'Error') === 0 ? 'text-red-500' : 'text-green-500' ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" action="">
        <h2>Add Vehicle</h2>
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required>

        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required>

        <label for="registration_number">Registration Number:</label>
        <input type="text" id="registration_number" name="registration_number" required>

        <label for="price_per_day">Price per Day:</label>
        <input type="number" step="0.01" id="price_per_day" name="price_per_day" required>

        <label for="status">Status:</label>
        <input type="text" id="status" name="status" required>

        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url" required>

        <button type="submit" name="add_vehicle">Add Vehicle</button>
    </form>

    <div>
        <h2 style="text-align: center; margin-top: 20px;">Existing Vehicles</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Model</th>
                    <th>Brand</th>
                    <th>Year</th>
                    <th>Registration Number</th>
                    <th>Price per Day</th>
                    <th>Status</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vehicles as $vehicle): ?>
                    <tr>
                        <td><?= $vehicle['car_id'] ?></td>
                        <td><?= $vehicle['model'] ?></td>
                        <td><?= $vehicle['brand'] ?></td>
                        <td><?= $vehicle['year'] ?></td>
                        <td><?= $vehicle['registration_number'] ?></td>
                        <td><?= $vehicle['price_per_day'] ?></td>
                        <td><?= $vehicle['status'] ?></td>
                        <td><img src="<?= $vehicle['image_url'] ?>" alt="Vehicle Image"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>