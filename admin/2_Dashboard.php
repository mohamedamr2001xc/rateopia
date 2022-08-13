<?php

session_start();
$id = $_SESSION['ID'];
if (empty($id)) {
    header("Location: 2_login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="style_admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Dashboard</title>
</head>

<body>
    <script src="scripts.js"></script>
    <?php include 'admin_navbar.php';?>
    <section>
        <div class="container">
            <Legend>Welcome back to Rateopia</Legend>
        </div>
        </div>
        </div>
    </section>
</body>

</html>
<?php include 'footer.php'; ?>