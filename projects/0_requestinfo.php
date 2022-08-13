<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Companies Requests</title>
</head>

<body>
<?php include "user_main_navbar.php" ?>
<div class="container">
    <form action="includes/requestinfo-inc.php" method="post" class="form">
        <div class="form__message form__message--error"></div>
        <div class="form__input-group">
            <input type="text" class="form__input" autofocus placeholder="National ID of the user" name="National_id">
            <div class="form__input-error-message"></div>
        </div>
        <div class="form__input-group">
            <input type="text" class="form__input" autofocus placeholder="Name of your company" name="cName">
            <div class="form__input-error-message"></div>
        </div>
        <div class="form__input-group">
            <input type="text" class="form__input" autofocus placeholder="Email of your company" name="cEmail">
            <div class="form__input-error-message"></div>
        </div>
        <button class="form__button"name="submit" type="submit">Submit</button>
    </form>
  </div>
</body>

</html>