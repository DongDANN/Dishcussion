<?php
include 'db_connection.php';

if(isset($_POST['post_id']) && isset($_SESSION['user_id'])){
    $postId = $_POST['post_id'];
    $userId = $_SESSION['user_id'];

    $query = $conn->prepare("SELECT * FROM posts_like WHERE user_id = ? AND user_post_id = ?");
    $query->execute([$userId, $postId]);
    
    if($query->rowCount() == 0){
        $insert = $conn->prepare("INSERT INTO posts_like (user_id, user_post_id) VALUES (?, ?)");
        $insert->execute([$userId, $postId]);
    }

    $countLikes = $conn->prepare("SELECT COUNT(*) AS total_likes FROM posts_like WHERE user_post_id = ?");
    $countLikes->execute([$postId]);
    $totalLikes = $countLikes->fetch(PDO::FETCH_ASSOC);

    $confirmLikes = $conn->prepare("SELECT * FROM posts_like WHERE user_id = ? AND user_post_id = ?");
    $confirmLikes->execute([$userId, $postId]);
    
    echo json_encode([
        'total_likes' => $totalLikes['total_likes'],
        'liked' => $confirmLikes->rowCount() > 0
    ]);
}
?>
