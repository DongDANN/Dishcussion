<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

if (!empty($user_id)) {
$sql = "DELETE FROM users WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
if ($stmt->execute(['user_id' => $user_id])) {
echo json_encode(['success' => true]);

echo "<script>window.location.href = 'accounts.php';</script>";
exit;
} else {
echo json_encode(['success' => false, 'message' => 'Failed to delete user.']);
}
} else {
echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
}
}
?>