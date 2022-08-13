<?php
require 'database.php';
$outputvideo=array();
?>

<?php

if(isset($_POST['submit'])){
    session_start();
    $nationalid=$_POST['National_id'];
    $outputvideo=array();
    $htmlname=array();
    $htmlnationalid=array();
    $htmlimagepath=array();
    $platenumber=$_POST['plate_number'];
    $criminalname=$_POST['Name'];
    $hairtexture=$_POST['hair_texture'];
    $haircolor=$_POST['hair_color'];
    $age=$_POST['age'];
    $beard=$_POST['beard'];
    $body=$_POST['body'];
    $skincolor=$_POST['skin_color'];
    $height=$_POST['height'];
    $eyecolor=$_POST['eye_color'];
    $coloumnname=["National_id","Age","Rating","Name","Job","Plate_number","Email","lat","lng"];
    $agearray=array();
    $bodyarray=array();
    $locationarray=array();
    $heightarray=array();
    $lat=$_POST['lat'];
    $lng=$_POST['lng'];
    //national id check
    if($nationalid!=""){
        $sql="SELECT * FROM citizen WHERE National_id=$nationalid";
        $result=mysqli_query($conn,$sql);
        $row_count=mysqli_num_rows($result);
        $row=mysqli_fetch_assoc($result);
        if($row_count==0){
            header("Location: ../Search.php?error=nationaliddeosntexist");
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

        }*/
        $national_id=$row['National_id'];
        header("Location: ../ouputinformation.php?subject=$national_id");
    }
    elseif($platenumber!="" && $nationalid==""){
        $sql="SELECT * FROM citizen WHERE Plate_number='$platenumber'";
        $result=mysqli_query($conn,$sql);
        $row_count=mysqli_num_rows($result);
        $row=mysqli_fetch_assoc($result);
        if($row_count==0){
            header("Location: ../Search.php?error=platenumberdontexist");
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

        }*/
        $national_id=$row['National_id'];
        header("Location: ../ouputinformation.php?subject=$national_id");
    }
    elseif(isset($_FILES['my_image']) && $_FILES['my_image']['size'] !=0  ) {
        require 'image_database.php';
        /*echo"<pre>";
        print_r($_FILES['my_image']);
        echo"</pre>";*/
        $img_name=$_FILES['my_image']['name'];
        $img_size=$_FILES['my_image']['size'];
        $tmp_name=$_FILES['my_image']['tmp_name'];
        $error=$_FILES['my_image']['error'];
        $allowed_exc2=array("mp4");
        if($error===0){
            if($img_size >1250000000000000000){
                $em="Filetoolarge";
                header("Location: ../image_upload.php?error=$em");
                
            }
            else{
                $img_ex=pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $allowed_exc=array("jpg","jpeg","png");
                if(in_array($img_ex,$allowed_exc)){
                    $mydir='../face_rec/faces/';
                    $myfiles = array_diff(scandir($mydir), array('.', '..')); 
                /*print_r($myfiles);*/
                    for($i=2;$i<(2+count($myfiles));$i++){
                        $myfiles[$i]=substr($myfiles[$i], 0, strrpos($myfiles[$i], '.'));
                    }
                    $folder = '../face_rec/test/';
                    //Get a list of all of the file names in the folder.
                    $files = glob($folder . '/*');
                    //Loop through the file list.
                    foreach($files as $file){
                        //Make sure that this is a file and not a directory.
                        if(is_file($file)){
                        //Use the unlink function to delete the file.
                        unlink($file);
                        }
                    }
                    $new_img_name=uniqid("IMG-",true).'.'.$img_ex_lc;
                    /*$createfolder=mkdir("../searchupload/"."imagesearch");*/
                    #$img_upload_path="../face_rec/test/"."test".".".$img_ex_lc;
                    $img_upload_path="../face_rec/test/"."test"."."."jpg";
                    move_uploaded_file($tmp_name,$img_upload_path);
                    $command = 'cd C:\xampp\htdocs\PHP_Course\projects\face_rec && python face_rec.py';
                    $output = shell_exec($command);
                    #echo $output;
                        for($i=2,$j=0;$i<(2+count($myfiles));$i++,$j++){
                            if(str_contains($output, $myfiles[$i])){
                                /*echo $myfiles[$i];
                                echo "<br>";*/
                                array_push($outputvideo,$myfiles[$i]);
                            } 
                        }
                        echo "<br>";
                }
                else if(in_array($img_ex,$allowed_exc2)){
                    $img_upload_path="../face_rec/test"."."."mp4";
                    $mydir='../face_rec/faces/';
                    $myfiles = array_diff(scandir($mydir), array('.', '..')); 
                    /*print_r($myfiles);*/
                    for($i=2;$i<(2+count($myfiles));$i++){
                        $myfiles[$i]=substr($myfiles[$i], 0, strrpos($myfiles[$i], '.'));  
                    }
                    $check=array();
                    for($i=0;$i<count($myfiles);$i++){
                        array_push($check,"FALSE");

                    }
                    move_uploaded_file($tmp_name,$img_upload_path);
                    $command = 'cd C:\xampp\htdocs\PHP_Course\projects\face_rec && python face_rec_videos.py';
                    $output = shell_exec($command);
                    #echo $output; 
                    for($i=2,$j=0;$i<(2+count($myfiles));$i++,$j++){
                        if(str_contains($output, $myfiles[$i])){
                            /*echo $myfiles[$i];
                            echo "<br>";*/
                            array_push($outputvideo,$myfiles[$i]);
                        } 
                    }
                    echo "<br>";
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
    elseif($nationalid=="" &&$platenumber==""){        
        $querey_names="SELECT National_id FROM citizen WHERE Name LIKE '%$criminalname' OR Name LIKE '$criminalname%' OR Name LIKE '%$criminalname%' ";
        $result_names=mysqli_query($conn,$querey_names); //execute Names querey
        $names_row_count=mysqli_num_rows($result_names);//get number of rows that match entered name
        $namesarray=array();//store names
        for($x=0;$x<$names_row_count;$x++){
            $names_fetch=mysqli_fetch_assoc($result_names);//fetch data
            $combining=$names_fetch["National_id"];//store in variable
            array_push($namesarray,$combining);//store national id that match condition
        }
        if($lat!="" && $lng!=""){
                        $quereylocation="SELECT National_id , 
            111.111 *
            DEGREES(ACOS(LEAST(1.0, COS(RADIANS(lat))
                * COS(RADIANS($lat))
                * COS(RADIANS(lng - $lng))
                + SIN(RADIANS(lat))
                * SIN(RADIANS($lat))))) AS distance_in_km
        FROM citizen 
        Having  distance_in_km between 0 and 30 
        ORDER BY distance_in_km"
        ;
            $resultlocation=mysqli_query($conn,$quereylocation);
            $location_row_cont=mysqli_num_rows($resultlocation);
            for($x=0;$x<$location_row_cont;$x++){
                $location_fetch=mysqli_fetch_assoc($resultlocation);//fetch data
                $combining=$location_fetch["National_id"];//store in variable
                echo $location_fetch["National_id"]."       ".$location_fetch["distance_in_km"]."<br>";
                array_push($locationarray,$combining);//store national id that match condition
            }
        }
        $querey_hairtexture="SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE hair_texture='$hairtexture') ";
        $result_hairtexture=mysqli_query($conn,$querey_hairtexture);
        $hairtexture_row_count=mysqli_num_rows($result_hairtexture);
        $hairtexturearray=array();
        for($x=0;$x<$hairtexture_row_count;$x++){
            $hairtexture_fetch=mysqli_fetch_assoc($result_hairtexture);
            $combining=$hairtexture_fetch["National_id"];
            array_push($hairtexturearray,$combining);//store national id that match condition
        }
        $querey_haircolor="SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE hair_color='$haircolor') ";
        $result_haircolor=mysqli_query($conn,$querey_haircolor);
        $haircolor_row_count=mysqli_num_rows($result_haircolor);
        $haircolorarray=array();
        for($x=0;$x<$haircolor_row_count;$x++){
            $haircolor_fetch=mysqli_fetch_assoc($result_haircolor);
            $combining=$haircolor_fetch["National_id"];
            array_push($haircolorarray,$combining);//store national id that match condition
        }
        /*$combinearray=array_intersect($namesarray,$haircolorarray);
        print_r($combinearray);*/
        $querey_eyecolor="SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE eye_color='$eyecolor') ";
        $result_eyecolor=mysqli_query($conn,$querey_eyecolor);
        $eyecolor_row_count=mysqli_num_rows($result_eyecolor);
        $eyecolorarray=array();
        for($x=0;$x<$eyecolor_row_count;$x++){
            $eyecolor_fetch=mysqli_fetch_assoc($result_eyecolor);
            $combining=$eyecolor_fetch["National_id"];
            array_push($eyecolorarray,$combining);//store national id that match condition
        }
        $querey_eyecolor="SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE eye_color='$eyecolor') ";
        $result_eyecolor=mysqli_query($conn,$querey_eyecolor);
        $eyecolor_row_count=mysqli_num_rows($result_eyecolor);
        $eyecolorarray=array();
        for($x=0;$x<$eyecolor_row_count;$x++){
            $eyecolor_fetch=mysqli_fetch_assoc($result_eyecolor);
            $combining=$eyecolor_fetch["National_id"];
            array_push($eyecolorarray,$combining);//store national id that match condition
        }
        if($age=="Kid"){
            $querey_age="SELECT National_id FROM citizen WHERE Age between 10 And 15 ";
            $result_age=mysqli_query($conn,$querey_age);
            $age_row_count=mysqli_num_rows($result_age);
            for($x=0;$x<$age_row_count;$x++){
                $age_fetch=mysqli_fetch_assoc($result_age);
                $combining=$age_fetch["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }
        }
        elseif($age=="teenager"){
            $querey_age="SELECT National_id FROM citizen WHERE Age between 16 And 20 ";
            $result_age=mysqli_query($conn,$querey_age);
            $age_row_count=mysqli_num_rows($result_age);
            for($x=0;$x<$age_row_count;$x++){
                $age_fetch=mysqli_fetch_assoc($result_age);
                $combining=$age_fetch["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }
        }
        elseif($age=="young"){
            $querey_age="SELECT National_id FROM citizen WHERE Age between 21 And 30 ";
            $result_age=mysqli_query($conn,$querey_age);
            $age_row_count=mysqli_num_rows($result_age);
            for($x=0;$x<$age_row_count;$x++){
                $age_fetch=mysqli_fetch_assoc($result_age);
                $combining=$age_fetch["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }
        }
        elseif($age=="young middleaged"){
            $querey_age="SELECT National_id FROM citizen WHERE Age between 31 And 40 ";
            $result_age=mysqli_query($conn,$querey_age);
            $age_row_count=mysqli_num_rows($result_age);
            for($x=0;$x<$age_row_count;$x++){
                $age_fetch=mysqli_fetch_assoc($result_age);
                $combining=$age_fetch["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }


        }
        elseif($age=="old middleadged"){
            $querey_age="SELECT National_id FROM citizen WHERE Age between 41 And 50 ";
            $result_age=mysqli_query($conn,$querey_age);
            $age_row_count=mysqli_num_rows($result_age);
            for($x=0;$x<$age_row_count;$x++){
                $age_fetch=mysqli_fetch_assoc($result_age);
                $combining=$age_fetch["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }
        }
        elseif($age=="old"){
            $querey_age="SELECT National_id FROM citizen WHERE Age between 51 And 60 ";
            $result_age=mysqli_query($conn,$querey_age);
            $age_row_count=mysqli_num_rows($result_age);
            for($x=0;$x<$age_row_count;$x++){
                $age_fetch=mysqli_fetch_assoc($result_age);
                $combining=$age_fetch["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }
        }
        elseif($age=="very old"){
            $querey_age="SELECT National_id FROM citizen WHERE Age between 61 And 70 ";
            $result_age=mysqli_query($conn,$querey_age);
            $age_row_count=mysqli_num_rows($result_age);
            for($x=0;$x<$age_row_count;$x++){
                $age_fetch=mysqli_fetch_assoc($result_age);
                $combining=$age_fetch["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }
        }
        elseif($age=="very very old"){
            $querey_age="SELECT National_id FROM citizen WHERE Age between 71 And 80 ";
            $result_age=mysqli_query($conn,$querey_age);
            $age_row_count=mysqli_num_rows($result_age);
            for($x=0;$x<$age_row_count;$x++){
                $age_fetch=mysqli_fetch_assoc($result_age);
                $combining=$age_fetch["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }
        }
        $querey_beard="SELECT National_id FROM citizen WHERE National_id IN (SELECT National_id FROM physical_features WHERE beard_style='$beard') ";
        $result_beard=mysqli_query($conn,$querey_beard);
        $beard_row_count=mysqli_num_rows($result_beard);
        $beardarray=array();
        for($x=0;$x<$beard_row_count;$x++){
            $beard_fetch=mysqli_fetch_assoc($result_beard);
            $combining=$beard_fetch["National_id"];
            array_push($beardarray,$combining);//store national id that match condition
        }
        if($body=="under weight"){
            $querey_body="SELECT National_id FROM physical_features WHERE weightt/pow((height/100),2) between 0 And 18.5 ";
            $result_body=mysqli_query($conn,$querey_body);
            $body_row_count=mysqli_num_rows($result_body);
            for($x=0;$x<$body_row_count;$x++){
                $body_fetch=mysqli_fetch_assoc($result_body);
                $combining=$body_fetch["National_id"];
                array_push($bodyarray,$combining);//store national id that match condition
            }
        }
        elseif($body=="normal weight"){
            $querey_body="SELECT National_id FROM physical_features WHERE weightt/pow((height/100),2) between 18.6 And 24.9 ";
            $result_body=mysqli_query($conn,$querey_body);
            $body_row_count=mysqli_num_rows($result_body);
            for($x=0;$x<$body_row_count;$x++){
                $body_fetch=mysqli_fetch_assoc($result_body);
                $combining=$body_fetch["National_id"];
                array_push($bodyarray,$combining);//store national id that match condition
            }
        }
        elseif($body=="Over Weight"){
            $querey_body="SELECT National_id FROM physical_features WHERE weightt/pow((height/100),2) between 25.0 And 29.9 ";
            $result_body=mysqli_query($conn,$querey_body);
            $body_row_count=mysqli_num_rows($result_body);
            for($x=0;$x<$body_row_count;$x++){
                $body_fetch=mysqli_fetch_assoc($result_body);
                $combining=$body_fetch["National_id"];
                array_push($bodyarray,$combining);//store national id that match condition
            }
        }
        elseif($body=="Obese"){
            $querey_body="SELECT National_id FROM physical_features WHERE weightt/pow((height/100),2) between 30.0 And 34.9 ";
            $result_body=mysqli_query($conn,$querey_body);
            $body_row_count=mysqli_num_rows($result_body);
            for($x=0;$x<$body_row_count;$x++){
                $body_fetch=mysqli_fetch_assoc($result_body);
                $combining=$body_fetch["National_id"];
                array_push($bodyarray,$combining);//store national id that match condition
            } 
        }
        $querey_skin="SELECT National_id FROM physical_features WHERE National_id IN (SELECT National_id FROM physical_features WHERE skin_color='$skincolor') ";
        $result_skin=mysqli_query($conn,$querey_skin);
        $skin_row_count=mysqli_num_rows($result_skin);
        $skinarray=array();
        for($x=0;$x<$skin_row_count;$x++){
            $skin_fetch=mysqli_fetch_assoc($result_skin);
            $combining=$skin_fetch["National_id"];
            array_push($skinarray,$combining);//store national id that match condition
        }
        if($height=="short"){
            $querey_height="SELECT National_id FROM physical_features WHERE height between 150 And 160 ";
            $result_height=mysqli_query($conn,$querey_height);
            $height_row_count=mysqli_num_rows($result_height);
            for($x=0;$x<$height_row_count;$x++){
                $height_fetch=mysqli_fetch_assoc($result_height);
                $combining=$height_fetch["National_id"];
                array_push($heightarray,$combining);//store national id that match condition
            }
        }
        elseif($height=="normal"){
            $querey_height="SELECT National_id FROM physical_features WHERE height between 161 And 170 ";
            $result_height=mysqli_query($conn,$querey_height);
            $height_row_count=mysqli_num_rows($result_height);
            for($x=0;$x<$height_row_count;$x++){
                $height_fetch=mysqli_fetch_assoc($result_height);
                $combining=$height_fetch["National_id"];
                array_push($heightarray,$combining);//store national id that match condition
            }
        }
        elseif($height=="tall"){
            $querey_height="SELECT National_id FROM physical_features WHERE height between 171 And 180 ";
            $result_height=mysqli_query($conn,$querey_height);
            $height_row_count=mysqli_num_rows($result_height);
            for($x=0;$x<$height_row_count;$x++){
                $height_fetch=mysqli_fetch_assoc($result_height);
                $combining=$height_fetch["National_id"];
                array_push($heightarray,$combining);//store national id that match condition
            }
        }
        elseif($height=="very tall"){
            $querey_height="SELECT National_id FROM physical_features WHERE height between 181 And 190 ";
            $result_height=mysqli_query($conn,$querey_height);
            $height_row_count=mysqli_num_rows($result_height);
            for($x=0;$x<$height_row_count;$x++){
                $height_fetch=mysqli_fetch_assoc($result_height);
                $combining=$height_fetch["National_id"];
                array_push($heightarray,$combining);//store national id that match condition
            }
        }
        elseif($height=="very very tall"){
            $querey_height="SELECT National_id FROM physical_features WHERE height between 191 And 210 ";
            $result_height=mysqli_query($conn,$querey_height);
            $height_row_count=mysqli_num_rows($result_height);
            for($x=0;$x<$height_row_count;$x++){
                $height_fetch=mysqli_fetch_assoc($result_height);
                $combining=$height_fetch["National_id"];
                array_push($heightarray,$combining);//store national id that match condition
            }
        }
        if(count($namesarray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++){
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($namesarray,$combining);//store national id that match condition
            }
        }
        if(count($hairtexturearray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++){
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($hairtexturearray,$combining);//store national id that match condition
            }
        }
        if(count($haircolorarray)==0)
        {
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++){
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($haircolorarray,$combining);//store national id that match condition
            }
        }
        if(count($eyecolorarray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++){
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($eyecolorarray,$combining);//store national id that match condition
            }
        }
        if(count($agearray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++){
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($agearray,$combining);//store national id that match condition
            }
        }
        if(count($beardarray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++){
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($beardarray,$combining);//store national id that match condition
            }
        }
        if(count($bodyarray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++){
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($bodyarray,$combining);//store national id that match condition
            }
        }
        if(count($skinarray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++)
            {
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($skinarray,$combining);//store national id that match condition
            }
        }
        if(count($heightarray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++)
            {
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($heightarray,$combining);//store national id that match condition
            }
        }
        if(count($locationarray)==0){
            $final_quere="SELECT National_id FROM citizen";
            $finalquereresult=mysqli_query($conn,$final_quere);
            $finalquererowcount=mysqli_num_rows($finalquereresult);
            for($x=0;$x<$finalquererowcount;$x++){
                $rowfinalquery=mysqli_fetch_assoc($finalquereresult);
                $combining=$rowfinalquery["National_id"];
                array_push($locationarray,$combining);//store national id that match condition
            }
        }
        $combinearray=array_intersect($namesarray,$hairtexturearray,$haircolorarray,$eyecolorarray,$agearray,$beardarray,$bodyarray,$skinarray,$heightarray,$locationarray);  
        /*$combinearray=array_intersect($namesarray,$hairtexturearray,$eyecolorarray);*/
        /*print_r($combinearray);*/
        /*echo  ($combinearray[0] ?? null);
        if(($combinearray[1]??null)=="")
        {
            echo"hi";
        }*/
        for($xx=0;$xx<max(array_keys($combinearray))+1;$xx++){
            if(($combinearray[$xx]??null)!=""){   
                $finalnational=$combinearray[$xx];
                $finalsql="SELECT Name,National_id from citizen WHERE National_id='$finalnational'
                UNION
                SELECT image_url , id  FROM images WHERE National_id='$finalnational'";
                $finalfinalquery=mysqli_query($conn,$finalsql);
                $counter=0;
                while($finalfetch=mysqli_fetch_assoc($finalfinalquery)){
                    if($counter==1){
                        /*echo "Image:path:  "."uploads/" .$finalfetch['Name']."  ";
                        echo "Image id:  ".$finalfetch['National_id']."  ";*/
                         array_push($htmlimagepath,$finalfetch['Name']);
                         /*"Image id:  ".$finalfetch['National_id']."  ";*/
                    }
                    else{
                        /*echo "Name: " . $finalfetch['Name'] ."      ";
                        echo "National id: ".$finalfetch['National_id']."        ";*/
                        array_push($htmlname,$finalfetch['Name']);
                        array_push($htmlnationalid,$finalfetch['National_id']);
                        $counter++;
                    }                
                }
                $counter=0;
            }
        }
        $_SESSION['htmlname']=$htmlname;
        $_SESSION['htmlnationalid']=$htmlnationalid;
        $_SESSION['htmlimagepath']=$htmlimagepath;
        header("Location: ../outputsearch.php");
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
    <link rel="stylesheet" href="style_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Search ouput</title>
</head>
<body>
    <div >
        <table class="table-adjust">
            <tr>
                <th style="padding:10px;" colspan="2">Result</th>
            </tr>
            <tr>
                <td class="trinf">
                    <h1>
                        <?php
                        /*$htmlname=$_SESSION['htmlname'];
                        $htmlnationalid=$_SESSION['htmlnationalid'];
                        $htmlimagepath= $_SESSION['htmlimagepath'];*/
                        for($i=0;$i<count($outputvideo);$i++){
                            $sql="SELECT Name From citizen WHERE National_id='$outputvideo[$i]' ";
                            $result=mysqli_query($conn,$sql);
                            $row=mysqli_fetch_assoc($result);
                        echo "Name: ".$row['Name']." , National id: ".'<a target="_blank" href="../ouputinformation.php?subject='.$outputvideo[$i].'">'.$outputvideo[$i]."</a>"." <br>";
                        }
                        ?>
                    </h1>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>