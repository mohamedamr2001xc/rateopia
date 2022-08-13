<?php
session_start();
require 'database.php';
$national_id = $_GET['subject'];
if (empty($national_id)) {
    header("Location: 0_login.php");
}
include 'database.php';
$sql = "SELECT * From citizen WHERE National_id='$national_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$sql2 = "SELECT image_url FROM images WHERE National_id='$national_id'";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$image_path = "../face_rec/faces/" . $national_id . ".jpg";
$_SESSION['National_id2'] = $national_id;
$_SESSION['key'] = bin2hex(random_bytes(32));
$csrf = hash_hmac('sha256', 'this is some string: 0_login.php', $_SESSION['key']);
$_SESSION['csrf'] = $csrf;
$national_id = $_SESSION['National_id2']; //national id of accused
$reporter = $_SESSION['reporter']; //national id of reporter
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <title>Submit Report</title>
</head>
<style>
    .smallbtn {
        float: right;
        width: 15%;
        height: 100%;
        text-align: center;
        display: grid;
        padding: 10px;
        font-size: 12px;
        color: #ffffff;
        border: none;
        border-radius: var(--border-radius);
        outline: none;
        cursor: pointer;
        background: #000;
    }

    .smallbtn:hover {
        background: #20a668;
    }
</style>
<script>
    var crimCat = {
        "Felonies": [<?php
                        $sql = "SELECT * From crimes";
                        $result = mysqli_query($conn, $sql);
                        $rowcount = mysqli_num_rows($result);
                        for ($i = 0; $i < $rowcount; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            if ($i == ($rowcount - 1)) {
                                if ($row['Felonies'] == "") {
                                    continue;
                                } else {
                                    echo '"' . $row['Felonies'] . '"';
                                }
                            } else {
                                if ($row['Felonies'] == "") {
                                    continue;
                                } else {
                                    echo '"' . $row['Felonies'] . '"' . ",";
                                }
                            }
                        }
                        ?>],
        "Misdemeanors": [<?php
                            $sql = "SELECT * From crimes";
                            $result = mysqli_query($conn, $sql);
                            $rowcount = mysqli_num_rows($result);
                            for ($i = 0; $i < $rowcount; $i++) {
                                $row = mysqli_fetch_assoc($result);
                                if ($i == ($rowcount - 1)) {
                                    if ($row['Misdemeanors'] == "") {
                                        continue;
                                    } else {
                                        echo '"' . $row['Misdemeanors'] . '"';
                                    }
                                } else {
                                    if ($row['Misdemeanors'] == "") {
                                        continue;
                                    } else {
                                        echo '"' . $row['Misdemeanors'] . '"' . ",";
                                    }
                                }
                            }
                            ?>],
        "Infractions": [<?php
                        $sql = "SELECT * From crimes";
                        $result = mysqli_query($conn, $sql);
                        $rowcount = mysqli_num_rows($result);
                        for ($i = 0; $i < $rowcount; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            if ($i == ($rowcount - 1)) {
                                echo '"' . $row['Infractions'] . '"';
                                if ($row['Infractions'] == "") {
                                    continue;
                                } else {
                                    echo '"' . $row['Infractions'] . '"';
                                }
                            } else {
                                if ($row['Infractions'] == "") {
                                    continue;
                                } else {
                                    echo '"' . $row['Infractions'] . '"' . ",";
                                }
                            }
                        }
                        ?>]
    }
    window.onload = function() {
        var catSel = document.getElementById("catSel");
        var crimSel = document.getElementById("crimSel");
        for (var x in crimCat) {
            catSel.options[catSel.options.length] = new Option(x, x);
        }
        catSel.onchange = function() {
            crimSel.length = 1;
            var z = crimCat[this.value];
            for (var i = 0; i < z.length; i++) {
                crimSel.options[crimSel.options.length] = new Option(z[i]);
            }
        }
    }
    function myMap() {
            var mapProp = {
                center: new google.maps.LatLng(31, 31),
                zoom: 5,
            };
            var map = new google.maps.Map(document.getElementById("map"), mapProp);
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(map, event.latLng);
            });
            function placeMarker(map, location) {
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
                var infowindow = new google.maps.InfoWindow({
                    content: 'Latitude: ' + location.lat() +
                        '<br>Longitude: ' + location.lng()
                });
                infowindow.open(map, marker);
            }
            infoWindow = new google.maps.InfoWindow();
            const locationButton = document.createElement("a");
            locationButton.textContent = "Pan to Current Location";
            locationButton.classList.add("map");
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
            locationButton.addEventListener("click", () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            infoWindow.setPosition(pos);
                            infoWindow.setContent("Location found.");
                            infoWindow.open(map);
                            map.setCenter(pos);
                        },
                        () => {
                            handleLocationError(true, infoWindow, map.getCenter());
                        }
                    );
                } else {
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            });
        }
    </script>
    
