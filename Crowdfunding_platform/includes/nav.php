<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$role = $_SESSION['role'];
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid" >
    <a class="navbar-brand" href="../includes/index.php"><img src="../assets/images/logo.png" style="height:auto; clip-path: polygon(0 0, 100% 0 100% 75% 0 100%); display: flex; background-color: floralwhite; border-radius: 15px;" /></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">

        <?php if ($role === 'entrepreneur'): ?>
          <li class="nav-item"><a class="nav-link" href="../entrepreneur/dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../entrepreneur/post_idea.php">Post Idea</a></li>
          <li class="nav-item"><a class="nav-link" href="../entrepreneur/ideas_list.php">My Ideas</a></li>
          <li class="nav-item"><a class="nav-link" href="../entrepreneur/profile.php">Profile</a></li>

        <?php elseif ($role === 'supporter'): ?>
          <li class="nav-item"><a class="nav-link" href="../supporter/dashboard.php">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../supporter/view_ideas.php">View Ideas</a></li>
          <li class="nav-item"><a class="nav-link" href="../supporter/my_profile.php">Profile</a></li>

        <?php elseif ($role === 'admin'): ?>
          <li class="nav-item"><a class="nav-link" href="../admin/dashboard.php">Admin Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="../admin/manage_users.php">Manage Users</a></li>
          <li class="nav-item"><a class="nav-link" href="../admin/approve_ideas.php">Approve Ideas</a></li>
        <?php endif; ?>

        <li class="nav-item"><a class="nav-link text-danger" href="../auth/logout.php">Logout</a></li>
      </ul>
      
      <span class="navbar-text text-white">
        <?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : 'Guest'; ?>

      </span>
    </div>
  </div>
</nav>
