<?php
require_once 'includes/header.php';
require_once 'includes/footer.php';
$_SESSION['key'] = bin2hex(random_bytes(32));
$csrf = hash_hmac('sha256', 'this is some string: 0_login.php', $_SESSION['key']);
$_SESSION['csrf'] = $csrf;
$id = $_SESSION['National_id'];
$sql = "SELECT * FROM citizen where National_id='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script>
    function validate() {
      let phonenumber = document.myform.new_phone.value;
      let email = document.myform.new_Email.value;
      let letters = /^[A-Za-z]+$/;
      let job = document.myform.new_job.value;
      job = job.toLowerCase();
      let isnum = /^\d+$/;
      let emailval = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      if (phonenumber.length > 0) {
        if (!isnum.test(phonenumber) || phonenumber.length != 11) {
          alert("Enter valid phone number");
          return false;
        }
      }
      if (email.length > 0) {
        if (emailval.test(email) == false) {
          alert("Enter a valid email");
          return false;

        }
      }
      if (job.length > 0) {
        if (!letters.test(job) || job.includes("select") || job.includes("insert") || job.includes("delete") || job.includes("update") || job.includes("grant") || job.includes("revoke")) {
          alert("Enter valid job");
          return false;

        }
      }
      return true;



    }
  </script>
</head>

<body>
  <?php include 'user_navbar.php' ?>
  <section>
    <div class="container">
      <form name="myform" action="includes/Modification.php" method="post" onsubmit="return validate();">
        <table class="table-adjust" width="200" height="200">
          <tr>
            <th style="padding:10px;" colspan="2">Edit Information</th>
          </tr>
          <tr>
            <td class="trinf">Phone Number</td>
            <td class="trinf"><input type="text" name="new_phone" id="signupPhone" class="form__input" value=<?php echo $row['phone_number'] ?>></td>
          </tr>
          <tr>
            <td class="trinf">Email</td>
            <td class="trinf"><input name="new_Email" type="text" id="signupMail" class="form__input" value=<?php echo $row['Email'] ?>></td>
          </tr>
          <tr>
            <td class="trinf">Job</td>
            <td class="trinf"><input name="new_job" type="text" id="new_job" class="form__input" value=<?php echo $row['Job'] ?>></td>

          </tr>
          <input type="hidden" name='csrf' value=<?php echo $csrf; ?>>
          <!---<tr>
                        <td class="trinf">Car Plate number</td>
                        <td class="trinf"><input name="plate_number" type="text" id="signupPN" class="form__input" autofocus placeholder="Edit car plate number"></td>
                    </tr>--->
        </table>
        <button class="form__button" type="submit" name="submit">Submit</button>
      </form>
    </div>
  </section>
</body>

</html>