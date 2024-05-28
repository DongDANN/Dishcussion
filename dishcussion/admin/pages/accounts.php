<?php
include '../includes/connection.php';


$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT user_id, name, email, datetime_creation, status FROM users WHERE 1=1";
if ($filter == 'active') {
    $sql .= " AND status = 'active'";
} elseif ($filter == 'disabled') {
    $sql .= " AND status = 'disabled'";
}
if (!empty($search)) {
    $sql .= " AND (name LIKE :search OR email LIKE :search)";
}

$countSql = "SELECT COUNT(*) as total FROM users WHERE 1=1";
if ($filter == 'active') {
    $countSql .= " AND status = 'active'";
} elseif ($filter == 'disabled') {
    $countSql .= " AND status = 'disabled'";
}
if (!empty($search)) {
    $countSql .= " AND (name LIKE :search OR email LIKE :search)";
}

$countStmt = $conn->prepare($countSql);
if (!empty($search)) {
    $countStmt->execute(['search' => "%$search%"]);
} else {
    $countStmt->execute();
}
$totalUsers = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPages = ceil($totalUsers / $limit);

$sql .= " LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
}
$stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Accounts</h1>
                </div>
                <div class="col-sm-6">
                    <form action="" method="get" class="float-sm-right">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                            <select name="filter" class="form-control" onchange="this.form.submit()">
                                <option value="all" <?= $filter == 'all' ? 'selected' : '' ?>>All</option>
                                <option value="active" <?= $filter == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="disabled" <?= $filter == 'disabled' ? 'selected' : '' ?>>Disabled</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <!--content-->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Manage Users</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Creation Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (count($users) > 0) {
                                        foreach ($users as $row) {
                                            $statusButton = $row['status'] == 'active'
                                                ? "<button type='submit' name='status' value='disable' class='btn btn-warning btn-sm'><i class='fas fa-user-slash'></i> Disable</button>"
                                                : "<button type='submit' name='status' value='activate' class='btn btn-success btn-sm'><i class='fas fa-user-check'></i> Activate</button>";
                                            echo "<tr>
                                                    <td>{$row['user_id']}</td>
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['email']}</td>
                                                    <td>{$row['datetime_creation']}</td>
                                                    <td>{$row['status']}</td>
                                                    <td>
                                                        <form action='change_user_status.php' method='post' style='display:inline;'>
                                                            <input type='hidden' name='user_id' value='{$row['user_id']}'>
                                                            $statusButton
                                                        </form>
                                                        <form action='delete_user.php' method='post' style='display:inline;'>
                                                            <input type='hidden' name='user_id' value='{$row['user_id']}'>
                                                            <button type='submit' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'><i class='fas fa-trash'></i> Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>No users found.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <?php if ($page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&filter=<?= $filter ?>&search=<?= htmlspecialchars($search) ?>">&laquo; Previous</a></li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&filter=<?= $filter ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a></li>
                                <?php endfor; ?>
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&filter=<?= $filter ?>&search=<?= htmlspecialchars($search) ?>">Next &raquo;</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<?php include '../includes/footer.php'; ?>
