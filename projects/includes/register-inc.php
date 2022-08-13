<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
if(isset($_POST['submit']))
{
    //add database connection
     require 'database.php';
     $username=$_POST['Name'];
     $birthdate=$_POST['Age'];
     $longitude=$_POST['lng'];
     $ladtitude=$_POST['lat'];
     $email=$_POST['Email'];
     $job=$_POST['Job'];
     $nationalid=$_POST['National_id'];
     $today=date("Y-m-d");
     $age1=date_diff(date_create($birthdate),date_create($today));
     $age=$age1->format('%y');
     $string="abcdefghijklmnopqrstuvwxyz0123456789@#$%&!";
     $password=substr(str_shuffle($string),0,14);
     $rating=4;
     $plate_number=$_POST['Plate_number'];
     $phonenumber=$_POST['phone_number'];
     //send mail
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
    $mail->setFrom('rateopia@gmail.com', 'rateopia');
    $mail->addAddress($email);     //Add a recipient       
    $mail->addReplyTo('noreplyrateopia@gmail.com', 'noreply');
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Register user info';
    $mail->Body    = "Name: ".$username."<br>"."Age: ".$age."<br>"."National id : ".$nationalid."<br>" ."plate number : ". $plate_number."<br>". "job: ".$job."<br>"."Email: ".$email."<br>"."<br>"."Password (This is a randomly generated password please change as soon as possible): ".$password."<br>";
    $mail->AltBody = $password;
    $mail->send();
    echo 'Message has been sent';
    } catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    if(empty($username) || empty($birthdate) || empty($ladtitude) || empty($longitude) || empty($email) || empty($job) || empty($nationalid) ){
        header("Location: ../register.php?error=emptyfields");
    }
    elseif($age <=0)
    {
        header("Location: ../register.php?error=youarentbornyet");
        exit();
    }
    else{
        $sql="SELECT National_id from citizen WHERE National_id=? ";
        $stmt=mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)){
            header("Location: ../register.php?error=sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt,"s",$nationalid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $rowCount=mysqli_stmt_num_rows($stmt);
            if($rowCount>0){
                header("Location: ../register.php?error=nationalidexists");
                exit();
            }
            else{
                $sql="INSERT INTO citizen (Age, Rating, National_id, Name, Job, Plate_number, Email, phone_number, lng , lat, passwordd) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";
                $stmt=mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    header("Location: ../register.php?error=sqlerror");
                    exit();
                }
                else{
                    
                    mysqli_stmt_bind_param($stmt,"sssssssssss",$age, $rating, $nationalid, $username, $job, $plate_number, $email,$phonenumber, $longitude,$ladtitude,$password );
                    mysqli_stmt_execute($stmt);
                    $sql = "INSERT INTO images (id,image_url,National_id) VALUES (NULL, '', $nationalid)";
                    $sql6="INSERT INTO physical_features (height, weightt, skin_color, face_shape, forehead_set, forehead_size, unique_features, cheekbones, eye_shape, eye_size, eye_setting, eye_lashes, eye_brows, nose_shape, nose_profile, nose_width, nose_length, ear_size, ear_loops, ear_distance_from_head, upper_lip, lower_lip, teeth_size, chin_shape, chin_prominence, face_scar, physical_id, National_id) VALUES ('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', NULL, $nationalid)";
                    $result6=mysqli_query($conn,$sql6);
                    //$row6=mysqli_fetch_assoc($result6);
                    if(mysqli_query($conn, $sql))
                    {
                        header("Location: ../moreinfo.php");
                    }
                    else
                    {
                        header("Location: ../register.php?error=sqlerror");
                    }
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    //mysqli_close($conn);   
}

?>