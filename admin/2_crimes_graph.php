<?php
include 'includes/database.php';
if (isset($_POST['submit'])) {
    $governate = $_POST['governate'];
    if (!ctype_alpha($governate)) {
        header("Location: 2_crimes.php?error=1");
        exit();
    }
    $sql = "SELECT * FROM districts WHERE governate='$governate'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        header("Location: 2_crimes.php?error=2");
        exit();
    }
    $array_districts = array();
    while ($rows = mysqli_fetch_assoc($result)) {
        $rowoutput = $rows['Namee'];
        array_push($array_districts, $rowoutput);
    }
    $sql = "SELECT * FROM crime_report WHERE TIMESTAMPDIFF(DAY, Date(NOW()),datee)>-30  ORDER BY datee Desc,timee DESC";
    $result = mysqli_query($conn, $sql);
    $rowcount = mysqli_num_rows($result);
    $counterofincidents = array();
    for ($j = 0; $j < count($array_districts); $j++) {
        array_push($counterofincidents, 0);
    }
    for ($i = 0; $i < $rowcount; $i++) {
        echo "<tr>";
        $row = mysqli_fetch_assoc($result);
        $lat = $row['lat'];
        $lng = $row['lng'];
        $latcheck = str_replace('.', '', $lat);
        $lngcheck = str_replace('.', '', $lng);
        if (!is_numeric($latcheck) || !is_numeric($lngcheck)) {
            header("Location 2_crimes.php?error=sqlerror");
            exit();
        }
        $sql = "SELECT Namee , 
            111.111 *
            DEGREES(ACOS(LEAST(1.0, COS(RADIANS(lat))
                * COS(RADIANS($lat))
                * COS(RADIANS(lng - $lng))
                + SIN(RADIANS(lat))
                * SIN(RADIANS($lat))))) AS distance_in_km
        FROM districts
        Having  distance_in_km between 0 and 30 
        ORDER BY distance_in_km
        Limit 1";
        $querey = mysqli_query($conn, $sql);
        $outputlocation = mysqli_fetch_assoc($querey);
        $locationofincident = $outputlocation['Namee'];
        for ($j = 0; $j < count($array_districts); $j++) {
            if ($locationofincident == $array_districts[$j]) {
                $counterofincidents[$j] = $counterofincidents[$j] + 1;
            }
        }
    }
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <title>Graph</title>
    <script>
        function validate() {
            let governate = document.myform.district.value;
            let letters = /^[A-Za-z]+$/;
            if (!letters.test(governate)) {
                alert("Enter valid district");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <?php include 'admin_navbar.php'; ?>

    <section>
        <form name="myform" action="2_crimes_graph1.php" method="post">
            <div class="container" style="width:50%;">
                <div class="form__input-group">
                    <canvas id="myChart" style="width:100%;max-width:700px"></canvas>
                </div>
                <div class="form__input-group">
                    <select name="district" class="form__input">
                        <option hidden>Select district</option>
                        <?php
                        $sql = "SELECT Namee From districts WHERE governate='$governate'";
                        $result = mysqli_query($conn, $sql);

                        $rowcount = mysqli_num_rows($result);
                        for ($i = 0; $i < $rowcount; $i++) {
                            $row = mysqli_fetch_assoc($result);
                            echo '<option value="' . $row['Namee'] . '">' . $row['Namee'] . "</option>";
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
<script>
    var xValues = <?php echo json_encode($array_districts); ?>;
    var yValues = <?php echo json_encode($counterofincidents); ?>;
    var barColors = "#20a668";

    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "Crimes in <?php echo $governate; ?> in the last 30 days"
            }
        }
    });
</script>