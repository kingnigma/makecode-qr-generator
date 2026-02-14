<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$user = trim($_POST['user'] ?? '');
$password = $_POST['password'] ?? '';

if ($user === '' || $password === '') {
    header('Location: login.php?error=' . urlencode('Please enter your credentials'));
    exit;
}

$pdo = getDbConnection();
if (!$pdo) {
    header('Location: login.php?error=' . urlencode('Database unavailable'));
    exit;
}

$stmt = $pdo->prepare('SELECT id, username, email, password FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$user, $user]);
$row = $stmt->fetch();
if (!$row) {
    header('Location: login.php?error=' . urlencode('Invalid credentials'));
    exit;
}

if (!password_verify($password, $row['password'])) {
    header('Location: login.php?error=' . urlencode('Invalid credentials'));
    exit;
}

// Successful login
$_SESSION['user_id'] = $row['id'];
$_SESSION['username'] = $row['username'];
header('Location: index.php');
exit;
