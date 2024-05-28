<?php
include 'includes/connection.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);

    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $loggedinsusername = htmlspecialchars($row['name'], ENT_QUOTES);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($loggedinsusername); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
        .body {
            font-family: 'Poppins', sans-serif;
        }
        .container {
        font-family: 'Poppins', sans-serif;
        }
        .card-text {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            overflow: hidden;
            -webkit-line-clamp: 2;
        }
        .icons-wrapper {
            display: flex;
            justify-content: flex-end;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-options {
            display: flex;
        }
        .post-status {
            margin-right: 10px;
            color: #dc3545;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>
<body>
<?php include 'includes/header.php'?>
<section class="posts-container mt-4">
    <div class="container">
        <h2 class="heading">My Recipes</h2>
        <div class="row" id="postContainer">
            <?php
            $select_posts = $conn->prepare("SELECT * FROM `user_posts` WHERE user_id = ?");
            $select_posts->execute([$user_id]);
            if ($select_posts->rowCount() > 0) {
                while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
                    $post_id = $fetch_posts['user_post_id'];

                    $count_post_comments = $conn->prepare("SELECT * FROM `posts_comment` WHERE user_post_id = ?");
                    $count_post_comments->execute([$post_id]);
                    $total_post_comments = $count_post_comments->rowCount();

                    $count_post_likes = $conn->prepare("SELECT * FROM `posts_like` WHERE user_post_id = ?");
                    $count_post_likes->execute([$post_id]);
                    $total_post_likes = $count_post_likes->rowCount();

                    $confirm_likes = $conn->prepare("SELECT * FROM `posts_like` WHERE user_id = ? AND user_post_id = ?");
                    $confirm_likes->execute([$user_id, $post_id]);

                    
                    $categories_query = $conn->prepare("SELECT * FROM `category`");
                    $categories_query->execute();
                    $categories = $categories_query->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="col-lg-4 mb-4 post-item" data-title="<?= htmlspecialchars($fetch_posts['post_title']); ?>" data-content="<?= htmlspecialchars($fetch_posts['post_content']); ?>" data-name="<?= htmlspecialchars($fetch_posts['name']); ?>" data-category="<?= htmlspecialchars($fetch_posts['category']); ?>">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="user_post_id" value="<?= $post_id; ?>">
                        <input type="hidden" name="user_id" value="<?= $fetch_posts['user_id']; ?>">
                        <div class="buttons">
                            <div class="btn-options">
                                <a href="#" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#UserEditPostModal-<?= $post_id ?>">Edit</a>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal-<?= $post_id; ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                        <div class="post-user">
                            <i class="fas fa-user"></i>
                            <div>
                                <div><?= $fetch_posts['name']; ?></div>
                                <div><?= $fetch_posts['datetime']; ?></div>
                            </div>
                        </div>
                        <?php if ($fetch_posts['post_image'] != '') { ?>
                        <img src="assets/upload_images/<?= $fetch_posts['post_image']; ?>" class="card-img-top" alt="Post Image">
                        <?php } ?>
                        <h5 class="card-title"><?= htmlspecialchars($fetch_posts['post_title'], ENT_QUOTES); ?></h5>
                        <p class="cart-text"><?= htmlspecialchars($fetch_posts['category'], ENT_QUOTES); ?></p>
                        <p class="card-text"><?= htmlspecialchars($fetch_posts['post_content'], ENT_QUOTES); ?></p>
                        <?php if ($fetch_posts['status'] == 'active') { ?>
                            <a href="view_post.php?user_post_id=<?= $post_id; ?>" class="btn btn-primary">Read More</a>
                        <?php } else { ?>
                            <span class="post-status">Post waiting for approval</span>
                        <?php } ?>
                        <div class="icons-wrapper">
                            <div class="icons">
                                <div class="comment-info text-start">
                                    <img src="assets/icons/<?php echo ($confirm_likes->rowCount() > 0) ? 'heart-fill.png' : 'heart.png'; ?>" class="img-fluid" alt="">
                                    <span style="text-decoration: none;"><?= $total_post_likes; ?></span>
                                    <span style="text-decoration: none;"><img src="assets/icons/chat-circle.png" class="image-fluid" alt=""><?= $total_post_comments; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Post Modal -->
            <div class="modal fade" id="UserEditPostModal-<?= $post_id ?>" tabindex="-1" aria-labelledby="UserEditPostModalLabel-<?= $post_id ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="UserEditPostModalLabel-<?= $post_id ?>">Edit Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="edit_post.php" enctype="multipart/form-data">
                                <input type="hidden" name="user_post_id" value="<?= $post_id; ?>">
                                <div class="mb-3">
                                    <label for="post_title" class="form-label">Title</label>
                                    <input type="text" class="form-control" id="post_title" name="post_title" value="<?= htmlspecialchars($fetch_posts['post_title'], ENT_QUOTES); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="post_content" class="form-label">Content</label>
                                    <textarea class="form-control" id="post_content" name="post_content" rows="4" required><?= htmlspecialchars($fetch_posts['post_content'], ENT_QUOTES); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <?php foreach ($categories as $category) { ?>
                                            <option value="<?= htmlspecialchars($category['category'], ENT_QUOTES); ?>" <?= ($fetch_posts['category'] == $category['category']) ? 'selected' : ''; ?>>
                                                <?= htmlspecialchars($category['category'], ENT_QUOTES); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="post_image" class="form-label">Image</label>
                                    <input type="file" class="form-control" id="post_image" name="post_image">
                                    <?php if ($fetch_posts['post_image'] != '') { ?>
                                    <img src="assets/upload_images/<?= $fetch_posts['post_image']; ?>" class="img-fluid mt-2" alt="Post Image">
                                    <?php } ?>
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="confirmDeleteModal-<?= $post_id; ?>" tabindex="-1" aria-labelledby="confirmDeleteModalLabel-<?= $post_id; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel-<?= $post_id; ?>">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the post titled "<?= htmlspecialchars($fetch_posts['post_title'], ENT_QUOTES); ?>"?
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
            <?php
                }
            } else {
                echo '<p class="empty">No posts added yet</p>';
            }
            ?>
        </div>
    </div>
</section>
</body>
</html>
