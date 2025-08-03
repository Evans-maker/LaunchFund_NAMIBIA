<?php
// auth/register_action.php
include '../includes/db.php';

if (isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['role'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $role     = $_POST['role'];

    if ($name === "" || $email === "" || $role === "") {
        echo "Please fill in all fields correctly.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        // Show welcome page with role-based button
        echo '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Registration Success</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    background-color: #f8f9fa;
                }
                .card {
                    max-width: 500px;
                    margin: 80px auto;
                    padding: 30px;
                    border-radius: 12px;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
                }
                .btn-custom {
                    width: 100%;
                    margin-top: 10px;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="card text-center">
                    <h3 class="text-success mb-3">Registration Successful!</h3>
                    <p><strong>Name:</strong> ' . htmlspecialchars($name) . '</p>
                    <p><strong>Email:</strong> ' . htmlspecialchars($email) . '</p>
                    <p><strong>Role:</strong> ' . htmlspecialchars(ucfirst($role)) . '</p>';

                    if ($role === 'entrepreneur') {
                        echo '<a href="../entrepreneur/post_idea.php" class="btn btn-success btn-custom">Post an Idea</a>';
                    } elseif ($role === 'supporter') {
                        echo '<a href="../supporter/view_ideas.php" class="btn btn-primary btn-custom">View Ideas</a>';
                    }

                    echo '
                    <a href="login.php" class="btn btn-outline-secondary btn-custom">Go to Login</a>
                </div>
            </div>
        </body>
        </html>';
    } else {
        echo "Registration failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Please fill in all fields correctly.";
}
