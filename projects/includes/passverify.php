<?php
session_start();
$verficationcode = $_SESSION['password'];
$nationalid = $_SESSION['National_id'];
echo $verficationcode;
echo "<br>";
echo $nationalid;
echo "<br>";
if (isset($_POST['submit'])) {
    require 'database.php';
    $userverify = $_POST['verfication'];
    $newpassword = $_POST['newpassword'];
    $yarbpass = $newpassword;
    $newpasswordconfirm = $_POST['confirmpassword'];
    if ($verficationcode != $userverify) {
        header("Location: ../verfication.php?subject=wrongverficationcode");
    } elseif ($newpassword != $newpasswordconfirm) {
        header("Location: ../verfication.php?subject=passwordsdontmatch");
    } else {
        if (preg_match('/[A-Z]/', $newpassword) && strlen($newpassword) > 15 && strpos($newpassword, " ") == false && (strpos($newpassword, "@") || strpos($newpassword, "#") || strpos($newpassword, "!") || strpos($newpassword, "%"))) {


            echo $newpassword;
            echo $nationalid;
            echo $verficationcode;
            $newpassword = hash('sha256', $newpassword);
            $sql = "UPDATE citizen SET passwordd='$newpassword' WHERE National_id='$nationalid';";
            mysqli_query($conn, $sql);
            header("Location: logout.php");
        } else {
            header("Location: ../verfication.php?subject=1");
        }
    }
}
