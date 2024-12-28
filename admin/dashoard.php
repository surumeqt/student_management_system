<?php

include '../database.php';

try {
    // Get count of approved students
    $studentCountQuery = "SELECT COUNT(*) as count FROM student_profiles WHERE status = 'approved'";
    $studentCountResult = $conn->query($studentCountQuery);
    $studentCount = $studentCountResult->fetch_assoc();

    // Get count of students pending review
    $pendingReviewsQuery = "SELECT COUNT(*) as count FROM student_profiles WHERE status = 'pending'";
    $pendingReviewsResult = $conn->query($pendingReviewsQuery);
    $pendingReviews = $pendingReviewsResult->fetch_assoc();


    $coursesQuery = "SELECT * FROM courses";
    $coursesResult = $conn->query($coursesQuery);
    $courses = $coursesResult->fetch_all(MYSQLI_ASSOC);

    $pendingStudentsQuery = "
    SELECT sr.studentID,
           CONCAT(sr.first_name, ' ', sr.middle_name, ' ', sr.last_name) AS name,
           sr.created_at,
           sc.email,
           sr.status,
           sr.reason_for_course,
           c.course_name AS course_name, 
           c.id AS course_id
    FROM student_profiles sr
    JOIN courses c ON sr.course_id = c.id
    JOIN student_credential sc ON sr.studentID = sc.studentID
    WHERE sr.status = 'pending'
";
$pendingStudentsResult = $conn->query($pendingStudentsQuery);
$pendingStudents = $pendingStudentsResult->fetch_all(MYSQLI_ASSOC);

} catch (mysqli_sql_exception $e) {
    die("Database error: " . $e->getMessage());
}

?>
