<?php

include 'includes/connection.php';

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    if (strlen($pass) < 6 || strlen($pass) > 16) {
        $message[] = 'Password must be between 6 and 16 characters';
    } else {
        $pass = sha1($pass);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);
        $cpass = sha1($cpass);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user->execute([$email]);

        if (empty($name) || strlen($name) < 6 || !preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $message[] = 'Username must have a minimum of 6 characters, and should contain only letters and numbers';
        } elseif ($select_user->rowCount() > 0) {
            $message[] = 'Email already exists';
        } else {
            $select_name = $conn->prepare("SELECT * FROM `users` WHERE name = ?");
            $select_name->execute([$name]);
            if ($select_name->rowCount() > 0) {
                $message[] = 'Name already taken';
            } elseif ($pass != $cpass) {
                $message[] = 'Confirm password not matched';
            } else {
                $insert_user = $conn->prepare("INSERT INTO `users` (name, email, password) VALUES (?, ?, ?)");
                $insert_user->execute([$name, $email, $pass]);
                $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
                $select_user->execute([$email, $pass]);
                $row = $select_user->fetch(PDO::FETCH_ASSOC);
                if ($select_user->rowCount() > 0) {
                    $_SESSION['user_id'] = $row['id'];
                    header('location:login.php');
                }
            }
        }
    }
}
?>
<?php

include 'includes/connection.php';

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    if (strlen($pass) < 6 || strlen($pass) > 16) {
        $message[] = 'Password must be between 6 and 16 characters';
    } else {
        $pass = sha1($pass);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);
        $cpass = sha1($cpass);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

        $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        $select_user->execute([$email]);

        if (empty($name) || strlen($name) < 6 || !preg_match('/^[a-zA-Z0-9]+$/', $name)) {
            $message[] = 'Username must have a minimum of 6 characters, and should contain only letters and numbers';
        } elseif ($select_user->rowCount() > 0) {
            $message[] = 'Email already exists';
        } else {
            $select_name = $conn->prepare("SELECT * FROM `users` WHERE name = ?");
            $select_name->execute([$name]);
            if ($select_name->rowCount() > 0) {
                $message[] = 'Name already taken';
            } elseif ($pass != $cpass) {
                $message[] = 'Confirm password not matched';
            } else {
                $insert_user = $conn->prepare("INSERT INTO `users` (name, email, password) VALUES (?, ?, ?)");
                $insert_user->execute([$name, $email, $pass]);
                $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
                $select_user->execute([$email, $pass]);
                $row = $select_user->fetch(PDO::FETCH_ASSOC);
                if ($select_user->rowCount() > 0) {
                    $_SESSION['user_id'] = $row['id'];
                    header('location:login.php');
                }
            }
        }
    }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<title>Signup - Dishcussion</title>
<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
<style>
body {
    background-image: url('assets/images/background.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    font-family: 'Poppins', sans-serif;
}
.bd-placeholder-img {
    font-size: 1.125rem;
    text-anchor: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
}
@media (min-width: 768px) {
    .bd-placeholder-img-lg {
        font-size: 3.5rem;
    }
}
.b-example-divider {
    width: 100%;
    height: 3rem;
    background-color: rgba(0, 0, 0, .1);
    border: solid rgba(0, 0, 0, .15);
    border-width: 1px 0;
    box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
}
.b-example-vr {
    flex-shrink: 0;
    width: 1.5rem;
    height: 100vh;
}
.bi {
    vertical-align: -.125em;
    fill: currentColor;
}
.nav-scroller {
    position: relative;
    z-index: 2;
    height: 2.75rem;
    overflow-y: hidden;
}
.nav-scroller .nav {
    display: flex;
    flex-wrap: nowrap;
    padding-bottom: 1rem;
    margin-top: -1px;
    overflow-x: auto;
    text-align: center;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}
.btn-bd-primary {
    --bd-violet-bg: #712cf9;
    --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
    --bs-btn-font-weight: 600;
    --bs-btn-color: var(--bs-white);
    --bs-btn-bg: var(--bd-violet-bg);
    --bs-btn-border-color: var(--bd-violet-bg);
    --bs-btn-hover-color: var(--bs-white);
    --bs-btn-hover-bg: #6528e0;
    --bs-btn-hover-border-color: #6528e0;
    --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
    --bs-btn-active-color: var(--bs-btn-hover-color);
    --bs-btn-active-bg: #5a23c8;
    --bs-btn-active-border-color: #5a23c8;
}
.bd-mode-toggle {
    z-index: 1500;
}
.bd-mode-toggle .dropdown-menu .active .bi {
    display: block !important;
}
form i {
    margin-left: -30px;
    cursor: pointer;
}
</style>
<!-- Custom styles for this template -->
<link href="assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="form-signin w-100 m-auto">
<form method="post">
<div class="text-center">
    <img class="mb-4 rounded-circle shadow" src="assets/icons/Dishcussion.png" alt="" width="120" height="120">
</div>
<h1 class="h3 mb-3 fw-normal">Join Dishcussion</h1>
<div class="form-floating">
    <input type="text" class="form-control my-1" name="name" id="floatingInput" placeholder="Username">
    <label for="floatingInput">Username</label>
</div>
<div class="form-floating">
    <input type="Email" class="form-control my-1" name="email" id="floatingInput1" placeholder="Email">
    <label for="floatingInput1">Email</label>
</div>
<div class="form-floating position-relative">
    <input type="password" class="form-control my-1" name="password" id="password" placeholder="Password">
    <label for="password">Password</label>
    <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" id="togglePassword"></i>
</div>
<div class="form-floating position-relative">
    <input type="password" class="form-control my-1" name="confirm_password" id="confirm_password" placeholder="Confirm Password">
    <label for="confirm_password">Confirm Password</label>
    <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3" id="toggleConfirmPassword"></i>
</div>
<?php
if(isset($message)) {
    foreach ($message as $msg) {
        echo '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
    }
}
?>
<div class="my-2">Already have an account? <a href="login.php">Click Here</a></div>
<button class="btn btn-primary w-100 py-2 my-2" type="submit" name="submit">Sign up</button>
</form>
</main>
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', function (e) {
        
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        this.classList.toggle('bi-eye');
    });

    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPassword = document.querySelector('#confirm_password');
    toggleConfirmPassword.addEventListener('click', function (e) {
        
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        
        this.classList.toggle('bi-eye');
    });
</script>
</body>
</html>
