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
  <link rel="shortcut icon" href="/assets/favicon.ico">
  <link rel="stylesheet" href="style_admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Registration 2</title>
  <script>
    function validate() {
      let isnum = /^\d+$/;
      let weight = document.myform.weight.value;
      let height = document.myform.height.value;
      let hairtexture = document.myform.hair_texture.value;
      let haircolor = document.myform.hair_color.value;
      let eyecolor = document.myform.eye_color.value;
      let beard = document.myform.beard.value;
      let skin = document.myform.skin_color.value;
      if (!isnum.test(weight)) {
        alert("Enter a valid weight");
        return false;
      }
      if (!isnum.test(height)) {
        alert("Enter a valid height");
        return false;
      }
      if (!(hairtexture == 'bald' || hairtexture == 'straight' || hairtexture == 'wavy' || hairtexture == 'curly')) {
        alert("Enter a valid hair texture");
        return false;

      }
      if (!(haircolor == 'black' || haircolor == 'light Brown' || haircolor == 'dark brown' || haircolor == 'blond' || haircolor == 'White')) {
        alert("Enter a valid hair color");
        return false;

      }
      if (!(eyecolor == 'brown' || eyecolor == 'light Brown' || eyecolor == 'dark brown' || eyecolor == 'green' || eyecolor == 'grey' || eyecolor == 'blue')) {
        alert("Enter a valid eye color");
        return false;

      }
      if (!(beard == 'No Beard' || beard == 'Long Beard' || beard == 'Normal Beard' || beard == 'Short Beard')) {
        alert("Enter a valid beard");
        return false;

      }
      if (!(skin == 'White' || skin == 'Tanned white' || skin == 'Olive' || skin == 'Brown' || skin == 'Black')) {
        alert("Enter a valid skin color");
        return false;

      }


      return true;
    }
  </script>
</head>


<body>
  <?php include 'admin_navbar.php'; ?>
  <div class="container">
    <form name="myform" onsubmit="return validate();" action="includes/moreinfo-inc.php" method="post" class="form " id="createAccount" enctype="multipart/form-data">
      <h1 class="form__title">Create Account</h1>
      <div class="form__input-group">
        <input class="submitButton" style="width:100%;" type="file" name="my_image" id="fileToUpload">
      </div>
      <legend>Physical Features:</legend>
      <div>
        <div class="form__input-group">
          <label for="hair">Hair texture:</label>
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
          <label for="height">Enter Height:</label>
          <input name="height" type="number" id="height.." class="form__input" autofocus placeholder="Height.." required>
        </div>
        <div class="form__input-group">
          <label for="weight">Enter Weight:</label>
          <input name="weight" type="number" id="weight.." class="form__input" autofocus placeholder="Weight.." required>
        </div>
      </div>
      <div class="form__input-group">
        <a href="#.html"><input class="submitButton" style="float:left;" type="reset"></a>
        <a href="2_Dashboard.php"><input class="submitButton" style="float:right;" type="submit" name="submit"></a>
        <div class="form__input-error-message"></div>
      </div>
    </form>
  </div>
</body>

</html>