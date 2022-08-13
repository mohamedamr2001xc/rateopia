<?php
session_start();
require 'includes/database.php';
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
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <title>View Profile</title>
</head>
<body>
    <script src="scripts.js"></script>
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <ul class="imagecontainer">
            <li><a class="imagelink" href="1_Dashboard.php"><img class="imagelogo" src="Media/Rateopia_Black.png"><span
                        class="logotxt"> Rateopia </span></a></li>
        </ul>
        <ul class="dashNav">
            <li><a href="1_Search.php"><i class="fa fa-fw fa-search"></i>Search</a></li>
            <li><a href="1_Changes.php">Recent Changes</a></li>
            <li><a href="0_Login.php">Logout</a></li>
        </ul>
    </nav>
    <section>
        <div class="container">
            <div style="width:45%;float:left;"class="image">
                <a href="#"><img width="300" height="300" src=<?php echo $image_path ?>></a><br>
            </div>
            <form action="includes/report-inc.php" method="post"  enctype="multipart/form-data">
                <div style="width:50%;float:right;">
                    <textarea class="message__input" name="message" autofocus placeholder="More details about your interaction with the user."></textarea><br>
                    <legend>Attach Evidence</legend>
                    <input class="form__button"style="float:left;"  type="file" name="my_image" id="fileToUpload">
                    <br>
                    <input name="rating" style="display: inline; width:85%;"type="range" value="0" min="0" max="100" oninput="this.nextElementSibling.value = this.value/20" id="myRange">
                    <output style="display: inline;font-weight:bold; width:15%;">0</output>
                    <input class="form__button" type="submit" name="submit" value="Submit Report">
                    <input type="checkbox" id="Authorities" name="Authorities" value="1">
                    <label style="font-size:15px;font-weight:bold;" for="Authorities">Send to authorities</label><br>
                    <label style="font-size:13px;"><b>Note:</b> This button is for very serious and sensitive cases check this button under your own responsibility.</label><br>
                </div>
            </form>
        </div>
    </section>
</body>
</html>