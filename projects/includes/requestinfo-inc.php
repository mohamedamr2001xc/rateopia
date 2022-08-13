<?php
require 'database.php';
if (isset($_POST['submit'])) {
    $nationalid = $_POST['National_id'];
    $companyname = $_POST['cName'];
    $email = $_POST['cEmail'];
    $email = mysqli_real_escape_string($conn, $email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../0_requestinfo.php?error=invalidemail");
        exit();
    }
    if (!is_numeric($nationalid) || strlen($nationalid) != 14) {
        header("Location: ../0_requestinfo.php?error=invalidnationalid");
        exit();
    }
    $companynamecheck = str_replace(' ', '', $companyname);
    $companynamecheck = mysqli_real_escape_string($conn, $companynamecheck);
    if (!ctype_alpha($companynamecheck)) {
        header("Location: ../0_requestinfo.php?error=invalidcompanyname");
        exit();
    }
    $sql = "INSERT INTO companies_inquiry (National_id,email,company_name,submit_date) VALUES (?,?,?,NOW())";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../0_requestinfo.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "sss", $nationalid, $email, $companyname);
        mysqli_stmt_execute($stmt);
    }
    header("Location: ../0_requestinfo.php");
}
