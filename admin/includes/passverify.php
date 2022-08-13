<?php
session_start();
$verficationcode=$_SESSION['password'];
$nationalid=$_SESSION['National_id'];
echo $verficationcode;
echo "<br>";
echo $nationalid;
echo"<br>";
if(isset($_POST['submit'])){
    require 'database.php';
    $userverify=$_POST['verfication'];
    $newpassword=$_POST['newpassword'];
    $yarbpass=$newpassword;
    $newpasswordconfirm=$_POST['confirmpassword'];
    if($verficationcode != $userverify){
        header("Location: ../verfication.php?wrongverficationcode");
    }
    elseif($newpassword !=$newpasswordconfirm){
        header("Location: ../verfication.php?error=passwordsdontmatch");   
    }
    else{
        echo $newpassword;
        echo $nationalid;
        echo $verficationcode;
        $sql="UPDATE citizen SET passwordd='$newpassword' WHERE National_id='$nationalid';";
        mysqli_query($conn,$sql);
        header("Location: ../0_login.php");
    }
}
?>