<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';
require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "health_records_2024";

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);

    // Check if the email exists
    $stmt = $conn->prepare("SELECT id FROM doctors_registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a secure token
        $token = bin2hex(random_bytes(32));

        // Save the token in the database
        $stmt_update = $conn->prepare("UPDATE doctors_registration SET reset_token = ? WHERE email = ?");
        $stmt_update->bind_param("ss", $token, $email);
        $stmt_update->execute();

        // Create the reset link
        $reset_link = "http://localhost/health_records/reset_password.php?token=" . $token;

        // Initialize PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ehrgroup1@gmail.com'; // Your email
            $mail->Password = 'zcum qfsc jcqs qdtu';   // App password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('ehrgroup1@gmail.com', 'Health Records');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click the link below to reset your password:<br><br>
                           <a href='$reset_link'>$reset_link</a>";

            // Send email
            if ($mail->send()) {
                $_SESSION['success_message'] = "Password reset email sent successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to send reset email.";
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Failed to send reset email: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error_message'] = "Email not found.";
    }

    $conn->close();

    // Redirect to the login page with success or error message
    header("Location: login.html");
    exit();
}
?>


