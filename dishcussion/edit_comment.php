<?php
include 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in to edit comments.";
    exit;
}

if (isset($_POST['update_comment'])) {
    $comment_id = $_POST['comment_id'];
    $updated_comment = $_POST['edit_comment'];
    $user_id = $_SESSION['user_id'];

    $select_comment = $conn->prepare("SELECT user_id, user_post_id FROM posts_comment WHERE comment_id = ?");
    $select_comment->execute([$comment_id]);
    $comment = $select_comment->fetch(PDO::FETCH_ASSOC);

    if ($comment) {
        
        if ($comment['user_id'] === $user_id) {
            
            $update_comment_query = "UPDATE posts_comment SET comment = ? WHERE comment_id = ?";
            $stmt_update = $conn->prepare($update_comment_query);
            $stmt_update->execute([$updated_comment, $comment_id]);
            
            header("Location: view_post.php?user_post_id=" . $comment['user_post_id']);
            exit;
        } else {
            echo "You can only edit your own comments.";
        }
    } else {
        echo "Comment not found.";
    }
} else {
    echo "No comment data provided.";
}
?>
