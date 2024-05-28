<?php
include 'includes/connection.php';
 
    $user_id = $_SESSION['user_id'];
    $query = "SELECT name FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $name = $user['name'];

    $query = "SELECT category FROM category";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_COLUMN);

if(isset($_POST['publish'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $post_title = $_POST['post_title'];
   $post_title = filter_var($post_title, FILTER_SANITIZE_STRING);
   $post_content = $_POST['post_content'];
   $post_content = filter_var($post_content, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);
   $status = 'disabled';
   
   $image = $_FILES['post_image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['post_image']['size'];
   $image_tmp_name = $_FILES['post_image']['tmp_name'];
   $image_folder = 'assets/upload_images/'.$image;

   $select_image = $conn->prepare("SELECT * FROM `user_posts` WHERE post_image = ? AND user_id = ?");
   $select_image->execute([$image, $user_id]);

   if(isset($image)){
      if($select_image->rowCount() > 0 AND $image != ''){
         $message[] = 'image name repeated';
      }elseif($image_size > 2000000){
         $message[] = 'images size is too large';
      }else{
         move_uploaded_file($image_tmp_name, $image_folder);
      }
   }else{
      $image = '';
   }

   if($select_image->rowCount() > 0 AND $image != ''){
      $message[] = 'please rename your image';
   }else{
      $insert_post = $conn->prepare("INSERT INTO `user_posts`(user_id, name, post_title, post_content, category, post_image, status) VALUES(?,?,?,?,?,?,?)");
      $insert_post->execute([$user_id, $name, $post_title, $post_content, $category, $image, $status]);
      $message[] = 'last post added, waiting for admin approval';
   }
   
}

?>

<div class="modal fade" id="UserAddPostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Submit New Recipe</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="name" value="<?= htmlspecialchars($user['name']); ?>">
          <p>Recipe Name <span>*</span></p>
          <input type="text" name="post_title" maxlength="100" required placeholder="Add Recipe Name" class="form-control">
          <p>Recipe Content <span>*</span></p>
          <textarea name="post_content" class="form-control" required maxlength="10000" placeholder="Write your content..." cols="30" rows="10"></textarea>
          <p>Recipe Category <span>*</span></p>
          <select name="category" class="form-control" required>
            <option value="" selected disabled>-- Select category* </option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></option>
            <?php endforeach; ?>
          </select>
          <p>Post Image</p>
          <input type="file" name="post_image" class="form-control" accept="image/jpg, image/jpeg, image/png, image/webp">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" value="Submit" name="publish" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
    <?php
        if(isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
            }
        }
    ?>
  </div>
</div>