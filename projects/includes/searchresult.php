<?php
require 'database.php';
$outputvideo = array();
?>

<?php

if (isset($_POST['submit'])) {

    session_start();
    $id = $_SESSION['National_id'];
    $sql = "SELECT * From citizen where National_id='$id' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $nationalidcheck = $row['id'];
    $nationalid = $_POST['National_id'];
    $sql = "INSERT INTO monitor (id,event_type,datee) values (?,'search users',NOW())";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../0_login.php?error=sqlerror");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
    }
    $sql = "SELECT * from monitor where id='$id' AND datee > (NOW() - INTERVAL 1 Minute)";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 10) {
        $sql = "INSERT INTO blocked_users (userid) values ('$nationalidcheck')";
        mysqli_query($conn, $sql);
        session_destroy();
        header("Location: ../0_login.php?error=blockeduser");
        exit();
    }
    if (strlen($nationalid) > 0) {
        if (!is_numeric($nationalid)) {
            header("Location: ../1_Search.php?error=invalidnationalid");
            exit();
        }
    }
    $outputvideo = array();
    $htmlname = array();
    $htmlnationalid = array();
    $htmlimagepath = array();
    $platenumber = $_POST['plate_number'];
    $platenumbercheck = strtolower($platenumber);
    if (strlen($platenumber) > 0) {
        if (!ctype_alnum($platenumbercheck) || str_contains($platenumbercheck, 'select') || str_contains($platenumbercheck, 'delete') || str_contains($platenumbercheck, 'update') || str_contains($platenumbercheck, 'insert') || str_contains($platenumbercheck, 'grant') || str_contains($platenumbercheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidplatenumber");
            exit();
        }
    }
    $criminalname = $_POST['Name'];
    $criminalnamecheck = strtolower($criminalname);
    $criminalnamecheck = str_replace(' ', '', $criminalnamecheck);
    if (strlen($criminalname) > 0) {
        if (!ctype_alpha($criminalnamecheck) || str_contains($criminalnamecheck, 'drop') || str_contains($criminalnamecheck, 'select') || str_contains($criminalnamecheck, 'delete') || str_contains($criminalnamecheck, 'update') || str_contains($criminalnamecheck, 'insert') || str_contains($criminalnamecheck, 'grant') || str_contains($criminalnamecheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidname");
            exit();
        }
    }

    $hairtexture = $_POST['hair_texture'];
    $hairtexturecheck = preg_replace('/\s+/', '', $hairtexture);
    $hairtexturecheck = strtolower($hairtexturecheck);
    if (strlen($hairtexture) > 0) {
        if (!ctype_alpha($hairtexturecheck) || str_contains($hairtexturecheck, 'select') || str_contains($hairtexturecheck, 'delete') || str_contains($hairtexturecheck, 'drop') || str_contains($hairtexturecheck, 'update') || str_contains($hairtexturecheck, 'insert') || str_contains($hairtexturecheck, 'grant') || str_contains($hairtexturecheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidhairtexture");
            exit();
        }
    }
    $haircolor = $_POST['hair_color'];
    $haircolorcheck = preg_replace('/\s+/', '', $haircolor);
    $haircolorcheck = strtolower($haircolorcheck);
    if (strlen($haircolor) > 0) {
        if (!ctype_alpha($haircolorcheck) || str_contains($haircolorcheck, 'select') || str_contains($haircolorcheck, 'delete') || str_contains($haircolorcheck, 'drop') || str_contains($haircolorcheck, 'update') || str_contains($haircolorcheck, 'insert') || str_contains($haircolorcheck, 'grant') || str_contains($haircolorcheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidhaircolor");
            exit();
        }
    }

    $age = $_POST['age'];
    $agecheck = preg_replace('/\s+/', '', $age);
    $agecheck = strtolower($agecheck);
    if (strlen($age) > 0) {
        if (!ctype_alpha($agecheck) || str_contains($agecheck, 'select') || str_contains($agecheck, 'delete') || str_contains($agecheck, 'drop') || str_contains($agecheck, 'update') || str_contains($agecheck, 'insert') || str_contains($agecheck, 'grant') || str_contains($agecheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidage");
            exit();
        }
    }
    $beard = $_POST['beard'];
    $beardcheck = preg_replace('/\s+/', '', $beard);
    $beardcheck = strtolower($beardcheck);
    if (strlen($beard) > 0) {
        if (!ctype_alpha($beardcheck) || str_contains($beardcheck, 'select') || str_contains($beardcheck, 'delete') || str_contains($beardcheck, 'drop') || str_contains($beardcheck, 'update') || str_contains($beardcheck, 'insert') || str_contains($beardcheck, 'grant') || str_contains($beardcheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidbeard");
            exit();
        }
    }
    $body = $_POST['body'];
    $bodycheck = preg_replace('/\s+/', '', $body);
    $bodycheck = strtolower($bodycheck);
    if (strlen($body) > 0) {
        if (!ctype_alpha($bodycheck) || str_contains($bodycheck, 'select') || str_contains($bodycheck, 'delete') || str_contains($bodycheck, 'drop') || str_contains($bodycheck, 'update') || str_contains($bodycheck, 'insert') || str_contains($bodycheck, 'grant') || str_contains($bodycheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidbody");
            exit();
        }
    }
    $skincolor = $_POST['skin_color'];
    $skincolorcheck = preg_replace('/\s+/', '', $skincolor);
    $skincolorcheck = strtolower($skincolorcheck);
    if (strlen($skincolor) > 0) {
        if (!ctype_alpha($skincolorcheck) || str_contains($skincolorcheck, 'select') || str_contains($skincolorcheck, 'delete') || str_contains($skincolorcheck, 'drop') || str_contains($skincolorcheck, 'update') || str_contains($skincolorcheck, 'insert') || str_contains($skincolorcheck, 'grant') || str_contains($skincolorcheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidskincolor");
            exit();
        }
    }
    $height = $_POST['height'];
    $heightcheck = preg_replace('/\s+/', '', $height);
    $heightcheck = strtolower($heightcheck);
    if (strlen($height) > 0) {
        if (!ctype_alpha($heightcheck) || str_contains($heightcheck, 'select') || str_contains($heightcheck, 'delete') || str_contains($heightcheck, 'drop') || str_contains($heightcheck, 'update') || str_contains($heightcheck, 'insert') || str_contains($heightcheck, 'grant') || str_contains($heightcheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalidheight");
            exit();
        }
    }
    $eyecolor = $_POST['eye_color'];
    $eyecolorcheck = preg_replace('/\s+/', '', $eyecolor);
    $eyecolorcheck = strtolower($eyecolorcheck);
    if (strlen($eyecolor) > 0) {
        if (!ctype_alpha($eyecolorcheck) || str_contains($eyecolorcheck, 'select') || str_contains($eyecolorcheck, 'delete') || str_contains($eyecolorcheck, 'drop') || str_contains($eyecolorcheck, 'update') || str_contains($eyecolorcheck, 'insert') || str_contains($eyecolorcheck, 'grant') || str_contains($eyecolorcheck, 'revoke')) {
            header("Location: ../1_Search.php?error=invalideyecolor");
            exit();
        }
    }
    $coloumnname = ["National_id", "Age", "Rating", "Name", "Job", "Plate_number", "Email", "lat", "lng"];
    $agearray = array();
    $bodyarray = array();
    $locationarray = array();
    $heightarray = array();
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $lngcheck = str_replace('.', '', $lng);
    $latcheck = str_replace('.', '', $lat);
    $lngcheck = str_replace('-', '', $lngcheck);
    $latcheck = str_replace('-', '', $latcheck);
    if (strlen($lat) > 0 || strlen($lng) > 0)
        if (!is_numeric($lngcheck) || !is_numeric($latcheck) || substr_count($lng, '.') != 1 || substr_count($lng, '.') != 1) {
            header("Location: ../1_Search.php?error=invalidlocation");
            echo $latcheck;
            exit();
        }
    //national id check
    if ($nationalid != "") {
        $sql = "SELECT * FROM citizen WHERE id=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../1_Search.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $nationalid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row_count = mysqli_num_rows($result);
            $row = mysqli_fetch_assoc($result);
        }
        if ($row_count == 0) {
            header("Location: ../1_Search.php?error=nationaliddeosntexist");
        }
        /*else
        {
        
            while($row=mysqli_fetch_assoc($result))
            {
                for($x=0;$x<9;$x++)
                {
                    echo $coloumnname[$x]." :  ".$row[$coloumnname[$x]] ." ";
                    
                }
                echo "<br>";
    
            }

        }*/ else {
            $national_id = $row['National_id'];
            header("Location: ../ouputinformation.php?subject=$national_id");
        }
    } elseif ($platenumber != "" && $nationalid == "") {
        $sql = "SELECT * FROM citizen WHERE Plate_number='$platenumber'";
        $result = mysqli_query($conn, $sql);
        $row_count = mysqli_num_rows($result);
        $row = mysqli_fetch_assoc($result);
        if ($row_count == 0) {
            header("Location: ../1_Search.php?error=platenumberdontexist");
        }
        /*else
        {
        
            while($row=mysqli_fetch_assoc($result))
            {
                for($x=0;$x<9;$x++)
                {
                    echo $coloumnname[$x]." :  ".$row[$coloumnname[$x]] ." ";
                    
                }
                echo "<br>";
    
            }

        }*/ else {
            $national_id = $row['National_id'];
            header("Location: ../ouputinformation.php?subject=$national_id");
        }
    } elseif (isset($_FILES['my_image']) && $_FILES['my_image']['size'] != 0) {
        require 'image_database.php';
        /*echo"<pre>";
        print_r($_FILES['my_image']);
        echo"</pre>";*/
        $img_name = $_FILES['my_image']['name'];
        $img_size = $_FILES['my_image']['size'];
        $tmp_name = $_FILES['my_image']['tmp_name'];
        $error = $_FILES['my_image']['error'];
        $allowed_exc2 = array("mp4");
        if ($error === 0) {
            if ($img_size > 1250000000000000000) {
                $em = "Filetoolarge";
                header("Location: ../image_upload.php?error=$em");
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $allowed_exc = array("jpg", "jpeg", "png");
                if (in_array($img_ex, $allowed_exc)) {
                    $mydir = '../face_rec/faces/';
                    $myfiles = array_diff(scandir($mydir), array('.', '..'));
                    /*print_r($myfiles);*/
                    for ($i = 2; $i < (2 + count($myfiles)); $i++) {
                        $myfiles[$i] = substr($myfiles[$i], 0, strrpos($myfiles[$i], '.'));
                    }
                    $folder = '../face_rec/test/';
                    //Get a list of all of the file names in the folder.
                    $files = glob($folder . '/*');
                    //Loop through the file list.
                    foreach ($files as $file) {
                        //Make sure that this is a file and not a directory.
                        if (is_file($file)) {
                            //Use the unlink function to delete the file.
                            unlink($file);
                        }
                    }
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    /*$createfolder=mkdir("../searchupload/"."imagesearch");*/
                    #$img_upload_path="../face_rec/test/"."test".".".$img_ex_lc;
                    $img_upload_path = "../face_rec/test/" . "test" . "." . "jpg";
                    move_uploaded_file($tmp_name, $img_upload_path);
                    $command = 'cd C:\xampp\htdocs\dev.rateopia.com\projects\face_rec && python face_rec.py';
                    $output = shell_exec($command);
                    #echo $output;
                    for ($i = 2, $j = 0; $i < (2 + count($myfiles)); $i++, $j++) {
                        if (str_contains($output, $myfiles[$i])) {
                            /*echo $myfiles[$i];
                                echo "<br>";*/
                            array_push($outputvideo, $myfiles[$i]);
                        }
                    }
                    echo "<br>";
                } else if (in_array($img_ex, $allowed_exc2)) {
                    $img_upload_path = "../face_rec/test" . "." . "mp4";
                    $mydir = '../face_rec/faces/';
                    $myfiles = array_diff(scandir($mydir), array('.', '..'));
                    /*print_r($myfiles);*/
                    for ($i = 2; $i < (2 + count($myfiles)); $i++) {
                        $myfiles[$i] = substr($myfiles[$i], 0, strrpos($myfiles[$i], '.'));
                    }
                    $check = array();
                    for ($i = 0; $i < count($myfiles); $i++) {
                        array_push($check, "FALSE");
                    }
                    move_uploaded_file($tmp_name, $img_upload_path);
                    $command = 'cd C:\xampp\htdocs\dev.rateopia.com\projects\face_rec && python face_rec_videos.py';
                    $output = shell_exec($command);
                    #echo $output; 
                    for ($i = 2, $j = 0; $i < (2 + count($myfiles)); $i++, $j++) {
                        if (str_contains($output, $myfiles[$i])) {
                            /*echo $myfiles[$i];
                            echo "<br>";*/
                            array_push($outputvideo, $myfiles[$i]);
                        }
                    }
                    echo "<br>";
                } else {
                    $em = "you cant upload files of this type";
                    header("Location: ../image_upload.php?error=$em");
                }
            }
        } else {
            $em = "unknownerroroccured";
            header("Location: ../image_upload.php?error=$em");
        }
    } elseif ($nationalid == "" && $platenumber == "") {
        $querey_names = "SELECT National_id FROM citizen WHERE Name LIKE '%$criminalname' OR Name LIKE '$criminalname%' OR Name LIKE '%$criminalname%' ";
        $result_names = mysqli_query($conn, $querey_names); //execute Names querey
        $names_row_count = mysqli_num_rows($result_names); //get number of rows that match entered name
        $namesarray = array(); //store names
        for ($x = 0; $x < $names_row_count; $x++) {
            $names_fetch = mysqli_fetch_assoc($result_names); //fetch data
            $combining = $names_fetch["National_id"]; //store in variable
            array_push($namesarray, $combining); //store national id that match condition
        }
        if ($lat != "" && $lng != "") {
            $quereylocation = "SELECT National_id , 
            111.111 *
            DEGREES(ACOS(LEAST(1.0, COS(RADIANS(lat))
                * COS(RADIANS($lat))
                * COS(RADIANS(lng - $lng))
                + SIN(RADIANS(lat))
                * SIN(RADIANS($lat))))) AS distance_in_km
        FROM citizen 
        Having  distance_in_km between 0 and 30 
        ORDER BY distance_in_km";
            $resultlocation = mysqli_query($conn, $quereylocation);
            $location_row_cont = mysqli_num_rows($resultlocation);
            for ($x = 0; $x < $location_row_cont; $x++) {
                $location_fetch = mysqli_fetch_assoc($resultlocation); //fetch data
                $combining = $location_fetch["National_id"]; //store in variable
                echo $location_fetch["National_id"] . "       " . $location_fetch["distance_in_km"] . "<br>";
                array_push($locationarray, $combining); //store national id that match condition
            }
        }
        $querey_hairtexture = "SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE hair_texture='$hairtexture') ";
        $result_hairtexture = mysqli_query($conn, $querey_hairtexture);
        $hairtexture_row_count = mysqli_num_rows($result_hairtexture);
        $hairtexturearray = array();
        for ($x = 0; $x < $hairtexture_row_count; $x++) {
            $hairtexture_fetch = mysqli_fetch_assoc($result_hairtexture);
            $combining = $hairtexture_fetch["National_id"];
            array_push($hairtexturearray, $combining); //store national id that match condition
        }
        $querey_haircolor = "SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE hair_color='$haircolor') ";
        $result_haircolor = mysqli_query($conn, $querey_haircolor);
        $haircolor_row_count = mysqli_num_rows($result_haircolor);
        $haircolorarray = array();
        for ($x = 0; $x < $haircolor_row_count; $x++) {
            $haircolor_fetch = mysqli_fetch_assoc($result_haircolor);
            $combining = $haircolor_fetch["National_id"];
            array_push($haircolorarray, $combining); //store national id that match condition
        }

        $querey_eyecolor = "SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE eye_color='$eyecolor') ";
        $result_eyecolor = mysqli_query($conn, $querey_eyecolor);
        $eyecolor_row_count = mysqli_num_rows($result_eyecolor);
        $eyecolorarray = array();
        for ($x = 0; $x < $eyecolor_row_count; $x++) {
            $eyecolor_fetch = mysqli_fetch_assoc($result_eyecolor);
            $combining = $eyecolor_fetch["National_id"];
            array_push($eyecolorarray, $combining); //store national id that match condition
        }
        $querey_eyecolor = "SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE eye_color='$eyecolor') ";
        $result_eyecolor = mysqli_query($conn, $querey_eyecolor);
        $eyecolor_row_count = mysqli_num_rows($result_eyecolor);
        $eyecolorarray = array();
        for ($x = 0; $x < $eyecolor_row_count; $x++) {
            $eyecolor_fetch = mysqli_fetch_assoc($result_eyecolor);
            $combining = $eyecolor_fetch["National_id"];
            array_push($eyecolorarray, $combining); //store national id that match condition
        }
        if ($age == "Kid") {
            $querey_age = "SELECT National_id FROM citizen WHERE Age between 10 And 15 ";
            $result_age = mysqli_query($conn, $querey_age);
            $age_row_count = mysqli_num_rows($result_age);
            for ($x = 0; $x < $age_row_count; $x++) {
                $age_fetch = mysqli_fetch_assoc($result_age);
                $combining = $age_fetch["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        } elseif ($age == "teenager") {
            $querey_age = "SELECT National_id FROM citizen WHERE Age between 16 And 20 ";
            $result_age = mysqli_query($conn, $querey_age);
            $age_row_count = mysqli_num_rows($result_age);
            for ($x = 0; $x < $age_row_count; $x++) {
                $age_fetch = mysqli_fetch_assoc($result_age);
                $combining = $age_fetch["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        } elseif ($age == "young") {
            $querey_age = "SELECT National_id FROM citizen WHERE Age between 21 And 30 ";
            $result_age = mysqli_query($conn, $querey_age);
            $age_row_count = mysqli_num_rows($result_age);
            for ($x = 0; $x < $age_row_count; $x++) {
                $age_fetch = mysqli_fetch_assoc($result_age);
                $combining = $age_fetch["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        } elseif ($age == "young middleaged") {
            $querey_age = "SELECT National_id FROM citizen WHERE Age between 31 And 40 ";
            $result_age = mysqli_query($conn, $querey_age);
            $age_row_count = mysqli_num_rows($result_age);
            for ($x = 0; $x < $age_row_count; $x++) {
                $age_fetch = mysqli_fetch_assoc($result_age);
                $combining = $age_fetch["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        } elseif ($age == "old middleadged") {
            $querey_age = "SELECT National_id FROM citizen WHERE Age between 41 And 50 ";
            $result_age = mysqli_query($conn, $querey_age);
            $age_row_count = mysqli_num_rows($result_age);
            for ($x = 0; $x < $age_row_count; $x++) {
                $age_fetch = mysqli_fetch_assoc($result_age);
                $combining = $age_fetch["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        } elseif ($age == "old") {
            $querey_age = "SELECT National_id FROM citizen WHERE Age between 51 And 60 ";
            $result_age = mysqli_query($conn, $querey_age);
            $age_row_count = mysqli_num_rows($result_age);
            for ($x = 0; $x < $age_row_count; $x++) {
                $age_fetch = mysqli_fetch_assoc($result_age);
                $combining = $age_fetch["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        } elseif ($age == "very old") {
            $querey_age = "SELECT National_id FROM citizen WHERE Age between 61 And 70 ";
            $result_age = mysqli_query($conn, $querey_age);
            $age_row_count = mysqli_num_rows($result_age);
            for ($x = 0; $x < $age_row_count; $x++) {
                $age_fetch = mysqli_fetch_assoc($result_age);
                $combining = $age_fetch["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        } elseif ($age == "very very old") {
            $querey_age = "SELECT National_id FROM citizen WHERE Age between 71 And 80 ";
            $result_age = mysqli_query($conn, $querey_age);
            $age_row_count = mysqli_num_rows($result_age);
            for ($x = 0; $x < $age_row_count; $x++) {
                $age_fetch = mysqli_fetch_assoc($result_age);
                $combining = $age_fetch["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        }
        $querey_beard = "SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE beard_style='$beard') ";
        $result_beard = mysqli_query($conn, $querey_beard);
        $beard_row_count = mysqli_num_rows($result_beard);
        $beardarray = array();
        for ($x = 0; $x < $beard_row_count; $x++) {
            $beard_fetch = mysqli_fetch_assoc($result_beard);
            $combining = $beard_fetch["National_id"];
            array_push($beardarray, $combining); //store national id that match condition
        }
        if ($body == "under weight") {
            $querey_body = "SELECT National_id FROM physical_features WHERE weightt/pow((height/100),2) between 0 And 18.5 ";
            $result_body = mysqli_query($conn, $querey_body);
            $body_row_count = mysqli_num_rows($result_body);
            for ($x = 0; $x < $body_row_count; $x++) {
                $body_fetch = mysqli_fetch_assoc($result_body);
                $combining = $body_fetch["National_id"];
                array_push($bodyarray, $combining); //store national id that match condition
            }
        } elseif ($body == "normal weight") {
            $querey_body = "SELECT National_id FROM physical_features WHERE weightt/pow((height/100),2) between 18.6 And 24.9 ";
            $result_body = mysqli_query($conn, $querey_body);
            $body_row_count = mysqli_num_rows($result_body);
            for ($x = 0; $x < $body_row_count; $x++) {
                $body_fetch = mysqli_fetch_assoc($result_body);
                $combining = $body_fetch["National_id"];
                array_push($bodyarray, $combining); //store national id that match condition
            }
        } elseif ($body == "Over Weight") {
            $querey_body = "SELECT National_id FROM physical_features WHERE weightt/pow((height/100),2) between 25.0 And 29.9 ";
            $result_body = mysqli_query($conn, $querey_body);
            $body_row_count = mysqli_num_rows($result_body);
            for ($x = 0; $x < $body_row_count; $x++) {
                $body_fetch = mysqli_fetch_assoc($result_body);
                $combining = $body_fetch["National_id"];
                array_push($bodyarray, $combining); //store national id that match condition
            }
        } elseif ($body == "Obese") {
            $querey_body = "SELECT National_id FROM physical_features WHERE weightt/pow((height/100),2) between 30.0 And 34.9 ";
            $result_body = mysqli_query($conn, $querey_body);
            $body_row_count = mysqli_num_rows($result_body);
            for ($x = 0; $x < $body_row_count; $x++) {
                $body_fetch = mysqli_fetch_assoc($result_body);
                $combining = $body_fetch["National_id"];
                array_push($bodyarray, $combining); //store national id that match condition
            }
        }
        $querey_skin = "SELECT National_id FROM physical_features WHERE National_id IN (SELECT National_id FROM physical_features WHERE skin_color='$skincolor') ";
        $result_skin = mysqli_query($conn, $querey_skin);
        $skin_row_count = mysqli_num_rows($result_skin);
        $skinarray = array();
        for ($x = 0; $x < $skin_row_count; $x++) {
            $skin_fetch = mysqli_fetch_assoc($result_skin);
            $combining = $skin_fetch["National_id"];
            array_push($skinarray, $combining); //store national id that match condition
        }
        if ($height == "short") {
            $querey_height = "SELECT National_id FROM physical_features WHERE height between 150 And 160 ";
            $result_height = mysqli_query($conn, $querey_height);
            $height_row_count = mysqli_num_rows($result_height);
            for ($x = 0; $x < $height_row_count; $x++) {
                $height_fetch = mysqli_fetch_assoc($result_height);
                $combining = $height_fetch["National_id"];
                array_push($heightarray, $combining); //store national id that match condition
            }
        } elseif ($height == "normal") {
            $querey_height = "SELECT National_id FROM physical_features WHERE height between 161 And 170 ";
            $result_height = mysqli_query($conn, $querey_height);
            $height_row_count = mysqli_num_rows($result_height);
            for ($x = 0; $x < $height_row_count; $x++) {
                $height_fetch = mysqli_fetch_assoc($result_height);
                $combining = $height_fetch["National_id"];
                array_push($heightarray, $combining); //store national id that match condition
            }
        } elseif ($height == "tall") {
            $querey_height = "SELECT National_id FROM physical_features WHERE height between 171 And 180 ";
            $result_height = mysqli_query($conn, $querey_height);
            $height_row_count = mysqli_num_rows($result_height);
            for ($x = 0; $x < $height_row_count; $x++) {
                $height_fetch = mysqli_fetch_assoc($result_height);
                $combining = $height_fetch["National_id"];
                array_push($heightarray, $combining); //store national id that match condition
            }
        } elseif ($height == "very tall") {
            $querey_height = "SELECT National_id FROM physical_features WHERE height between 181 And 190 ";
            $result_height = mysqli_query($conn, $querey_height);
            $height_row_count = mysqli_num_rows($result_height);
            for ($x = 0; $x < $height_row_count; $x++) {
                $height_fetch = mysqli_fetch_assoc($result_height);
                $combining = $height_fetch["National_id"];
                array_push($heightarray, $combining); //store national id that match condition
            }
        } elseif ($height == "very very tall") {
            $querey_height = "SELECT National_id FROM physical_features WHERE height between 191 And 210 ";
            $result_height = mysqli_query($conn, $querey_height);
            $height_row_count = mysqli_num_rows($result_height);
            for ($x = 0; $x < $height_row_count; $x++) {
                $height_fetch = mysqli_fetch_assoc($result_height);
                $combining = $height_fetch["National_id"];
                array_push($heightarray, $combining); //store national id that match condition
            }
        }
        if (count($namesarray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($namesarray, $combining); //store national id that match condition
            }
        }
        if (count($hairtexturearray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($hairtexturearray, $combining); //store national id that match condition
            }
        }
        if (count($haircolorarray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($haircolorarray, $combining); //store national id that match condition
            }
        }
        if (count($eyecolorarray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($eyecolorarray, $combining); //store national id that match condition
            }
        }
        if (count($agearray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($agearray, $combining); //store national id that match condition
            }
        }
        if (count($beardarray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($beardarray, $combining); //store national id that match condition
            }
        }
        if (count($bodyarray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($bodyarray, $combining); //store national id that match condition
            }
        }
        if (count($skinarray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($skinarray, $combining); //store national id that match condition
            }
        }
        if (count($heightarray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($heightarray, $combining); //store national id that match condition
            }
        }
        if (count($locationarray) == 0) {
            $final_quere = "SELECT National_id FROM citizen";
            $finalquereresult = mysqli_query($conn, $final_quere);
            $finalquererowcount = mysqli_num_rows($finalquereresult);
            for ($x = 0; $x < $finalquererowcount; $x++) {
                $rowfinalquery = mysqli_fetch_assoc($finalquereresult);
                $combining = $rowfinalquery["National_id"];
                array_push($locationarray, $combining); //store national id that match condition
            }
        }
        $combinearray = array_intersect($namesarray, $hairtexturearray, $haircolorarray, $eyecolorarray, $agearray, $beardarray, $bodyarray, $skinarray, $heightarray, $locationarray);

        for ($xx = 0; $xx < max(array_keys($combinearray)) + 1; $xx++) {
            if (($combinearray[$xx] ?? null) != "") {
                $finalnational = $combinearray[$xx];
                $finalsql = "SELECT Name,National_id from citizen WHERE National_id='$finalnational'
                UNION
                SELECT image_url , id  FROM images WHERE National_id='$finalnational'";
                $finalfinalquery = mysqli_query($conn, $finalsql);
                $counter = 0;
                while ($finalfetch = mysqli_fetch_assoc($finalfinalquery)) {
                    if ($counter == 1) {

                        array_push($htmlimagepath, $finalfetch['Name']);
                    } else {

                        array_push($htmlname, $finalfetch['Name']);
                        array_push($htmlnationalid, $finalfetch['National_id']);
                        $counter++;
                    }
                }
                $counter = 0;
            }
        }
        $_SESSION['htmlname'] = $htmlname;
        $_SESSION['htmlnationalid'] = $htmlnationalid;
        $_SESSION['htmlimagepath'] = $htmlimagepath;
        if ($names_row_count == 0 && !empty($criminalname)) {

            header("Location: ../emptysearch.php");
        } elseif ($location_row_cont == 0 && (!empty($lat) && !empty($lng))) {
            header("Location: ../emptysearch.php");
        } elseif ($hairtexture_row_count == 0 && !empty($hairtexture)) {
            header("Location: ../emptysearch.php");
        } elseif ($haircolor_row_count == 0 && !empty($haircolor)) {
            header("Location: ../emptysearch.php");
        } elseif ($eyecolor_row_count == 0 && !empty($eyecolor)) {
            header("Location: ../emptysearch.php");
        } elseif ($age_row_count == 0 && !empty($age)) {
            header("Location: ../emptysearch.php");
        } elseif ($beard_row_count == 0 && !empty($beard)) {
            header("Location: ../emptysearch.php");
        } elseif ($body_row_count == 0 && !empty($body)) {
            header("Location: ../emptysearch.php");
        } elseif ($skin_row_count == 0 && !empty($skincolor)) {
            header("Location: ../emptysearch.php");
        } elseif ($height_row_count == 0 && !empty($height)) {
            header("Location: ../emptysearch.php");
        } elseif (empty($height) && empty($skincolor) &&  empty($body) && empty($beard) && empty($age) &&  empty($eyecolor) && empty($haircolor)  && empty($hairtexture) && empty($criminalname) && empty($lat) && empty($lng)) {
            header("Location: ../emptysearch.php");
        } else {
            header("Location: ../outputsearch.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Search ouput</title>
</head>

<body>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <ul class="imagecontainer">
            <li>
                <a class="imagelink" href="../1_Dashboard.php">
                    <img class="imagelogo" src="../Media/Rateopia_Black.png">
                    <span class="logotxt"> Rateopia
                    </span>
                </a>
            </li>
        </ul>
        <ul class="dashNav">
            <li><a class="active" href="../1_Search.php"><i class="fa fa-fw fa-search"></i>Search</a></li>
            <li><a href="../1_Changes.php">Recent Cahnges</a></li>
            <li><a href="../0_Login.php">Logout</a></li>
        </ul>
    </nav>
    <div>
        <table class="table-adjust">
            <tr>
                <th style="padding:10px;" colspan="3">Results</th>
            </tr>
            <tr>
                <td class='trinf'><b>Name</b></td>
                <td class='trinf'><b>ID</b></td>
                <td class='trinf'><b>Image</b></td>
            </tr>
            <?php

            for ($i = 0; $i < count($outputvideo); $i++) {
                $sql = "SELECT * From citizen WHERE National_id='$outputvideo[$i]' ";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                echo "<tr>";
                echo "<td class='trinf'>";
                echo '<a target="_blank" class="form__link" href="../ouputinformation.php?subject=' . $outputvideo[$i] . '">' . $row['Name'] . '</a>';
                echo "</td>";
                echo "<td class='trinf'>";
                echo '<a target="_blank" class="form__link" href="../ouputinformation.php?subject=' . $outputvideo[$i] . '">' . $row['National_id'] . '</a>';
                echo "</td>";
                echo "<td class='trinf'>";
                echo '<a target="_blank" class="form__link" href="../face_rec/faces/' . $outputvideo[$i] . ".jpg" . '"' . ">" . "<img src=" . '"../face_rec/faces/' . $outputvideo[$i] . ".jpg" . '" ' . 'width="100px" height="100px">' . "</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>