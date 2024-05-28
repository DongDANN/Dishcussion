<?php
include 'includes/connection.php';
?>
<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

<div class="container">
    <header class="border-bottom lh-1 py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4 pt-1">
                <img class="mb-0 rounded-circle shadow me-2" src="assets/icons/Dishcussion.png" alt="" width="32" height="32">
            </div>
            <div class="col-4 text-center">
                <a class="blog-header-logo text-body-emphasis text-decoration-none" href="home.php#">Dishcussion</a>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">
                <?php
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    $query = "SELECT name FROM users WHERE user_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$user_id]);

                    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $loggedinsusername = htmlspecialchars($row['name'], ENT_QUOTES);

                        echo '
                        <div class="dropdown">
                          <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Hello, ' . $loggedinsusername . '
                          </button>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#" id="addPostBtn">Submit Recipe</a></li>
                            <li><a class="dropdown-item" href="my_posts.php">My Recipes</a></li>
                            <li><a class="dropdown-item" href="#" id="updateProfileBtn">Update Password</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                          </ul>
                        </div>';
                    } else {
                        echo "Error: User name not found";
                    }
                } else {
                    echo '<a class="btn btn-sm btn-outline-secondary" href="login.php">Login</a>';
                }
                ?>
            </div>
        </div>
    </header>
    <?php
    if (isset($_SESSION['user_id'])) {
        include 'add_post.php';
        include 'update_profile.php';
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function(){
        $('#addPostBtn').click(function(e){
            e.preventDefault();
            $('#UserAddPostModal').modal('show');
        });
        $('#updateProfileBtn').click(function(e){
            e.preventDefault();
            $('#updateProfileModal').modal('show');
        });
    });
    </script>
</body>
</html>
