<?php
session_start();

include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    if (!isset($_SESSION['studentID'])) {
        header("Location: ../credentials/studentlogin.php?error=Unauthorized");
        exit;
    }

    $studentID = $_SESSION['studentID'];

    $conn->begin_transaction();

    try {
        $queryProfiles = "DELETE FROM student_profiles WHERE studentID = ?";
        $stmtProfiles = $conn->prepare($queryProfiles);
        $stmtProfiles->bind_param("i", $studentID);

        if ($stmtProfiles->execute()){
            $queryCredentials = "UPDATE student_credential SET status = null WHERE studentID = ?";
            $stmtCredentials = $conn->prepare($queryCredentials);
            $stmtCredentials->bind_param('i', $studentID);
            if($stmtCredentials->execute()){
                $conn->commit();
            }
        }
        session_destroy();
        header("Location: ../credentials/studentlogin.php?message=Data deleted successfully");
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        header("Location: ../credentials/studentlogin.php?error=Failed to delete data");
        exit;
    }
}
?>
