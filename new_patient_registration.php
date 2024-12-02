<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Data Collection</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container">
        <h1>Patient Data Collection</h1>
        <form action="patient_data.php" method="post">
            <div class="form-group">
                <label for="patient_name">Name:</label>
                <input type="text" id="patient_name" name="patient_name" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
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
                <label for="immunization">Immunization:</label>
                <textarea id="immunization" name="immunization" placeholder="List immunization details" required></textarea>
            </div>
            <div class="form-group">
                <label for="allergies">Allergies:</label>
                <textarea id="allergies" name="allergies" placeholder="List any allergies" required></textarea>
            </div>
            <button type="submit">Next</button>
        </form>
    </div>
</body>
</html>
