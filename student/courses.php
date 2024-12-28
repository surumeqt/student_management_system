<?php
session_start();
include '../database.php';

if (!isset($_SESSION['studentID'])) {
    header('Location: ../credentials/studentlogin.php');
    exit;
}

$student_id = $_SESSION['studentID'];

$sqlCheck = "SELECT sp.course_id, sp.reason_for_course, sp.status, c.course_name 
             FROM student_profiles sp
             LEFT JOIN courses c ON sp.course_id = c.id
             WHERE sp.studentID = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $student_id);
$stmtCheck->execute();
$stmtCheck->bind_result($selectedCourseId, $reasonForCourse, $currentStatus, $courseName);
$stmtCheck->fetch();
$stmtCheck->close();

$alreadySelected = !is_null($selectedCourseId);

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$alreadySelected) {
    $courseId = $_POST['course_id'] ?? '';
    $reason = $_POST['reason'] ?? '';

    if (!empty($courseId) && !empty($reason)) {
        $sqlUpdate = "UPDATE student_profiles 
                      SET course_id = ?, reason_for_course = ?, status = 'pending' 
                      WHERE studentID = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("isi", $courseId, $reason, $student_id);

        if ($stmtUpdate->execute()) {
            $sqlUpdateCredentials = "UPDATE student_credential 
            SET status = 'pending' 
            WHERE studentID = ?";
            $stmtUpdateCredentials = $conn->prepare($sqlUpdateCredentials);
            $stmtUpdateCredentials->bind_param("i", $student_id);
            $stmtUpdateCredentials->execute();
            
            $alreadySelected = true;
            header('Location: courses.php');
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        $error = "Both course selection and reason are required.";
    }
}

$sqlCourses = "SELECT id, course_name FROM courses";
$stmtCourses = $conn->prepare($sqlCourses);
if ($stmtCourses->execute()) {
    $resultCourses = $stmtCourses->get_result();
} else {
    echo "Error executing query: " . $stmtCourses->error;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./aesthetics/studentportal.css">
    <title>Select Courses</title>
</head>
<body>
    <div id="coursesInfo">
        <div class="course">
            <h1>Select Courses</h1>
            <h3>Here you can select your course for the upcoming semester.</h3>

            <?php if ($alreadySelected): ?>
                <p><strong>Course Selected:</strong> <?= htmlspecialchars($courseName); ?></p>
                <p><strong>Reason for Choosing This Course:</strong> <?= htmlspecialchars($reasonForCourse); ?></p>
                <p><em>Your course selection cannot be changed after submission.</em></p>
            <?php else: ?>
                <form action="courses.php" method="POST">
                    <label><b>Select your Course</b></label><br><br>
                    <select name="course_id" required>
                        <?php while ($row = $resultCourses->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['id']); ?>"><?= htmlspecialchars($row['course_name']); ?></option>
                        <?php endwhile; ?>
                    </select><br><br>
                    <label for="reason"><b>Reason For Choosing This Course</b></label><br><br>
                    <textarea name="reason" id="reason" cols="50" rows="5" required></textarea><br><br>
                    <input type="submit" value="Submit" id="submit">
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script>
        document.getElementById('submit').addEventListener('click', function(){
            alert('Information Updated, Please Refresh the page.');
        })
    </script>
</body>
</html>
