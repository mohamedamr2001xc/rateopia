<?php
session_start();
$check=$_GET['subject'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Change Password</title>
</head>
<body>
  <div class="container">
    <form class="form" action="includes/passverify.php" method="post">
      <h1 class="form__title">Change Password</h1>
      <div class="form__input-group">
        <input class="form__input" name="verfication" autofocus placeholder="Verfication code" type="text" id="fname" required><br>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input class="form__input" name="newpassword" autofocus placeholder="New password" type="password" id="fname" required><br>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input class="form__input" name="confirmpassword" autofocus placeholder="Confirm new password" type="password" id="fname" required><br>
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <?php
      
        if($check==1)
        {
          echo '<p style="color:red;">Password must be at least 8 characters , contain uppercase letter , contain (!,@,#,%)</p>';
        }
        elseif($check=="passwordsdontmatch")
        {
          echo "<p style='color:red;'>Password doesn't match</p>";

        }
        elseif($check=="h\wrongverficationcode")
        {
          echo "<p style='color:red;'>Wrong verfication code/p>";

        }
        ?>
      </div>
      <button class="form__button"name="submit" type="submit">Submit</button>
    </form>
  </div>
</body>
</html>