<?php
session_start();
require 'database.php';
$national_id=$_SESSION['National_id2']; //national id of accused
$reporter=$_SESSION['reporter']; //national id of reporter






if(isset($_FILES['my_image']) )
{
    
echo"<pre>";
print_r($_FILES['my_image']);
echo"</pre>";
$img_name=$_FILES['my_image']['name'];
$img_size=$_FILES['my_image']['size'];
$tmp_name=$_FILES['my_image']['tmp_name'];
$error=$_FILES['my_image']['error'];
if($error===0)
{
    if($img_size >125000*80){
        $em="Filetoolarge";
        header("Location: ../image_upload.php?error=$em");
    
    }
    else{
        $img_ex=pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);
        $allowed_exc=array("jpg","jpeg","png","mp4","WMV","WEBM","pdf","docx","txt","zip","pptx");
        $allowed_exc2=array("mp4","WMV","WEBM");

        if(5==5)
        {
            
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
            mysqli_close($conn);

        }
        else
        {
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




?>