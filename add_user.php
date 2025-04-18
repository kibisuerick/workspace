<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($fullName) || empty($email) || empty($role) || empty($password)) {
        echo json_encode(['error' => 'All fields are required.']);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['error' => 'Invalid email address.']);
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (full_name, email, role, password, created_at) VALUES (:full_name, :email, :role, :password, NOW())");
        $stmt->execute([
            'full_name' => $fullName,
            'email' => $email,
            'role' => $role,
            'password' => $hashedPassword
        ]);

        echo json_encode(['success' => 'User added successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}