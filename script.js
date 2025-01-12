function updateDateTime() {
    const now = new Date();
    const time = now.toLocaleTimeString();
    const date = now.toLocaleDateString();
    document.getElementById("currentTime").textContent = time;
    document.getElementById("currentDate").textContent = date;
}
setInterval(updateDateTime, 1000);
updateDateTime();

function displayPatientData() {
    // Get input values
    const name = document.getElementById('patient_name').value;
    const age = document.getElementById('age').value;
    const gender = document.getElementById('gender').value;
    const dob = document.getElementById('date_of_birth').value;
    const immunization = document.getElementById('immunization').value;
    const allergies = document.getElementById('allergies').value;

    // Set output values
    document.getElementById('output_name').textContent = name;
    document.getElementById('output_age').textContent = age;
    document.getElementById('output_gender').textContent = gender;
    document.getElementById('output_dob').textContent = dob;
    document.getElementById('output_immunization').textContent = immunization;
    document.getElementById('output_allergies').textContent = allergies;
 }