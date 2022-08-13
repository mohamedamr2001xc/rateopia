<?php
require 'database.php';
session_start();
$national_id=$_SESSION['National_id2']; //national id of accused
$reporter=$_SESSION['reporter']; //national id of reporter
/*$sql="INSERT INTO reports (report_rating,report_info,document,National_id_1,reportsNational_id_2) Values
(NULL,NULL,NULL,'$reporter','$national_id') ";*/
if(isset($_POST['submit'])){
    echo "hello";
    echo"<pre>";
    print_r($_FILES['my_image']);
    echo"</pre>";
    $img_name=$_FILES['my_image']['name'];
    $img_size=$_FILES['my_image']['size'];
    $tmp_name=$_FILES['my_image']['tmp_name'];
    $error=$_FILES['my_image']['error'];
    if(5==5){
        if($img_size >125000*80){
            $em="Filetoolarge";
            header("Location: ../image_upload.php?error=$em");
        }
        else{
            $img_ex=pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exc=array("jpg","jpeg","png","mp4","WMV","WEBM","pdf","docx","txt","zip","pptx","MP4");
            $allowed_exc2=array("mp4","WMV","WEBM");
            if(in_array( $img_ex, $allowed_exc) ){ 
                $new_img_name=uniqid("Doc-",true).'.'.$img_ex_lc;
                $sql="SELECT reportid FROM reports WHERE report_info ='' ";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $row2=$row['reportid'];
                $createfolder=mkdir("../uploads/".$row2);
                #$img_upload_path="../face_rec/images/".$row2.".".$img_ex_lc;
                $img_upload_path="../uploads/".$row2."/".$new_img_name;
                move_uploaded_file($tmp_name,$img_upload_path);
                $sql="UPDATE reports SET document = '$new_img_name' WHERE reportid = '$row2'";
                mysqli_query($conn,$sql);
                echo $national_id;
                header("Location: ../makereport.php?subject=$national_id");
                //mysqli_close($conn);
            }
            else{
                $em="you cant upload files of this type";
                header("Location: ../image_upload.php?error=$em");
            }
        }
    }
    else{
        $em="unknownerroroccured";
        header("Location: ../image_upload.php?error=$em");
    }
}
if (isset($_POST['submit'])) {
    $message=$_POST['message'];
    $rate=$_POST['rating']/20;
    $checkbox=$_POST['Authorities'];
    if(empty($message) && empty($rate)){
        header("Location: ../Report.php?error=emptyfields");
    }
    else{
        $sql="UPDATE reports set urgent='$checkbox',datee=NOW(),time=TIME (NOW()), report_info='$message',report_rating='$rate' where report_info=''";
        mysqli_query($conn,$sql);
        $sql="UPDATE citizen set Rating=(Rating+'$rate')/2 where National_id='$national_id'";
        mysqli_query($conn,$sql);
        header("Location: ../1_Dashboard.php");
        echo $checkbox;
    }
}
?>