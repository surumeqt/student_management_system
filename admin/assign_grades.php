<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = intval($_POST['student_id']);
    $grades = $_POST['grades'];

    $studentTable = "student_" . $studentId;

    try {
        foreach ($grades as $subjectCode => $grade) {
            $updateQuery = "UPDATE $studentTable SET grade = ? WHERE subject_code = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param('ds', $grade, $subjectCode);
            $stmt->execute();
        }

        header('Location: adminPortal.php?message=Grades Successfully Saved');
        exit;
    } catch (Exception $e) {
        die("Error updating grades: " . $e->getMessage());
    }
}
?>
