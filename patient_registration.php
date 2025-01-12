<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Registration</title>
    <link rel="stylesheet" href="patient.css">
</head>
<body>
<h1>Patient Registration</h1>
<form action="patient_form.php" method="post">
    <div class="form-group">
        <label for="doctor_id">Select a Doctor:</label>
        <select id="doctor_id" name="doctor_id" required>
            <option value="">Select a doctor</option>
            <?php
            // Enable error reporting
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);

            // Database connection
            $conn = new mysqli("localhost", "root", "", "health_records_2024");
            if ($conn->connect_error) {
               die("Connection failed: " . $conn->connect_error);
            }

            // Fetch data from doctors_registration
            $result = $conn->query("SELECT doctor_id, CONCAT(title, ' ', full_name) AS full_name FROM doctors_registration_table");
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['doctor_id']) . "'>" . htmlspecialchars($row['full_name']) . "</option>";
                }
            } else {
                echo "<option value=''>No doctors available</option>";
            }
            $conn->close();
            ?>
            </select>

        <div class="form-group">
            <label for="patient_name">Patient Name:</label>
            <input type="text" id="patient_name" name="patient_name" placeholder="Enter patient name" required>
        </div>

        <div class="form-group">
             <label for="age">Age:</label>
            <input type="number" id="age" name="age" placeholder="Enter age" required>
        </div>

        <div class="form-group">
            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" id="date_of_birth" name="date_of_birth" required>
        </div>

        <div class="form-group">
            <label for="weight">Weight (kg):</label>
            <input type="number" step="0.1" id="weight" name="weight" placeholder="Enter weight" required>
        </div>

        <div class="form-group">
           <label for="blood_pressure">Blood Pressure:</label>
           <input type="text" id="blood_pressure" name="blood_pressure" placeholder="Enter blood pressure" required>
        </div>

        <div class="form-group">
          <label for="Immunization">Immunization:</label>
          <textarea id="immunization" name="immunization" placeholder="Immunization"  required></textarea>
        </div>

        <div class="form-group">
           <label for="allergies">Allergies:</label>
           <textarea id="allergies" name="allergies" placeholder="Enter allergies"></textarea>
        </div>


        <div class="form-group">
        <button type="submit">Register</button>
        </div>
</form>
</body>
</html>
