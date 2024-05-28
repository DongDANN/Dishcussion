<?php
include '../includes/connection.php';

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$itemsPerPage = 10;
$offset = ($page - 1) * $itemsPerPage;

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT category_id, category FROM category";
$countSql = "SELECT COUNT(*) AS total FROM category";
if ($searchTerm) {
    $sql .= " WHERE category LIKE ?";
    $countSql .= " WHERE category LIKE ?";
}
$sql .= " LIMIT $itemsPerPage OFFSET $offset";
$stmt = $conn->prepare($sql);
$stmtCount = $conn->prepare($countSql);

if ($searchTerm) {
    $stmt->execute(["%$searchTerm%"]);
    $stmtCount->execute(["%$searchTerm%"]);
} else {
    $stmt->execute();
    $stmtCount->execute();
}

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalItems = $stmtCount->fetchColumn();
$totalPages = ceil($totalItems / $itemsPerPage);
?>

<?php include '../includes/header.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Manage Categories</h1>
                </div>
                <div class="col-sm-6">
                    <form action="" method="get" class="float-sm-right">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search Categories" value="<?= htmlspecialchars($searchTerm) ?>">
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Categories</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCategoryModal">
                                    Add Category
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table id="categoryTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($categories) > 0): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <tr>
                                                <td><?= $category['category_id'] ?></td>
                                                <td><?= $category['category'] ?></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm editCategoryBtn" data-id="<?= $category['category_id'] ?>" data-name="<?= $category['category'] ?>"><i class="fas fa-edit"></i> Edit</button>
                                                    <button class="btn btn-danger btn-sm deleteCategoryBtn" data-id="<?= $category['category_id'] ?>"><i class="fas fa-trash"></i> Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3">No categories found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-right">
                                <?php if ($page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&search=<?= urlencode($searchTerm) ?>">&laquo; Previous</a></li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $page == $i ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($searchTerm) ?>"><?= $i ?></a></li>
                                <?php endfor; ?>
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&search=<?= urlencode($searchTerm) ?>">Next &raquo;</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <!-- End Pagination -->
                    </div>
                </div>
            </div>
        </div>
   


<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="categoryName">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                </button>
            </div>
            <form id="editCategoryForm">
                <div class="modal-body">
                    <input type="hidden" id="editCategoryId" name="editCategoryId">
                    <div class="form-group">
                        <label for="editCategoryName">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="editCategoryName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
 
    $(document).on('submit', '#addCategoryForm', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'add_category.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json', 
            success: function(response) {
                if ('message' in response) {
                    alert(response.message);
                    $('#addCategoryModal').modal('hide');
                    $('#categoryName').val('');
                    $('#categoryTable').load(document.URL + ' #categoryTable');
                } else if ('error' in response) {
                    alert("Error: " + response.error);
                } else {
                    alert("Unexpected response format");
                }
            },
            error: function(xhr, status, error) {
                alert("Error: " + xhr.responseText);
            }
        });
    });

    $(document).on('click', '.editCategoryBtn', function() {
        var categoryId = $(this).data('id');
        var categoryName = $(this).data('name');
        $('#editCategoryId').val(categoryId);
        $('#editCategoryName').val(categoryName);
        $('#editCategoryModal').modal('show');
    });

    $(document).on('submit', '#editCategoryForm', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'edit_category.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#editCategoryModal').modal('hide');
                $('#categoryTable').load(document.URL + ' #categoryTable');
            }
        });
    });

    $(document).on('click', '.deleteCategoryBtn', function() {
        var categoryId = $(this).data('id');
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: 'delete_category.php',
                type: 'POST',
                data: {categoryId: categoryId},
                success: function(response) {
                    $('#categoryTable').load(document.URL + ' #categoryTable');
                }
            });
        }
    });
</script>
<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
