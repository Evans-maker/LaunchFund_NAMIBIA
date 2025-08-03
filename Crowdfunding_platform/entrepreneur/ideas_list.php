<?php
session_start();
include '../includes/nav.php'; 
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "entrepreneur") {
    header("Location: ../auth/login.php");
    exit();
}
require_once("../includes/db.php");
$user_id = $_SESSION["user_id"];

// Get entrepreneur's ideas
$result = $conn->query("SELECT * FROM business_ideas WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Business Ideas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <h3 class="mb-4 text-primary">My Posted Ideas</h3>
  <a href="post_idea.php" class="btn btn-success mb-3">+ Post New Idea</a>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Title</th>
        <th>Funding Goal (N$)</th>
        <th>Description</th>
        <th>Posted</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
  <?php while($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= htmlspecialchars($row['title']) ?></td>
    <td><?= number_format($row['funding_goal'], 2) ?></td>
    <td><?= nl2br(htmlspecialchars($row['description'])) ?></td>
    <td><?= $row['created_at'] ?></td>
    <td>
      <a href="edit_idea.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
      <a href="delete_idea.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this idea?');">Delete</a>
    </td>
  </tr>
  <tr>
    <td colspan="5">
      <!-- COMMENTS & REPLIES SECTION -->
      <strong>Comments & Replies:</strong>
      <div class="ms-3">
        <?php
          // Fetch comments for this idea, with supporter info
          $idea_id = $row['id'];
          $commentSql = "SELECT idea_comments.*, users.full_name AS supporter_name
                         FROM idea_comments 
                         JOIN users ON idea_comments.supporter_id = users.id
                         WHERE idea_comments.idea_id = ?
                         ORDER BY idea_comments.created_at DESC";

          $stmt = $conn->prepare($commentSql);
          if (!$stmt) {
              echo "<div class='text-danger'>Failed to load comments.</div>";
          } else {
              $stmt->bind_param("i", $idea_id);
              $stmt->execute();
              $commentsResult = $stmt->get_result();

              if ($commentsResult->num_rows > 0) {
                  while ($comment = $commentsResult->fetch_assoc()) {
                    // Fetch entrepreneur's reply for this comment (assuming 1 reply per comment)
                    $replySql = "SELECT * FROM idea_comments WHERE comment_id = ? LIMIT 1";
                    $replyStmt = $conn->prepare($replySql);
                    $replyText = null;
                    if ($replyStmt) {
                        $replyStmt->bind_param("i", $comment['id']);
                        $replyStmt->execute();
                        $replyRes = $replyStmt->get_result();
                        if ($replyRes->num_rows > 0) {
                            $replyRow = $replyRes->fetch_assoc();
                            $replyText = $replyRow['reply'];
                        }
                        $replyStmt->close();
                    }
                    ?>
                    <div class="border rounded p-2 mb-3">
                      <strong><?= htmlspecialchars($comment['supporter_name']) ?> said:</strong><br>
                      <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                      <small class="text-muted"><?= date("F j, Y H:i", strtotime($comment['created_at'])) ?></small>

                      <?php if ($replyText): ?>
                        <div class="mt-2 ms-4 p-2 bg-light border-start border-3 border-primary rounded">
                          <strong>You replied:</strong>
                          <p><?= nl2br(htmlspecialchars($replyText)) ?></p>
                        </div>
                      <?php endif; ?>

                      <!-- Reply form -->
                      <form action="reply_action.php" method="post" class="mt-3 ms-4">
                        <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                        <textarea name="reply" rows="2" class="form-control mb-2" placeholder="Write your reply here..." required></textarea>
                        <button type="submit" class="btn btn-sm btn-primary">Reply</button>
                      </form>
                    </div>
                  <?php
                  }
              } else {
                  echo "<p>No comments yet.</p>";
              }
              $stmt->close();
          }
        ?>
      </div>
    </td>
  </tr>
  <?php endwhile; ?>
</tbody>
  </table>
  <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
</body>
</html>
