<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "health_records_2024");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all patients
$result = $conn->query("SELECT * FROM patients");

echo "<h1>Patient Management</h1>";
echo "<a href='patient_registration.php'>Add New Patient</a>";
echo "<table border='1'>";
echo "<tr><th>Patient Name</th><th>Age</th><th>Gender</th><th>Actions</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
    echo "<td>" . htmlspecialchars($row['age']) . "</td>";
    echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
    echo "<td>
        <a href='patient_details.php?patient_id=" . $row['patient_id'] . "'>View</a> | 
        <a href='update_patient.php?patient_id=" . $row['patient_id'] . "'>Edit</a> | 
        <a href='delete_patient.php?patient_id=" . $row['patient_id'] . "' onclick='return confirm(\"Are you sure you want to delete this patient?\")'>Delete</a>
    </td>";
    echo "</tr>";
}
echo "</table>";

$conn->close();
?>
