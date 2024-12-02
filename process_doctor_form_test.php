<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "POST request received successfully!";
    print_r($_POST); // Print form data
} else {
    echo "Invalid request method.";
}
?>
