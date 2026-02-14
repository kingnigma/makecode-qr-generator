<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    header('Location: register.php?error=' . urlencode('Please fill all required fields'));
    exit;
}

if ($password !== $password_confirm) {
    header('Location: register.php?error=' . urlencode('Passwords do not match'));
    exit;
}

if (strlen($password) < 6) {
    header('Location: register.php?error=' . urlencode('Password must be at least 6 characters'));
    exit;
}

$pdo = getDbConnection();
if (!$pdo) {
    header('Location: register.php?error=' . urlencode('Database unavailable'));
    exit;
}

// Check uniqueness
$stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
    header('Location: register.php?error=' . urlencode('Username or email already in use'));
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
try {
    $stmt->execute([$username, $email, $passwordHash]);
    $userId = $pdo->lastInsertId();
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    header('Location: index.php');
    exit;
} catch (Exception $e) {
    header('Location: register.php?error=' . urlencode('Unable to create account'));
    exit;
}
