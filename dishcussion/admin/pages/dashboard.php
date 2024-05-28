<?php
include '../../includes/connection.php';

include '../includes/header.php';


$sql = "SELECT user_id, name, email, datetime_creation, status FROM users";
$stmt = $conn->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT COUNT(*) as active_account_count FROM users WHERE status = 'active'";
$stmt = $conn->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$activeAccountCount = $result['active_account_count'];

$sql = "SELECT COUNT(*) as disabled_post_count FROM users WHERE status = 'disabled'";
$stmt = $conn->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$disabledpostCount = $result['disabled_post_count'];

$sql = "SELECT COUNT(*) as disabled_count FROM user_posts WHERE status = 'disabled'";
$stmt = $conn->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$disabledCount = $result['disabled_count'];

$sql = "SELECT COUNT(*) as active_count FROM user_posts WHERE status = 'active'";
$stmt = $conn->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$activeCount = $result['active_count'];

$sqlTotalLikes = "SELECT COUNT(*) as total_likes FROM posts_like";
$stmtTotalLikes = $conn->query($sqlTotalLikes);
$resultTotalLikes = $stmtTotalLikes->fetch(PDO::FETCH_ASSOC);
$totalLikes = $resultTotalLikes['total_likes'];

$sqlTotalComments = "SELECT COUNT(*) as total_comments FROM posts_comment";
$stmtTotalComments = $conn->query($sqlTotalComments);
$resultTotalComments = $stmtTotalComments->fetch(PDO::FETCH_ASSOC);
$totalComments = $resultTotalComments['total_comments'];

$sqlTotalAdmins = "SELECT COUNT(*) as total_admins FROM admins";
$stmtTotalAdmins = $conn->query($sqlTotalAdmins);
$resultTotalAdmins = $stmtTotalAdmins->fetch(PDO::FETCH_ASSOC);
$totalAdmins = $resultTotalAdmins['total_admins'];

$sqlTotalCategory = "SELECT COUNT(*) as total_admins FROM category";
$stmtTotalCategory = $conn->query($sqlTotalCategory);
$resultTotalCategory = $stmtTotalCategory->fetch(PDO::FETCH_ASSOC);
$totalCategory= $resultTotalCategory['total_admins'];

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3> <?php echo $activeAccountCount; ?> </h3>
                            <p>Total Active Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="accounts.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $disabledpostCount; ?></h3>
                            <p>Total Deactivated Users</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="accounts.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $totalCategory; ?></h3>
                            <p>Total Categories</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="categories.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3> <?php echo $disabledCount; ?> </h3>
                            <p>Total Pending Posts</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="pending_posts.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3> <?php echo $activeCount; ?> </h3>
                            <p>Total Posts</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3> <?php echo $totalLikes; ?> </h3>
                            <p>Total Likes</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3> <?php echo $totalComments; ?> </h3>
                            <p>Total Comments</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3> <?php echo $totalAdmins; ?> </h3>
                            <p>Total Admins</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>

