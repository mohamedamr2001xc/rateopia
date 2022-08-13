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

<body>
    <?php
    require 'includes/database.php';
    $national_id = $_GET['subject'];
    $sql = "SELECT * From citizen WHERE National_id=$national_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $sql2 = "SELECT image_url FROM images WHERE National_id=$national_id";
    $result2 = mysqli_query($conn, $sql2);
    $row2 = mysqli_fetch_assoc($result2);
    $image_path = "face_rec/faces/" . $national_id . ".jpg";
    ?>
     <?php include 'user_navbar.php'?>


    <section>
        <div class="book">
            <div class="container">
                <div class="image">
                    <a href=<?php echo $image_path ?>><img style="width:300px;height:300px;" src=<?php echo $image_path ?> alt="picture"></a>
                </div>

                <div style="text-align:center;">
                    <form action="includes/addtoreportdatabase.php" method="get">
                        <a class="form__button" style="display:inline-grid;width:40%;"" href='includes/report_submit.php?subject=<?php echo $row['National_id'] ?>'>Submit rating</a>
                        <a class="form__button" style="display:inline-grid;width:40%;"" href='includes/report_crime.php?subject=<?php echo $row['National_id'] ?>'>Report a crime</a>
                    </form>
                    <div class=" rating"><?php echo number_format((float)$row['Rating'], 2, '.', '');?></div>
                </div>
                <div class="chart" id="donut_single"></div>
            </div>
        </div>
        <div class="book">
            <div class="container">

                <table class="table-adjust" width="200" height="200">
                    <tr>
                        <th colspan="2">
                            User Information
                        </th>
                    </tr>
                    <tr>
                        <td class="trinf">Name</td>
                        <td>
                            <?php echo $row['Name']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Age</td>
                        <td>
                            <?php echo $row['Age']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Rateopia ID</td>
                        <td>
                            <?php echo $row['National_id']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Email</td>
                        <td>
                            <?php echo $row['Email']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Job</td>
                        <td>
                            <?php echo $row['Job']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="trinf">Plate number</td>
                        <td>
                            <?php echo $row['Plate_number']; ?>
                        </td>
                    </tr>
                </table>
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