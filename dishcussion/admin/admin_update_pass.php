<?php
include '../includes/connection.php';

$admin_id = $_SESSION['admin_id'];

if(isset($_POST['submit'])){

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $select_prev_pass = $conn->prepare("SELECT password FROM `admins` WHERE admin_id = ?");
    $select_prev_pass->execute([$admin_id]);
    $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
    $prev_pass = $fetch_prev_pass['password'];
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass = sha1($_POST['confirm_pass']);
    $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

    if($new_pass != $confirm_pass){
        $message[] = 'Confirm password not matched!';
    } else {
        if($new_pass != $empty_pass){
            $update_pass = $conn->prepare("UPDATE `admins` SET password = ? WHERE admin_id = ?");
            $update_pass->execute([$confirm_pass, $admin_id]);
            $message[] = 'Password updated successfully!';
        } else {
            $message[] = 'Please enter a new password!';
        }
    }
}
?>

<div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateProfileModalLabel">Update Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updatePasswordForm" action="" method="post" onsubmit="return validateForm()">
                    <div class="mb-3">
                        <label for="new_pass" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" id="new_pass" name="new_pass" placeholder="Enter your new password" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                            <button class="btn btn-outline-secondary" type="button" id="toggleNewPass">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_pass" class="form-label">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm your new password" class="form-control" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPass">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary">Update Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function validateForm() {
    var newPass = document.getElementById('new_pass').value.trim();
    var confirmPass = document.getElementById('confirm_pass').value.trim();

    if (newPass === '' || confirmPass === '') {
        alert('Please fill in all fields.');
        return false;
    }
    if (newPass !== confirmPass) {
        alert('New password and confirm password do not match.');
        return false;
    }
    return true;
}

function togglePasswordVisibility(inputId, toggleButtonId) {
    var inputField = document.getElementById(inputId);
    var toggleButton = document.getElementById(toggleButtonId);
    if (inputField.type === "password") {
        inputField.type = "text";
        toggleButton.innerHTML = '<i class="bi bi-eye-slash"></i>';
    } else {
        inputField.type = "password";
        toggleButton.innerHTML = '<i class="bi bi-eye"></i>';
    }
}

document.getElementById('toggleNewPass').addEventListener('click', function() {
    togglePasswordVisibility('new_pass', 'toggleNewPass');
});

document.getElementById('toggleConfirmPass').addEventListener('click', function() {
    togglePasswordVisibility('confirm_pass', 'toggleConfirmPass');
});

$('#updateProfileModal').on('hidden.bs.modal', function (e) {
    $('#updatePasswordForm')[0].reset();
    document.getElementById('toggleNewPass').innerHTML = '<i class="bi bi-eye"></i>';
    document.getElementById('toggleConfirmPass').innerHTML = '<i class="bi bi-eye"></i>';
});
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="../assets/js/popper.min.js"></script>