<body>
    <script src="scripts.js"></script>
    <?php include 'user_navbar.php' ?>
    <section>

        <div class="container" style="float:left;width:40%">
            <div class="form__input-group">

                <div>
                    <datalist id="ice-cream-flavors" class="searchList">
                        <?php
                        $sql = "SELECT * From incidents_normal";
                        $result = mysqli_query($conn, $sql);

                        $rowcount = mysqli_num_rows($result);
                        for ($i = 0; $i < $rowcount; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            echo '<option value="' . $row['Namee'] . '">' . $row['Namee'] . "</option>";
                        }
                        ?>
                    </datalist>
                </div>
                <div class="image">
                    <a href=<?php echo $image_path ?>><img style="width:300px;height:300px;" src=<?php echo $image_path ?> alt="picture"></a>
                </div>
                <form action="report-inc2.php" method="post" enctype="multipart/form-data">
                    <div>
                        <div class="form__input-group">
                            <legend>Select crime category</legend>
                            <label style="font-size:15px;"><b>Note: </b>Please check the table provided and select crime category</label><br>
                            <select name="category" class="form__input" id="catSel">
                                <option selected disabled>Select crime category...</option>
                            </select>
                            <legend>Select crime</legend>
                            <select name="inc_type" class="form__input" id="crimSel">
                                <option selected disabled>Select crime...</option>
                            </select>
                            <br>
                            <textarea class="message__input" name="report_info" autofocus placeholder="More details about your interaction with the user."></textarea><br>
                        </div>
                        <div class="form__input-group">
                            <legend>Attach Evidence</legend>
                            <input class="form__button" style="float:left;" type="file" name="my_image" id="fileToUpload">

                        </div>
                        <div class="form__input-group">
                            <legend>Date</legend>
                            <input name="date" class="form__input" style="float:left;" type="date">
                        </div>
                        <div class="form__input-group">
                            <legend>Time</legend>
                            <input name="time" class="form__input" style="float:left;" type="time">
                        </div>
                        <div class="form__input-group">
                            <legend>Witness</legend>
                            <input name="witness" autofocus placeholder="Witness.." class="form__input" />
                        </div>
                        <div class="form__input-group">
                            <legend>Location:</legend>
                            <p style="color:red;font-size:small;text-align:center;">*Use left click on the map to view latidude and longtude
                     <br>then copy-paste them in their specific feilds in the search form*</p>
                     <div id="map"></div>
                            <input name="lat" type="text" id="name" class="form__input" autofocus placeholder="Latitude...">
                            <input name="lng" type="text" id="name" class="form__input" autofocus placeholder="Longitude...">
                        </div>
                        <input type="hidden" value=<?php echo $csrf; ?>>

                        <input class="form__button" type="submit" name="submit" value="Submit Report">
                        <label style="font-size:15px;"><b>Note:</b> the reporter identity will be hidden from non-authorized users for your own privacy .</label><br>
                    </div>
                </form>
            </div>

        </div>
        <div class="container" style="float:right;width:40%">
            <table class="table-adjust">
                <tr colspan="3">
                    <th class="trinf">Felonies</td>
                    <th class="trinf">Misdemeanors</td>
                    <th class="trinf">Infractions</td>
                </tr>
                <?php
                $sql = "SELECT * From crimes";
                $result = mysqli_query($conn, $sql);
                $rowcount = mysqli_num_rows($result);
                for ($i = 0; $i < $rowcount; $i++) {
                    echo '<tr>';
                    $row = mysqli_fetch_assoc($result);
                    if ($row['Felonies'] != "NULL") {
                        echo '<td class="trinf">' . $row['Felonies'] . "</td>";
                    }

                    if ($row['Misdemeanors'] != "NULL") {
                        echo '<td class="trinf">' . $row['Misdemeanors'] . "</td>";
                    }
                    if ($row['Infractions'] != "NULL") {
                        echo '<td class="trinf">' . $row['Infractions'] . "</td>";
                    }
                }
                echo '</tr>'

                ?>
            </table>
        </div>

    </section>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiU10uC7ln1nu1_PfD1e7a9F3uLMa0J7E&callback=myMap"></script>
</html>
<?php include '../footer.php'; ?>
<style>
        .smallbtn {
            float: right;
            width: 15%;
            height: 100%;
            text-align: center;
            display: grid;
            padding: 10px;
            font-size: 12px;
            color: #ffffff;
            border: none;
            border-radius: var(--border-radius);
            outline: none;
            cursor: pointer;
            background: #000;
        }

        .smallbtn:hover {
            background: #20a668;
        }
        .map {
            width: auto;
            height: auto;
            margin-top: 10px;
            padding: 5px;
            font-size: 15px;
            color: #ffffff;
            border: none;
            border-radius: var(--border-radius);
            outline: none;
            cursor: pointer;
            background: #000;
        }

        .map:hover {
            background: #20a668;
        }
        #map {
            float: right;
            width: 100%;
            height:300px;
            margin-bottom:5px;
            z-index:0;
        }
        img{
            border-radius: 0;
        }
    </style>
