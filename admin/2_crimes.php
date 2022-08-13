<?php
include 'includes/database.php';

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
    <title>Crimes</title>
    <script>
        function validate() {
            let governate = document.myform.governate.value;
            let letters = /^[A-Za-z]+$/;
            if (!letters.test(governate)) {
                alert("Enter valid governate");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <?php include 'admin_navbar.php'; ?>

    <section>
        <form name="myform" onsubmit="return validate();" action="2_crimes_graph.php" method="post">
            <div class="container">
                <div class="form__input-group">
                    <select class="form__input" name="governate">
                        <option hidden>Select governorate</option>
                        <?php
                        $sql = "SELECT DISTINCT governate From districts";
                        $result = mysqli_query($conn, $sql);

                        $rowcount = mysqli_num_rows($result);
                        for ($i = 0; $i < $rowcount; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            echo '<option value="' . $row['governate'] . '">' . $row['governate'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form__input-group">
                    <button class="form__button" type="submit" name="submit">Search</button>
                </div>
            </div>
        </form>
    </section>
</body>

</html>
<?php include 'footer.php'; ?>