<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Welcome to Reteopia</title>
</head>

<body>
  <nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
      <i class="fas fa-bars"></i>
    </label>
    <ul class="imagecontainer">
      <li><a class="imagelink" href="index.php"><img class="imagelogo" src="Media/Rateopia_Black.png"><span class="logotxt"> Rateopia </span></a></li>
    </ul>
    <ul>
      <li><a href="projects/0_ContactUs.php">About Us</a></li>
    </ul>
  </nav>

  <section class="main">
    <video width=500px autoplay>
      <source src="Media/Rateopia.mp4" type="video/mp4">
      <source src="Media/Rateopia.mp4" type="video/ogg">
    </video>
    <div>
      <h2>
        Welcome to <span style="color:#20a668 ;">Rateopia® </span>the first citizen rating website.<br>
        <a href="projects/0_login.php" class="main-button">User Login</a>
        <a href="admin/2_login.php" class="main-button">Admin Login</a>
      </h2>
    </div>
  </section>
</body>

</html>