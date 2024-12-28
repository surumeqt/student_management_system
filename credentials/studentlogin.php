<?php
session_start();
include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['email_or_studentID'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM student_credential WHERE (email = ? OR studentID = ?)";
    $checkQuery = $conn->prepare($sql);

    $studentID = is_numeric($identifier) ? (int)$identifier : 0;
    $checkQuery->bind_param('si', $identifier, $studentID);
    $checkQuery->execute();
    $result = $checkQuery->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['studentID'] = $user['studentID'];

        if ($user['status'] !== null) {
            $profileCheck = $conn->prepare("SELECT * FROM student_profiles WHERE studentID = ?");
            $profileCheck->bind_param("i", $user['studentID']);
            $profileCheck->execute();
            $profileResult = $profileCheck->get_result();

            if ($profileResult->num_rows === 0) {
                $profileInsert = $conn->prepare("INSERT INTO student_profiles (studentID) VALUES (?)");
                $profileInsert->bind_param("i", $user['studentID']);
                $profileInsert->execute();
            }
        }
        header('Location: ../student/studentportal.php');
        exit();
    } else {
        $error = 'Invalid Credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="studentlogin.css">
</head>
<body>
<div>
    <div class="container">
        <div class="grid2">
            <img src="img/newlogo.jpg" alt="">
            <h1>Welcome Students!</h1>
            <form action="studentlogin.php" method="POST">
                <?php if (isset($error)) { echo '<div class="alert">'.$error.'</div>'; } ?><br>
                <fieldset class="f1">
                    <label>🔒<input type="text" placeholder="Email Address" name="email_or_studentID" required></label>
                </fieldset>
                <br>
                <fieldset class="f2">
                    <label>🔑<input type="password" placeholder="Password" name="password" required></label>
                </fieldset>
                <br>
                <div> 
                    <input class="submit" type="submit" value="Log in">
                </div>
                <div class="userinfo2">
                    <p>Are you an admin? <a href="adminlogin.php">Click here</a></p>
                    <br><br>
                    <p>Don't have an account? <a href="register.php">Register here!</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
