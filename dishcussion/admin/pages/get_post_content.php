<?php
include '../includes/connection.php';

if(isset($_GET['user_post_id'])){
    $user_post_id = $_GET['user_post_id'];

    $stmt = $conn->prepare("SELECT user_id, name, category, post_title, post_content, post_image FROM user_posts WHERE user_post_id = ?");
    $stmt->execute([$user_post_id]);

    if($stmt->rowCount() > 0){
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        echo "<h5>Title: {$post['post_title']}</h5>";
        echo "<p><strong>Poster: </strong> {$post['name']} <strong>Category: </strong> {$post['category']}</p>";
        echo "<img src='../../assets/upload_images/{$post['post_image']}' alt='Image' style='width: 100%; height: auto;'>";
        echo "<p>" . nl2br(htmlspecialchars($post['post_content'])) . "</p>";
    } else {
        echo "Post not found!";
    }
} else {
    echo "No post ID provided.";
}
?>
