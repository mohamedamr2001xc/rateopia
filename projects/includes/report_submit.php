<?php
session_start();
require 'database.php';




$_SESSION['key'] = bin2hex(random_bytes(32));
$csrf = hash_hmac('sha256', 'this is some string: 0_login.php', $_SESSION['key']);
$_SESSION['csrf'] = $csrf;


$national_id = $_GET['subject'];
if (empty($national_id) || !is_numeric($national_id)) {
    header("Location: ../0_login.php");
    exit();
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

<body>
    <?php include 'user_navbar.php'; ?>

    <section>
        <div class="container">
            <div class="form__input-group">

                <div>
                    <datalist id="ice-cream-flavors" class="searchList">
                        <?php
                        $sql = "SELECT * From incidents_normal UNION SELECT * From incidents_postive ";
                        $result = mysqli_query($conn, $sql);

                        $rowcount = mysqli_num_rows($result);
                        for ($i = 0; $i < $rowcount; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            echo '<option value="' . $row['Namee'] . '">' . $row['Namee'] . "</option>";
                        }
                        ?>
                    </datalist>
                    <datalist id="ice-cream-flavors7" class="searchList">
                        <?php
                        $sql = "SELECT * From incidents_postive";
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
                    <a href=<?php echo $image_path ?>><img style="width:300px;height:300px;float:left;border-radius:50%;" src=<?php echo $image_path ?> alt="picture"></a>
                </div>
                <form name="myform" onsubmit="return validate();" action="report-inc.php" method="post" enctype="multipart/form-data">

                    <div style="width:50%;float:right;">
                        <div class="form__input-group">
                            <input style="width:75%;float:left;" autofocus placeholder="Select type of incident " class="form__input" list="ice-cream-flavors" id="ice-cream-choice" name="inc_type" />
                            <a class="smallbtn" style="width:20%;float:right;" href="../1_suggestion.php">Add Suggestion</a>
                            <textarea class="message__input" name="message" autofocus placeholder="More details about your interaction with the user."></textarea><br>

                        </div>
                        <div class="form__input-group">
                            <legend>Attach Evidence</legend>
                            <input class="form__button" style="float:left;" type="file" name="my_image" id="fileToUpload">
                        </div>

                        <div class="form__input-group">
                            <legend>Location:</legend>
                            <p style="color:red;font-size:small;text-align:center;">*Use left click on the map to view latidude and longtude
                     <br>then copy-paste them in their specific feilds in the search form*</p>
                     <div id="map"></div>
                            <input name="lat" type="text" id="name" class="form__input" autofocus placeholder="Latitude...">
                            <input name="lng" type="text" id="name" class="form__input" autofocus placeholder="Longitude...">
                        </div>

                        <input class="form__button" type="submit" name="submit" value="Submit Report">
                        <label style="font-size:15px;"><b>Note:</b> the reporter identity will be hidden from non-authorized users for your own privacy .</label><br>
                        <input type="hidden" name='csrf' value=<?php echo $csrf ?>>
                    </div>
                </form>
            </div>
    </section>
</body>

</html>
<?php include '../footer.php'; ?>
<script>
    function validate() {
        let letters = /^[A-Za-z]+$/;
        let isnum = /^\d+$/;
        let alphanum = /^[0-9a-zA-Z]+$/;
        let incident = document.myform.inc_type.value;
        incident = incident.toLowerCase();
        incidentcheck = incidentcheck.replace(/\s/g, '');
        if (!letters.test(incident) || incident.includes("select") || incident.includes("insert") || incident.includes("delete") || incident.includes("update") || incident.includes("grant") || incident.includes("revoke")) {
            alert("Enter valid username");
            return false;

        }
        let details = document.myform.message.value;
        if (!alphanum.letters(incidentcheck)) {
            alert("Enter valid details");
            return false;

        }
        let lat = document.myform.lat.value;
        lat = lat.toLowerCase();
        let lng = document.myform.lng.value;
        lng = lng.toLowerCase();
        let latcheck = lat.replace('.', '');
        let lngcheck = lng.replace('.', '');
        if (isnum.test(latcheck) == false || isnum.test(lngcheck) == false) {

            alert("Enter valid latitude and longitude");
            return false;
        }
        return true;
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiU10uC7ln1nu1_PfD1e7a9F3uLMa0J7E&callback=myMap"></script>

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