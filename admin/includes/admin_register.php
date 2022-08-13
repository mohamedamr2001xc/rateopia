<?php
require 'database.php';


if (isset($_POST['submit'])) {
    $id = $_POST['Name'];
    $district = $_POST['district'];
    $pass = $_POST['pass'];
    $pass = hash('sha256', $pass);
    $confirmpass = $_POST['pass2'];
    $confirmpass = $_POST['pass2'];
    if (empty($id) || empty($district) || empty($pass) || empty($confirmpass)) {
        header("Location: ../2_register.php?error=emptyfields");
    } else {
        if ($pass != $confirmpass) {
            header("Location: ../2_register.php?error=passworddontmatch");
        } else {
            $sql = "INSERT INTO adminn (ID,District,passwordd) VALUES (?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_register.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "sss", $id, $district, $pass);

                if (mysqli_stmt_execute($stmt)) {
                    header("Location: ../2_login.php");
                } else {
                    header("Location: ../register.php?error=sqlerror");
                }
            }
        }
    }
}
