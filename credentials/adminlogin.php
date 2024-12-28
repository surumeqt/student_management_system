<?php
session_start();

include '../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = $conn->prepare("SELECT * FROM admin WHERE username = ?");
    $query->bind_param('s', $username);

    $query->execute();
    $result = $query->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        header("Location: ../admin/adminPortal.php");
        exit;
    } else {
        $error = "Invalid admin username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="adminlogin.css">
</head>
<body>
    
<div class="container">
        <div class="grid1">

        </div>

        <div class="grid2">
        <img src="img/newlogo.jpg" alt="">
        <h1>Welcome Admin!</h1>
            <form action="adminlogin.php" method="POST">
                <?php if (isset($error)) { echo '<div class="alert">'.$error.'</div>'; } ?><br>
                <fieldset class="f1">
                    <label>🔒<input type="text" placeholder=" Domain address" name="username" required></label>
                </fieldset>

                <br>
                <fieldset class="f2">
                    <label>🔑<input type="password" placeholder=" Password" name="password" required></label>
                </fieldset>
                <br>
                <div> 
                <input class="submit" type="submit" value="Log in">
                </div>

                <div class="userinfo2">
            <p>Are you a student? <a href="studentlogin.php">click here</a></p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>