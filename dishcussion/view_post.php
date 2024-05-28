<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>View Post</title>
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
   <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    .post-image {
        min-width: 100%;
        max-width: 100%;
        height: 300px;
    }
    .user-info {
        text-align: left;
    }
    .user-info .comment-info {
        text-align: left;
    }
    .scrollable-comments {
        max-height: 300px;
        overflow-y: auto;
    }
    .comment-date {
        color: #888;
    }
    .body {
        font-family: 'Poppins', sans-serif;
    }
    .container {
        font-family: 'Poppins', sans-serif;
    }
    .card-container {
        display: flex;
        flex-wrap: wrap;
    }
    .card-body {
        flex: 1;
    }
    .post-content-container {
        width: 50%;
        max-height: 500px;
        overflow-y: auto;
        text-align: left;
    }
    .post-image-container {
        width: 50%;
        max-height: 500px;
        display: flex;
        flex-direction: column;
    }
    .post-content-container {
        align-items: center;
    }
    @media (max-width: 768px) {
        .post-image-container, .post-content-container {
            width: 100%;
        }
    }
</style>

<body>
   
<?php
include 'includes/connection.php';
include 'includes/header.php';

if (isset($_GET['user_post_id'])) {
    $get_id = $_GET['user_post_id'];
} else {
    echo "No post ID provided.";
    exit;
}

if(isset($_POST['like_post'])){
    if(isset($_SESSION['user_id'])) {
       $user_post_id = $_GET['user_post_id'];
       $user_id = $_SESSION['user_id'];
       $user_post_id = filter_var($user_post_id, FILTER_SANITIZE_STRING);
       
       $select_post_like = $conn->prepare("SELECT * FROM `posts_like` WHERE user_post_id = ? AND user_id = ?");
       $select_post_like->execute([$user_post_id, $user_id]);
       
       if($select_post_like->rowCount() > 0){
           $remove_like = $conn->prepare("DELETE FROM `posts_like` WHERE user_post_id = ? AND user_id = ?");
           $remove_like->execute([$user_post_id, $user_id]);
           $message[] = 'removed from likes';
       } else {
           $add_like = $conn->prepare("INSERT INTO `posts_like`(user_id, user_post_id) VALUES(?,?)");
           $add_like->execute([$user_id, $user_post_id]);
           $message[] = 'added to likes';
       }
    }
}

if(isset($_POST['add_comment'])){
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];
    $user_post_id = $_GET['user_post_id'];
    
    $select_user_query = "SELECT name FROM users WHERE user_id = ?";
    $stmt_user = $conn->prepare($select_user_query);
    $stmt_user->execute([$user_id]);
    $user_info = $stmt_user->fetch(PDO::FETCH_ASSOC);
    
    $user_name = $user_info['name'];
    
    $insert_comment_query = "INSERT INTO posts_comment (user_post_id, user_id, name, comment) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_comment_query);
    $stmt->execute([$user_post_id, $user_id, $user_name, $comment]);
}

if(isset($_GET['user_post_id'])){
    $get_id = $_GET['user_post_id'];
} else {
    echo "No post ID provided.";
    exit;
}

