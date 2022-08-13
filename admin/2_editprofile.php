<?php
require 'includes/database.php';
?>
<?php

if (isset($_POST['submit'])) {
    session_start();
    $_SESSION['key'] = bin2hex(random_bytes(32));
    $csrf = hash_hmac('sha256', 'this is some string: 0_login.php', $_SESSION['key']);
    $_SESSION['csrf'] = $csrf;
    if (!isset($_SESSION['ID'])) {
        header("Location: 2_login.php");
    }

    if (isset($_POST['National_id'])) {
        $nationalid = $_POST['National_id'];
    }

    if (!is_numeric($nationalid)) {
        $header("Location: 2_searchprofile.php?error=nationaliddeosntexist");
    }



    if ($nationalid != "") {
        if (isset($_POST['National_id'])) {
            $sql = "SELECT * FROM citizen WHERE id=?";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: register.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $nationalid);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $row = mysqli_fetch_assoc($result);
                $nationalid = $row['National_id'];
            }
        }
        $_SESSION['National_id'] = $nationalid;
        $sql = "SELECT * FROM physical_features WHERE National_id=?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: register.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $nationalid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row3 = mysqli_fetch_assoc($result);
            $row_count = mysqli_num_rows($result);
            $nationalid = $row3['National_id'];
            $row2 = $row3;
        }
        /*$result = mysqli_query($conn, $sql2);
        $row_count2 = mysqli_num_rows($result2);*/
        if ($row_count == 0) {
            header("Location: 2_searchprofile.php?error=nationaliddeosntexist");
        }
    }
    $image_path = "face_rec/faces/" . $nationalid . ".jpg";
} else {
    header("Location: 2_login.php");
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
    <title>Profile Details</title>
</head>
<script>
    function validate() {
        let letters = /^[A-Za-z]+$/;
        let isnum = /^\d+$/;
        let alphanum = /^[0-9a-zA-Z]+$/;
        let rating = document.myform.rating.value;


        let fullname = document.myform.name.value;
        fullname = fullname.toLowerCase();
        fullname = fullname.replace(/\s/g, '');

        let lat = document.myform.lat.value;
        lat = lat.toLowerCase();
        let lng = document.myform.lng.value;
        lng = lng.toLowerCase();
        let latcheck = lat.replace('.', '');
        let lngcheck = lng.replace('.', '');
        let emailval = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        let phonenum = document.myform.phone_number.value;
        phonenum = phonenum.toLowerCase();
        let plate = document.myform.plate_number.value;
        plate = plate.toLowerCase();
        let job = document.myform.job.value;
        job = job.replace(/\s/g, '');
        job = job.toLowerCase();
        let weight = document.myform.weight.value;
        weight = weight.toLowerCase();
        let height = document.myform.height.value;
        weight = weight.toLowerCase();
        let hairtexture = document.myform.hair_texture.value;
        height = height.toLowerCase();
        let haircolor = document.myform.hair_color.value;
        let beard = document.myform.beard.value;

        /*if (fullname.inludes(" ")) {
            fullname = fullname.replace(/\s/g, '');
            alert(fullname);
            return false;
        }*/
        if (weight.length > 0) {

            if (!isnum.test(weight) || weight < 0) {
                alert("Enter valid weight");
                return false;
            }
        }
        if (height.length > 0) {
            if (!isnum.test(height) || height < 0) {
                alert("Enter valid height");
                return false;
            }
        }

        if (fullname.length > 0) {

            if (fullname.length < 13 || !letters.test(fullname) || fullname.includes("select") || fullname.includes("insert") || fullname.includes("delete") || fullname.includes("update")) {
                alert("Enter valid username");
                return false;

            }
        }
        if (job.length > 0) {
            job = job.toLowerCase();

            if (!letters.test(job) || job.includes("select") || job.includes("insert") || job.includes("delete") || job.includes("update")) {
                alert("Enter valid job");
                return false;

            }
        }
        if (lat.length > 0) {
            if (isnum.test(latcheck) == false) {

                alert("Enter valid latitude");
                return false;
            }
        }
        if (lng.length > 0) {
            if (isnum.test(lngcheck) == false) {

                alert("Enter valid longitude");
                return false;
            }
        }

        let email = document.myform.email.value;
        if (email.length > 0) {
            if (emailval.test(email) == false) {
                alert("enter a valid mail");
                return false;
            }
        }
        if (phonenum.length > 0) {

            if (!isnum.test(phonenum) || phonenum.length != 11) {
                alert("enter a valid phone number");
                return false;

            }
        }
        if (rating.length > 0) {
            if (rating < 0) {
                alert("enter a valid rating");
                return false;

            }
        }
        if (plate.length > 0) {
            if (!alphanum.test(plate) || plate.length != 6) {
                alert("enter a valid plate number");
                return false;

            }
        }
        if (hairtexture.length > 0) {
            if (!(hairtexture == 'bald' || hairtexture == 'straight' || hairtexture == 'wavy' || hairtexture == 'curly')) {
                alert("enter a valid hair texture");
                return false;

            }
        }
        if (haircolor.length > 0) {
            if (!(haircolor == 'black' || haircolor == 'light Brown' || haircolor == 'dark brown' || haircolor == 'blond' || haircolor == 'White')) {

                alert("enter a valid hair color");
                return false;

            }
        }
        if (beard.length > 0) {
            if (!(beard == 'No Beard' || beard == 'Long Beard' || beard == 'Normal Beard' || beard == 'Short Beard')) {
                alert("enter a valid beard type");
                return false;

            }
        }
        return true;
    }
</script>

<body>
    <script src="scripts.js"></script>
    <?php include 'admin_navbar.php'; ?>

    <form id="regForm" name="myform" onsubmit="return validate();" action="includes/editprofileadmin.php" method="post" enctype="multipart/form-data">
        <section>
            <div class="container">
                <div class="image">
                    <a href="#"><img width="300" height="300" src=<?php echo $image_path  ?>></a><br>
                </div>
                <div class="form__input-group">
                    <legend style="text-align:center;">Edit Image</legend>
                    <input class="form__button" style="width:50%;margin-left: 25%;" name="my_image" type="file">
                    <div class="form__input-error-message"></div>
                </div>
                <table class="table-adjust" width="200" height="200">
                    <tr>
                        <th style="padding:10px;" colspan="3">User Information: </th>
                    </tr>
                    <tr>
                        <td class="trinf"><b>Details</b></td>
                        <td class="trinf"><b>Old</b></td>
                        <td class="trinf"><b>New</b></td>
                    </tr>
                    <tr>
                        <td class="trinf">Name</td>
                        <td class="trinf"><?php echo $row['Name'] ?></td>
                        <td class="trinf"><input class="form__input" type="text" name="name" placeholder="Edit Name"></td>
                    </tr>
                    <tr>
                        <td class="trinf">Rating</td>
                        <td class="trinf"><?php echo $row['Rating'] ?></td>
                        <td class="trinf"><input class="form__input" name="rating" type="text" placeholder="Edit Rating"></td>
                    </tr>
                    <tr>
                        <td class="trinf">Phone Number</td>
                        <td class="trinf"><?php echo $row['phone_number'] ?></td>
                        <td class="trinf"><input class="form__input" name="phone_number" type="text" placeholder="Edit Phone Number"></td>
                    </tr>
                    <tr>
                        <td class="trinf">Email</td>
                        <td class="trinf"><?php echo $row['Email'] ?></td>
                        <td class="trinf"><input class="form__input" name="email" type="email" placeholder="Edit Email"></td>
                    </tr>
                    <tr>
                        <td class="trinf">Longitude</td>
                        <td class="trinf"><?php echo $row['lng'] ?></td>
                        <td class="trinf"><input class="form__input" name="lng" type="text" placeholder="Edit Longitude"></td>
                    </tr>
                    <tr>
                        <td class="trinf">Latitude</td>
                        <td class="trinf"><?php echo $row['lat'] ?></td>
                        <td class="trinf"><input class="form__input" name="lat" type="text" placeholder="Edit Latitude"></td>
                    </tr>
                    <tr>
                        <td class="trinf">Job</td>
                        <td class="trinf"><?php echo $row['Job'] ?></td>
                        <td class="trinf"><input class="form__input" name="job" type="text" placeholder="Edit Job">
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Car plate number</td>
                        <td class="trinf"><?php echo $row['Plate_number'] ?></td>
                        <td class="trinf"><input class="form__input" name="plate_number" type="text" placeholder="Edit Car Plate Number"></td>
                    </tr>
                    <tr>
                        <th style="padding:10px;" colspan="3">Physical Features</th>
                    </tr>
                    <tr>
                        <td class="trinf"><b>Details</b></td>
                        <td class="trinf"><b>Old</b></td>
                        <td class="trinf"><b>New</b></td>
                    </tr>
                    <tr>
                        <td class="trinf">Hair texture</td>
                        <td class="trinf"><?php echo $row2['hair_texture'] ?></td>
                        <td class="trinf"><select name="hair_texture" class="trinf form__input" id="hair_texture">
                                <option value="">Select hair texture</option>
                                <option value="bald">bald</option>
                                <option value="straight">straight</option>
                                <option value="wavy">wavy</option>
                                <option value="curly">curly</option>
                            </select>
                    </tr>
                    <tr>
                        <td class="trinf">Hair color</td>
                        <td class="trinf"><?php echo $row2['hair_color'] ?></td>
                        <td class="trinf"><select name="hair_color" class="trinf form__input" id="hair_color">
                                <option value="">Select hair color</option>
                                <option value="black">black</option>
                                <option value="light Brown">light Brown</option>
                                <option value="dark brown">dark brown</option>
                                <option value="blond">blond</option>
                                <option value="White">White</option>
                            </select>
                    </tr>
                    <tr>
                        <td class="trinf">Beard style</td>
                        <td class="trinf"><?php echo $row2['beard_style'] ?></td>
                        <td class="trinf"><select name="beard" class="trinf form__input" id="beard">
                                <option value="">Select Beard style</option>
                                <option value="No Beard">No Beard</option>
                                <option value="Long Beard">Long Beard</option>
                                <option value="Normal Beard">Normal Beard</option>
                                <option value="Short Beard">Short Beard</option>
                            </select>
                    </tr>
                    <tr>
                        <td class="trinf">Weight</td>
                        <td class="trinf"><?php echo $row2['weightt'] ?></td>
                        <td class="trinf"><input class="form__input" type="text" name="weight" placeholder="Edit Weight"></td>
                    </tr>
                    <tr>
                        <td class="trinf">Height</td>
                        <td class="trinf"><?php echo $row2['height'] ?></td>
                        <td class="trinf"><input class="form__input" type="text" name="height" placeholder="Edit height"></td>
                    </tr>
                </table>
                <input type="hidden" name="csrf" value=<?php echo $csrf; ?>>
                <div style="display:flex;">
                    <input class="submitButton" style="width:30%;margin-right:5px;" type="reset">
                    <button class="submitButton" style="width:30%;margin-right:5px;" type="submit" name="submit">Submit</button>
                    <input class="submitButton" style="width:30%;" type="submit" value="Delete" name="delete">
                </div>
            </div>
        </section>
    </form>
</body>

</html>