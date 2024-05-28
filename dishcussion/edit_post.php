<?php
include 'includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['user_post_id'];
    $post_title = htmlspecialchars($_POST['post_title'], ENT_QUOTES);
    $post_content = htmlspecialchars($_POST['post_content'], ENT_QUOTES);
    $category = htmlspecialchars($_POST['category'], ENT_QUOTES);

    $post_image = '';
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] == 0) {
        $target_dir = "assets/upload_images/";
        $target_file = $target_dir . basename($_FILES["post_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["post_image"]["tmp_name"]);
        if ($check !== false) {
            if ($_FILES["post_image"]["size"] <= 2000000) {
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    if (move_uploaded_file($_FILES["post_image"]["tmp_name"], $target_file)) {
                        $post_image = basename($_FILES["post_image"]["name"]);
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                        exit;
                    }
                } else {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    exit;
                }
            } else {
                echo "Sorry, your file is too large.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
    }

    if ($post_image) {
        $sql = "UPDATE user_posts SET post_title = ?, post_content = ?, category = ?, post_image = ? WHERE user_post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$post_title, $post_content, $category, $post_image, $post_id]);
    } else {
        $sql = "UPDATE user_posts SET post_title = ?, post_content = ?, category = ? WHERE user_post_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$post_title, $post_content, $category, $post_id]);
    }

    header("Location: my_posts.php");
    exit();
} else {
    echo "Invalid request method.";
    exit();
}
?>
