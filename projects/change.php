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
  <!---<div class="container"> 
  <form class="form" action="includes/forgetpassword.php" method="post">

    <label for="fname">National id:</label><br>
    <input class="" type="text" id="fname" name="National_id"><br>
    <label for="lname">Email:</label><br>
    <input type="email" id="lname" name="Email">
    <button class="form__button"name="submit" type="submit">Submit</button>
  </form>
  </div>--->
  <div class="container">
    <form action="includes/forgetpassword.php" method="post" class="form" id="login">
        <h1 class="form__title">Change Password</h1>
        <div class="form__message form__message--error"></div>
        <div class="form__input-group">
            <input type="text" class="form__input" autofocus placeholder="National ID" name="National_id">
            <div class="form__input-error-message"></div>
        </div>
        <div class="form__input-group">
            <input type="text" class="form__input" autofocus placeholder="Email" name="Email">
            <div class="form__input-error-message"></div>
        </div>
        <button class="form__button"name="submit" type="submit">Submit</button>
    </form>
  </div>
</body>
</html>