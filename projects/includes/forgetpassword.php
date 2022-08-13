<?php
session_start();
require_once 'database.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if (isset($_POST['submit'])) {
    $nationalid = $_POST['National_id'];
    echo $nationalid;
    $email = $_POST['Email'];
    $string = "0123456789";
    $password = substr(str_shuffle($string), 0, 8);
    $_SESSION['password'] = $password;
    if (empty($nationalid) || empty($email)) {
        header("Location: ../change.php?error=emptyfeilds");
    } else {
        $sql = "SELECT National_id FROM citizen WHERE id= $nationalid";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row['National_id'] == "") {
            header("Location: ../change.php?error=nationaliddeosntexist");
        }
        $sql2 = "SELECT Email FROM citizen WHERE id= $nationalid";
        $result2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($result2);
        if ($row2['Email'] != $email) {
            header("Location: ../change.php?error=emaildeosntmatch");
        } else {
            $_SESSION['National_id'] = $row["National_id"];
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
                $mail->setFrom('rateopia@gmail.com', 'rateopia');
                $mail->addAddress($email);     //Add a recipient       
                $mail->addReplyTo('noreplyrateopia@gmail.com', 'noreply');

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = 'New password';
                $mail->Body    = "Your verfication code : " . $password;
                $mail->AltBody = $password;
                $mail->send();
                header("Location: ../verfication.php?subject=");
                echo "<br>";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
