<?php
require 'database.php';
if (isset($_POST['submit'])) {
    $type = $_POST['type'];
    $details = $_POST['details'];
    $type = mysqli_real_escape_string($conn, $type);
    $details = mysqli_real_escape_string($conn, $details);
    $sql = "INSERT INTO `suggestions`(incident_name, details) VALUES (?,?)";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../1_suggestion.php");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ss", $type, $details);
        mysqli_stmt_execute($stmt);
    }
    header("Location: ../1_suggestion.php");
}
