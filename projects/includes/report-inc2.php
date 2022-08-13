<?php
require 'database.php';
session_start();
$national_id = $_SESSION['National_id2']; //national id of accused
$reporter = $_SESSION['reporter']; //national id of reporter

if ($national_id == $reporter) {
    header("Location: ../1_Dashboard.php&error=cantreportyourself");
    exit();
}

$sql = "SELECT * From citizen where National_id='$reporter' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$nationalidcheck = $row['id'];
$sql = "SELECT * From blocked_users where userid='$nationalidcheck'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    header("Location: ../0_login.php?error=blockeduer");
    exit();
}





$sql = "INSERT INTO monitor (id,event_type,datee) values (?,'crime report',NOW())";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../0_login.php?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $reporter);
    mysqli_stmt_execute($stmt);
}
$sql = "SELECT * from monitor where id='$reporter' AND datee > (NOW() - INTERVAL 1 Minute)";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 20) {
    $sql = "INSERT INTO blocked_users (userid) values '$nationalidcheck'  ";
    mysqli_query($conn, $sql);
    header("Location: ../0_login.php?error=blockeduser");
    exit();
}




if (!is_numeric($national_id) || !is_numeric($reporter)) {
    header("Location: report_crime.php?error=invalidid");
    exit();
}
$sql = "INSERT INTO crime_report (category,document,National_id_1,reportsNational_id_2) Values
('',NULL,'$reporter','$national_id') ";
mysqli_query($conn, $sql);
if (isset($_POST['submit'])) {
    if ($_SESSION['csrf'] != $_POST['csrf']) {
        header("Location: logout.php");
    }
    echo "hello";
    echo "<pre>";
    print_r($_FILES['my_image']);
    echo "</pre>";
    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];
    if (5 == 5) {
        if ($img_size > 125000 * 80) {
            $em = "Filetoolarge";
            header("Location: ../image_upload.php?error=$em");
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exc = array("jpg", "jpeg", "png", "mp4", "WMV", "WEBM", "pdf", "docx", "txt", "zip", "pptx", "MP4");
            $allowed_exc2 = array("mp4", "WMV", "WEBM");
            if (in_array($img_ex, $allowed_exc)) {
                $new_img_name = uniqid("Doc-", true) . '.' . $img_ex_lc;
                $sql = "SELECT id FROM crime_report WHERE category ='' ";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $row2 = $row['id'];
                $createfolder = mkdir("../uploads/" . $row2);
                #$img_upload_path="../face_rec/images/".$row2.".".$img_ex_lc;
                $img_upload_path = "../uploads/" . $row2 . "/" . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
                $sql = "UPDATE crime_report SET document = '$new_img_name' WHERE id = '$row2'";
                mysqli_query($conn, $sql);
                echo $national_id;
                #header("Location: ../makereport.php?subject=$national_id");
                //mysqli_close($conn);
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
if (isset($_POST['submit'])) {
    $category = $_POST['category'];
    $categorycheck = str_replace(' ', '', $category);
    if (!ctype_alpha($categorycheck)) {
        header("Location: report_crime.php?subject=$national_id&error=invalidcategory");
        exit;
    }
    $crimetype = $_POST['inc_type'];
    $crimecheck = str_replace(' ', '', $crimetype);
    if (!ctype_alnum($crimecheck)) {
        header("Location: report_crime.php?subject=$national_id&error=invalidcrime");
        exit;
    }
    echo $category;
    echo $crimetype;
    $sql = "SELECT * From crimes Where Felonies='$crimetype'";
    $result = mysqli_query($conn, $sql);
    $row_num1 = mysqli_num_rows($result);


    $sql = "SELECT * From crimes Where Misdemeanors='$crimetype'";
    $result = mysqli_query($conn, $sql);
    $row_num2 = mysqli_num_rows($result);


    $sql = "SELECT * From crimes Where Infractions ='$crimetype'";
    $result = mysqli_query($conn, $sql);
    $row_num3 = mysqli_num_rows($result);

    if ($row_num1 == 0 && $category == 'Felonies') {
        header("Location: report_crime.php?subject=$national_id&error=categoryandcrimedontmatch");
        exit();
    } elseif ($row_num2 == 0 && $category == 'Misdemeanors') {
        header("Location: report_crime.php?subject=$national_id&error=categoryandcrimedontmatch");
        exit();
    } elseif ($row_num3 == 0 && $category == 'Infractions') {
        header("Location: report_crime.php?subject=$national_id&error=categoryandcrimedontmatch");
        exit();
    } else {
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $lngcheck = str_replace('.', '', $lng);
        $latcheck = str_replace('.', '', $lat);
        $lngcheck = str_replace('-', '', $lngcheck);
        $latcheck = str_replace('-', '', $latcheck);
        if (!is_numeric($latcheck) || !is_numeric($lngcheck)) {
            header("Location: report_crime.php?subject=$national_id&error=locationnotavailable");
            exit();
        }
        $date = $_POST['date'];
        $datecheck = str_replace('-', '', $date);
        if (!is_numeric($datecheck)) {
            header("Location: report_crime.php?subject=$national_id&error=invaliddate");
            exit();
        }
        $time = $_POST['time'];
        $timecheck = str_replace(' ', '', $time);
        $timecheck = str_replace(':', '', $timecheck);
        if (!ctype_alnum($timecheck)) {
            header("Location: report_crime.php?subject=$national_id&error=invalidtime");
            exit();
        }
        $witness = $_POST['witness'];
        if ((!is_numeric($witness) && !empty($witness)) || $witness == $national_id || $witness == $reporter) {
            header("Location: report_crime.php?subject=$national_id&error=invalidwitness");
            exit();
        }
        if (!empty($witness)) {
            $sql = "SELECT * FROM citizen WHERE National_id='$witness'";
            $result = mysqli_query($conn, $sql);
            $numrows = mysqli_num_rows($result);
            if ($numrows != 1) {
                header("Location: report_crime.php?subject=$national_id&error=invalidwitness");
                exit();
            }
        }
        $reportinfo = $_POST['report_info'];
        $reportinfocheck = strtolower($reportinfo);
        $reportinfocheck = str_replace(' ', '', $reportinfocheck);
        if (!ctype_alnum($reportinfocheck)) {
            header("Location: report_crime.php?subject=$national_id&error=invalidinfo");
            exit();
        }
        $reportinfo = str_replace('select', '//select', $reportinfo);
        $reportinfo = str_replace('insert', '//insert', $reportinfo);
        $reportinfo = str_replace('delete', '//delete', $reportinfo);
        $reportinfo = str_replace('drop', '//drop', $reportinfo);
        $reportinfo = str_replace('update', '//update', $reportinfo);
        $reportinfo = str_replace('grant', '//grant', $reportinfo);
        $reportinfo = str_replace('revoke', '//revoke', $reportinfo);



        if (empty($inctype) && empty($lat) && empty($lng) && empty($reportinfo)) {
            header("Location: report_crime.php?subject=$national_id&error=emptyfields");
        } else {



            /*$sql="SELECT Rating From citizen where National_id='$national_id'";
        $result=mysqli_query($conn,$sql);
        $accusedrating=mysqli_fetch_assoc($result);
        $accusedrating2=$accusedrating['Rating'];

        $newrating=$accusedrating2-$incidentwieght2;*/

            $sql = "SELECT Namee , 
        111.111 *
        DEGREES(ACOS(LEAST(1.0, COS(RADIANS(lat))
            * COS(RADIANS($lat))
            * COS(RADIANS(lng - $lng))
            + SIN(RADIANS(lat))
            * SIN(RADIANS($lat))))) AS distance_in_km
        FROM districts
        Having  distance_in_km between 0 and 30 
        ORDER BY distance_in_km
        Limit 1";
            $result = mysqli_query($conn, $sql);
            $districtrow = mysqli_fetch_assoc($result);
            $district = $districtrow['Namee'];


            $sql = "UPDATE crime_report set district='$district', datee='$date',timee='$time', category='$category',report_info='$reportinfo',crime_type='$crimetype',witnesses='$witness',lng='$lng',lat='$lat' where category=''";
            mysqli_query($conn, $sql);



            #$curenrtrating2=$accusedrating2;






            /*$sql="SELECT SUM(report_weight) From reports where reportsNational_id_2='$national_id'";
        $result=mysqli_query($conn,$sql);
        $curenrtrating1=mysqli_fetch_assoc($result); //current rating of user

        $sumtrating=$curenrtrating1['SUM(report_weight)'];

        

        $sql="SELECT Count(report_weight) From reports where reportsNational_id_2='$national_id'";
        $result=mysqli_query($conn,$sql);
        $curenrtrating1=mysqli_fetch_assoc($result); //current rating of user

        $countrating=$curenrtrating1['Count(report_weight)'];
    

        $newrating=($curenrtrating2+$sumtrating)/($countrating+1);

    

        $sql="UPDATE citizen set Rating='$newrating' Where National_id='$national_id'";
        $result=mysqli_query($conn,$sql);*/













            header("Location: ../1_Dashboard.php");
        }
    }
}
