<?php
// auth/login.php
include("../includes/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - LaunchFund Namibia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      max-width: 500px;
      margin: 80px auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
    }
    .form-label {
      font-weight: 500;
    }
    .form-control {
      border-radius: 8px;
    }
    .btn-success {
      width: 100%;
    }
    a {
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="card">
      <h3 class="text-center mb-4 text-success">Login to Your Account</h3>
      <form action="login_action.php" method="POST">
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required />
        </div>
        <button type="submit" class="btn btn-success">Login</button>
      </form>
      <p class="text-center mt-3">
        Don't have an account? <a href="register.php" class="text-decoration-underline">Register here</a>
      </p>
    </div>
  </div>

</body>
</html>
