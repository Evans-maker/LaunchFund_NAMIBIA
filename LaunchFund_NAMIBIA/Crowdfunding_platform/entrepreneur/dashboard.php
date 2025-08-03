<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../includes/db.php';
include '../includes/nav.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM business_ideas WHERE user_id = $user_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head><?php

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Fetch entrepreneur's ideas and their funding status
$entrepreneur_id = $_SESSION['user_id'];

$sql = "SELECT business_ideas.title, business_ideas.funding_goal, 
               IFNULL(SUM(pledges.amount), 0) AS pledged
        FROM business_ideas
        LEFT JOIN pledges ON business_ideas.id = pledges.idea_id
        WHERE business_ideas.user_id = ?
        GROUP BY business_ideas.id";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $entrepreneur_id);
$stmt->execute();
$result = $stmt->get_result();

$titles = [];
$goals = [];
$pledged = [];


while ($row = $result->fetch_assoc()) {
    $titles[] = $row['title'];
    $goals[] = $row['funding_goal'];
    $pledged[] = $row['pledged'];
}


while ($row = $result->fetch_assoc()) {
    $titles[] = $row['title'];
    $goals[] = $row['goal'];
    $pledged[] = $row['pledged'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Entrepreneur Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">My Business Ideas - Funding Overview</h2>

    <canvas id="fundingChart" height="120"></canvas>

    <script>
        const ctx = document.getElementById('fundingChart').getContext('2d');
        const fundingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($titles) ?>,
                datasets: [{
                    label: 'Funding Goal',
                    data: <?= json_encode($goals) ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)'
                },
                {
                    label: 'Amount Pledged',
                    data: <?= json_encode($pledged) ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Funding Progress Per Idea'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (N$)'
                        }
                    }
                }
            }
        });
    </script>
</div>
</body>
</html>

    <title>Your Dashboard</title>
    <link rel="stylesheet" href="../assets/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h2>Welcome, <?= $_SESSION['user_id'] ?>!</h2>
    <a href="post_idea.php" class="btn btn-success mb-3">Post New Idea</a>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">
                    <strong><?= htmlspecialchars($row['title']) ?></strong>
                </div>
                <div class="card-body">
                    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
                    <p><strong>Funding Goal:</strong> N$ <?= number_format($row['funding_goal'], 2) ?></p>
                    <p><strong>Current Funding:</strong> N$ <?= number_format($row['current_funding'], 2) ?></p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
