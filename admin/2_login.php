<?php
include 'admin_main_navbar.php';
require_once 'includes/login-inc.php';
session_destroy();




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script>
        function validate() {
            let id = document.myform.National_id.value;
            let password = document.myform.password.value;
            id = id.toLowerCase();
            let letters = /^[A-Za-z]+$/;
            let isnum = /^\d+$/;
            let alphanum = /^[0-9a-zA-Z]+$/;
            if (!isnum.test(id)) {
                alert("Enter a valid id");
                return false;
            }
            if (password.length == 0) {
                alert("Password field is empty");
                return false;

            }

            return true;

        }
    </script>
</head>

<body>
    <div class="container">
        <form name="myform" onsubmit="return validate();" action="includes/2_login-inc.php" method="post" class="form" id="login">
            <h1 class="form__title">Login</h1>
            <div class="form__message form__message--error"></div>
            <div class="form__input-group">
                <input type="text" class="form__input" autofocus placeholder="ID" name="National_id">
                <div class="form__input-error-message"></div>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" autofocus placeholder="Password" name="password">
                <div class="form__input-error-message"></div>
            </div>
            <div class="g-recaptcha" data-sitekey="6Lcc1JkgAAAAAICoy4f2nMn8zek_fJM-tI9QItOj"></div>
            <button href="#" class="form__button" name="submit" type="submit">Login</button>
            <p class="form__text">

            </p>
        </form>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </div>
</body>

</html>
<?php include 'footer.php'; ?>