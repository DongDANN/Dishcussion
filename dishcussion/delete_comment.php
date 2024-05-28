<?php
include 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "You need to be logged in to delete comments.";
    exit;
}

if (isset($_GET['comment_id'])) {
    $comment_id = $_GET['comment_id'];
    $user_id = $_SESSION['user_id'];

    $select_comment = $conn->prepare("SELECT user_id, user_post_id FROM posts_comment WHERE comment_id = ?");
    $select_comment->execute([$comment_id]);
    $comment = $select_comment->fetch(PDO::FETCH_ASSOC);

    if ($comment) {
        if ($comment['user_id'] === $user_id) {

            $delete_comment = $conn->prepare("DELETE FROM posts_comment WHERE comment_id = ?");
            $delete_comment->execute([$comment_id]);

            header("Location: view_post.php?user_post_id=" . $comment['user_post_id']);
            exit;
        } else {
            echo "You can only delete your own comments.";
        }
    } else {
        echo "Comment not found.";
    }
} else {
    echo "No comment ID provided.";
}
?>
