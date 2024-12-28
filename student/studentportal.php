<?php
session_start();
include '../database.php';

if (!isset($_SESSION['studentID'])) {
    header('Location: ../credentials/studentlogin.php');
    exit;
}

$studentID = $_SESSION['studentID'];

$sql = "
SELECT sp.status AS profile_status, 
       sc.status AS credential_status, 
       sp.first_name, 
       sp.last_name 
FROM student_profiles sp
LEFT JOIN student_credential sc ON sp.studentID = sc.studentID
WHERE sp.studentID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $studentID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $profile_status = $student['profile_status'];
    $credential_status = $student['credential_status'];
    $profileUpdated = ($profile_status === 'approved');
} else {
    $profile_status = null;
    $credential_status = null;
    $error_message = "Student not found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <link rel="stylesheet" href="./aesthetics/studentportal.css">
    <script src="./aesthetics/studentportal.js"></script>
</head>
<body>
    <div class="container">
        <div class="GCPORTAL">
            <img src="../credentials/img/newlogo.jpg" alt="Gordon College Logo">
            <h3>University of Olongapo</h3>

            <!-- Navigation Buttons -->
            <?php if ($profile_status === 'approved' && $credential_status !== 'dropped'): ?>
                <button class="profile-btn" onclick="loadSection('dashboard.php')">Dashboard</button>
                <button class="profile-btn" onclick="loadSection('profile.php')">Profile / Enlistment</button>
                <button class="profile-btn" onclick="loadSection('academic_track.php')">Check Academic Track</button>
            <?php elseif ($credential_status === 'notSet' || $profile_status === 'notSet' || $credential_status === 'pending'): ?>
                <?php if ($profile_status === 'pending'): ?>
                <button class="profile-btn" onclick="loadSection('dashboard.php')">Dashboard</button>
                <?php endif; ?>
                <button class="profile-btn" onclick="loadSection('profile.php')">Profile / Enlistment</button>
                <button class="profile-btn" onclick="loadSection('courses.php')">Select Courses</button>
            <?php elseif ($profile_status === 'dropped' || $credential_status === 'dropped' || $profile_status === 'denied' || $credential_status === 'denied' || $credential_status === null): ?>
                <button class="profile-btn" onclick="loadSection('dashboard.php')">Dashboard</button>
                <button class="profile-btn btn-danger" onclick="openModal()">Delete My Data</button>
            <?php endif; ?>

            <a href="../logout.php" class="profile-logout">Log-out</a>
        </div>
        
        <div class="GCPORTAL1">
            <iframe id="contentFrame" 
                src="<?php 
                    if ($profile_status === 'dropped' || $credential_status === 'dropped' || $profile_status === 'denied' || $credential_status === 'denied' || $credential_status === null) {
                        echo 'dashboard.php';
                    } elseif ($profile_status === 'approved') {
                        echo 'dashboard.php';
                    } elseif ($credential_status === 'notSet' || $profile_status === 'notSet') {
                        echo 'profile.php';
                    } elseif ($profile_status === 'pending') {
                        echo 'dashboard.php';
                    }
                ?>" 
                frameborder="0" style="width: 100%; height: 100vh;">
            </iframe>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal" id="deleteModal">
        <div class="modal-header">
            Confirm Deletion
        </div>
        <div class="modal-body">
            Are you sure you want to delete your data? This action cannot be undone.
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <form method="POST" action="delete_data.php">
                <button type="submit" name="confirm_delete" class="btn-delete">Yes, Delete</button>
            </form>
        </div>
    </div>
</body>
</html>


