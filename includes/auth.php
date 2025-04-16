<?php
session_start();

function checkAuth($role = null) {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit();
    }

    if ($role && $_SESSION['user']['role'] !== $role) {
        header('Location: unauthorized.php');
        exit();
    }
}

function loginUser($username, $password) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'role' => $user['role'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'phone' => $user['phone']
        ];
        return true;
    }

    return false;
}

function logoutUser() {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>