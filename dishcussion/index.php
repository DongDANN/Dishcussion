<?php
include 'includes/connection.php';
if(isset($_SESSION['user_id'])){
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dishcussion</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="assets/js/ism-2.2.min.js"></script>
    <link rel="stylesheet" href="assets/css/my-slider.css"/>
    <style>
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
        .card-img-top {
            width: 100%;
            height: 200px; 
            object-fit: cover;
        }


    </style>
</head>
<body>
    <div class="nav-scroller py-1 mb-3 border-bottom">
        <nav class="nav nav-underline justify-content-between">
            <a class="nav-item nav-link link-body-emphasis" href="home.php" data-category="all">Random Recipes</a>
            <?php
                $select_categories = $conn->prepare("SELECT category_id, category FROM category");
                $select_categories->execute();
                while ($category = $select_categories->fetch(PDO::FETCH_ASSOC)) {
                    echo '<a class="nav-item nav-link link-body-emphasis" href="#" data-category="' . htmlspecialchars($category['category']) . '">' . htmlspecialchars($category['category']) . '</a>';
                }
            ?>
        </nav>
    </div>
    <br>
    <div class='container'>
        <div class="ism-slider" data-play_type="loop" data-interval="3000" id="my-slider">
            <ol>
                <li>
                <img src="assets/images/slides/1.png">
                </li>
                <li>
                <img src="assets/images/slides/2.png">
                </li>
                <li>
                <img src="assets/images/slides/31.png">
                </li>
                <li>
                <img src="assets/images/slides/4.png">
                </li>
            </ol>
        </div>
    </div>
    <br>
    <section class="posts-container mt-4">
        <div class="container">
        <h2 class="heading">Community Recipes</h2>
                <form class="w-100 me-3" role="search">
                    <input type="search" id="searchInput" class="form-control" placeholder="Search..." aria-label="Search">
                    <br>
                </form>
            <div class="row" id="postContainer">
                <?php
                    $select_posts = $conn->prepare("SELECT * FROM `user_posts` WHERE status = ? ORDER BY RAND() LIMIT 6 ");
                    $select_posts->execute(['active']);
                    if($select_posts->rowCount() > 0){
                        while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                        
                        $post_id = $fetch_posts['user_post_id'];

                        $count_post_comments = $conn->prepare("SELECT * FROM `posts_comment` WHERE user_post_id = ?");
                        $count_post_comments->execute([$post_id]);
                        $total_post_comments = $count_post_comments->rowCount(); 

                        $count_post_likes = $conn->prepare("SELECT * FROM `posts_like` WHERE user_post_id = ?");
                        $count_post_likes->execute([$post_id]);
                        $total_post_likes = $count_post_likes->rowCount();

                ?>
                <div class="col-lg-4 mb-4 post-item" data-title="<?= htmlspecialchars($fetch_posts['post_title']); ?>" data-content="<?= htmlspecialchars($fetch_posts['post_content']); ?>" data-name="<?= htmlspecialchars($fetch_posts['name']); ?>" data-category="<?= htmlspecialchars($fetch_posts['category']); ?>">
                    <div class="card">
                        <div class="card-body">
                            <input type="hidden" name="user_post_id" value="<?= $post_id; ?>">
                            <input type="hidden" name="user_id" value="<?= $fetch_posts['user_id']; ?>">
                            <div class="post-user">
                                <div>
                                    <div><h5><?= $fetch_posts['name']; ?></h5></div>
                                    <div><?= $fetch_posts['datetime']; ?></div>
                                </div>
                            </div>
                            <?php if($fetch_posts['post_image'] != ''){ ?>
                            <img src="assets/upload_images/<?= $fetch_posts['post_image']; ?>" class="card-img-top" alt="Post Image">
                            <?php } ?>
                            <h5 class="card-title"><?= $fetch_posts['post_title']; ?></h5>
                            <p class="cart-text"><?= $fetch_posts['category']; ?></p>
                            <p class="card-text"><?= $fetch_posts['post_content']; ?></p>
                            <a href="view_post.php?user_post_id=<?= $post_id; ?>" class="btn btn-primary">Read More</a>
                            <div class="icons-wrapper">
                            <div class="icons">
    <div class="comment-info text-start">
        <a href="view_post.php?user_post_id=<?= $post_id; ?>" style="text-decoration: none; color: inherit;">
            <img src="assets/icons/heart.png" class="img-fluid" alt="">
            <span style="text-decoration: none;"><?= $total_post_likes; ?></span>
            <span style="text-decoration: none;"><img src="assets/icons/chat-circle.png" class="image-fluid" alt=""><?= $total_post_comments; ?></span>
        </a>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                }else{
                    echo '<p class="empty">No posts added yet</p>';
                }
                ?>
            </div>
        <div class="row" id="MostLikedPostContainer">
        <h2 class="heading">Most Liked Recipes</h2>
        <?php
            $select_posts = $conn->prepare("
                SELECT 
                    user_posts.*, 
                    COUNT(posts_like.user_post_id) AS total_likes 
                FROM 
                    user_posts 
                LEFT JOIN 
                    posts_like 
                ON 
                    user_posts.user_post_id = posts_like.user_post_id 
                WHERE 
                    user_posts.status = ? 
                GROUP BY 
                    user_posts.user_post_id 
                ORDER BY 
                    total_likes DESC 
                LIMIT 6
            ");
            $select_posts->execute(['active']);
            if($select_posts->rowCount() > 0){
                while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
                
                $post_id = $fetch_posts['user_post_id'];

                $count_post_comments = $conn->prepare("SELECT * FROM `posts_comment` WHERE user_post_id = ?");
                $count_post_comments->execute([$post_id]);
                $total_post_comments = $count_post_comments->rowCount(); 
        ?>
        <div class="col-lg-4 mb-4 post-item" data-title="<?= htmlspecialchars($fetch_posts['post_title']); ?>" data-content="<?= htmlspecialchars($fetch_posts['post_content']); ?>" data-name="<?= htmlspecialchars($fetch_posts['name']); ?>" data-category="<?= htmlspecialchars($fetch_posts['category']); ?>">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" name="user_post_id" value="<?= $post_id; ?>">
                    <input type="hidden" name="user_id" value="<?= $fetch_posts['user_id']; ?>">
                    <div class="post-user">
                        <i class="fas fa-user"></i>
                        <div>
                            <div><?= $fetch_posts['name']; ?></div>
                            <div><?= $fetch_posts['datetime']; ?></div>
                        </div>
                    </div>
                    <?php if($fetch_posts['post_image'] != ''){ ?>
                    <img src="assets/upload_images/<?= $fetch_posts['post_image']; ?>" class="card-img-top" alt="Post Image">
                    <?php } ?>
                    <h5 class="card-title"><?= $fetch_posts['post_title']; ?></h5>
                    <p class="cart-text"><?= $fetch_posts['category']; ?></p>
                    <p class="card-text"><?= $fetch_posts['post_content']; ?></p>
                    <a href="view_post.php?user_post_id=<?= $post_id; ?>" class="btn btn-primary">Read More</a>
                    <div class="icons-wrapper">
                        <div class="icons">
                            <div class="comment-info text-start">
                                <img src="assets/icons/heart.png" class="img-fluid" alt="">
                                <span style="text-decoration: none;"><?= $fetch_posts['total_likes']; ?></span>
                                <span style="text-decoration: none;"><img src="assets/icons/chat-circle.png" class="image-fluid" alt=""><?= $total_post_comments; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            }
        }else{
            echo '<p class="empty">No posts added yet</p>';
        }
        ?>
    </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function(){
            var initialPosts = $('#postContainer .post-item').clone(); 

            $('#searchInput').on('keyup', function(){
                var searchText = $(this).val().toLowerCase();
                if (searchText === '') {
                    $('#postContainer').html(initialPosts);
                } else {
                    $('#postContainer .post-item').each(function(){
                        var title = $(this).data('title').toLowerCase();
                        var content = $(this).data('content').toLowerCase();
                        var name = $(this).data('name').toLowerCase(); 
                        var category = $(this).data('category').toLowerCase();
                        if(title.indexOf(searchText) === -1 && content.indexOf(searchText) === -1 && name.indexOf(searchText) === -1 && category.indexOf(searchText) === -1){
                            $(this).hide();
                        } else {
                            $(this).show();
                        }
                    });
                }
            });

            $('.nav-item').on('click', function(e){
                e.preventDefault();
                var filterCategory = $(this).data('category').toLowerCase();
                if (filterCategory === 'all') {
                    $('#postContainer').html(initialPosts);
                    $('#MostLikedPostContainer').show();
                } else {
                    $('#MostLikedPostContainer').hide();
                    $('#postContainer .post-item').each(function(){
                        var category = $(this).data('category').toLowerCase();
                        if(category.indexOf(filterCategory) !== -1){
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>