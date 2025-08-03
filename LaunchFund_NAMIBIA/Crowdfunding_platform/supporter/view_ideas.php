<?php
session_start();
require_once "../includes/db.php";
include '../includes/nav.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supporter') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT bi.*, u.full_name 
          FROM business_ideas bi
          JOIN users u ON bi.user_id = u.id
          WHERE bi.status = 'approved'
          ORDER BY bi.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>View Ideas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Approved Business Ideas</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                <small>By: <?php echo htmlspecialchars($row['full_name']); ?> | 
                       <?php echo date("F j, Y", strtotime($row['created_at'])); ?></small>
            </div>
            <div class="card-body">
                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

                <!-- Poster Image -->
                <?php if (!empty($row['poster']) && file_exists(__DIR__ . '/../uploads/' . $row['poster'])): ?>
                    <img src="../uploads/<?php echo htmlspecialchars($row['poster']); ?>" alt="Poster" class="img-fluid mb-3" style="max-height:300px;">
                <?php endif; ?>

                <!-- Video -->
                <?php
                $videoFile = $row['video'];  // filename only
                // Debugging line (can remove later)
                // echo "DEBUG - Video filename from DB: " . htmlspecialchars($videoFile) . "<br>";

                $videoServerPath = __DIR__ . '/../uploads/videos/' . basename($videoFile);
                $videoWebPath = '../uploads/videos/' . basename($videoFile);
                ?>

                <?php if (!empty($videoFile) && file_exists($videoServerPath)): ?>
                    <video controls preload="metadata" class="w-100 mb-3" style="max-height:300px;">
                        <source src="<?php echo htmlspecialchars($videoWebPath); ?>" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>
                <?php elseif (!empty($videoFile)): ?>
                    <div class="text-danger mb-3">⚠️ Video file not found: <?php echo htmlspecialchars($videoFile); ?></div>
                <?php endif; ?>

                <!-- Document -->
                <?php if (!empty($row['document']) && file_exists(__DIR__ . '/../uploads/' . $row['document'])): ?>
                    <a href="../uploads/<?php echo htmlspecialchars($row['document']); ?>" target="_blank" class="btn btn-secondary btn-sm mb-3">View Document</a>
                <?php endif; ?>

                <p><strong>Goal:</strong> N$<?php echo number_format($row['funding_goal'], 2); ?></p>

                <?php
                $pledgeQuery = "SELECT SUM(amount) AS total_pledged FROM pledges WHERE idea_id = ?";
                $pledgeStmt = $conn->prepare($pledgeQuery);
                $pledgeStmt->bind_param("i", $row['id']);
                $pledgeStmt->execute();
                $pledgeResult = $pledgeStmt->get_result();
                $pledgeData = $pledgeResult->fetch_assoc();
                $totalPledged = $pledgeData['total_pledged'] ?? 0;
                $pledgeStmt->close();

                $percentageFunded = $row['funding_goal'] > 0 ? min(100, ($totalPledged / $row['funding_goal']) * 100) : 0;
                ?>

                <!-- Pledge Progress Bar -->
                <div class="mb-3">
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar" role="progressbar" style="width: <?php echo $percentageFunded; ?>%;" aria-valuenow="<?php echo $percentageFunded; ?>" aria-valuemin="0" aria-valuemax="100">
                            <?php echo number_format($percentageFunded, 2); ?>%
                        </div>
                    </div>
                    <small>N$<?php echo number_format($totalPledged, 2); ?> pledged of N$<?php echo number_format($row['funding_goal'], 2); ?> goal</small>
                </div>

                <!-- Buttons -->
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#pledgeModal<?php echo $row['id']; ?>">Pledge</button>
                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#commentModal<?php echo $row['id']; ?>">Comment</button>
            </div>

            <!-- Comments and Replies -->
            <div class="card-footer">
                <strong>Comments:</strong><br>
                <?php
                $idea_id = $row['id'];
                $commentQuery = "SELECT ic.*, u.full_name 
                                 FROM idea_comments ic
                                 JOIN users u ON ic.supporter_id = u.id
                                 WHERE ic.idea_id = ?
                                 ORDER BY ic.created_at DESC";
                $stmt = $conn->prepare($commentQuery);
                if (!$stmt) {
                    echo "<div class='text-danger'>Failed to load comments.</div>";
                } else {
                    $stmt->bind_param("i", $idea_id);
                    $stmt->execute();
                    $commentsResult = $stmt->get_result();

                    if ($commentsResult->num_rows > 0):
                        while ($comment = $commentsResult->fetch_assoc()):
                ?>
                            <div class="border p-2 mb-2">
                                <strong><?php echo htmlspecialchars($comment['full_name']); ?>:</strong><br>
                                <p><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                                <small class="text-muted"><?php echo date("F j, Y H:i", strtotime($comment['created_at'])); ?></small>

                                <?php
                                $comment_id = $comment['id'];
                                $replyQuery = "SELECT cr.*, u.full_name AS entrepreneur_name
                                               FROM comment_replies cr
                                               JOIN users u ON cr.entrepreneur_id = u.id
                                               WHERE cr.comment_id = ?";
                                $replyStmt = $conn->prepare($replyQuery);
                                if ($replyStmt) {
                                    $replyStmt->bind_param("i", $comment_id);
                                    $replyStmt->execute();
                                    $replyResult = $replyStmt->get_result();

                                    while ($reply = $replyResult->fetch_assoc()):
                                ?>
                                        <div class="bg-light border-start border-4 ps-3 mt-2">
                                            <strong><?php echo htmlspecialchars($reply['entrepreneur_name']); ?> (Entrepreneur):</strong><br>
                                            <p><?php echo nl2br(htmlspecialchars($reply['reply'])); ?></p>
                                            <small class="text-muted"><?php echo date("F j, Y H:i", strtotime($reply['created_at'])); ?></small>
                                        </div>
                                <?php
                                    endwhile;
                                    $replyStmt->close();
                                }
                                ?>
                            </div>
                <?php
                        endwhile;
                    else:
                        echo "<p>No comments yet.</p>";
                    endif;
                    $stmt->close();
                }
                ?>
            </div>
        </div>

        <!-- Pledge Modal -->
        <div class="modal fade" id="pledgeModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="pledge_action.php" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pledge to <?php echo htmlspecialchars($row['title']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="idea_id" value="<?php echo $row['id']; ?>" />
                            <div class="mb-3">
                                <label>Pledge Amount (N$)</label>
                                <input type="number" name="amount" class="form-control" min="1" step="0.01" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Comment Modal -->
        <div class="modal fade" id="commentModal<?php echo $row['id']; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="comment_action.php" method="post">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Comment on <?php echo htmlspecialchars($row['title']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="idea_id" value="<?php echo $row['id']; ?>" />
                            <textarea name="comment" rows="3" class="form-control" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Post</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    <?php endwhile; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
