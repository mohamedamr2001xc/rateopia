<?php
include 'user_main_navbar.php';
require_once 'includes/login-inc.php';
require_once 'includes/footer.php';
session_destroy();
session_start();
$_SESSION['key'] = bin2hex(random_bytes(32));
$csrf = hash_hmac('sha256', 'this is some string: 0_login.php', $_SESSION['key']);
$_SESSION['csrf'] = $csrf;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>

</head>

<body>
    <div class="container">
        <form name="myform" action="includes/login-inc.php" method="post" class="form" id="login" onsubmit="return validate();">
            <h1 class="form__title">Login</h1>
            <div class="form__message form__message--error"></div>
            <div class="form__input-group">
                <input type="text" class="form__input" autofocus placeholder="National ID" name="National_id">
                <div class="form__input-error-message"></div>
            </div>
            <div class="form__input-group">
                <input type="password" class="form__input" autofocus placeholder="Password" name="password">
                <div class="form__input-error-message"></div>
            </div>
            <input type="hidden" name="csrf" value=<?php echo $csrf ?>>
            <div class="g-recaptcha" data-sitekey="6Lcc1JkgAAAAAICoy4f2nMn8zek_fJM-tI9QItOj"></div>

            <button href="#" class="form__button" name="submit" type="submit">Login</button>
            <p class="form__text">
                <a href="change.php" class="form__link">Forgot your password?</a>
            </p>

        </form>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </div>
</body>

</html>
<?php include 'footer.php'; ?>
