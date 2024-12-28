<?php
session_start();
include '../database.php';

function generateStudentID() {
    $datePart = date('Ym');
    $randomDigit = rand(100, 999);
    return $datePart . $randomDigit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmP = $_POST['confirmPass'];

    $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    if ($password !== $confirmP) {
        $error = 'Passwords don\'t match';
    }

    $checkQuery = $conn->prepare("SELECT * FROM student_credential WHERE email = ?");
    $checkQuery->bind_param('s', $email);
    $checkQuery->execute();
    $checkQuery->store_result();

    if ($checkQuery->num_rows > 0) {
        $error = "Email already taken!";
    } elseif (!filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid Email Address';
    } elseif (strlen($password) < 8) {
        $error = 'Password is too short';
    } elseif ($password !== $confirmP) {
        $error = 'Passwords don\'t match';
    } else {
        $studentId = generateStudentID();

        $idCheckQuery = $conn->prepare("SELECT * FROM student_credential WHERE studentID = ?");
        $idCheckQuery->bind_param('i', $studentId);
        $idCheckQuery->execute();
        $idCheckQuery->store_result();

        while ($idCheckQuery->num_rows > 0) {
            $studentId = generateStudentID();
            $idCheckQuery->execute();
        }

        $idCheckQuery->free_result();

        $query = $conn->prepare("INSERT INTO student_credential (studentID, email, password) VALUES (?, ?, ?)");
        $query->bind_param('iss', $studentId, $sanitizedEmail, $hashed);
        $query->execute();

        $profileQuery = $conn->prepare("INSERT INTO student_profiles (studentID) VALUES (?)");
        $profileQuery->bind_param("i", $studentId);
        $profileQuery->execute();

        $_SESSION['studentID'] = $studentId;

        header('Location: ../student/studentportal.php');
        exit;
    }
    $checkQuery->free_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <link rel="stylesheet" href="./aesthetics/register.css">
    <script src="./aesthetics/register.js"></script>
</head>
<body>

<div class="userinfo">
    <img src="img/newlogo.jpg" alt="">
    <h2>Welcome to the Student Portal! Register your account</h2>
    <?php if (isset($error)) { echo '<div class="error-message">'.$error.'</div>'; } ?>
    <br>
    <form action="register.php" method="post" onsubmit="return validatePasswords()">
        <fieldset class="f2">
            <legend><b>Email</b></legend>
            <input class="input2" type="text" placeholder="(Ex. *example@gmail.com)" name="email" required>
        </fieldset>
        <br>
        <fieldset class="f3">
            <legend>Enter Your Password</legend>
            <input class="input3" type="password" placeholder="Password" id="password" name="password" required>
            <label>Show password</label>
            <input type="checkbox" id="showPassword" onchange="toggleShowPassword()" />
        </fieldset>
        <br>
        <fieldset class="f3">
            <legend>Confirm Your Password</legend>
            <input class="input3" type="password" placeholder="Confirm Password" id="confirmPassword" name="confirmPass" required>
            <label>Show password</label>
            <input type="checkbox" id="showConfirmPassword" onchange="toggleShowPassword()" />
        </fieldset>
        <br>
        <input class="button1" type="submit" value="Sign up">
        <br>

        <div class="userinfo2">
            <p>Have an account? <a href="studentlogin.php">Log-in</a></p>
        </div>
    </form>
</div>


</body>
</html>
