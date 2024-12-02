<?php
$conn = new mysqli("localhost", "root", "", "health_records_2024");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT doctor_id FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($doctor_id);
    $stmt->fetch();
    ?>
    <!DOCTYPE html>
    <html lang="en-us">
    <head>
        <meta charset="UTF-8">
        <title>Reset Password</title>
    </head>
    <body>
        <form action="process_reset_password.php" method="post">
            <input type="hidden" name="doctor_id" value="<?php echo $doctor_id; ?>">
            <div class="input-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">Reset Password</button>
        </form>
    </body>
    </html>
    <?php
} else {
    echo "Invalid or expired token.";
}
?>
