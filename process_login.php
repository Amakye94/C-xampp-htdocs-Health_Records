<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "health_records_2024");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if (empty($email) || empty($password)) {
        die("Please enter both email and password.");
    }

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT id, full_name, password FROM doctors_registration WHERE email = ?");
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Fetch doctor details
        $doctor = $result->fetch_assoc();
        $doctor_id = $doctor['id']; // Fetching doctor's ID
        $full_name = $doctor['full_name'];
        $hashed_password = $doctor['password'];

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Store data in session variables
            $_SESSION['doctor_id'] = $doctor_id;
            $_SESSION['full_name'] = $full_name;

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password. Please try again.";
        }
    } else {
        echo "No doctor found with this email.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
