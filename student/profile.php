<?php
session_start();

include '../database.php';


$student_id = $_SESSION['studentID'];

$sql = "SELECT sc.email, sp.last_name, sp.first_name, sp.middle_name, sp.address, sp.dob, sp.place_of_birth, sp.gender, 
               sp.citizenship, sp.mobile_number, sp.elementary_school, 
               sp.elementary_graduation_year, sp.high_school, sp.high_school_graduation_year, sp.strand, 
               sp.fathers_name, sp.fathers_occupation, sp.fathers_mobile, sp.mothers_name, sp.mothers_occupation, 
               sp.mothers_mobile, sp.number_of_siblings, sp.guardian_name, sp.guardian_phone 
        FROM student_credential sc
        INNER JOIN student_profiles sp ON sc.studentID = sp.studentID
        WHERE sc.studentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $lastName = $row['last_name'];
    $firstName = $row['first_name'];
    $middleName = $row['middle_name'];
    $address = $row['address'];
    $dob = $row['dob'];
    $placeOfBirth = $row['place_of_birth'];
    $gender = $row['gender'];
    $citizenship = $row['citizenship'];
    $mobileNumber = $row['mobile_number'];
    $elementarySchool = $row['elementary_school'];
    $elementaryGraduationYear = $row['elementary_graduation_year'];
    $highSchool = $row['high_school'];
    $highSchoolGraduationYear = $row['high_school_graduation_year'];
    $strand = $row['strand'];
    $fathersName = $row['fathers_name'];
    $fathersOccupation = $row['fathers_occupation'];
    $fathersMobileNumber = $row['fathers_mobile'];
    $mothersName = $row['mothers_name'];
    $mothersOccupation = $row['mothers_occupation'];
    $mothersMobileNumber = $row['mothers_mobile'];
    $numberOfSiblings = $row['number_of_siblings'];
    $guardianName = $row['guardian_name'];
    $guardianPhone = $row['guardian_phone'];
} else {
    $lastName = $firstName = $middleName = $address = $dob = $placeOfBirth = $gender = $citizenship = $mobileNumber = '';
    $elementarySchool = $elementaryGraduationYear = '';
    $highSchool = $highSchoolGraduationYear = $strand = '';
    $fathersName = $fathersOccupation = $fathersMobileNumber = '';
    $mothersName = $mothersOccupation = $mothersMobileNumber = '';
    $numberOfSiblings = $guardianName = $guardianPhone = '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Personal Information
    $lastName = $_POST['last_name'] ?? '';
    $firstName = $_POST['first_name'] ?? '';
    $middleName = $_POST['middle_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $placeOfBirth = $_POST['place_of_birth'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $citizenship = $_POST['citizenship'] ?? '';
    $mobileNumber = $_POST['mobile_number'] ?? '';

    // Education
    $elementarySchool = $_POST['elementary_school'] ?? '';
    $elementaryGraduationYear = $_POST['elementary_graduation_year'] ?? '';
    $highSchool = $_POST['high_school'] ?? '';
    $highSchoolGraduationYear = $_POST['high_school_graduation_year'] ?? '';
    $strand = $_POST['strand'] ?? '';

    // Family
    $fathersName = $_POST['fathers_name'] ?? '';
    $fathersOccupation = $_POST['fathers_occupation'] ?? '';
    $fathersMobileNumber = $_POST['fathers_mobile'] ?? '';
    $mothersName = $_POST['mothers_name'] ?? '';
    $mothersOccupation = $_POST['mothers_occupation'] ?? '';
    $mothersMobileNumber = $_POST['mothers_mobile'] ?? '';
    $numberOfSiblings = $_POST['number_of_siblings'] ?? '';
    $guardianName = $_POST['guardian_name'] ?? '';
    $guardianPhone = $_POST['guardian_phone'] ?? '';

    $sql = "UPDATE student_profiles SET 
        last_name = ?, first_name = ?, middle_name = ?, address = ?, dob = ?, place_of_birth = ?, gender = ?, 
        citizenship = ?, mobile_number = ?, 
        elementary_school = ?, elementary_graduation_year = ?, high_school = ?, high_school_graduation_year = ?, 
        strand = ?, fathers_name = ?, fathers_occupation = ?, fathers_mobile = ?, mothers_name = ?, 
        mothers_occupation = ?, mothers_mobile = ?, number_of_siblings = ?, guardian_name = ?, guardian_phone = ? 
        WHERE studentID = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssssssssssi",
        $lastName, $firstName, $middleName, $address, $dob, $placeOfBirth, $gender, $citizenship, $mobileNumber,
        $elementarySchool, $elementaryGraduationYear, $highSchool, 
        $highSchoolGraduationYear, $strand, $fathersName, $fathersOccupation, $fathersMobileNumber, $mothersName,
        $mothersOccupation, $mothersMobileNumber, $numberOfSiblings, $guardianName, $guardianPhone, $student_id
    );
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./aesthetics/studentportal.css">
    <script src="./aesthetics/studentportal.js"></script>
    <title>Profile</title>

</head>
<body> 
<form id="profileForm" action="profile.php" method="post" onsubmit="return submitForm(event)">
        <h1>Profile/Enlistment</h1>
        <h4>This section is for updating your profile and enlistment. Complete the form to proceed.</h4> <br>
        <p style="text-align: center; color: rgb(134, 128, 128);">
        Rest assured that any information you provide here will be handled with the utmost care and confidentiality. Both the administration and the school organization are committed to ensuring that your data remains secure and is used solely for its intended purposes, in accordance with our privacy and data protection policies.
        </p><br>

        <!-- Personal Information -->
        <fieldset class="f1">
            <legend><b>Personal Information</b></legend>
            <fieldset>
                <legend>Full Name</legend>
                <input class="input1" type="text" name="last_name" placeholder="Last Name*" required value="<?= htmlspecialchars($lastName) ?>">
                <input class="input1" type="text" name="first_name" placeholder="First Name*" required value="<?= htmlspecialchars($firstName) ?>">
                <input class="input1" type="text" name="middle_name" placeholder="Middle Name" value="<?= htmlspecialchars($middleName) ?>">
            </fieldset>
            <fieldset>
                <legend>Address</legend>
                <input class="input1" type="text" name="address" placeholder="Address" value="<?= htmlspecialchars($address) ?>">
            </fieldset>
            <fieldset>
                <legend>Date of Birth</legend>
                <input class="input1" type="date" name="dob" placeholder="Date of Birth*" required value="<?= htmlspecialchars($dob) ?>">
            </fieldset>
            <fieldset>
                <legend>Place of Birth</legend>
                <input class="input1" type="text" name="place_of_birth" placeholder="Place of Birth*" required value="<?= htmlspecialchars($placeOfBirth) ?>">
            </fieldset>
            <fieldset>
                <legend>Gender</legend>
                <select class="input1" name="gender" required>
                    <option value="">Gender</option>
                    <option value="Male" <?= $gender === "Male" ? "selected" : "" ?>>Male</option>
                    <option value="Female" <?= $gender === "Female" ? "selected" : "" ?>>Female</option>
                </select>
            </fieldset>
            <fieldset>
                <legend>Citizenship</legend>
                <input class="input1" type="text" name="citizenship" placeholder="Citizenship*" required value="<?= htmlspecialchars($citizenship) ?>">
            </fieldset>
            <fieldset>
                <legend>Mobile Number</legend>
                <input class="input1" type="text" name="mobile_number" placeholder="Mobile Number*" required value="<?= htmlspecialchars($mobileNumber) ?>">
            </fieldset>
        </fieldset><br>

        <!-- Education -->
        <fieldset class="f1">
            <legend><b>Educational Background</b></legend>
            <fieldset>
                <legend>Elementary School</legend>
                <input class="input1" type="text" name="elementary_school" placeholder="Elementary School" value="<?= htmlspecialchars($elementarySchool) ?>">
                <input class="input1" type="text" name="elementary_graduation_year" placeholder="Year Graduated" value="<?= htmlspecialchars($elementaryGraduationYear) ?>">
            </fieldset>
           
            <fieldset>
                <legend>High School</legend>
                <input class="input1" type="text" name="high_school" placeholder="High School" value="<?= htmlspecialchars($highSchool) ?>">
                <input class="input1" type="text" name="high_school_graduation_year" placeholder="Graduation Year" value="<?= htmlspecialchars($highSchoolGraduationYear) ?>">
            </fieldset>

            <fieldset>
                <legend>Strand</legend>
                <input class="input1" type="text" name="strand" placeholder="Senior-High Strand" value="<?= htmlspecialchars($strand) ?>">
            </fieldset>
        </fieldset><br>

        <!-- Family Information -->
        <fieldset class="f1">
            <legend><b>Family Information</b></legend>
            <input class="input1" type="text" name="fathers_name" placeholder="Father's Name" value="<?= htmlspecialchars($fathersName) ?>">
            <input class="input1" type="text" name="fathers_occupation" placeholder="Father's Occupation" value="<?= htmlspecialchars($fathersOccupation) ?>">
            <input class="input1" type="text" name="fathers_mobile" placeholder="Father's Mobile" value="<?= htmlspecialchars($fathersMobileNumber) ?>">
            <br><br>
            <input class="input1" type="text" name="mothers_name" placeholder="Mother's Name" value="<?= htmlspecialchars($mothersName) ?>">
            <input class="input1" type="text" name="mothers_occupation" placeholder="Mother's Occupation" value="<?= htmlspecialchars($mothersOccupation) ?>">
            <input class="input1" type="text" name="mothers_mobile" placeholder="Mother's Mobile" value="<?= htmlspecialchars($mothersMobileNumber) ?>">
            <br><br>
            <input class="input1" type="number" name="number_of_siblings" placeholder="Number of Siblings" value="<?= htmlspecialchars($numberOfSiblings) ?>">
            <input class="input1" type="text" name="guardian_name" placeholder="Guardian's Name" value="<?= htmlspecialchars($guardianName) ?>">
            <input class="input1" type="text" name="guardian_phone" placeholder="Guardian's Phone" value="<?= htmlspecialchars($guardianPhone) ?>">
        </fieldset><br>

        <button id="submit" class="submit" type="submit">Update Profile</button>
    </form>

    <script>
        function submitForm(event) {
        event.preventDefault();

        const form = document.getElementById('profileForm');
        const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes('Profile Successfully updated!')) {
                    showAlert('Profile updated successfully!', 'success');
                } else {
                    showAlert('Error updating profile.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred. Please try again.', 'error');
            });
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${type}`;
            alertDiv.innerText = message;
            document.body.insertBefore(alertDiv, document.body.firstChild);

            window.scrollTo({ top: 0, behavior: 'smooth' });

            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
    </script>

</body>
</html>
