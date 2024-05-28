<?php
include '../includes/connection.php';
if(isset($_SESSION['admin_id'])){
  header('Location: ../admin/pages/dashboard.php');
  exit; 
}

if(isset($_POST['submit'])){
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $pass = $_POST['pass'];

  if(filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($pass)) {
      $hashed_pass = sha1($pass);

      try {
          $select_user = $conn->prepare("SELECT admin_id, status FROM `admins` WHERE email = ? AND password = ?");
          $select_user->execute([$email, $hashed_pass]);
          $row = $select_user->fetch(PDO::FETCH_ASSOC);

          if($row){
             if($row['status'] == 'disabled') {
                 $message[] = 'Your account is disabled. Please contact admin or wait for activation.';
             } else {
                 $_SESSION['admin_id'] = $row['admin_id'];
                 header('Location: ../admin/pages/dashboard.php');
                 exit;
             }
          } else {
             $message[] = 'Incorrect username or password';
          }
      } catch(PDOException $e) {
          $message[] = "Error: " . $e->getMessage();
      }
  } else {
      $message[] = 'Invalid email or password';
  }
}
?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Login - Dishcussion</title>

    <link href="../assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
      body {
        background-image: url('../assets/images/background.jpg');
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

      form i {
          margin-left: -30px;
          cursor: pointer;
      }
    </style>
    <link href="../assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="form-signin w-100 m-auto">
  <form method="post">
    <div class="text-center">
      <img class="mb-4 rounded-circle shadow" src="../assets/icons/Dishcussion.png" alt="" width="120" height="120">
    <h1 class="h3 mb-3 fw-normal">Sign In</h1>
    </div>
    <div class="form-floating">
      <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating position-relative">
      <input type="password" name="pass" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
      <i class="bi bi-eye-slash position-absolute" id="togglePassword" style="right: 10px; top: 50%; transform: translateY(-50%);"></i>
    </div>
    <?php
        if(isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
            }
        }
    ?>
    <button class="btn btn-primary w-100 py-2" type="submit" name="submit">Sign in</button>
  </form>
</main>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#floatingPassword');
    togglePassword.addEventListener('click', () => {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        togglePassword.classList.toggle('bi-eye');
    });
</script>
</body>
</html>
