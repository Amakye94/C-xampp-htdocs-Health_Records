<?php
$conn = new mysqli("localhost", "root", "", "health_records_2024");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST['email']);

    if (empty($email)) {
        die("Email is required.");
    }

    // Check if the email exists
    $stmt = $conn->prepare("SELECT id FROM doctors_registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($doctor_id);
        $stmt->fetch();

        // Generate a reset token
        $reset_token = bin2hex(random_bytes(16));
        $expiry_time = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Insert the reset token into the database
        $insert_stmt = $conn->prepare("INSERT INTO password_resets (doctor_id, token, expires_at) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("iss", $doctor_id, $reset_token, $expiry_time);
        $insert_stmt->execute();

        // Send email with reset link
        $reset_link = "http://localhost/health_records/reset_password.php?token=$reset_token";
        mail($email, "Password Reset Request", "Click the link to reset your password: $reset_link");

        echo "A password reset link has been sent to your email.";
    } else {
        echo "Email not found.";
    }

    $stmt->close();
}

$conn->close();
?>
