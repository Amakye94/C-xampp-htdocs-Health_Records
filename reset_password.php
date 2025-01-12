<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Database connection
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "health_records_2024";

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Verify the token
    $stmt = $conn->prepare("SELECT id FROM doctors_registration WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display the password reset form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

            $stmt_update = $conn->prepare("UPDATE doctors_registration SET password = ?, reset_token = NULL WHERE reset_token = ?");
            $stmt_update->bind_param("ss", $new_password, $token);
            if ($stmt_update->execute()) {
                echo "Password reset successfully.";
            } else {
                echo "Failed to reset password.";
            }
        }
    } else {
        echo "Invalid token.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <form method="POST">
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