$select_posts = $conn->prepare("SELECT * FROM `user_posts` WHERE status = ? AND user_post_id = ?");
$select_posts->execute(['active', $get_id]);
if($select_posts->rowCount() > 0){
   while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
      
   $post_id = $fetch_posts['user_post_id'];

   $count_post_comments = $conn->prepare("SELECT * FROM `posts_comment` WHERE user_post_id = ?");
   $count_post_comments->execute([$post_id]);
   $total_post_comments = $count_post_comments->rowCount(); 

   $count_post_likes = $conn->prepare("SELECT * FROM `posts_like` WHERE user_post_id = ?");
   $count_post_likes->execute([$post_id]);
   $total_post_likes = $count_post_likes->rowCount();

   $user_liked = false;
   if(isset($_SESSION['user_id'])) {
       $user_id = $_SESSION['user_id'];
       $confirm_likes = $conn->prepare("SELECT * FROM `posts_like` WHERE user_id = ? AND user_post_id = ?");
       $confirm_likes->execute([$user_id, $post_id]);
       $user_liked = $confirm_likes->rowCount() > 0;
   }
?>
<br>
<div class="container text-center">
<section class="posts-container" style="padding-bottom: 0;">
   <div class="card-container">
      <div class="card post-image-container">
         <div class="card-body">
            <input type="hidden" name="post_id" value="<?= $post_id; ?>">
            <input type="hidden" name="user_id" value="<?= $fetch_posts['user_id']; ?>">
            <div class="post-user">
               <div class="user-info">
                  <a href="home.php" class="btn btn-light" ><img src="assets/icons/2.png" class="img-fluid" alt=""></a>
                  <div><?= $fetch_posts['name']; ?></div>
                  <div><?= $fetch_posts['datetime']; ?></div>
               </div>
            </div>
            <div class="post-title"><h5><?= $fetch_posts['post_title']; ?></h5></div> 
            <?php
               if($fetch_posts['post_image'] != ''){  
            ?>
            <img src="assets/upload_images/<?= $fetch_posts['post_image']; ?>" class="post-image" alt="">
            <?php
            }
            ?>
<form action="" method="post" class="like_post">
    <div class="icons">
        <div class="comment-info text-start">
            <button type="submit" name="like_post" class="btn btn-link" style="text-decoration: none;">
                <img src="assets/icons/<?php echo $user_liked ? 'heart-fill.png' : 'heart.png'; ?>" class="img-fluid" alt="">
                <span style="text-decoration: none;"><?= $total_post_likes; ?></span>
            </button>
            <span style="text-decoration: none;"><img src="assets/icons/chat-circle.png" class="image-fluid" alt=""><?= $total_post_comments; ?></span>
        </div>
    </div>
</form>

         </div>
      </div>
      <div class="card post-content-container">
         <div class="card-body">
            <div class="post-content scrollable-content"><?= nl2br($fetch_posts['post_content']); ?></div>
         </div>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no posts found!</p>';
      }
      ?>
   </div>
</section>
<br>
<section class="comments-container">
    <?php if(isset($_SESSION['user_id'])) { ?>
    <form action="" method="post" class="add-comment" >
        <div class="d-flex align-items-stretch">
            <div class="flex-grow-1">
                <textarea name="comment" maxlength="1000" class="comment-box form-control" cols="120" rows="2" placeholder="Write your comment" required></textarea>
            </div>
            <button type="submit" class="inline-btn btn btn-primary btn-sm align-self-stretch" name="add_comment">Add Comment</button>
        </div>
    </form>
    <?php } else { ?>
    <p>Please <a href="login.php" class="btn btn-primary">login</a> to comment.</p>
    <?php } ?>
   
    <p class="comment-title">Post Comments</p>
    <div class="user-comments-container scrollable-comments">
    <?php
    $select_comments = $conn->prepare("SELECT * FROM `posts_comment` WHERE user_post_id = ?");
    $select_comments->execute([$get_id]);
    if($select_comments->rowCount() > 0){
        while($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)){
    ?>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-2">
                        <div class="comment-user">
                            <span><?= $fetch_comments['name']; ?> <span class="comment-date"><?= $fetch_comments['date']; ?></span></span>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['user_id']) && $fetch_comments['user_id'] === $_SESSION['user_id']) { ?>
                    <div class="col-10 text-end">
                        <button type="button" class="btn btn-warning btn-sm edit-comment-btn" data-id="<?= $fetch_comments['comment_id']; ?>" data-comment="<?= htmlspecialchars($fetch_comments['comment']); ?>">Edit</button>
                        <a href="delete_comment.php?comment_id=<?= $fetch_comments['comment_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                    </div>
                    <?php } ?>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="comment-box"><?= $fetch_comments['comment']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        }
    } else {
        echo '<p class="empty">No comments added yet.</p>';
    }
    ?>
    </div>
</section>
</div>

<div class="modal fade" id="editCommentModal" tabindex="-1" aria-labelledby="editCommentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editCommentForm" action="edit_comment.php" method="post">
          <input type="hidden" name="comment_id" id="comment_id">
          <div class="mb-3">
            <label for="edit_comment" class="form-label">Comment</label>
            <textarea name="edit_comment" id="edit_comment" class="form-control" rows="3" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary" name="update_comment">Save changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
$(document).ready(function() {
    var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    
    $('.edit-comment-btn').click(function() {
        var commentId = $(this).data('id');
        var commentContent = $(this).data('comment');

        $('#comment_id').val(commentId);
        $('#edit_comment').val(commentContent);

        $('#editCommentModal').modal('show');
    });

    $('form.like_post').submit(function(e) {
        if (!isLoggedIn) {
            e.preventDefault();
            var loginConfirm = confirm("Login is required, Do you want to login?");
            if (loginConfirm) {
                window.location.href = 'login.php';
            }
        }
    });
});
</script>
</body>
</html>
