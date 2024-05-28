<?php
include 'includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['user_post_id'])) {
    $post_id = $_GET['user_post_id'];
    $user_id = $_SESSION['user_id'];

    $select_post = $conn->prepare("SELECT * FROM `user_posts` WHERE user_post_id = ? AND user_id = ?");
    $select_post->execute([$post_id, $user_id]);
    if ($select_post->rowCount() > 0) {
        $post = $select_post->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Unauthorized action!";
        exit;
    }
}

if (isset($_POST['confirm_delete'])) {
    $post_id = $_POST['user_post_id'];
    $user_id = $_SESSION['user_id'];

    $delete_post = $conn->prepare("DELETE FROM `user_posts` WHERE user_post_id = ? AND user_id = ?");
    $delete_post->execute([$post_id, $user_id]);

    header('Location: my_posts.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Post</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="alert alert-warning">
        <h4 class="alert-heading">Delete Post</h4>
        <p>Are you sure you want to delete the post titled "<?= htmlspecialchars($post['post_title'], ENT_QUOTES); ?>"?</p>
        <hr>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">Yes, Delete</button>
        <a href="my_posts.php" class="btn btn-secondary">Cancel</a>
    </div>
</div>

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete the post titled "<?= htmlspecialchars($post['post_title'], ENT_QUOTES); ?>"?
      </div>
      <div class="modal-footer">
        <form method="post" action="delete_post.php">
            <input type="hidden" name="user_post_id" value="<?= htmlspecialchars($post_id, ENT_QUOTES); ?>">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Yes, Delete</button>
        </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

</body>
</html>
