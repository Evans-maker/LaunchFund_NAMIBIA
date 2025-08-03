<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LaunchFund Namibia - Empowering Ideas, Igniting Futures</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="icon" href="assets/img/favicon.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }
    .navbar {display: fixed;
      background-color: #b00020;
    }
    .navbar .nav-link, .navbar .navbar-brand {
      color: white !important;
    }
    .navbar .nav-link:hover {
      color: #ffcdd2 !important;
    }
    header {
      background-color: #b00020;
      color: white;
    }
    .btn-danger-custom {
      background-color: #b00020;
      border-color: #b00020;
      color: white;
    }
    .btn-danger-custom:hover {
      background-color: #a0001b;
      border-color: #a0001b;
    }
    .card-box {
      transition: transform 0.2s ease-in-out;
    }
    .card-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    footer {
      background-color: #212529;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="#"><img src="../assets/images/logo.png" style="height:auto; clip-path: polygon(0 0, 100% 0 100% 75% 0 100%); display: flex; background-color: floralwhite; border-radius: 15px;" />
      LaunchFund Namibia
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
      aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a href="../auth/register.php" class="nav-link">Register</a></li>
        <li class="nav-item"><a href="../auth/login.php" class="nav-link">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<header class="text-center py-5">
  <div class="container">
    <h1 class="display-4 fw-bold">Empowering Ideas, Igniting Futures</h1>
    <p class="lead mb-4">LaunchFund Namibia helps young entrepreneurs bring their innovative ideas to life by connecting
      them with supporters ready to pledge — show interests and kindness and motivation!</p>
    <a href="../auth/register.php" class="btn btn-light btn-lg me-3">Get Started</a>
    <a href="../auth/login.php" class="btn btn-outline-light btn-lg">Login</a>
  </div>
</header>

<section class="container my-5">
  <div class="row text-center">
    <div class="col-md-4 mb-4">
      <div class="p-4 border rounded bg-white shadow-sm card-box">
        <h4 class="text-danger">Post Your Idea</h4>
        <p>Easily submit your business concept with clear goals to attract supporters,sponsors and investors!</p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="p-4 border rounded bg-white shadow-sm card-box">
        <h4 class="text-danger">Support Entrepreneurs</h4>
        <p>Browse ideas and simulate pledges to encourage innovation in a risk-free environment , just to show interest and give motivation and encouragement.</p>
      </div>
    </div>
    <div class="col-md-4 mb-4">
      <div class="p-4 border rounded bg-white shadow-sm card-box">
        <h4 class="text-danger">Track Progress</h4>
        <p>Stay updated with real-time progress bars and transparent funding visuals.</p>
      </div>
    </div>
  </div>
</section>

<footer class="text-white text-center py-3">
  <div class="container">
    &copy; <?= date('Y') ?> LaunchFund Namibia. All rights reserved. <i><h6>developed by Cyber Eagles! </h6></i>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
