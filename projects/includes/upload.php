
<?php
if(isset($_POST['submit']) && isset($_FILES['my_image']) )
{
    require 'image_database.php';
    echo"<pre>";
    print_r($_FILES['my_image']);
    echo"</pre>";
    $img_name=$_FILES['my_image']['name'];
    $img_size=$_FILES['my_image']['size'];
    $tmp_name=$_FILES['my_image']['tmp_name'];
    $error=$_FILES['my_image']['error'];
    if($error===0){
        if($img_size >125000*4){
            $em="Filetoolarge";
            header("Location: ../image_upload.php?error=$em");    
        }
        else{
            $img_ex=pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exc=array("jpg","jpeg","png");
            $allowed_exc2=array("mp4","WMV","WEBM");
            if(in_array($img_ex,$allowed_exc)){
                $new_img_name=uniqid("IMG-",true).'.'.$img_ex_lc;
                $sql="SELECT National_id FROM images WHERE image_url LIKE ''";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $row2=$row["National_id"];
                #$createfolder=mkdir("../uploads/".$row2);
                #$img_upload_path="../face_rec/images/".$row2.".".$img_ex_lc;
                $img_upload_path="../face_rec/faces/".$row2."."."jpg";
                move_uploaded_file($tmp_name,$img_upload_path);                
                $sql="UPDATE images SET image_url = '$new_img_name' WHERE National_id = $row2";
                mysqli_query($conn,$sql);
                header("Location: ../moreinfo.php");
                mysqli_close($conn);
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
?>