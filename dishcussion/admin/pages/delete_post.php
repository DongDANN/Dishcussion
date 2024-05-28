<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];

    $sql = "DELETE FROM user_posts WHERE user_post_id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt->execute([$post_id])) {
        header('Location: pending_posts.php?msg=Post deleted successfully');
    } else {
        header('Location: pending_posts.php?msg=Failed to delete post');
    }
} else {
    header('Location: pending_posts.php');
}
?>
