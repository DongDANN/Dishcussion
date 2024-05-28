<?php
include 'includes/connection.php';

header('Content-Type: application/json');

$searchText = isset($_POST['searchText']) ? $_POST['searchText'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : 'all';

$query = "SELECT * FROM `user_posts` WHERE status = 'active'";
$params = [];

if ($category !== 'all') {
    $query .= " AND category = ?";
    $params[] = $category;
}

if (!empty($searchText)) {
    $query .= " AND (post_title LIKE ? OR post_content LIKE ?)";
    $params[] = "%$searchText%";
    $params[] = "%$searchText%";
}

$query .= " ORDER BY RAND() LIMIT 6";
$select_posts = $conn->prepare($query);
$select_posts->execute($params);

$posts = [];
while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
    $post_id = $fetch_posts['user_post_id'];

    $count_post_comments = $conn->prepare("SELECT * FROM `posts_comment` WHERE user_post_id = ?");
    $count_post_comments->execute([$post_id]);
    $total_post_comments = $count_post_comments->rowCount();

    $count_post_likes = $conn->prepare("SELECT * FROM `posts_like` WHERE user_post_id = ?");
    $count_post_likes->execute([$post_id]);
    $total_post_likes = $count_post_likes->rowCount();

    $user_liked = false;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $confirm_likes = $conn->prepare("SELECT * FROM `posts_like` WHERE user_id = ? AND user_post_id = ?");
        $confirm_likes->execute([$user_id, $post_id]);
        $user_liked = $confirm_likes->rowCount() > 0;
    }

    $posts[] = [
        'user_post_id' => $fetch_posts['user_post_id'],
        'user_id' => $fetch_posts['user_id'],
        'name' => htmlspecialchars($fetch_posts['name']),
        'datetime' => htmlspecialchars($fetch_posts['datetime']),
        'post_title' => htmlspecialchars($fetch_posts['post_title']),
        'post_content' => htmlspecialchars($fetch_posts['post_content']),
        'post_image' => htmlspecialchars($fetch_posts['post_image']),
        'category' => htmlspecialchars($fetch_posts['category']),
        'total_post_comments' => $total_post_comments,
        'total_post_likes' => $total_post_likes,
        'user_liked' => $user_liked,
    ];
}

echo json_encode($posts);
?>
