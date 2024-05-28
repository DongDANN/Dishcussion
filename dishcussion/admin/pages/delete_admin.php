<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$admin_id = isset($_POST['admin_id']) ? $_POST['admin_id'] : '';

if (!empty($admin_id)) {
$sql = "DELETE FROM admins WHERE admin_id = :admin_id";
$stmt = $conn->prepare($sql);
if ($stmt->execute(['admin_id' => $admin_id])) {
echo json_encode(['success' => true]);

echo "<script>window.location.href = 'admin_accounts.php';</script>";
exit;
} else {
echo json_encode(['success' => false, 'message' => 'Failed to delete admin.']);
}
} else {
echo json_encode(['success' => false, 'message' => 'Invalid admin ID.']);
}
}
?>