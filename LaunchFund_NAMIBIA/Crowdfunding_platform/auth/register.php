<?php
// auth/register.php
include '../includes/db.php';
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - LaunchFund Namibia</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      max-width: 500px;
      margin: 80px auto;
      padding: 35px 30px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
      background-color: #ffffff;
    }
    .form-label {
      font-weight: 500;
    }
    .form-control,
    .form-select {
      border-radius: 8px;
    }
    .btn-success {
      width: 100%;
      padding: 10px;
      font-weight: 500;
    }
    a {
      text-decoration: none;
    }
    .text-link {
      color: #198754; /* Bootstrap green */
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="card">
      <h3 class="text-center mb-4 text-success">Create Your Account</h3>
      <form action="register_action.php" method="POST">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control" id="name" name="name" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" id="email" name="email" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required />
        </div>
        <div class="mb-3">
          <label for="role" class="form-label">Register as</label>
          <select name="role" id="role" class="form-select" required>
            <option value="">-- Select Role --</option>
            <option value="entrepreneur">Entrepreneur</option>
            <option value="supporter">Supporter</option>
          </select>
        </div>
        <button type="submit" class="btn btn-success">Register</button>
      </form>
      <p class="text-center mt-4">
        Already have an account?
        <a href="login.php" class="text-link">Login here</a>
      </p>
    </div>
  </div>

</body>
</html>
