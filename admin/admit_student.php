<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = intval($_POST['student_id']);
    $courseId = isset($_POST['course_id']) ? intval($_POST['course_id']) : null;
    $action = $_POST['action'];

    if ($action === 'approve') {
        try {
            $conn->begin_transaction();

            $studentQuery = $conn->prepare("SELECT 
                CONCAT(sp.first_name, ' ', sp.middle_name, ' ', sp.last_name) AS name,
                CONCAT(sp.first_name, ' ', sp.middle_name, ' ', sp.last_name, ' ', sc.email, ' ', sp.created_at) AS details,
                sp.year_level
            FROM student_profiles sp
            JOIN student_credential sc ON sp.studentID = sc.studentID
            WHERE sp.studentID = ? AND sp.status = 'pending'");
            $studentQuery->bind_param('i', $studentId);
            $studentQuery->execute();
            $studentResult = $studentQuery->get_result();
            $student = $studentResult->fetch_assoc();

            if ($student) {
                $updateStatus = $conn->prepare("UPDATE student_profiles SET status = 'approved', course_id = ? WHERE studentID = ?");
                $updateStatus->bind_param('ii', $courseId, $studentId);
                $updateStatus->execute();

                $courseQuery = $conn->prepare("SELECT course_availability FROM courses WHERE id = ?");
                $courseQuery->bind_param('i', $courseId);
                $courseQuery->execute();
                $courseResult = $courseQuery->get_result();
                $course = $courseResult->fetch_assoc();

                if ($course && $course['course_availability'] > 0) {
                    $updateCourse = $conn->prepare("UPDATE courses SET course_availability = course_availability - 1 WHERE id = ? AND course_availability > 0");
                    $updateCourse->bind_param('i', $courseId);
                    $updateCourse->execute();
                } else {
                    throw new Exception("No available slots for the course.");
                }

                $createTableQuery = "CREATE TABLE student_{$studentId} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    subject_name VARCHAR(255) NOT NULL,
                    subject_code VARCHAR(10) NOT NULL,
                    subject_units INT NOT NULL,
                    grade DECIMAL(5, 2) DEFAULT NULL,
                    date_recorded TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )";

                if (!$conn->query($createTableQuery)) {
                    throw new Exception("Error creating table for student: " . $conn->error);
                } else {
                    echo "Table created successfully for student {$studentId}.<br>";
                }

                $subjectsQuery = $conn->prepare("SELECT subject_code, subject_name, subject_units FROM subjects WHERE course_id = ? AND year_level = ?");
                $subjectsQuery->bind_param('ii', $courseId, $student['year_level']);
                $subjectsQuery->execute();
                $subjectsResult = $subjectsQuery->get_result();

                if ($subjectsResult->num_rows > 0) {
                    while ($subject = $subjectsResult->fetch_assoc()) {
                        $insertSubjectQuery = $conn->prepare("INSERT INTO student_{$studentId} (subject_code, subject_name, subject_units) VALUES (?, ?, ?)");
                        $insertSubjectQuery->bind_param('ssi', $subject['subject_code'], $subject['subject_name'], $subject['subject_units']);
                        if (!$insertSubjectQuery->execute()) {
                            throw new Exception("Error inserting subject: " . $conn->error);
                        } else {
                            echo "Inserted subject: " . $subject['subject_name'] . "<br>";
                        }
                    }
                } else {
                    echo "No subjects found for this course and year level.<br>";
                }
            }

            $conn->commit();
            header('Location: adminPortal.php?message=Student was Admitted sucessfully!');
            exit;
        } catch (Exception $e) {
            $conn->rollback();
            die("Error approving student: " . $e->getMessage());
        }
    } elseif ($action === 'deny') {
        try {
            $conn->begin_transaction();
            $denyProfile = $conn->prepare("UPDATE student_profiles SET status = 'denied' WHERE studentID = ?");
            $denyProfile->bind_param('i', $studentId);
            $denyProfile->execute();

            $denyCredential = $conn->prepare("UPDATE student_credential SET status = 'denied' WHERE studentID = ?");
            $denyCredential->bind_param('i', $studentId);
            $denyCredential->execute();

            $conn->commit();
            header("Location: adminPortal.php?message=Student was denied successfully!");
        } catch (Exception $e) {
            $conn->rollback();
            die("Error denying student: " . $e->getMessage());
        }
    }
}
