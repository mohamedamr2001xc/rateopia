<?php
session_start();
require 'database.php';
$nationalidcheck = $_SESSION['National_id'];
$sql = "SELECT * From citizen where National_id='$nationalidcheck' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$nationalidcheck = $row['id'];
$id = $_SESSION['National_id'];
$sql = "INSERT INTO monitor (id,event_type,datee) values (?,'modify info',NOW())";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../0_login.php?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
}
$sql = "SELECT * from monitor where id='$id' AND datee > (NOW() - INTERVAL 1 Minute)";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 20) {
    $sql = "INSERT INTO blocked_users (userid) values '$nationalidcheck'  ";
    mysqli_query($conn, $sql);
    header("Location: ../0_login.php?error=blockeduser");
    exit();
}




use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if (isset($_POST['submit'])) {

    if ($_SESSION['csrf'] != $_POST['csrf']) {
        header("Location: logout.php");
        exit();
    }
    if (isset($_POST['new_job'])) {
        $newjob = $_POST['new_job'];
    }
    $newEmail = $_POST['new_Email'];
    $newphone = $_POST['new_phone'];
    if (empty($newphone) && empty($newjob) && empty($newEmail)) {

        header("Location: ../modifes.php?error=emptyfeilds");
        exit();
    } else {
        $national_id = $_SESSION['National_id'];
        //send email if any modifes of user we must make it fuction
        /*if(!empty($newAddress))
        {
            //must check if the address esixts
            $sqlnewAddress="UPDATE citizen SET Adress = '$newAddress' WHERE National_id ='$national_id'";
            $resultnewAddress=mysqli_query($conn,$sqlnewAddress);
        }*/
        if (!empty($newjob)) {
            $newjobcheck = strtolower($newjob);
            $newjobcheck = preg_replace('/\s+/', '', $newjobcheck);
            if (!ctype_alpha($newjobcheck) || str_contains($newjobcheck, 'select') || str_contains($newjobcheck, 'revoke') || str_contains($newjobcheck, 'insert') || str_contains($newjobcheck, 'update') || str_contains($newjobcheck, 'delete') || str_contains($newjobcheck, 'drop') || str_contains($newjobcheck, 'grant')) {
                header("Location: ../modifes.php?error=invalidjob");
                exit();
            }
            #$sqlnewjob = "UPDATE citizen SET Job = '$newjob' WHERE National_id ='$national_id'";
            $sql = "UPDATE citizen SET Job = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../modifies.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $newjob, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($newphone)) {
            if (!is_numeric($newphone) || strlen($newphone) != 11) {
                header("Location: ../modifes.php?error=invalidphone");
                exit();
            }
            $sql = "UPDATE citizen SET phone_number = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../modifies.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $newphone, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($newEmail)) {
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                header("Location: ../modifes.php?error=invalidemail");
                exit();
            }
            $sql = "UPDATE citizen SET Email = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../modifies.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $newEmail, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        /*if(!empty($newplate))
        {
            $sqlnewplate="UPDATE citizen SET Plate_number = '$newplate' WHERE National_id ='$national_id'";
            $resultnewplate=mysqli_query($conn,$sqlnewplate);
        }*/
        header("Location: ../1_Dashboard.php");
    }
}
