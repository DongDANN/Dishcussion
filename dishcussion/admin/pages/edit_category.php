<?php
include '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryId = $_POST['editCategoryId'];
    $categoryName = $_POST['editCategoryName'];

    $sql = "UPDATE category SET category = :categoryName WHERE category_id = :categoryId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Category updated successfully";
    } else {
        echo "Error updating category";
    }
}
?>
