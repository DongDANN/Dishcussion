<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id']) && isset($_POST['status'])) {
    $user_id = intval($_POST['user_id']);
    $new_status = $_POST['status'] === 'activate' ? 'active' : 'disabled';

    $sql = "UPDATE users SET status = :status WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "User status updated successfully.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} else {
    echo "Invalid request.";
}

$conn = null;

header("Location: accounts.php");
exit;
?>
