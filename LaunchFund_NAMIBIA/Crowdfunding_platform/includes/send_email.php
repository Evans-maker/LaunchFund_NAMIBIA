<?php
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';
require_once __DIR__ . '/PHPMailer/Exception.php';
include '../includes/db.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendMockEmail($to, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com';
        $mail->SMTPAuth = false;
        $mail->Port = 1025;

        $mail->setFrom('noreply@crowdfund.local', 'Crowdfund Platform');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        echo "<div class='alert alert-success text-center mt-4'>
                <strong>Mock Email Sent:</strong> '$subject' to <em>$to</em>
              </div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger text-center mt-4'>
                <strong>Email Error:</strong> {$mail->ErrorInfo}
              </div>";
    }
}
?>
