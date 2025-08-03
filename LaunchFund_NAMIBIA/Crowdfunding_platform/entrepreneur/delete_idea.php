<?php
session_start();
require_once '../includes/db.php';
include '../includes/nav.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'entrepreneur') {
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'] ?? null;
$user_id = $_SESSION['user_id'];

if ($id) {
    $stmt = $conn->prepare("DELETE FROM business_ideas WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $user_id);
    if ($stmt->execute()) {
        header("Location: ideas_list.php?deleted=1");
    } else {
        echo "Delete failed: " . $stmt->error;
    }
} else {
    echo "Invalid ID.";
}
?>
