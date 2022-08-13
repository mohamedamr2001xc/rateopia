<?php
session_start();
if (!isset($_SESSION['ID'])) {
  header("Location: 2_login.php");
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
  <title>Profile Search</title>
  <script>
    function validate() {
      let isnum = /^\d+$/;
      let x = document.myform.National_id.value;
      if (!isnum.test(x)) {
        alert("enter a valid national id");
        return false;
      }

      return true;

    }
  </script>
</head>

<body>
  <?php include 'admin_navbar.php'; ?>
  <div class="container">
    <form name="myform" action="2_editprofile.php" method="post" class="form" id="search" onsubmit="return validate();">
      <h1 class="form__title">Search Profile</h1>
      <div class="form__input-group">
        <input type="text" name="National_id" id="search" class="form__input" autofocus placeholder="Search">
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <a><input class="submitButton" name="submit" style="float:left;" type="reset"></a>
        <a href="#"><input class="submitButton" name="submit" style="float:right;" type="submit" value="Search"></a>
        <div class="form__input-error-message"></div>
      </div>
    </form>
  </div>
</body>
</html>
<?php include 'footer.php'; ?>

