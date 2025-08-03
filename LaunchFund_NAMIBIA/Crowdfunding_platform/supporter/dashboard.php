<?php
session_start();
include '../includes/db.php'; 

// Not logged in? Send to login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Optional: Restrict role-based access
if ($_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php");
    exit();
}
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "supporter") {
    header("Location: ../auth/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Supporter Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h3>Welcome, <?php echo $_SESSION["full_name"]; ?> (Supporter)</h3>
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
  </div>
</body>
</html>
