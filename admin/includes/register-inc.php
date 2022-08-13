<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function validateDate($date, $format = 'm-d-y')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if (isset($_POST['submit'])) {
    session_start();

    $id = $_SESSION['ID'];

    //add database connection
    require 'database.php';

    $sql = "INSERT INTO monitor (id,event_type,datee) values (?,'register users',NOW())";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../2_login.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
    }
    $sql = "SELECT * from monitor where id='$id' AND datee > (NOW() - INTERVAL 1 Minute)";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 20) {
        $sql = "INSERT INTO blocked_users (userid) values '$id'  ";
        mysqli_query($conn, $sql);
        header("Location: ../2_login.php?error=blockeduser");
        exit();
    }


    $username = $_POST['Name'];
    $usernamecheck = str_replace(' ', '', $username);
    $usernamecheck = mysqli_real_escape_string($conn, $usernamecheck);
    if (!ctype_alpha($usernamecheck) || strlen($username) < 10) {
        header("Location: ../register.php?error=invalidusername");
        echo $usernamecheck;
        exit();
    }
    $birthdate = $_POST['Age'];
    $birthdatecheck = str_replace('/', '', $birthdate);
    $birthdatecheck = str_replace('-', '', $birthdatecheck);
    $birthdatecheck = mysqli_real_escape_string($conn, $birthdatecheck);
    if (!is_numeric($birthdatecheck)) {
        #header("Location: ../register.php?error=9");
        echo $birthdatecheck;
        exit();
    }
    $longitude = $_POST['lng'];
    $ladtitude = $_POST['lat'];
    $lngcheck = str_replace('.', '', $longitude);
    $latcheck = str_replace('.', '', $ladtitude);
    $lngcheck = str_replace('-', '', $lngcheck);
    $latcheck = str_replace('-', '', $latcheck);
    $latcheck = mysqli_real_escape_string($conn, $latcheck);
    $lngcheck = mysqli_real_escape_string($conn, $lngcheck);
    if (!is_numeric($lngcheck) || !is_numeric($latcheck) || substr_count($longitude, '.') != 1 || substr_count($longitude, '.') != 1) {
        header("Location: ../register.php?error=3");
        exit();
    }

    $email = $_POST['Email'];
    $email = mysqli_real_escape_string($conn, $email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../register.php?error=4");
        exit();
    }


    $job = $_POST['Job'];
    $jobcheck = str_replace(' ', '', $job);
    $jobcheck = strtolower($jobcheck);
    $job = mysqli_real_escape_string($conn, $job);
    if ($job != "") {
        if (!ctype_alpha($jobcheck) || str_contains($jobcheck, 'select') || str_contains($jobcheck, 'insert') || str_contains($jobcheck, 'update') || str_contains($jobcheck, 'delete')) {
            header("Location: ../register.php?error=5");

            exit();
        }
    }

    $nationalid = $_POST['National_id'];
    $_SESSION['National_id'] = $nationalid;

    if (!is_numeric($nationalid) || strlen($nationalid) != 14) {
        header("Location: ../register.php?error=6");
        exit();
    }
    $today = date("Y-m-d");
    $age1 = date_diff(date_create($birthdate), date_create($today));
    $age = $age1->format('%y');
    if ($age <= 0) {
        header("Location: ../register.php?error=2");
        exit();
    }
    $string = "abcdefghijklmnopqrstuvwxyz0123456789@#$%&!";
    $password = substr(str_shuffle($string), 0, 14);

    $rating = 5;
    $plate_number = $_POST['Plate_number'];
    if ($plate_number != "") {
        if (!ctype_alnum($plate_number) || strlen($plate_number) != 6) {
            header("Location: ../register.php?error=8");
            exit();
        }
    }

    $phonenumber = $_POST['phone_number'];
    if (!is_numeric($phonenumber) || strlen($phonenumber) != 11) {
        header("Location: ../register.php?error=7");
        exit();
    }
    //send mail
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
        $mail->Subject = 'Register user info';
        $mail->Body    = "Name: " . $username . "<br>" . "Age: " . $age . "<br>" . "National id : " . $nationalid . "<br>" . "plate number : " . $plate_number . "<br>" . "job: " . $job . "<br>" . "Email: " . $email . "<br>" . "<br>" . "Password (This is a randomly generated password please change as soon as possible): " . $password . "<br>";
        $mail->AltBody = $password;
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    if (empty($username) || empty($birthdate) || empty($ladtitude) || empty($longitude) || empty($email)  || empty($nationalid)) {
        header("Location: ../register.php?error=emptyfields");
    } else {
        $sql = "SELECT National_id from citizen WHERE id=? ";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../register.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $nationalid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            $rowCountnational = mysqli_stmt_num_rows($stmt);
            $sql = "SELECT * FROM citizen where phone_number='$phonenumber'";
            $result = mysqli_query($conn, $sql);
            $rowCountphonenum = mysqli_num_rows($result);
            $sql = "SELECT * FROM citizen where Plate_number='$plate_number'";
            $result = mysqli_query($conn, $sql);
            $rowCountplate = mysqli_num_rows($result);
            $sql = "SELECT * FROM citizen where Email='$email'";
            $result = mysqli_query($conn, $sql);
            $rowCountemail = mysqli_num_rows($result);
            if ($rowCountnational > 0) {
                header("Location: ../register.php?error=nationalidexists");
                exit();
            } elseif ($rowCountphonenum > 0) {
                header("Location: ../register.php?error=phoneexists");
                exit();
            } elseif ($rowCountplate > 0 && $plate_number != '') {
                header("Location: ../register.php?error=platenumberexists");
                exit();
            } elseif ($rowCountemail > 0) {
                header("Location: ../register.php?error=emailsexists");
                exit();
            } else {
                $sql = "INSERT INTO citizen (Age, Rating, id, Name, Job, Plate_number, Email, phone_number, lng , lat, passwordd) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../register.php?error=sqlerror");
                    exit();
                } else {
                    $password = hash('sha256', $password);
                    mysqli_stmt_bind_param($stmt, "sssssssssss", $age, $rating, $nationalid, $username, $job, $plate_number, $email, $phonenumber, $longitude, $ladtitude, $password);
                    mysqli_stmt_execute($stmt);
                    $sql = "SELECT National_id FROM citizen WHERE id=?";
                    $Stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../register.php?error=sqlerror");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $nationalid);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        $nationalid = $row['National_id'];
                    }
                    #$sql = "INSERT INTO images (image_url,National_id) VALUES ( '', $nationalid)";
                    $sql = "INSERT INTO images (image_url,National_id) VALUES ( ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../register.php?error=sqlerror");
                        exit();
                    } else {
                        $empty = '';
                        mysqli_stmt_bind_param($stmt, "ss", $empty, $nationalid);
                        mysqli_stmt_execute($stmt);
                    }

                    $sql = "INSERT INTO physical_features (height, weightt, skin_color, face_shape, forehead_set, forehead_size, unique_features, cheekbones, eye_shape, eye_size, eye_setting, eye_lashes, eye_brows, nose_shape, nose_profile, nose_width, nose_length, ear_size, ear_loops, ear_distance_from_head, upper_lip, lower_lip, teeth_size, chin_shape, chin_prominence, face_scar, National_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../register.php?error=sqlerror");
                        exit();
                    } else {
                        $empty = '';
                        mysqli_stmt_bind_param($stmt, "sssssssssssssssssssssssssss", $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $empty, $nationalid);
                        mysqli_stmt_execute($stmt);
                    }


                    header("Location: ../moreinfo.php");
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
