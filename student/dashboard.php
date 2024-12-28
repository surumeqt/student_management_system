<?php
session_start();

include '../database.php';

if (!isset($_SESSION['studentID'])) {
    header("Location: ../credentials/studentlogin.php");
    exit;
}

$studentID = $_SESSION['studentID'];

$sql = "
SELECT sr.studentID,
       CONCAT(sr.first_name, ' ', sr.middle_name, ' ', sr.last_name) AS name,
       sr.status AS stdStat,
       sr.reason_for_course,
       sr.created_at,
       c.course_name AS course_name, 
       c.id AS course_id,
       sc.email,
       sc.status AS credStat
FROM student_credential sc
LEFT JOIN student_profiles sr ON sc.studentID = sr.studentID
LEFT JOIN courses c ON sr.course_id = c.id
WHERE sc.studentID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $studentID);

if (!$stmt->execute()) {
    die("Error executing query: " . $conn->error);
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
    $stdStat = $student['stdStat'];
    $credStat = $student['credStat'];
    $name = $student['name'] ?? "Student";
    $courseName = $student['course_name'] ?? "No course assigned";
} else {
    $stdStat = null;
    $credStat = "dropped";
    $name = "Student";
    $courseName = "No course assigned";
}

$statusMessage = '';

if ($stdStat === 'dropped' || $credStat === 'dropped') {
    $statusMessage = "You have been dropped from the course. Please contact the administration for further information.";
    $greeting = "Goodbye";
} elseif ($stdStat === 'denied' || $credStat === 'denied') {
    $statusMessage = "Your application has been denied. Please contact the administration for more details.";
    $greeting = "Goodbye";
} elseif ($stdStat === 'approved' || $credStat === 'approved') {
    $statusMessage = "You are successfully enrolled in the course.";
    $greeting = "Welcome";
} elseif ($stdStat === 'pending' || $credStat === 'pending') {
    $statusMessage = "Your application is pending. Please wait for further updates.";
    $greeting = "Hello";
} elseif ($credStat === null) {
    $statusMessage = "You have been dropped. you may have deleted your information in our database, 
    if its not showing you name and chosen course means that it was successful.
    Dont worry, there's still next year. GOODLUCK!
    ";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h2>Dashboard</h2>

    <?php if ($credStat !== null): ?>
        <h1><?php echo $greeting; ?>, <?php echo htmlspecialchars($name); ?></h1>
    <?php endif; ?>

    <table border="1px solid" width="100%" style="text-align:center;">
        <thead>
            <tr>
                <th>Course</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($courseName); ?></td>
                <td><?php echo htmlspecialchars($stdStat ?? $credStat); ?></td>
            </tr>
        </tbody>
    </table>
<br> <br>
        <div class="a">
        <p>Enrollment Status Description</p>
            <ul>
                     <li><b>Pending:</b> You have submitted your courses, waiting for admin approval</li>
                <br> <li><b>Denied:</b> Somehow you've been denied admission in your chosen program, possibly due to failing to pass the retention policy of the program you are in. You may choose to try another program</li>
                <br> <li><b>Dropped:</b> You have been dropped by the admin, you may delete your information regarding the admission.</li>
                <br> <li><b>Approved:</b> Your enrollment has been approved by the admin.</li>

            </ul>
        </div>
<br>
        <div class="c">
            <p>Inbox </p>
            <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php if (isset($statusMessage)): ?>
                <div class="alert">
                    <?php echo htmlspecialchars($statusMessage); ?>
                </div>
            <?php endif; ?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>
        </div>
</body>
</html>
