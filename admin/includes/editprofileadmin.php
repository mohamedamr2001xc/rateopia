<?php
require 'database.php';
session_start();
$id = $_SESSION['ID'];
$sql = "INSERT INTO monitor (id,event_type,datee) values (?,'edit citizens',NOW())";
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
$national_id = $_SESSION['National_id'];

echo $national_id . "<br>";
if (/*isset($_POST['submit']) &&*/isset($_FILES['my_image'])  && $_FILES['my_image']['size'] != 0) {
    if ($_POST['csrf'] != $_SESSION['csrf']) {
        header("Location: logout.php");
        exit();
    }
    require 'image_database.php';
    echo "<pre>";
    print_r($_FILES['my_image']);
    echo "</pre>";
    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];
    if ($error === 0) {
        if ($img_size > 125000 * 4) {
            $em = "Filetoolarge";
            header("Location: ../image_upload.php?error=$em");
        } else {
            $filepointer = "../face_rec/faces/" . $national_id . ".jpg";
            echo $filepointer;
            if (unlink($filepointer)) {
                echo "success";
            } else {
                header("Location: ../2_editprofile.php?error=imagefailedtoupload&id=$national_id");
            }
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exc = array("jpg", "jpeg", "png");
            $allowed_exc2 = array("mp4", "WMV", "WEBM");
            if (in_array($img_ex, $allowed_exc)) {
                $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                $row2 = $national_id;
                #$createfolder=mkdir("../uploads/".$row2);
                #$img_upload_path="../face_rec/images/".$row2.".".$img_ex_lc;
                $img_upload_path = "../face_rec/faces/" . $row2 . "." . "jpg";
                move_uploaded_file($tmp_name, $img_upload_path);
                $img_upload_path2 = "../../projects/face_rec/faces/" . $row2 . "." . "jpg";
                copy($img_upload_path, $img_upload_path2);

                $sql = "UPDATE images SET image_url = ? WHERE National_id = ?";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../2_searchprofile.php?error=sqlerror");
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "ss", $new_img_name, $row2);
                    mysqli_stmt_execute($stmt);
                }
                /*$img_upload_path="../../projects/face_rec/faces/".$row2."."."jpg";*/
                /*header("Location: ../moreinfo.php");*/
                /*mysqli_close($conn);*/
            } else {
                $em = "you cant upload files of this type";
                /*header("Location: ../image_upload.php?error=$em");*/
            }
        }
    } else {
        $em = "unknownerroroccured";
        header("Location: ../image_upload.php?error=$em");
    }
    header("Location: ../2_searchprofile.php");
}
if (isset($_POST['submit'])) {
    if ($_POST['csrf'] != $_SESSION['csrf']) {
        header("Location: ../2_searchprofile.php");
        exit();
    }
    if (isset($_POST['job'])) {
        $newjob = $_POST['job'];
    }
    if (isset($_POST['hair_texture'])) {
        $hair_texture = $_POST['hair_texture'];
    }
    if (isset($_POST['hair_color'])) {
        $hair_color = $_POST['hair_color'];
    }
    if (isset($_POST['beard'])) {
        $beard = $_POST['beard'];
    }
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $lang = $_POST['lng'];
    $latitude = $_POST['lat'];
    $plate = $_POST['plate_number'];
    $newEmail = $_POST['email'];
    $newphone = $_POST['phone_number'];
    if (empty($newphone) && empty($newjob) && empty($newEmail) && empty($name) && empty($rating) && empty($lang) && empty($latitude) && empty($plate) && empty($hair_texture) && empty($hair_color) && empty($beard) && empty($weight) && empty($height)  && $_FILES['my_image']['size'] == 0) {
        header("Location: ../2_editprofile.php?error=2&id=$national_id");
    } else {
        //send email if any modifes of user we must make it fuction
        /*if(!empty($newAddress))
        {
            //must check if the address esixts
            $sqlnewAddress="UPDATE citizen SET Adress = '$newAddress' WHERE National_id ='$national_id'";
            $resultnewAddress=mysqli_query($conn,$sqlnewAddress);
        }*/
        if (!empty($name)) {
            $namecheck = str_replace(' ', '', $name);
            $namecheck = strtolower($name);
            if (strlen($name) < 13 || !ctype_alpha($namecheck) || str_contains($namecheck, 'select') || str_contains($namecheck, 'insert') || str_contains($namecheck, 'update') || str_contains($namecheck, 'delete')) {
                header("Location: ../2_editprofile.php?error=1&id=$national_id");
                exit();
            }
            $sqlname = "UPDATE citizen SET Name = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $name, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($rating)) {
            if (!is_numeric($rating) || $rating < 0) {
                header("Location: ../2_editprofile.php?error=2&id=$national_id");
                exit();
            }
            $sql = "UPDATE citizen SET Rating = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $rating, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($lang)) {
            $langcheck = str_replace('.', '', $lang);
            if (!str_contains($lang, '.') || !is_numeric($langcheck)) {
                header("Location: ../2_editprofile.php?error=3&id=$national_id");
                exit();
            }

            $sql = "UPDATE citizen SET lng = ? WHERE National_id =?'";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $lang, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($latitude)) {
            $latitudecheck = str_replace('.', '', $latitude);
            if (!str_contains($latitude, '.') || !is_numeric($latitudecheck)) {
                header("Location: ../2_editprofile.php?error=4&id=$national_id");
                exit();
            }

            $sql = "UPDATE citizen SET lat = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $latitude, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($plate)) {
            if (!ctype_alnum($plate) || strlen($plate) != 6) {
                header("Location: ../2_editprofile.php?error=5&id=$national_id");
                exit();
            }
            echo $plate . "<br>";
            $sqlplate = "SELECT * FROM citizen where Plate_number ='$plate'";
            $resultplate = mysqli_query($conn, $sqlplate);
            $rowcountplate = mysqli_num_rows($resultplate);
            echo $rowcountplate . "<br>";
            if ($rowcountplate != 0) {
                header("Location: ../2_editprofile.php?error=13&id=$national_id");
                exit();
            }
            $sql = "UPDATE citizen SET Plate_number = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $plate, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($hair_texture)) {
            if (!($hair_texture == 'bald' || $hair_texture == 'straight' || $hair_texture == 'wavy' || $hair_texture == 'curly')) {
                header("Location: ../2_editprofile.php?error=7&id=$national_id");
                exit();
            }

            $sql = "UPDATE physical_features SET hair_texture = ? WHERE National_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $hair_texture, $national_id);
                mysqli_stmt_execute($stmt);
            };
        }
        if (!empty($hair_color)) {
            if (!($hair_color == 'black' || $hair_color == 'light Brown' || $hair_color == 'dark brown' || $hair_color == 'blond' || $hair_color == 'White')) {
                header("Location: ../2_editprofile.php?error=8&id=$national_id");
                exit();
            }
            $sql = "UPDATE physical_features SET hair_color = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $hair_color, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($beard)) {
            if (!($beard == 'No Beard' || $beard == 'Long Beard' || $beard == 'Normal Beard' || $beard == 'Short Beard')) {
                header("Location: ../2_editprofile.php?error=9&id=$national_id");
                exit();
            }
            $sql = "UPDATE physical_features SET beard_style = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $beard, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($weight)) {
            if (!is_numeric($weight) || $weight < 0) {
                header("Location: ../2_editprofile.php?error=9&id=$national_id");
                exit();
            }
            $sql = "UPDATE physical_features SET weightt = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $weight, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($height)) {
            if (!is_numeric($height) || $height < 0) {
                header("Location: ../2_editprofile.php?error=10&id=$national_id");
                exit();
            }
            $sql = "UPDATE physical_features SET height = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $height, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($newEmail)) {
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                header("Location: ../2_editprofile.php?error=11&id=$national_id");
                exit();
            }
            $sql = "SELECT * FROM citizen where Email='$newEmail'";
            $result = mysqli_query($conn, $sql);
            $rowcount = mysqli_num_rows($result);

            if ($rowcount != 0) {
                header("Location: ../2_editprofile.php?error=13&id=$national_id");
                exit();
            }
            $sql = "UPDATE citizen SET Email = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $newEmail, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($newjob)) {
            $newjobcheck = str_replace(' ', '', $newjob);
            $newjobcheck = strtolower($newjob);
            if (!ctype_alpha($newjobcheck) || str_contains($newjobcheck, 'select') || str_contains($newjobcheck, 'insert') || str_contains($newjobcheck, 'update') || str_contains($newjobcheck, 'delete')) {
                header("Location: ../2_editprofile.php?error=12&id=$national_id");
                exit();
            }
            $sql = "UPDATE citizen SET Job = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $newjob, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($newphone)) {
            if (!is_numeric($newphone) || strlen($newphone) != 11) {
                header("Location: ../2_editprofile.php?error=13&id=$national_id");
                exit();
            }
            $sql = "SELECT * FROM citizen where phone_number='$newphone'";
            $result = mysqli_query($conn, $sql);
            $rowcount = mysqli_num_rows($result);

            if ($rowcount != 0) {
                header("Location: ../2_editprofile.php?error=13&id=$national_id");
                exit();
            }
            $sql = "UPDATE citizen SET phone_number = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $newphone, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        if (!empty($plate)) {
            echo $plate . "<br>";
            $sqlplate = "SELECT * FROM citizen where Plate_number ='$plate'";
            $resultplate = mysqli_query($conn, $sqlplate);
            $rowcountplate = mysqli_num_rows($resultplate);
            echo $rowcountplate . "<br>";
            if ($rowcountplate != 0) {
                header("Location: ../2_editprofile.php?error=13&id=$national_id");
                exit();
            }
            if (!ctype_alnum($plate) || strlen($plate) != 6) {
                header("Location: ../2_editprofile.php?error=14&id=$national_id");
                exit();
            }
            $sql = "UPDATE citizen SET Plate_number = ? WHERE National_id =?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_searchprofile.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "ss", $plate, $national_id);
                mysqli_stmt_execute($stmt);
            }
        }
        /*header("Location: ../Dashboard.php");*/
        header("Location: ../2_searchprofile.php");
    }
}
if (isset($_POST['delete'])) {
    if ($_POST['csrf'] != $_SESSION['csrf']) {
        header("Location: ../2_searchprofile.php");
        exit();
    }
    $sql = "DELETE  FROM reports WHERE National_id_1=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../register.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $national_id);
        mysqli_stmt_execute($stmt);
    }
    $sql = "DELETE FROM reports WHERE reportsNational_id_2=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../register.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $national_id);
        mysqli_stmt_execute($stmt);
    }
    $sql = "DELETE  FROM physical_features WHERE National_id=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../register.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $national_id);
        mysqli_stmt_execute($stmt);
    }
    $sql = "DELETE  FROM images WHERE National_id=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../register.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $national_id);
        mysqli_stmt_execute($stmt);
    }
    $sql = "DELETE  FROM citizen WHERE National_id=?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../register.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $national_id);
        mysqli_stmt_execute($stmt);
    }

    $filepointer = "../face_rec/faces/" . $national_id . ".jpg";
    echo $filepointer;
    if (unlink($filepointer)) {
        echo "success";
    }
    $filepointer = "../../projects/face_rec/faces/" . $national_id . ".jpg";
    echo $filepointer;
    if (unlink($filepointer)) {
        echo "success";
    }
    header("Location: ../2_Dashboard.php");
}
