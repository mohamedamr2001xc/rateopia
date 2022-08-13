<?php
session_start();
require_once 'includes/database.php';
require_once 'includes/upload.php';
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

</head>
<body>
<nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>

        <ul class="imagecontainer">
            <li><a class="imagelink" href="2_Dashboard.php"><img class="imagelogo" src="Media/Rateopia.png"><span class="logotxt"> Rateopia </span></a></li>

        </ul>
        <ul>
            
        </ul>
    </nav>