<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_id']) && isset($_POST['status'])) {
    $admin_id = intval($_POST['admin_id']);
    $new_status = $_POST['status'] === 'activate' ? 'active' : 'disabled';

    $sql = "UPDATE admins SET status = :status WHERE admin_id = :admin_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
    $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "admin status updated successfully.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} else {
    echo "Invalid request.";
}

$conn = null;

header("Location: admin_accounts.php");
exit;
?>
