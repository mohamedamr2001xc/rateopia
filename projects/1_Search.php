<?php
require 'includes/database.php';
session_start();
if ($_SESSION['National_id'] == "") {
    header("Location: 0_login.php");
}
if (isset($_GET['error'])) {
    $errortype = $_GET['error'];
}
include 'user_navbar.php';
$nationalidcheck = $_SESSION['National_id'];
$sql = "SELECT * From citizen where National_id='$nationalidcheck' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$nationalidcheck = $row['id'];
$sql = "SELECT * From blocked_users where userid='$nationalidcheck'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    header("Location: 0_login.php?error=blockeduer");
    exit();
}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Search</title>
</head>
<body>
    <section>
        <div class="container">
            <div style="float:left;margin-right: 10px;">
                <form name="myform" id="regForm" onsubmit="return validate();" action="includes/searchresult.php" method="post" enctype="multipart/form-data">
                    <h1>Search By</h1>

                    <legend>National ID:</legend>
                    <div class="form__input-group">
                        <input name="National_id" type="text" id="nationalID.." class="form__input" autofocus placeholder="National ID..">
                    </div>
                    <legend> Plate Number:</legend>
                    <div class="form__input-group">
                        <input type="text" name="plate_number" id="plateNumber" class="form__input" autofocus placeholder="Plate Number..">
                    </div>
                    <legend>Name:</legend>
                    <div class="form__input-group">
                        <input name="Name" type="text" id="name" class="form__input" autofocus placeholder="Full Name...">
                    </div>
                    <legend>Location:</legend>
                    <p style="color:red;font-size:small;text-align:center;">*Use left click on the map to view latidude and longtude
                     <br>then copy-paste them in their specific feilds in the search form*</p>
                     <div id="map"></div>
                     <div class="form__input-group">
                        <input name="lat" type="text" id="name" class="form__input" autofocus placeholder="Latitude...">
                        <input name="lng" type="text" id="name" class="form__input" autofocus placeholder="Longitude...">
                    </div>
                    <legend>Physical Features:</legend>
                    <div>
                        <div class="form__input-group">
                            <label for="hair">Age:</label>
                            <select name="age" class="searchList" id="age...">
                                <option value="">Select Age Range</option>
                                <option value="Kid">10-15</option>
                                <option value="teenager">16-20</option>
                                <option value="young">21-30</option>
                                <option value="young middleaged">31-40</option>
                                <option value="old middleadged">41-50</option>
                                <option value="old">51-60</option>
                                <option value="very old">61-70</option>
                                <option value="very very old">71-80</option>
                            </select>
                        </div>
                        <div class="form__input-group">
                            <label for="hair">Hair Texture:</label>
                            <select name="hair_texture" class="searchList" id="hair_texture">
                                <option value="">Select Hair Texture</option>
                                <option value="bald">Bald</option>
                                <option value="straight">Straight</option>
                                <option value="wavy">Wavy</option>
                                <option value="curly">Curly</option>
                            </select>
                        </div>
                        <div class="form__input-group">
                            <label for="hair">Hair Color:</label>
                            <select name="hair_color" class="searchList" id="hair_color">
                                <option value="">Select Hair Color</option>
                                <option value="black">Black</option>
                                <option value="light Brown">Light Brown</option>
                                <option value="dark brown">Dark Brown</option>
                                <option value="blond">Blonde</option>
                                <option value="White">White</option>
                            </select>
                        </div>
                        <div class="form__input-group">
                            <label for="eye">Eye color:</label>
                            <select name="eye_color" class="searchList" id="eye_color">
                                <option value="">Select Eye Color</option>
                                <option value="brown">Brown</option>
                                <option value="light Brown">Light Brown</option>
                                <option value="dark brown">Dark Brown</option>
                                <option value="green">Green</option>
                                <option value="grey">Grey</option>
                                <option value="blue">Blue</option>
                            </select>
                        </div>
                        <div class="form__input-group">
                            <label for="beard">Choose Beard style:</label>
                            <select name="beard" class="searchList" id="beard">
                                <option value="">Select Beard style</option>
                                <option value="No Beard">No Beard</option>
                                <option value="Long Beard">Long Beard</option>
                                <option value="Normal Beard">Normal Beard</option>
                                <option value="Short Beard">Short Beard</option>
                            </select>
                        </div>
                        <div class="form__input-group">
                            <label for="body">Choose Body Type:</label>
                            <select name="body" class="searchList" id="body">
                                <option value="">Select Body Type</option>
                                <option value="under weight">Under Weight</option>
                                <option value="normal weight">Normal Weight</option>
                                <option value="Over Weight">Over Weight</option>
                                <option value="Obese">Obese</option>
                            </select>
                        </div>
                        <div class="form__input-group">
                            <label for="race">Skin color</label>
                            <select name="skin_color" class="searchList" id="race">
                                <option value="">Select Skin color</option>
                                <option value="White">White</option>
                                <option value="Tanned white">Tanned white</option>
                                <option value="Olive">Olive</option>
                                <option value="Brown">Brown</option>
                                <option value="Black">Black</option>
                            </select>
                        </div>
                        <div class="form__input-group">
                            <label for="height">Choose Height:</label>
                            <select name="height" class="searchList" id="race">
                                <option value="">Select Height</option>
                                <option value="short">150-160</option>
                                <option value="normal">160-170</option>
                                <option value="tall">170-180</option>
                                <option value="very tall">180-190</option>
                                <option value="very very tall">190-210</option>
                            </select>
                        </div>
                    </div>
                    <div class="form__input-group">
                        <legend>Search by a file:</legend>
                        <input class="form__button" style="width:82.5%;" type="file" name="my_image" id="fileToUpload">
                        <a id="bt" class="smallbtn" href="includes/webcam.php" onclick="load()">Open Live Camera</a>
                    </div>

                    <div class="form__input-group">
                        <input class="submitButton" style="float:left;" type="reset">
                        <input id="btv" class="submitButton" style="float:right;" type="submit" value="Search" name="submit" onclick="load()">
                    </div>
                    <i id="load" style="display:none;" class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i>
                </form>
            </div>
        </div>
    </section>
    <script>
                function validate() {
            let letters = /^[A-Za-z]+$/;
            let isnum = /^\d+$/;
            let alphanum = /^[0-9a-zA-Z]+$/;
            let fullname = document.myform.Name.value;
            fullname = fullname.toLowerCase();;
            fullname = fullname.replace(/\s/g, '');
            let nationalid = document.myform.National_id.value;
            nationalid = nationalid.toLowerCase();
            let lat = document.myform.lat.value;
            lat = lat.toLowerCase();
            let lng = document.myform.lng.value;
            lng = lng.toLowerCase();
            let latcheck = lat.replace('.', '');
            let lngcheck = lng.replace('.', '');
            if (fullname.length > 0) {
                if (!letters.test(fullname) || fullname.includes("select") || fullname.includes("insert") || fullname.includes("delete") || fullname.includes("update") || fullname.includes("grant") || fullname.includes("revoke")) {
                    alert("Enter valid username");
                    return false;

                }
            }
            let plate = document.myform.plate_number.value;
            plate = plate.toLowerCase();
            if (plate.length > 0) {
                if (!alphanum.test(plate) || plate.length != 6) {
                    alert("enter a valid plate number");
                    return false;

                }
            }
            if (nationalid.length > 0) {
                if (isnum.test(nationalid) == false || (nationalid.length != 14)) {
                    alert("enter a valid National id");
                    return false;

                }
            }
            if (lat.length > 0 || lng.length > 0) {
                if (isnum.test(latcheck) == false || isnum.test(lngcheck) == false) {

                    alert("Enter valid latitude and longitude");
                    return false;
                }
            }
            let age = document.myform.age.value;
            let hairtexture = document.myform.hair_texture.value;
            let haircolor = document.myform.hair_color.value;
            let eyecolor = document.myform.eye_color.value;
            let beard = document.myform.beard.value;
            let bodytype = document.myform.body.value;
            let skin = document.myform.race.value;
            let height = document.myform.height.value;
            if (hairtexture.length > 0) {
                if (!(hairtexture == 'bald' || hairtexture == 'straight' || hairtexture == 'wavy' || hairtexture == 'curly')) {
                    alert("Enter a valid hair texture");
                    return false;

                }
            }
            if (haircolor.length > 0) {
                if (!(haircolor == 'black' || haircolor == 'light Brown' || haircolor == 'dark brown' || haircolor == 'blond' || haircolor == 'White')) {
                    alert("Enter a valid hair color");
                    return false;

                }
            }
            if (eyecolor.length > 0) {
                if (!(eyecolor == 'brown' || eyecolor == 'light Brown' || eyecolor == 'dark brown' || eyecolor == 'green' || eyecolor == 'grey' || eyecolor == 'blue')) {
                    alert("Enter a valid eye color");
                    return false;

                }
            }
            if (beard.length > 0) {
                if (!(beard == 'No Beard' || beard == 'Long Beard' || beard == 'Normal Beard' || beard == 'Short Beard')) {
                    alert("Enter a valid beard");
                    return false;

                }
            }
            if (skin.length > 0) {
                if (!(skin == 'White' || skin == 'Tanned white' || skin == 'Olive' || skin == 'Brown' || skin == 'Black')) {
                    alert("Enter a valid skin color");
                    return false;

                }
            }
            if (bodytype.length > 0) {
                if (!(bodytype == 'under weight' || bodytype == 'normal weight' || bodytype == 'Over Weight' || bodytype == 'Obese')) {
                    alert("Enter a valid body type");
                    return false;

                }
            }
            if (height.length > 0) {
                if (!(height == 'short' || height == 'normal' || height == 'tall' || height == 'very tall' || height == 'very very tall')) {
                    alert("Enter a valid height");
                    return false;

                }
            }
            return true;
        }
        document.getElementById("load").style.display = "none";
        function load() {
            document.getElementById("load").style.margin = "auto 50%";
            var bt = document.getElementById("bt");
            bt.innerHTML = '<i id="load" class="fa fa-circle-o-notch fa-spin" style="font-size:40px"></i>';
            var bt = document.getElementById("btv");
            btv.value = "Loading..."
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
</body>
</html>
<?php include 'footer.php'; ?>

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