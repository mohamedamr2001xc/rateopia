<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if(isset($_POST['submit']))
{
    $username=$_POST['name'];
    $useremail=$_POST['mail'];
    $usermessage=$_POST['message'];
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'rateopia@gmail.com';                     //SMTP username
        $mail->Password   = 'kosmmostafamohamed1234';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom($useremail   , $username);
        $mail->addAddress('rateopia@gmail.com');     //Add a recipient       
        $mail->addReplyTo($useremail, 'reply');
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->addCustomHeader($useremail, "<".$useremail.">");
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


?>