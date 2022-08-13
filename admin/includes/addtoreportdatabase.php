<?php
session_start();
require 'database.php';
$national_id=$_GET['subject'];
$sql="SELECT * From citizen WHERE National_id='$national_id'";
$result=mysqli_query($conn,$sql);
$row=mysqli_fetch_assoc($result);
$sql2="SELECT image_url FROM images WHERE National_id='$national_id'";
$result2=mysqli_query($conn,$sql2);
$row2=mysqli_fetch_assoc($result2);
$image_path="face_rec/faces/".$national_id.".jpg";
$_SESSION['National_id2']=$national_id;

$national_id=$_SESSION['National_id2']; //national id of accused
$reporter=$_SESSION['reporter']; //national id of reporter
$sql="INSERT INTO reports (report_rating,report_info,document,National_id_1,reportsNational_id_2) Values (NULL,'',NULL,'$reporter','$national_id') ";
mysqli_query($conn,$sql);

header("Location: ../1_Report.php?subject=$national_id");



?>