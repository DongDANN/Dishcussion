<?php 
require '../../includes/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    
    $sql_add = "INSERT INTO category (category)
                VALUES ('$category')";
    
    if ($conn->query($sql_add) === TRUE) {
    } else {
        echo "Error: " . $sql_add . "<br>" . $conn->error;
    }
}
?>