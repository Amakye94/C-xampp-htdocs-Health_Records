<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "health_records_2024");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search
$search_query = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';

// Fetch patients
$sql = "SELECT * FROM patients";
if (!empty($search_query)) {
    $sql .= " WHERE patient_name LIKE '%$search_query%'";
}
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Patients</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Patient Management</h1>

    <!-- Search Form -->
    <form method="POST" action="manage_patients.php">
        <input type="text" name="search" placeholder="Search by patient name" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Patient List -->
    <table border="1">
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Doctor</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['age']); ?></td>
                        <td><?php echo htmlspecialchars($row['gender']); ?></td>
                        <td>
                            <?php
                            // Fetch the doctor's name
                            $doctor_result = $conn->query("SELECT CONCAT(title, ' ', full_name) AS doctor_name FROM doctors_registration_table WHERE doctor_id = " . intval($row['doctor_id']));
                            $doctor = $doctor_result->fetch_assoc();
                            echo htmlspecialchars($doctor['doctor_name']);
                            ?>
                        </td>
                        <td>
                            <a href="view_patient.php?patient_id=<?php echo $row['patient_id']; ?>">View</a> |
                            <a href="update_patient.php?patient_id=<?php echo $row['patient_id']; ?>">Update</a> |
                            <a href="delete_patient.php?patient_id=<?php echo $row['patient_id']; ?>" onclick="return confirm('Are you sure you want to delete this patient?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No patients found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
<?php
$conn->close();
?>
