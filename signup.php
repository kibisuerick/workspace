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
</head>
<body>
    <h1>Sign Up</h1>
    <?php if (isset($success)): ?>
        <p style="color: green;"> <?= $success ?> </p>
    <?php elseif (isset($error)): ?>
        <p style="color: red;"> <?= $error ?> </p>
    <?php endif; ?>
    <p>Welcome! Please fill out the form below to create your account. Choose your role and ensure your password meets the requirements for security.</p>
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="button" id="togglePassword">Show</button><br>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <button type="button" id="toggleConfirmPassword">Show</button><br>

        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" required><br>

        <label for="email">Email Address:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="customer">Customer</option>
            <option value="employee">Employee</option>
            <option value="admin">Admin</option>
        </select><br>

        <button type="submit">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Log In</a></p>

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
</body>
</html>