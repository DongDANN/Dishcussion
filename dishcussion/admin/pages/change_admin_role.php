<?php
include '../includes/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_id = isset($_POST['admin_id']) ? (int)$_POST['admin_id'] : 0;
    $new_role = isset($_POST['role']) ? $_POST['role'] : '';

    if ($admin_id > 0 && in_array($new_role, ['Admin', 'Moderator'])) {
        $sql = "UPDATE admins SET role = :new_role WHERE admin_id = :admin_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':new_role', $new_role, PDO::PARAM_STR);
        $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Role updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update role. Please try again.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid input.";
    }
}

header('Location: admin_accounts.php');
exit();
?>
