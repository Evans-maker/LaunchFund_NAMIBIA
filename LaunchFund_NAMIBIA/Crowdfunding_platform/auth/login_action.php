<?php
session_start(); // Start session before anything else
include '../includes/db.php';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);  

    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
        exit;
    }

    // Prepare and check query
    $stmt = $conn->prepare("SELECT id, full_name, password, role, email FROM users WHERE email = ?");
    if (!$stmt) {
        die("SQL error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // HTML output with Bootstrap styling
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Welcome</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body {
                        background-color: #f8f9fa;
                    }
                    .card {
                        max-width: 500px;
                        margin: 80px auto;
                        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                        border-radius: 10px;
                    }
                    .btn-custom {
                        width: 100%;
                        margin-top: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="card p-4">
                        <div class="text-center">
                            <h3 class="mb-3 text-success">Login Successful</h3>
                            <p><strong>Name:</strong> ' . htmlspecialchars($user['full_name']) . '</p>
                            <p><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</p>
                            <p><strong>Role:</strong> ' . htmlspecialchars(ucfirst($user['role'])) . '</p>
            ';

            if ($user['role'] === 'entrepreneur') {
                echo '<a href="../entrepreneur/post_idea.php" class="btn btn-success btn-custom">Go to Post an Idea</a>';
            } elseif ($user['role'] === 'supporter') {
                echo '<a href="../supporter/view_ideas.php" class="btn btn-primary btn-custom">Go to View Ideas</a>';
            } else {
                echo '<div class="alert alert-danger mt-3">Unknown role. Please contact admin.</div>';
            }

            echo '
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ';
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>
