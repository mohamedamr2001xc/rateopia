<?php
require 'database.php'
?>

<?php
if (isset($_POST['submit'])) {
    session_start();
    $nationalid = $_SESSION['National_id'];
    if (isset($_POST['submit']) && isset($_FILES['my_image'])) {
        require 'image_database.php';
        echo "<pre>";
        print_r($_FILES['my_image']);
        echo "</pre>";
        $img_name = $_FILES['my_image']['name'];
        $img_size = $_FILES['my_image']['size'];
        $tmp_name = $_FILES['my_image']['tmp_name'];
        $error = $_FILES['my_image']['error'];
        if ($error === 0) {
            if ($img_size > 125000 * 16) {
                $em = "Filetoolarge";
                header("Location: ../image_upload.php?error=$em");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $allowed_exc = array("jpg", "jpeg", "png");
                $allowed_exc2 = array("mp4", "WMV", "WEBM");
                if (in_array($img_ex, $allowed_exc)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $sql = "SELECT National_id FROM images WHERE image_url LIKE ?";
                    /*$result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $row2 = $row["National_id"];*/
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../moreinfo.php?error=sqlerror");
                        exit();
                    } else {
                        $empty = '';
                        mysqli_stmt_bind_param($stmt, "s", $empty);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);
                        $row2 = $row['National_id'];
                    }
                    #$createfolder=mkdir("../uploads/".$row2);
                    #$img_upload_path="../face_rec/images/".$row2.".".$img_ex_lc;
                    $img_upload_path = "../face_rec/faces/" . $row2 . "." . "jpg";
                    move_uploaded_file($tmp_name, $img_upload_path);
                    $img_upload_path2 = "../../projects/face_rec/faces/" . $row2 . "." . "jpg";
                    copy($img_upload_path, $img_upload_path2);

                    /*$sql = "UPDATE images SET image_url = '$new_img_name' WHERE National_id = $row2";
                    mysqli_query($conn, $sql);*/
                    $sql = "UPDATE images SET image_url = ? WHERE National_id = ?";
                    $stmt = mysqli_stmt_init($conn);
                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../moreinfo.php?error=sqlerror");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "ss", $new_img_name, $row2);
                        mysqli_stmt_execute($stmt);
                    }
                    #header("Location: ../moreinfo.php");
                    #mysqli_close($conn);
                } else {
                    $em = "you cant upload files of this type";
                    header("Location: ../image_upload.php?error=$em");
                }
            }
        } else {

            $em = "unknownerroroccured";
            header("Location: ../image_upload.php?error=$em");
        }
    }
    $hairtexture = $_POST['hair_texture'];
    $haircolor = $_POST['hair_color'];
    $beard = $_POST['beard'];
    $skincolor = $_POST['skin_color'];
    $height = $_POST['height'];
    $eyecolor = $_POST['eye_color'];
    $weight = $_POST['weight'];
    if (!is_numeric($weight) || !is_numeric($height)) {
        header("Location: ../moreinfo.php?error=incorrectinput");
    }
    if (empty($hairtexture) || empty($haircolor) || empty($beard) || empty($skincolor) || empty($eyecolor) || empty($height) || empty($weight)) {
        header("Location: ../moreinfo.php?error=emptyfields");
    } else {
        $sql = "SELECT * From citizen Where id  = ? ";
        $Stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../moreinfo.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $nationalid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);
            $row2 = $row['National_id'];
        }
        /*$result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $row2 = $row["National_id"];*/
        $sql = "UPDATE physical_features SET hair_texture = ? , hair_color= ? , beard_style= ? , skin_color= ? , height= ? , eye_color= ? , weightt= ?	WHERE National_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../moreinfo.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "ssssssss", $hairtexture, $haircolor, $beard, $skincolor, $height, $eyecolor, $weight, $row2);
            mysqli_stmt_execute($stmt);
        }

        header("Location: ../2_Dashboard.php");
    }
} else {
    header("Location: ../moreinfo.php?error=errorexist");
}
?>