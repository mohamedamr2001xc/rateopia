<?php
require 'database.php';
session_start();
$errornoofreports = 0; //number of reports user did last six month
$errorsameuser = 0; //number of reports user did on same accused in this month
$countreportypepermonth = 0; //number of reports that have this type and same accused in last month
$countreporttypealltime = 0; //number of reports that have this type and same accused all time
$noofreportssixmonth = 0; //number of reports reporter made on accuser in last six month

$national_id = $_SESSION['National_id2']; //national id of accused
$reporter = $_SESSION['reporter']; //national id of reporter
if ($national_id == $reporter) {
    header("Location: ../1_Dashboard.php");
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





$sql = "INSERT INTO monitor (id,event_type,datee) values (?,'make report',NOW())";
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






$sql = "INSERT INTO reports (report_info,document,National_id_1,reportsNational_id_2) Values
('',NULL,'$reporter','$national_id') ";
mysqli_query($conn, $sql);
if (isset($_SESSION['Files'])) {
    echo "hello";
    echo "<pre>";
    print_r($_FILES['my_image']);
    echo "</pre>";
    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];
    if ($img_size != 0) {
        if ($img_size > 125000 * 80) {
            $em = "Filetoolarge";
            #header("Location: ../image_upload.php?error=$em");
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);
            $allowed_exc = array("jpg", "jpeg", "png", "mp4", "WMV", "WEBM", "pdf", "docx", "txt", "zip", "pptx", "MP4");
            $allowed_exc2 = array("mp4", "WMV", "WEBM");
            if (in_array($img_ex, $allowed_exc)) {
                $new_img_name = uniqid("Doc-", true) . '.' . $img_ex_lc;
                $sql = "SELECT reportid FROM reports WHERE report_info ='' ";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $row2 = $row['reportid'];
                $createfolder = mkdir("../uploads/" . $row2);
                #$img_upload_path="../face_rec/images/".$row2.".".$img_ex_lc;
                $img_upload_path = "../uploads/" . $row2 . "/" . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);
                $sql = "UPDATE reports SET document = '$new_img_name' WHERE reportid = '$row2'";
                mysqli_query($conn, $sql);
                echo $national_id;
                ##header("Location: ../makereport.php?subject=$national_id");
                //mysqli_close($conn);
            } else {
                $em = "you cant upload files of this type";
                #header("Location: ../image_upload.php?error=$em");
            }
        }
    } else {
        $em = "unknownerroroccured";
        #header("Location: ../image_upload.php?error=$em");
    }
}
if (isset($_SESSION['inc_type'])) {
    $message = $_SESSION['message'];
    $inctype = $_SESSION['inc_type'];
    $lat = $_SESSION['lat'];
    $lng = $_SESSION['lng'];
    $messagecheck = strtolower($message);
    $messagecheck = str_replace(' ', '', $messagecheck);
    if (!ctype_alnum($messagecheck)) {
        header("Location: report_submit.php?error=invalidreport&subject=$national_id");
        exit();
    }
    $message = str_replace('select', '//select', $message);
    $message = str_replace('insert', '//insert', $message);
    $message = str_replace('delete', '//delete', $message);
    $message = str_replace('drop', '//drop', $message);
    $message = str_replace('update', '//update', $message);
    $message = str_replace('grant', '//grant', $message);
    $message = str_replace('revoke', '//revoke', $message);
    $inctypecheck = str_replace(' ', '', $inctype);
    if (!ctype_alpha($inctypecheck) || str_contains($inctypecheck, 'drop') || str_contains($inctypecheck, 'select') || str_contains($inctypecheck, 'delete') || str_contains($inctypecheck, 'update') || str_contains($inctypecheck, 'insert') || str_contains($inctypecheck, 'grant') || str_contains($inctypecheck, 'revoke')) {
        header("Location: report_submit.php?error=invalidname&subject=$national_id");
        exit();
    }
    $lngcheck = str_replace('.', '', $lng);
    $latcheck = str_replace('.', '', $lat);
    $lngcheck = str_replace('-', '', $lngcheck);
    $latcheck = str_replace('-', '', $latcheck);
    if (!is_numeric($latcheck) || !is_numeric($lngcheck)) {
        header("Location: report_submit.php?error=invalidlocation&subject=$national_id");
        exit();
    }

    if (empty($message) && empty($inctype) && empty($lat) && empty($lng)) {
        #header("Location: ../Report.php?error=emptyfields");
    } else {
        $sql = "SELECT incident_weight From incidents_postive where Namee='$inctype'";
        $result = mysqli_query($conn, $sql);
        $incidentwieght1 = mysqli_fetch_assoc($result);
        $incidentwieght2 = $incidentwieght1['incident_weight']; //Weight of incident

        $transdate = date('m-d-Y', time());



        $month = date('m', strtotime($transdate));


        $month = $month;

        $year = date('Y', strtotime($transdate));



        if ($month <= 6) {
            $date1 = $year . "-" . "1" . "-" . "1";

            $date2 = $year . "-" . "6" . "-" . "30";

            $sql = "SELECT *
            FROM reports
            WHERE (datee BETWEEN '$date1' AND '$date2') AND National_id_1='$reporter'";
            $result = mysqli_query($conn, $sql);
            $rowcount = mysqli_num_rows($result);

            $errornoofreports = $rowcount;
            $sql = "SELECT *
            FROM reports
            WHERE (datee BETWEEN '$date1' AND '$date2') AND National_id_1='$reporter' AND reportsNational_id_2='$national_id' ";
            $result = mysqli_query($conn, $sql);
            $rowcount = mysqli_num_rows($result);
            $noofreportssixmonth = $rowcount;
        } else {
            $date1 = $year . "-" . "7" . "-" . "1";

            $date2 = $year . "-" . "12" . "-" . "31";

            $sql = "SELECT *
            FROM reports
            WHERE (datee BETWEEN '$date1' AND '$date2') AND National_id_1='$reporter'";
            $result = mysqli_query($conn, $sql);
            $rowcount = mysqli_num_rows($result);


            $errornoofreports = $rowcount;
            $sql = "SELECT *
            FROM reports
            WHERE (datee BETWEEN '$date1' AND '$date2') AND National_id_1='$reporter' AND reportsNational_id_2='$national_id' ";
            $result = mysqli_query($conn, $sql);
            $rowcount = mysqli_num_rows($result);
            $noofreportssixmonth = $rowcount;
        }
        //////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////////
        $sql = "SELECT * FROM reports WHERE MONTH(datee)=MONTH(NOW()) AND National_id_1='$reporter' AND reportsNational_id_2='$national_id'  ";
        $result = mysqli_query($conn, $sql);
        $rowcount = mysqli_num_rows($result);

        $errorsameuser = $rowcount;

        /////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////////////////////////////////////////
        $sql = "SELECT * FROM reports WHERE TIMESTAMPDIFF(DAY, Date(NOW()),datee)>-30 AND report_type='$inctype' AND reportsNational_id_2='$national_id'";
        $result = mysqli_query($conn, $sql);
        $rowcount = mysqli_num_rows($result);
        $countreportypepermonth = $rowcount;
        $sql = "SELECT * FROM reports WHERE  report_type='$inctype' AND reportsNational_id_2='$national_id'";
        $result = mysqli_query($conn, $sql);
        $rowcount = mysqli_num_rows($result);
        $countreporttypealltime = $rowcount;

        /////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////
        if ($errornoofreports >= 120 || $errorsameuser >= 1 || $noofreportssixmonth >= 3) {
            $sql = "DELETE FROM reports WHERE report_weight=0";
            mysqli_query($conn, $sql);
            header("Location: report_submit.php?subject=$national_id&&error=youhavepassedmaximumnoofreports");
            echo "failed";
        } else {
            $sql = "SELECT MAX(reportid) FROM reports;";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $reportint = $row['MAX(reportid)'];

            $sql = "UPDATE reports set urgent='0',report_weight='$incidentwieght2',datee=NOW(),timee=TIME (NOW()), report_info='$message',report_type='$inctype',lng='$lng',lat='$lat' where report_info=''";
            mysqli_query($conn, $sql);
            $accusedreputation = 0;
            $reporterreputation = 0;
            $sql = "SELECT * From citizen where National_id='$national_id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $accusedrating = $row['Rating'];
            $accusedreputation = 1 - ($accusedrating / 5);

            $sql = "SELECT * From citizen where National_id='$reporter'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $reporterrating = $row['Rating'];
            $reporterreputation = $reporterrating / 5;



            $sql = "SELECT * From reports WHERE  	reportsNational_id_2 ='$national_id'";
            $result = mysqli_query($conn, $sql);
            $totalincidents = mysqli_num_rows($result);
            if ($totalincidents == 0) {
                $accusedrating = $accusedrating - $incidentwieght2;
                $sql = "UPDATE citizen set Rating='$accusedrating' WHERE National_id='$national_id'";
                mysqli_query($conn, $sql);
            } else {
                $sql = "SELECT * From reports WHERE  	reportsNational_id_2 ='$national_id' AND report_type='$inctype'";
                $result = mysqli_query($conn, $sql);
                $similarincidents = mysqli_num_rows($result);
                $previousincidents = 1 + ($similarincidents / $totalincidents);
                #$accusedreputation = 1 + $previousincidents;
                if ($previousincidents == 0) {
                    $previousincidents = 1;
                }

                $reporteffect = $incidentwieght2 * $reporterreputation * $previousincidents * $accusedreputation;
                $rating_effect = $accusedrating + $reporteffect;

                $sql = "UPDATE citizen set Rating='$rating_effect' WHERE National_id='$national_id'";
                mysqli_query($conn, $sql);
            }




            header("Location: ../1_Dashboard.php");
            echo "success";
        }
    }
}
