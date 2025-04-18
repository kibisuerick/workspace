<?php
require 'includes/db_connect.php';
require 'includes/auth.php';

checkAuth('admin');

$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$whereClauses = [];
$params = [];

if (!empty($search)) {
    $whereClauses[] = "(full_name LIKE :search OR email LIKE :search)";
    $params['search'] = "%$search%";
}

if (!empty($role)) {
    $whereClauses[] = "role = :role";
    $params['role'] = $role;
}

$whereSql = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

$totalUsers = $pdo->prepare("SELECT COUNT(*) FROM users $whereSql");
$totalUsers->execute($params);
$totalUsers = $totalUsers->fetchColumn();

$usersQuery = $pdo->prepare("SELECT id, full_name, email, role, status, created_at FROM users $whereSql LIMIT :limit OFFSET :offset");
foreach ($params as $key => $value) {
    $usersQuery->bindValue($key, $value);
}
$usersQuery->bindValue('limit', (int)$limit, PDO::PARAM_INT);
$usersQuery->bindValue('offset', (int)$offset, PDO::PARAM_INT);
$usersQuery->execute();
$users = $usersQuery->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode([
    'users' => $users,
    'totalUsers' => $totalUsers,
    'currentPage' => $page,
    'totalPages' => ceil($totalUsers / $limit)
]);