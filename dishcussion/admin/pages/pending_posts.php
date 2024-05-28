<?php
include '../includes/connection.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$countSql = "SELECT COUNT(*) FROM user_posts WHERE 1=1";
$params = [];

if ($status != 'all') {
    $countSql .= " AND status = ?";
    $params[] = $status;
}

if (!empty($search)) {
    $countSql .= " AND (post_title LIKE ? OR post_content LIKE ? OR user_id LIKE ? OR name LIKE ?  OR category LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$countStmt = $conn->prepare($countSql);
$countStmt->execute($params);
$totalItems = $countStmt->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);

$params = [];

$sql = "SELECT user_post_id, user_id, name, category, post_title, post_content, post_image, datetime, status, datetime_last_modified FROM user_posts WHERE 1=1";

if ($status != 'all') {
    $sql .= " AND status = ?";
    $params[] = $status;
}

if (!empty($search)) {
    $sql .= " AND (post_title LIKE ? OR post_content LIKE ? OR user_id LIKE ? OR name LIKE ?  OR category LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$sql .= " LIMIT $itemsPerPage OFFSET $offset";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../includes/header.php'; ?>

<div class="modal fade" id="viewPostModal" tabindex="-1" aria-labelledby="viewPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPostModalLabel">Review Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="postContent"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewPostModal = document.getElementById('viewPostModal');
    const postContent = document.getElementById('postContent');

    viewPostModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const postId = button.getAttribute('data-post-id');

        fetch(`get_post_content.php?user_post_id=${postId}`)
            .then(response => response.text())
            .then(data => {
                postContent.innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
});

function confirmAcceptAll() {
    return confirm('Are you sure you want to accept all pending posts?');
}
</script>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Posts</h1>
                </div>
                <div class="col-sm-6">
                    <form action="" method="get" class="float-sm-right">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search..." value="<?= htmlspecialchars($search) ?>">
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="all" <?= $status == 'all' ? 'selected' : '' ?>>All</option>
                                <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="disabled" <?= $status == 'disabled' ? 'selected' : '' ?>>Disabled</option>
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?= ucfirst($status) ?> Posts</h3>
                      <form action="accept_all_pending.php" method="post" style="display:inline;" onsubmit="return confirmAcceptAll();" class="float-sm-right">
                        <button type="submit" class="btn btn-success float-sm-right ml-2">Accept All Pending</button>
                      </form>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Image</th>
                                        <th>Date</th>
                                        <th>Last Modified</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($posts)): ?>
                                    <?php foreach ($posts as $row): ?>
                                        <?php $shortContent = strlen($row['post_content']) > 40 ? substr($row['post_content'], 0, 40) . '...' : $row['post_content']; ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['user_post_id']) ?></td>
                                            <td><?= htmlspecialchars($row['user_id']) ?></td>
                                            <td><?= htmlspecialchars($row['name']) ?></td>
                                            <td><?= htmlspecialchars($row['category']) ?></td>
                                            <td><?= htmlspecialchars($row['post_title']) ?></td>
                                            <td><?= htmlspecialchars($shortContent) ?></td>
                                            <td><img src="../../assets/upload_images/<?= htmlspecialchars($row['post_image']) ?>" alt="Image" width="50"></td>
                                            <td><?= htmlspecialchars($row['datetime']) ?></td>
                                            <td><?= htmlspecialchars($row['datetime_last_modified']) ?></td>
                                            <td><?= htmlspecialchars($row['status']) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#viewPostModal" data-post-id="<?= htmlspecialchars($row['user_post_id']) ?>">View Post</button>
                                                <?php if ($row['status'] == 'disabled'): ?>
                                                    <form action="activate_post.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($row['user_post_id']) ?>">
                                                        <button type="submit" class="btn btn-success btn-sm">Activate</button>
                                                    </form>
                                                <?php else: ?>
                                                    <form action="disable_post.php" method="post" style="display:inline;">
                                                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($row['user_post_id']) ?>">
                                                        <button type="submit" class="btn btn-warning btn-sm">Disable</button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="delete_post.php" method="post" style="display:inline; margin-left: 5px;" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                    <input type="hidden" name="post_id" value="<?= htmlspecialchars($row['user_post_id']) ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="11">No posts found.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page - 1 ?>&status=<?= $status ?>&search=<?= htmlspecialchars($search) ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $i ?>&status=<?= $status ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?= $page + 1 ?>&status=<?= $status ?>&search=<?= htmlspecialchars($search) ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>
