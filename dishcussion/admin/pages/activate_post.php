<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);

    $sql = "UPDATE user_posts SET status = 'active', datetime_last_modified = NOW() WHERE user_post_id = :post_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Post activated successfully.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }
} else {
    echo "Invalid request.";
}

$conn = null;

header("Location: pending_posts.php");
exit;
?>
