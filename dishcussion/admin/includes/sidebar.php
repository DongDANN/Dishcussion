<?php
include '../../includes/connection.php';

if(!isset($_SESSION['admin_id'])){
    header('Location: ../admin_login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];
$admin_name = '';
$admin_role = '';

$select_admin = $conn->prepare("SELECT name, role FROM `admins` WHERE admin_id = ?");
$select_admin->execute([$admin_id]);
$admin = $select_admin->fetch(PDO::FETCH_ASSOC);

if ($admin) {
    $admin_name = $admin['name'];
    $admin_role = $admin['role'];
} else {
    $admin_name = 'Admin'; 
}
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="dashboard.php" class="brand-link">
        <img src="../../assets/icons/Dishcussion.png" class="brand-image img-circle elevation-3" width="120" height="120">
        <span class="brand-text font-weight-light">Dishcussion</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info" style="color: white; font-family: Poppins, sans-serif">
                <span class="d-block">Hello, <?php echo htmlspecialchars($admin_name); ?></span>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php if ($admin_role !== 'Moderator'): ?>
                <li class="nav-item">
                    <a href="admin_accounts.php" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>Admin Accounts</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="accounts.php" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>User Accounts</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="categories.php" class="nav-link">
                        <i class="nav-icon fas fa-columns"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="pending_posts.php" class="nav-link">
                        <i class="nav-icon fas fa-image"></i>
                        <p>View Posts</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
