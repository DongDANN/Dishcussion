<?php
include '../includes/connection.php';

$sql = "UPDATE user_posts SET status = 'active' WHERE status = 'disabled'";

$stmt = $conn->prepare($sql);
$stmt->execute();

header("Location: pending_posts.php?status=all");
exit();
?>
