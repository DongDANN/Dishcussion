<?php
include 'includes/connection.php';

if(isset($_SESSION['user_id'])){
   header('Location: home.php');
   exit; 
}

if(isset($_POST['submit'])){
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = $_POST['pass'];

   if(filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($pass)) {
       $hashed_pass = sha1($pass);

       try {
           $select_user = $conn->prepare("SELECT user_id, status FROM `users` WHERE email = ? AND password = ?");
           $select_user->execute([$email, $hashed_pass]);
           $row = $select_user->fetch(PDO::FETCH_ASSOC);

           if($row){
              if($row['status'] == 'disabled') {
                  $message[] = 'Your account is disabled. Please contact admin or wait for activation.';
              } else {
                  $_SESSION['user_id'] = $row['user_id'];
                  header('Location: home.php');
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
<?php
include 'includes/connection.php';

if(isset($_SESSION['user_id'])){
   header('Location: home.php');
   exit; 
}

if(isset($_POST['submit'])){
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = $_POST['pass'];

   if(filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($pass)) {
       $hashed_pass = sha1($pass);

       try {
           $select_user = $conn->prepare("SELECT user_id, status FROM `users` WHERE email = ? AND password = ?");
           $select_user->execute([$email, $hashed_pass]);
           $row = $select_user->fetch(PDO::FETCH_ASSOC);

           if($row){
              if($row['status'] == 'disabled') {
                  $message[] = 'Your account is disabled. Please contact admin or wait for activation.';
              } else {
                  $_SESSION['user_id'] = $row['user_id'];
                  header('Location: home.php');
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
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Login - Dishcussion</title>
    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
      body {
        background-image: url('assets/images/background.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        font-family: 'Poppins', sans-serif;

      }
      .form-floating {
        position: relative;
      }
      .form-floating i {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
      }
    </style>
    <link href="assets/css/sign-in.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
<main class="form-signin w-100 m-auto">
  <form method="post">
    <div class="text-center">
      <img class="mb-4 rounded-circle shadow" src="assets/icons/Dishcussion.png" alt="" width="120" height="120">
      <h1 class="h3 mb-3 fw-normal">Sign In</h1>
    </div>
    <div class="form-floating">
      <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php echo htmlspecialchars($email ?? '', ENT_QUOTES); ?>">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" name="pass" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
      <i class="bi bi-eye-slash" id="togglePassword"></i>
    </div>
    <?php
        if(isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($msg, ENT_QUOTES) . '</div>';
            }
        }
    ?>
    <div class="my-2">Don't have an account?<a href="register.php">Register Here</a></div>
    <button class="btn btn-primary w-100 py-2" type="submit" name="submit">Sign in</button>
  </form>
</main>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#floatingPassword');
    togglePassword.addEventListener('click', function (e) {
        
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        this.classList.toggle('bi-eye');
    });
</script>
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
