<?php

include './db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
  // Validate user input
  $fullname = $_POST['fullname'];
  $username = $_POST['username'];
  $email = $_POST['email'];
  $password = $_POST['password'];

  $errors = [];
  $success = '';

  if (empty($fullname)) {
      $errors[] = "Fullname is required";
  }

  if (empty($username)) {
      $errors[] = "Username is required";
  }

  if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = "Valid email is required";
  }

  if (empty($password)) {
      $errors[] = "Password is required";
  }

  if (empty($errors)) {
      // All data is valid, do something with it (e.g., send an email)
      $hashed = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $conn->prepare("INSERT INTO students (fullname, username, email, password) VALUES (:fullname, :username, :email, :password)");

      $stmt->bindParam(':fullname', $fullname);
      $stmt->bindParam(':username', $username);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':password', $hashed);

      $stmt->execute();

      // Redirect to a thank-you page on successful submission
      // header('Location: thank_you.php');
      // exit();
      $success = 'Your registration has been saved successfully.';
  }
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Registration page</title>
  
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

  <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">  

</head>
<body>
  <div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
      <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 text-body-emphasis mb-3">Vertically centered hero sign-up form</h1>
        <p class="col-lg-10 fs-4">Below is an example form built entirely with Bootstrap’s form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
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
          
          if(isset($success) && !empty($success)){
              echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Success!</strong> '.$success.'
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
          }
        ?>

        <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" method="POST" action="">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Enter fullname">
            <label for="floatingInput">Name</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
            <label for="floatingInput">Username</label>
          </div>
          <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            <label for="floatingPassword">Password</label>
          </div>
          <button class="w-100 btn btn-lg btn-primary" type="submit">Sign up</button>
          <hr class="my-4">
          <small class="text-body-secondary">If you already have an account click here to <a href="login.php">Login</a></small>
        </form>
      </div>
    </div>
  </div>

  <script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
