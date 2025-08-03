<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    $reply = isset($_POST['reply']) ? trim($_POST['reply']) : '';

    if ($comment_id > 0 && !empty($reply)) {

        // Check if a reply already exists for this comment
        $check = $conn->prepare("SELECT id FROM comment_replies WHERE comment_id = ?");
        $check->bind_param("i", $comment_id);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            // Update existing reply
            $stmt = $conn->prepare("UPDATE comment_replies SET reply = ? WHERE comment_id = ?");
            if ($stmt) {
                $stmt->bind_param("si", $reply, $comment_id);
                $stmt->execute();
            } else {
                die("Failed to prepare UPDATE statement.");
            }
        } else {
            // Insert new reply
            $stmt = $conn->prepare("INSERT INTO comment_replies (comment_id, reply) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("is", $comment_id, $reply);
                $stmt->execute();
            } else {
                die("Failed to prepare INSERT statement.");
            }
        }

        header("Location: ideas_list.php?success=reply-posted");
        exit();
    } else {
        echo "Missing required fields.";
    }
}
?>
