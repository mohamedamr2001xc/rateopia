<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Dashboard</title>
</head>
<script>

</script>
<body>
    <?php
    session_start();
    if ($_SESSION['National_id'] == "") {
        header("Location: 0_login.php");
    }
    $national_id = $_SESSION['National_id'];

    require 'includes/database.php';
    $sql = "SELECT * From citizen WHERE National_id=$national_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $nationalidcheck = $row['id'];
    $sql = "SELECT * From blocked_users where userid='$nationalidcheck'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        header("Location: 0_login.php?error=blockeduer");
        exit();
    }
    $sql2 = "SELECT image_url FROM images WHERE National_id=$national_id";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);
    $image_path = "face_rec/faces/" . $national_id . ".jpg";
    $_SESSION['reporter'] = $national_id;
    $sql3 = "SELECT * from physical_features where National_id=$national_id ";
    $result3 = mysqli_query($conn, $sql3);
    $row3 = mysqli_fetch_assoc($result3);
    ?>
    <script src="scripts.js"></script>
    <?php include 'user_navbar.php' ?>
    <section>
        <div class="book">
            <div class="container">
                <div class="image">
                    <a ><img onclick="window.open(this.src)" style="width:300px;height:300px;" src=<?php echo $image_path ?> alt="picture"></a>
                </div>

                <div style="text-align:center;">
                    <a class="form__button" style="display:inline-grid;" href="modifes.php">Edit Profile</a>
                </div>
                <div class=" rating"><?php echo number_format((float)$row['Rating'], 2, '.', ''); ?></div>
                <div class="chart" id="donut_single"></div>
            </div>
        </div>
        <div class="book">
            <div class="container">
                <table class="table-adjust">
                    <tr>
                        <th colspan="2">User Information</th>
                    </tr>
                    <tr>
                        <td class="trinf">Name</td>
                        <td class="trinf">
                            <?php echo $row['Name']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Age</td>
                        <td class="trinf">
                            <?php echo $row['Age']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Rateopia ID</td>
                        <td class="trinf"><?php echo $row['National_id']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">National ID</td>
                        <td class="trinf"><?php echo $row['id']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Phone Number</td>
                        <td class="trinf"><?php echo $row['phone_number']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Email</td>
                        <td class="trinf"><?php echo $row['Email']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Job</td>
                        <td class="trinf">
                            <?php echo $row['Job']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Car plate number</td>
                        <td class="trinf">
                            <?php echo $row['Plate_number']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf" rowspan="2">Address</td>
                        <td class="trinf">Longitude: <?php echo $row['lng']; ?></td>
                    </tr>
                    <tr>
                        <td class="trinf">Latitude: <?php echo $row['lat']; ?></td>
                    </tr>
                    <tr>
                        <th colspan="2">Physical Features</th>
                    </tr>
                    <tr>
                        <td class="trinf">Eye color</td>
                        <td class="trinf">
                            <?php echo $row3['eye_color']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Hair color</td>
                        <td class="trinf">
                            <?php echo $row3['hair_color']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Hair style</td>
                        <td class="trinf">
                            <?php echo $row3['hair_texture']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Beard style</td>
                        <td class="trinf">
                            <?php echo $row3['beard_style']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Skin Color</td>
                        <td class="trinf">
                            <?php echo $row3['skin_color']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Height</td>
                        <td class="trinf">
                            <?php echo $row3['height']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Weight</td>
                        <td class="trinf">
                            <?php echo $row3['weightt']; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        </div>
        </div>
    </section>
</body>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        <?php
        $rat = (float)$row['Rating'];
        $rat2 = 5 - $rat;
        $percent = ($rat * 100) / 5;
        $percent2 = 100 - $percent;
        ?>
        var data = google.visualization.arrayToDataTable([
            ['Rating', 'Amount given'],
            ['Positive', <?php echo $percent; ?>],
            ['Negative', <?php echo $percent2; ?>],

        ]);
        var options = {
            tooltip: {
                text: 'percentage'
            },
            pieSliceText: 'none',
            pieHole: 0.8,
            pieStartAngle: 180,
            legend: 'none',
            slices: {
                0: {
                    color: '#20a668'
                },
                1: {
                    color: '#000'
                }
            },

        };
        var chart = new google.visualization.PieChart(document.getElementById('donut_single'));
        chart.draw(data, options);
    }
</script>

</html>
<?php include 'footer.php'; ?>
