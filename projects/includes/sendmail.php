<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'database.php';

if (isset($_POST['submit'])) {
    $username = $_POST['name'];
    $useremail = $_POST['mail'];
    $usermessage = $_POST['message'];
    $username = mysqli_real_escape_string($conn, $username);
    $useremail = mysqli_real_escape_string($conn, $useremail);
    $usermessage = mysqli_real_escape_string($conn, $usermessage);
    $usernamecheck = str_replace(' ', '', $username);
    $useremailcheck = str_replace(' ', '', $useremail);
    $usermessagecheck = str_replace(' ', '', $usermessage);
    if (!ctype_alpha($usernamecheck) || !ctype_alnum($usermessagecheck) || !filter_var($useremailcheck, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../0_ContactUs.php");
        exit();
    }
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'rateopia@gmail.com';                     //SMTP username
        $mail->Password   = 'iiqmwhsetgxkhnfy';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($useremail, $username);
        $mail->addAddress('rateopia@gmail.com');     //Add a recipient       
        $mail->addReplyTo($useremail, 'reply');

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->addCustomHeader($useremail, "<" . $useremail . ">");
        $mail->Subject = 'user inquiry';
        $mail->Body    = $usermessage;
        $mail->AltBody = "User inquiry";

        $mail->send();

        /*header("Location: ../sendsuccess.html");*/
        header("Location: ../0_ContactUs.php");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
