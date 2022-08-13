<?php
 require 'database.php'
?>

<?php
if(isset($_POST['submit'])){
    $hairtexture=$_POST['hair_texture'];
    $haircolor=$_POST['hair_color'];
    $beard=$_POST['beard'];
    $skincolor=$_POST['skin_color'];
    $height=$_POST['height'];
    $eyecolor=$_POST['eye_color'];
    $weight=$_POST['weight'];
    $sql="SELECT National_id From physical_features WHERE height =0 ";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $row2=$row["National_id"];
    $sql="UPDATE physical_features SET hair_texture = '$hairtexture' , hair_color='$haircolor' , beard_style='$beard' , skin_color='$skincolor' , height='$height' , eye_color='$eyecolor' , weightt='$weight'	WHERE National_id = $row2";
    $result = $conn->query($sql);
    header("Location: ../0_login.php");
}
else{
    header("Location: ../moreinfo.php?error=errorexist");
}
?>