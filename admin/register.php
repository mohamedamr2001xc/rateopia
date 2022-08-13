<?php
session_start();
if (is_null($_SESSION['ID'])) {
  header("Location: 2_login.php");
}
if (isset($_GET['error'])) {
  $errorcode = $_GET['error'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style_admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Registration</title>
  <script>
    function validate() {
      let letters = /^[A-Za-z]+$/;
      let isnum = /^\d+$/;
      let alphanum = /^[0-9a-zA-Z]+$/;

      let fullname = document.myform.Name.value;
      fullname = fullname.toLowerCase();
      fullname = fullname.replace(/\s/g, '');
      let lat = document.myform.lat.value;
      lat = lat.toLowerCase();
      let lng = document.myform.lng.value;
      lng = lng.toLowerCase();
      let latcheck = lat.replace('.', '');
      let lngcheck = lng.replace('.', '');
      let emailval = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      let nationalid = document.myform.National_id.value;
      nationalid = nationalid.toLowerCase();

      let phonenum = document.myform.phone_number.value;
      phonenum = phonenum.toLowerCase();
      let plate = document.myform.Plate_number.value;
      plate = plate.toLowerCase();
      let job = document.myform.Job.value;
      job = job.toLowerCase();
      job = job.replace(/\s/g, '');
      birthdate = document.myform.Age.value;
      bitthdate = birthdate.toLowerCase();


      if (fullname.length < 13 || !letters.test(fullname) || fullname.includes("select") || fullname.includes("insert") || fullname.includes("delete") || fullname.includes("update") || fullname.includes("grant") || fullname.includes("revoke")) {
        alert("Enter valid username");
        return false;

      }

      if (job.length > 0) {

        if (!letters.test(job) || job.includes("select") || job.includes("insert") || job.includes("delete") || job.includes("update") || job.includes("grant") || job.includes("revoke")) {
          alert("Enter valid job");
          return false;

        }
      }
      if (emailval.test(document.myform.Email.value) == false) {
        alert("enter a valid mail");
        return false;
      }
      if (isnum.test(nationalid) == false || (nationalid.length != 14)) {
        alert("enter a valid National id");
        return false;

      }
      if (!isnum.test(phonenum) || phonenum.length != 11) {
        alert("enter a valid phone number");
        return false;

      }
      if (plate.length > 0) {
        if (!alphanum.test(plate) || plate.length != 6) {
          alert("enter a valid plate number");
          return false;

        }
      }
      if (!Date.parse(birthdate)) {
        alert("Enter a valid date");
        return false;

      }

      /*alert(Date.parse(birthdate));*/

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
  
</head>

<body>
  <?php include 'admin_navbar.php'; ?>
  <div class="container">
    <form name="myform" action="includes/register-inc.php" method="post" class="form " id="createAccount" onsubmit="return validate();">
      <h1 class="form__title">Create Account</h1>

      <div class="form__input-group">
        <legend>Full Name</legend>
        <input name="Name" type="text" id="signupName" class="form__input" autofocus placeholder="Full name" required>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <legend>Address</legend>
        <p style="color:red;font-size:small;text-align:center;">*Use left click on the map to view latidude and longtude
                     <br>then copy-paste them in their specific feilds in the search form*</p>
                     <div id="map"></div>
                     <input name="lat" type="text" id="signupAddress" class="form__input" autofocus placeholder="Longitude" required>
        <input name="lng" type="text" id="signupAddress" class="form__input" autofocus placeholder="Latitude" required>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <legend>Email</legend>
        <input name="Email" type="text" id="signupMail" class="form__input" autofocus placeholder="Email" required>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <legend>National ID</legend>
        <input name="National_id" type="text" id="signupNID" class="form__input" autofocus placeholder="National ID" required>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <legend>Phone number</legend>
        <input name="phone_number" type="text" id="signupPhone" class="form__input" autofocus placeholder="Phone Number" required>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <legend>Car plate number</legend>
        <input name="Plate_number" type="text" id="signupPN" class="form__input" autofocus placeholder="Car plate Number (Optional)">
        <div class="form__input-error-message"></div>
      </div>
      <div class="form_input-group">
        <legend>Birth Date</legend>
        <input name="Age" class="form__input" type="date" id="signupBirthday" name="birthday" required>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form_input-group">
        <legend>Job</legend>
        <input name="Job" class="form__input" type="text" id="signupOccupation" placeholder="Job (Optional)">
        <div class="form__input-error-message"></div>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <a href="#"><input class="submitButton" style="float:left;" type="reset"></a>
        <a href="#"><input name="submit" class="submitButton" style="float:right;" type="submit" value="Register"></a>
        <div class="form__input-error-message"></div>
      </div>
    </form>
  </div>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiU10uC7ln1nu1_PfD1e7a9F3uLMa0J7E&callback=myMap"></script>

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