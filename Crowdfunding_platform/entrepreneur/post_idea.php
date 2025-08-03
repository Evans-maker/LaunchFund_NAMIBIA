<?php
session_start();
require_once "../includes/db.php";
include '../includes/nav.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../login.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $goal = (float) $_POST['funding_goal'];

    $poster = '';
    $document = '';
    $videoDbPath = '';

    // Upload Poster
    if (!empty($_FILES['poster']['name'])) {
        $posterName = uniqid() . '_' . basename($_FILES['poster']['name']);
        $posterPath = "../uploads/" . $posterName;
        move_uploaded_file($_FILES['poster']['tmp_name'], $posterPath);
        $poster = $posterName;
    }

    // Upload Document
    if (!empty($_FILES['document']['name'])) {
        $docName = uniqid() . '_' . basename($_FILES['document']['name']);
        $docPath = "../uploads/" . $docName;
        move_uploaded_file($_FILES['document']['tmp_name'], $docPath);
        $document = $docName;
    }

    // Upload Video
    if (isset($_FILES['video']) && $_FILES['video']['error'] === 0) {
        $videoName = uniqid() . '_' . basename($_FILES['video']['name']);
        $videoFolder = '../uploads/videos/';
        $videoPath = $videoFolder . $videoName;

        // Ensure folder exists
        if (!is_dir($videoFolder)) {
            mkdir($videoFolder, 0777, true);
        }

        // Check file size (max 100MB)
        if ($_FILES['video']['size'] <= 100 * 1024 * 1024) {
            if (move_uploaded_file($_FILES['video']['tmp_name'], $videoPath)) {
                $videoDbPath = 'uploads/videos/' . $videoName;
            } else {
                $message = "Error uploading video file.";
            }
        } else {
            $message = "Video file is too large. Max 100MB allowed.";
        }
    }

    // Save to DB
    $stmt = $conn->prepare("INSERT INTO business_ideas (user_id, title, description, funding_goal, poster, document, video, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    if ($stmt) {
        $stmt->bind_param("issdsss", $user_id, $title, $description, $goal, $poster, $document, $videoDbPath);
        if ($stmt->execute()) {
            $message = "Idea posted successfully!";
        } else {
            $message = "Database error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Prepare failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Post Business Idea</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Post New Business Idea</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" rows="5" required class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label>Goal Amount (N$)</label>
            <input type="number" name="funding_goal" step="0.01" required class="form-control">
        </div>
        <div class="mb-3">
            <label>Poster Image</label>
            <input type="file" name="poster" accept="image/*" class="form-control">
        </div>
        <div class="mb-3">
            <label>Supporting Document</label>
            <input type="file" name="document" accept=".pdf,.doc,.docx" class="form-control">
        </div>
        <div class="mb-3">
            <label>Pitch Video</label>
            <input type="file" name="video" accept="video/mp4" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Submit Idea</button>
    </form>
</div>
</body>
</html>
