<?php
session_start();
require 'database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if(isset($_POST['submit'])){
    if(isset($_POST['new_job'])){
        $newjob=$_POST['new_job'];
    }
    $newEmail=$_POST['new_Email'];
    $newphone=$_POST['new_phone'];
    if( empty($newphone) && empty($newjob) && empty($newEmail)){
       
        header("Location: ../modifes.php?error=emptyfeilds");
    }
    else{
        $national_id=$_SESSION['National_id'];
        //send email if any modifes of user we must make it fuction
        /*if(!empty($newAddress))
        {
            //must check if the address esixts
            $sqlnewAddress="UPDATE citizen SET Adress = '$newAddress' WHERE National_id ='$national_id'";
            $resultnewAddress=mysqli_query($conn,$sqlnewAddress);
        }*/
        if(!empty($newjob)){
            $sqlnewjob="UPDATE citizen SET Job = '$newjob' WHERE National_id ='$national_id'";
            $resultnewjob=mysqli_query($conn,$sqlnewjob);
        }
        if(!empty($newphone)){
            $sqlnewjob="UPDATE citizen SET phone_number = '$newphone' WHERE National_id ='$national_id'";
            $resultnewjob=mysqli_query($conn,$sqlnewjob);
        }
        if(!empty($newEmail)){
            $sqlnewEmail="UPDATE citizen SET Email = '$newEmail' WHERE National_id ='$national_id'";
            $resultnewEmail=mysqli_query($conn,$sqlnewEmail);
        }
        /*if(!empty($newplate))
        {
            $sqlnewplate="UPDATE citizen SET Plate_number = '$newplate' WHERE National_id ='$national_id'";
            $resultnewplate=mysqli_query($conn,$sqlnewplate);
        }*/
        header("Location: ../1_Dashboard.php");
    }
}
?>
