<?php
include '../../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['admin_username'];
    $email = $_POST['admin_email'];
    $password = $_POST['admin_password'];
    $confirmPassword = $_POST['admin_confirm_password'];
    $role = $_POST['admin_role'];

    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit;
    }

    if (strlen($password) < 6 || strlen($password) > 16) {
        echo json_encode(['success' => false, 'message' => 'Password must be between 6 and 16 characters.']);
        exit;
    }

    $checkExistence = $conn->prepare("SELECT COUNT(*) AS count FROM admins WHERE name = ? OR email = ?");
    $checkExistence->execute([$username, $email]);
    $result = $checkExistence->fetch(PDO::FETCH_ASSOC);
    if ($result['count'] > 0) {
        echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
        exit;
    }

    $insertAdmin = $conn->prepare("INSERT INTO admins (name, email, password, status, role) VALUES (?, ?, ?, 'active', ?)");
    $insertAdmin->execute([$username, $email, sha1($password), $role]);
    if ($insertAdmin) {
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to register admin account.']);
        exit;
    }
}
?>
