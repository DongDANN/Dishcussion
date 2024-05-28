<?php
include '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryId = $_POST['categoryId'];

    $sql = "DELETE FROM category WHERE category_id = :categoryId";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Category deleted successfully";
    } else {
        echo "Error deleting category";
    }
}
?>
