<?php
session_start();
require_once '../includes/db.php';
 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];
$error = '';
$idea = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM business_ideas WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $idea = $result->fetch_assoc();
    if (!$idea) {
        die("Idea not found.");
    }
} else {
    die("Invalid request.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $funding_goal = floatval($_POST['funding_goal']);

    if (empty($title) || empty($description) || $funding_goal <= 0) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("UPDATE business_ideas SET title=?, description=?, funding_goal=? WHERE id=? AND user_id=?");
        $stmt->bind_param("ssdii", $title, $description, $funding_goal, $id, $user_id);
        if ($stmt->execute()) {
            header("Location: ideas_list.php?updated=1");
            exit();
        } else {
            $error = "Failed to update: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Idea</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h3 class="mb-4 text-warning">Edit Business Idea</h3>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($idea['title']) ?>" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($idea['description']) ?></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label">Funding Goal (N$)</label>
      <input type="number" name="funding_goal" class="form-control" value="<?= $idea['funding_goal'] ?>" step="0.01" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Idea</button>
    <a href="ideas_list.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>
</body>
</html>
