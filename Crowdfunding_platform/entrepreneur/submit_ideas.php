<?php
session_start();
require_once '../includes/db.php';
include '../includes/nav.php'; 
if (file_exists('../includes/mailer_config.php')) {
    require_once '../includes/mailer_config.php';
} else {
    $mail = null;
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $funding_goal = floatval($_POST['funding_goal']);
    $poster = $_FILES['poster'];
    $document = $_FILES['document'];
    $video = $_FILES['video'];

    $posterName = '';
    $documentName = '';
    $videoName = '';

    $uploadDir = "../uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Upload poster image
    if ($poster['error'] === 0 && is_uploaded_file($poster['tmp_name'])) {
        $posterName = time() . '_' . basename($poster['name']);
        move_uploaded_file($poster['tmp_name'], $uploadDir . $posterName);
    }

    // Upload document
    if ($document['error'] === 0 && is_uploaded_file($document['tmp_name'])) {
        $documentName = time() . '_' . basename($document['name']);
        move_uploaded_file($document['tmp_name'], $uploadDir . $documentName);
    }

    // Upload video
    if ($video['error'] === 0 && is_uploaded_file($video['tmp_name'])) {
        $videoName = time() . '_' . basename($video['name']);
        move_uploaded_file($video['tmp_name'], $uploadDir . $videoName);
    }

    if (empty($title) || empty($description) || $funding_goal <= 0 || !$posterName || !$documentName) {
        $error = "Please fill all fields and upload required files.";
    } else {
        $stmt = $conn->prepare("INSERT INTO business_ideas (user_id, title, description, funding_goal, poster, document, video) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("issdsss", $user_id, $title, $description, $funding_goal, $posterName, $documentName, $videoName);

            if ($stmt->execute()) {
                if ($mail && isset($_SESSION['email'])) {
                    try {
                        $mail->addAddress($_SESSION['email']);
                        $mail->Subject = "Business Idea Submitted";
                        $mail->Body = "Hi {$_SESSION['full_name']},\n\nYour idea \"{$title}\" was successfully submitted.";
                        @$mail->send();
                    } catch (Exception $e) {
                        // Fail silently or log
                    }
                }

                header("Location: dashboard.php?success=1");
                exit();
            } else {
                $error = "Database error: " . $stmt->error;
            }
        }
    }
}
?>
