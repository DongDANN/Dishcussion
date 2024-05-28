<?php
include '../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $categoryName = $_POST['categoryName'];

    $checkExistence = $conn->prepare("SELECT COUNT(*) AS count FROM category WHERE category = :categoryName");
    $checkExistence->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
    $checkExistence->execute();
    $result = $checkExistence->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        echo json_encode(["error" => "Category already exists"]);
        exit;
    }

    
    $sql = "INSERT INTO category (category) VALUES (:categoryName)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':categoryName', $categoryName, PDO::PARAM_STR);
    if ($stmt->execute()) {
        
        echo json_encode(["message" => "Category added successfully"]);
    } else {
        echo json_encode(["error" => "Error adding category"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
