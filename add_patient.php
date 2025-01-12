<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>
    <link rel="stylesheet" href="all_pages.css">
</head>
<body>
    <h1>Add New Patient</h1>
    <form action="patient_details.php" method="post">
        <label for="patient_name">Name:</label>
        <input type="text" id="patient_name" name="patient_name" required>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required>

        <label for="gender">Gender:</label>
        <select id="gender" name="gender" required>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" required>

        <label for="immunization">Immunization:</label>
        <textarea id="immunization" name="immunization" placeholder="List immunization details" required></textarea>

        <label for="allergies">Allergies:</label>
        <textarea id="allergies" name="allergies" placeholder="List any allergies" required></textarea>

        <button type="submit">Next</button>
    </form>
</body>
</html>
