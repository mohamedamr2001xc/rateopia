<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="/assets/favicon.ico">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Contact Page</title>
</head>

<body>
  <?php
  require 'includes/sendmail.php';
  include "user_main_navbar.php"
  ?>

  <section>
    <div class="container">
      <div class="contact">
        <h2>Contact Us</h2>
        <p>For further information please contact us at.</p><br>
        <h4> Facebook</h4>
        <a class="form__link" href="https://www.facebook.com/Rateopia-107430828566062"><span class="Facebook">Rateopia rating app</span></a>
        <h4> Email</h4>
        <span class="Email">ratepoia@gmail.com</span>
        <br><br>
        <h4>Where We Are</h4>
          <address>5 Dr. Ahmed Zewail Street <br> Postal Code: 12613 <br> Orman <br> Giza, Egypt<br></address><br>
      </div>
      <div class="contact">
            <form class="form" action="includes/sendmail.php" method="post">
            <div class="form__input-group">
              <input class="form__input" type="text" name="name" autofocus placeholder="Your Name"><br>
              <input class="form__input" type="email" name="mail" autofocus placeholder="Your Email"><br>
              <textarea class="message__input" name="message" autofocus placeholder="Your Message"></textarea><br>
              <input class="form__button" type="submit" name="submit" value="Send Message">
            </div>
          </form>
      </div>
    </div>
  </section>
</body>

</html>