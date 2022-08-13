<?php

require_once 'includes/header.php';
require_once 'includes/register-inc.php';
?>

<?php

require_once 'includes/footer.php';
?>
<script>
    function validate() {
        let letters = /^[A-Za-z]+$/;
        let isnum = /^\d+$/;
        let alphanum = /^[0-9a-zA-Z]+$/;
        let id = document.myform.Name.value;
        id = id.toLowerCase();
        let district = document.myform.district.value;
        let password = document.myform.pass.value;
        let passwordrepeat = document.myform.pass2.value;

        district = district.toLowerCase();
        if (!isnum.test(id)) {
            alert("Enter a valid id");
            return false;
        }
        if (!letters.test(district)) {
            alert("Enter a valid district");
            return false;
        }
        if (password.includes(' ') || passwordrepeat.includes(' ') || password != passwordrepeat) {
            alert("Passwords dont match");
            return false;
        }


        return true;


    }
</script>
<div class="container">
    <form name="myform" onsubmit="return validate();" action="includes/admin_register.php" method="post" class="form " id="createAccount">
        <h1 class="form__title">Create Account</h1>
        <div class="form__message form__message--error"></div>
        <div class="form__input-group">
            <input name="Name" type="text" id="signupUsername" class="form__input" autofocus placeholder="Enter your ID" required>
            <div class="form__input-error-message"></div>
        </div>
        <div class="form__message form__message--error"></div>
        <div class="form__input-group">
            <input name="district" type="text" id="signupUsername" class="form__input" autofocus placeholder="Enter your District" required>
            <div class="form__input-error-message"></div>
        </div>
        <div class="form__input-group">
            <input name="pass" type="password" id="signupUsername" class="form__input" autofocus placeholder="Enter password" required>
            <div class="form__input-error-message"></div>
        </div>
        <div class="form__input-group">
            <input name="pass2" type="password" id="signupUsername" class="form__input" autofocus placeholder="Repeat password" required>
            <div class="form__input-error-message"></div>
        </div>
        <button href="login.php" class="form__button" type="submit" name="submit">Register</button>

    </form>
</div>
<script src="./src/main.js"></script>