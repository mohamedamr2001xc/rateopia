<?php
include 'includes/database.php';
if (isset($_POST['submit'])) {
    $crimesid = array();
    $governate = $_POST['district'];
    $governatecheck = str_replace(' ', '', $governate);
    if (!ctype_alpha($governatecheck)) {
        header("Location: 2_crimes.php?error=3");
        exit();
    }
    $sql = "SELECT * FROM districts WHERE 	Namee='$governate'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) == 0) {
        header("Location: 2_crimes.php?error=4");
        exit();
    }

    $sql = "SELECT Felonies FROM crimes";
    $result1 = mysqli_query($conn, $sql);
    $allfelonies = array();
    while ($row = mysqli_fetch_assoc($result1)) {
        $row1 = $row['Felonies'];
        if ($row1 == null) {
            continue;
        } else {
            array_push($allfelonies, $row1);
        }
    }
    $sql = "SELECT Misdemeanors FROM crimes";
    $result2 = mysqli_query($conn, $sql);
    $allmisdemeanors = array();
    while ($row = mysqli_fetch_assoc($result2)) {
        $row1 = $row['Misdemeanors'];
        if ($row1 == null) {
            continue;
        } else {
            array_push($allmisdemeanors, $row1);
        }
    }
    $sql = "SELECT Infractions FROM crimes";
    $result3 = mysqli_query($conn, $sql);
    $allinfractions = array();
    while ($row = mysqli_fetch_assoc($result3)) {
        $row1 = $row['Infractions'];
        if ($row1 == null) {
            continue;
        } else {
            array_push($allinfractions, $row1);
        }
    }
    $counterfelonies = array();
    for ($i = 0; $i < count($allfelonies); $i++) {
        array_push($counterfelonies, 0);
    }
    for ($i = 0; $i < count($allfelonies); $i++) {
        $crimetype = $allfelonies[$i];
        $sql = "SELECT COUNT(*) AS numberofrows FROM crime_report WHERE TIMESTAMPDIFF(DAY, Date(NOW()),datee)>-30 AND  crime_type='$crimetype' AND category='Felonies' AND district='$governate' ORDER BY datee Desc,timee DESC";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        $counter = $result['numberofrows'];
        $counterfelonies[$i] = (int)$counter;
    }
    $countermisdemeanor = array();
    for ($i = 0; $i < count($allmisdemeanors); $i++) {
        array_push($countermisdemeanor, 0);
    }
    for ($i = 0; $i < count($allmisdemeanors); $i++) {
        $crimetype = $allmisdemeanors[$i];
        $sql = "SELECT COUNT(*) AS numberofrows FROM crime_report WHERE TIMESTAMPDIFF(DAY, Date(NOW()),datee)>-30 AND  crime_type='$crimetype' AND category='Misdemeanors' AND district='$governate' ORDER BY datee Desc,timee DESC";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        $counter = $result['numberofrows'];
        $countermisdemeanor[$i] = (int)$counter;
    }
    $counterinfractions = array();
    for ($i = 0; $i < count($allinfractions); $i++) {
        array_push($counterinfractions, 0);
    }
    for ($i = 0; $i < count($allinfractions); $i++) {
        $crimetype = $allinfractions[$i];
        $sql = "SELECT COUNT(*) AS numberofrows FROM crime_report WHERE TIMESTAMPDIFF(DAY, Date(NOW()),datee)>-30 AND  crime_type='$crimetype' AND category='Infractions' AND district='$governate' ORDER BY datee Desc,timee DESC";
        $query = mysqli_query($conn, $sql);
        $result = mysqli_fetch_assoc($query);
        $counter = $result['numberofrows'];
        $counterinfractions[$i] = (int)$counter;
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
</head>

<body>
    <?php include 'admin_navbar.php'; ?>
    <section>
        <div class="container" style="width:105%;">
            <div class="form__input-group">
                <canvas id="felony" style="width:100%;"></canvas>
            </div>
            <div class="form__input-group">
                <canvas id="misdemeanor" style="width:100%;"></canvas>
            </div>
            <div class="form__input-group">
                <canvas id="infraction" style="width:100%;"></canvas>
            </div>
        </div>
    </section>
</body>

</html>
<?php include 'footer.php'; ?>
<script>
    var xfelony = <?php echo json_encode($allfelonies); ?>;
    var yfelony = <?php echo json_encode($counterfelonies); ?>;

    var xmisdemeanor = <?php echo json_encode($allmisdemeanors); ?>;
    var ymisdemeanor = <?php echo json_encode($countermisdemeanor); ?>;

    var xinfraction = <?php echo json_encode($allinfractions); ?>;
    var yinfraction = <?php echo json_encode($counterinfractions); ?>;

    var barColors = "#20a668";

    new Chart("felony", {
        type: "horizontalBar",
        data: {
            labels: xfelony,
            datasets: [{
                backgroundColor: barColors,
                data: yfelony
            }]
        },
        options: {
            legend: {
                display: false,
            },
            title: {
                display: true,
                text: "Number of felonies in <?php echo $governate; ?> in the last 30 days"
            }
        }
    });
    new Chart("misdemeanor", {
        type: "horizontalBar",
        data: {
            labels: xmisdemeanor,
            datasets: [{
                backgroundColor: barColors,
                data: ymisdemeanor
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "Number of misdemeanor in <?php echo $governate; ?> in the last 30 days"
            }
        }
    });
    new Chart("infraction", {
        type: "horizontalBar",
        data: {
            labels: xinfraction,
            datasets: [{
                backgroundColor: barColors,
                data: yinfraction
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "Number of infraction in <?php echo $governate; ?> in the last 30 days"
            }
        }
    });
</script>