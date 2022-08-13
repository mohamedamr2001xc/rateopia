<?php
//parameters to connect to database
$dbHost="localhost";
$dbUser="root";
$dbPass="";
$dbName="citizen_rating_system";
//connect to database
$conn=mysqli_connect($dbHost,$dbUser,$dbPass,$dbName);

if(!$conn){
    die("Database connection failed");

}





?>