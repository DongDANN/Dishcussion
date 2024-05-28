<?php
include '../includes/connection.php';

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT admin_id, name, email, datetime_creation, status, role FROM admins WHERE 1=1";
if ($filter == 'active') {
    $sql .= " AND status = 'active'";
} elseif ($filter == 'disabled') {
    $sql .= " AND status = 'disabled'";
}
if (!empty($search)) {
    $sql .= " AND (name LIKE :search OR email LIKE :search)";
}

$countSql = "SELECT COUNT(*) as total FROM admins WHERE 1=1";
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
                    <h1>Manage Admin</h1>
                    <br>
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
                    <br>
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
                            <h3 class="card-title">Admins</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                                    Add Account
                                </button>
                            </div>
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
                                        <th>Role</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($users)) {
                                    foreach ($users as $row) {
                                        $statusButton = $row['status'] == 'active'
                                            ? "<button type='submit' name='status' value='disable' class='btn btn-warning btn-sm'><i class='fas fa-user-slash'></i> Disable</button>"
                                            : "<button type='submit' name='status' value='activate' class='btn btn-success btn-sm'><i class='fas fa-user-check'></i> Activate</button>";

                                        $roleButton = $row['role'] == 'Admin'
                                            ? "<button type='submit' name='role' value='Moderator' class='btn btn-secondary btn-sm'>Make Moderator</button>"
                                            : "<button type='submit' name='role' value='Admin' class='btn btn-primary btn-sm'>Make Admin</button>";

                                        echo "<tr>
                                                <td>{$row['admin_id']}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['email']}</td>
                                                <td>{$row['datetime_creation']}</td>
                                                <td>{$row['status']}</td>
                                                <td>{$row['role']}</td>
                                                <td>
                                                    <form action='change_admin_status.php' method='post' style='display:inline;'>
                                                        <input type='hidden' name='admin_id' value='{$row['admin_id']}'>
                                                        $statusButton
                                                    </form>
                                                    <form action='change_admin_role.php' method='post' style='display:inline; margin-left: 5px;'>
                                                        <input type='hidden' name='admin_id' value='{$row['admin_id']}'>
                                                        $roleButton
                                                    </form>
                                                    <form action='delete_admin.php' method='post' style='display:inline; margin-left: 5px;' onsubmit=\"return confirm('Are you sure you want to delete this account?');\">
                                                        <input type='hidden' name='admin_id' value='{$row['admin_id']}'>
                                                        <button type='submit' name='delete' value='delete' class='btn btn-danger btn-sm'><i class='fas fa-trash'></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No users found.</td></tr>";
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
</div>
<!-- Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAdminModalLabel">Add Admin Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addAdminForm" method="post">
                    <div class="mb-3">
                        <label for="admin_username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="admin_username" name="admin_username" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="admin_confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="admin_confirm_password" name="admin_confirm_password" required>
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="admin_role" class="form-label">Role</label>
                        <select class="form-control" id="admin_role" name="admin_role" required>
                            <option value="Admin">Admin</option>
                            <option value="Moderator">Moderator</option>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Register</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $('#addAdminModal').on('hidden.bs.modal', function (e) {
        $('#addAdminForm')[0].reset();
    });

    function togglePasswordVisibility(inputId) {
        var inputField = document.getElementById(inputId);
        var toggleButton = document.getElementById('toggle' + inputId.charAt(0).toUpperCase() + inputId.slice(1));
        if (inputField.type === "password") {
            inputField.type = "text";
            toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            inputField.type = "password";
            toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
        }
    }

    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordVisibility('admin_password');
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
        togglePasswordVisibility('admin_confirm_password');
    });

    document.getElementById('addAdminForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var formData = new FormData(this);
        fetch('register_admin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Admin account registered successfully.');
                    window.location.reload(); 
                } else {
                    alert(data.message); 
                }
            })
            .catch(error => console.error('Error:', error));
    });
</script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

