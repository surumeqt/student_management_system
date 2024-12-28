<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'])) {
    $studentID = $_POST['student_id'];

    $conn->begin_transaction();

    try {
        $queryProfiles = "UPDATE student_profiles SET status = 'dropped' WHERE studentID = ?";
        $stmtProfiles = $conn->prepare($queryProfiles);
        $stmtProfiles->bind_param("s", $studentID);

        if (!$stmtProfiles->execute()) {
            throw new Exception("Failed to update status in student_profiles");
        }

        $queryCredentials = "UPDATE student_credential SET status = 'dropped' WHERE studentID = ?";
        $stmtCredentials = $conn->prepare($queryCredentials);
        $stmtCredentials->bind_param("s", $studentID);

        if (!$stmtCredentials->execute()) {
            throw new Exception("Failed to update status in student_credential");
        }

        $studentTable = "student_" . $studentID;
        $dropTableQuery = "DROP TABLE IF EXISTS $studentTable";

        if (!$conn->query($dropTableQuery)) {
            throw new Exception("Failed to drop student's table");
        }

        $conn->commit();

        header("Location: adminPortal.php?message=Student dropped successfully");
        exit;

    } catch (Exception $e) {
        $conn->rollback();

        header("Location: adminPortal.php?error=" . $e->getMessage());
        exit;
    }
} else {
    header("Location: adminPortal.php?error=Invalid request");
    exit;
}