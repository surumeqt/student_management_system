<?php
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $course_ids = $_POST['course_ids'];
    $course_slots = $_POST['course_slots'];

    for ($i = 0; $i < count($course_ids); $i++) {
        $id = intval($course_ids[$i]);
        $slots = intval($course_slots[$i]);
        $conn->query("UPDATE courses SET course_availability = $slots WHERE id = $id");
    }
}
header("Location: adminportal.php?message=Successfully Updated");
exit();

