<?php

session_start();

include './db.php';

if(!empty($_SESSION['user']) && !empty($_SESSION['user']['email'])){
  header('Location: home.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  // Validate user input
  $email = $_POST['email'];
  $password = $_POST['password'];

  $errors = [];
  $success = '';

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Valid email is required";
  }

  if (empty($password)) {
      $errors[] = "Password is required";
    }
    
    if (empty($errors)) {
      // All data is valid, do something with it (e.g., send an email)
      $stmt = $conn->prepare("SELECT * FROM students WHERE email = :email");
      $stmt->bindParam(':email', $email);
      $stmt->execute();
      
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      // print_r($result);
      if(!empty($result) && count($result)>0 && !empty($result[0]['password'])){
        if(password_verify($password, $result[0]['password'])){
          $_SESSION['user'] = $result[0];
          
          header('Location: home.php');
        } else {
          $errors[] = "Password does not match";
        }
      } else {
        $errors[] = "Email not found";
      }
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Login page</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  
  <style>
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
  </style>


</head>
<body>
  <div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
      <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 text-body-emphasis mb-3">Login form</h1>
      </div>
              
      <div class="col-md-10 mx-auto col-lg-5">
        <?php
          if(isset($errors) && !empty($errors)){
              foreach ($errors as $error) {
                  echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <strong>Error!</strong> '.$error.'
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
              }
          }
        ?>
        <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" method="POST" action="">
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            <label for="floatingPassword">Password</label>
          </div>
          <button class="w-100 btn btn-lg btn-primary" type="submit">Login</button>
          <hr class="my-4">
          <small class="text-body-secondary">If you don't have an account click here to <a href="register.php">Register</a></small>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
