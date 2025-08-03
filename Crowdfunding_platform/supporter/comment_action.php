<?php
session_start();
require_once "../includes/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'supporter') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idea_id = isset($_POST['idea_id']) ? intval($_POST['idea_id']) : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $supporter_id = $_SESSION['user_id'];

    if ($idea_id > 0 && !empty($comment)) {
        $stmt = $conn->prepare("INSERT INTO idea_comments (idea_id, supporter_id, comment, created_at) VALUES (?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("iis", $idea_id, $supporter_id, $comment);
            if ($stmt->execute()) {
                header("Location: view_ideas.php?success=1");
                exit();
            } else {
                echo "Error posting comment.";
            }
            $stmt->close();
        } else {
            echo "Database error: " . $conn->error;
        }
    } else {
        echo "Missing required fields.";
    }
} else {
    echo "Invalid request.";
}
?>
