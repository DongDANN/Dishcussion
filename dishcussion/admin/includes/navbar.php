<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="../pages/dashboard.php" class="nav-link">Home</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user-circle"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="../admin_update_pass.php" class="nav-link" id="updateProfileBtn">
                    <i class="fas fa-user mr-2"></i> Change Password
                </a>
                <div class="dropdown-divider"></div>
                <a href="../admin_logout.php" class="nav-link">
                    <i class="fas fa-arrow-left mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<?php include '../admin_update_pass.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function(){
        $('#updateProfileBtn').click(function(e){
            e.preventDefault();
            $('#updateProfileModal').modal('show');
        });
    });
    </script>
