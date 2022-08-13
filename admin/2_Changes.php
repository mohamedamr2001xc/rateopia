<?php
session_start();
$national_id = $_SESSION['ID'];


if (empty($national_id)) {
    header("Location: 2_login.php");
}
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
    <title>Urgent reports</title>
</head>

<body>
    <?php include 'admin_navbar.php'; ?>

    <section>
        <div class="container">
            <h2>Note: Urgent reports </h2>
            <table class="table-adjust">
                <tr style="border-bottom-style:solid;border-color: #20a668;">
                    <th style="padding:10px;" colspan="7">Urgent Reports</th>
                </tr>
                <tr style="border-bottom-style:solid;border-color: #20a668;">
                    <td class='trinf'><b>Reporter ID</b></td>
                    <td class='trinf'><b>Accused ID</b></td>
                    <td class='trinf'><b>Report ID</b></td>
                    <td class='trinf'><b>Report Location</b></td>
                    <td class='trinf'><b>Date</b></td>
                    <td class='trinf'><b>Time</td>
                    <td class='trinf'><b>Uploaded File</b></td>

                </tr>
                <?php

                $sql = "SELECT * FROM crime_report WHERE TIMESTAMPDIFF(DAY, Date(NOW()),datee)>-30  ORDER BY datee Desc,timee DESC";

                $resultfirst = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($resultfirst)) {

                    echo "<tr>";


                    $lat = $row['lat'];
                    $lng = $row['lng'];
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
                    $locationincident = $outputlocation['Namee'];
                    $sql = "SELECT District From adminn Where ID='$national_id'";
                    $result = mysqli_query($conn, $sql);
                    $adminlocation = mysqli_fetch_assoc($result);


                    if ($adminlocation['District'] != $locationincident) {
                        continue;
                    } else {

                        $reporter_id = $row['National_id_1'];

                        $sql = "SELECT id FROM citizen where National_id='$reporter_id'";
                        $ouputecho = mysqli_query($conn, $sql);
                        $rowoutput = mysqli_fetch_assoc($ouputecho);
                        echo "<td class='trinf'>";
                        echo '<a class="form__link"  href="../projects/ouputinformation.php?subject=' . $row['National_id_1'] . '"' . ">" . $rowoutput['id'] . "</a>" . "<br>";
                        echo "</td>";
                        $reporter_id = $row['reportsNational_id_2'];
                        $sql = "SELECT id FROM citizen where National_id='$reporter_id'";
                        $ouputecho = mysqli_query($conn, $sql);
                        $rowoutput = mysqli_fetch_assoc($ouputecho);
                        echo "<td class='trinf'>";
                        echo '<a class="form__link" href="../projects/ouputinformation.php?subject=' . $row['reportsNational_id_2'] . '"' . ">" . $rowoutput['id'] . "</a>";
                        echo "</td>";
                        echo "<td class='trinf'>";
                        echo $row['id'];
                        echo "</td>";

                        echo "<td class='trinf'>";
                        echo '<a href="' . 'https://www.google.com/maps/search/?api=1&query=' . $row['lat'] . '%2C' . $row['lng'] . '">' . "Location</a>";
                        echo "</td>";

                        echo "<td class='trinf'>";
                        echo $row['datee'] . "<br>";
                        echo "</td>";
                        echo "<td class='trinf'>";
                        echo $row['timee'] . "<br>";
                        echo "</td>";
                        echo "<td class='trinf'>";
                        if ($row['document'] != NULL) {
                            echo '<a class="form__link" href="../projects/uploads/' . $row['id'] . "/" . $row['document'] . '">' . "Uploaded file" . '</a>';
                        } else {
                            echo "No uploaded file";
                        }
                        echo "</td>";
                        echo "</tr>";
                        echo "<tr style='border-bottom-style:solid;border-color: #20a668;'>";
                        echo "<td colspan='7' class='trinf'>";
                        echo "Incident type: " . $row['crime_type'] . '<br>';
                        echo 'Incident details: ' . $row['report_info'];
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </section>
</body>

</html>
<?php include 'footer.php'; ?>