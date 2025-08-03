<?php
session_start();
include '../includes/db.php';
include '../includes/nav.php'; 

// ✅ Ensure user is logged in and is a supporter
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "supporter") {
    header("Location: ../auth/login.php");
    exit();
}

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ✅ Check if required POST data is present
    if (!isset($_POST["idea_id"], $_POST["amount"])) {
        echo "<script>alert('Missing pledge data.'); window.history.back();</script>";
        exit();
    }

    // ✅ Sanitize and validate inputs
    $idea_id = intval($_POST["idea_id"]);
    $supporter_id = intval($_SESSION["user_id"]);
    $amount = floatval($_POST["amount"]);

    if ($amount <= 0) {
        echo "<script>alert('Invalid pledge amount. Please enter a positive value.'); window.history.back();</script>";
        exit();
    }

    // ✅ Prepare SQL to insert pledge
    $stmt = $conn->prepare("INSERT INTO pledges (idea_id, supporter_id, amount) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // ✅ Bind all parameters (idea_id: int, supporter_id: int, amount: double)
    $stmt->bind_param("iid", $idea_id, $supporter_id, $amount);

    // ✅ Execute and check result
    if ($stmt->execute()) {

        // ✅ Optional: Mock email (if send_email.php exists)
        if (file_exists('../includes/send_email.php')) {
            require_once '../includes/send_email.php';

            if (function_exists('sendMockEmail') && isset($_SESSION['email'])) {
                sendMockEmail(
                    $_SESSION['email'],
                    'Thank you for your Pledge!',
                    'Your mock pledge of N$' . number_format($amount, 2) . ' has been recorded. Thank you for supporting entrepreneurs!'
                );
            }
        }

        // ✅ Redirect after success
        echo "<script>alert('Pledge submitted successfully!'); window.location.href='view_ideas.php';</script>";
    } else {
        echo "<script>alert('Error submitting pledge: " . addslashes($stmt->error) . "'); window.history.back();</script>";
    }

    // ✅ Cleanup
    $stmt->close();
    $conn->close();
}
?>
