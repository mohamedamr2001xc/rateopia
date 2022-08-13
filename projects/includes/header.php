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
    <title>Document</title>
    <link rel="stylesheet" href="includes/style.css">
</head>
<body>
<nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
      <i class="fas fa-bars"></i>
    </label>

    <ul class="imagecontainer">
      <li><a class="imagelink" href="index.php"><img class="imagelogo" src="Media/Rateopia_Black.png"><span class="logotxt"> Rateopia </span></a></li>
      </ul>
      <ul>
      <!---<li><a href="ContactUs.php">Contact us</a></li>--->   
   </ul>
    <ul>
      <li><a href="index.php">About Us</a></li>
    </ul>
  </nav>