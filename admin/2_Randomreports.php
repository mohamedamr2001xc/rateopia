<?php

include 'includes/database.php';
$ciphering = "AES-256-CBC";
$sql = "SELECT reportid FROM reports WHERE TIMESTAMPDIFF(DAY, Date(NOW()),datee)>-30";
$result = mysqli_query($conn, $sql);
$rowcount = mysqli_num_rows($result);
session_start();
$national_id = $_SESSION['ID'];

$arrayindex = array();
$reportsids = array();
$counter = 0;
while ($counter < 10) {
    $randomnumber = rand(0, $rowcount);

    array_push($arrayindex, $randomnumber);
    $counter++;
}

$counter = 0;
while ($row = mysqli_fetch_assoc($result)) {
    for ($i = 0; $i < count($arrayindex); $i++) {
        if ($counter == $arrayindex[$i]) {
            $id = $row['reportid'];
            array_push($reportsids, $id);
        }
    }
    $counter++;
}

$indexarray = array();


$reportsids = array_unique($reportsids);



$sql = "SELECT * FROM reports";
$result = mysqli_query($conn, $sql);
$rowcount = mysqli_num_rows($result);

for ($i = 0; $i < $rowcount; $i++) {
    $row = mysqli_fetch_assoc($result);
    $idnum = $row['reportid'];
    if (!in_array($idnum, $reportsids)) {
    } else {
        array_push($indexarray, $idnum);
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
    <title>Recent reports</title>
</head>

<body>
    <?php include 'admin_navbar.php'; ?>

    <section>
        <div class="container">
            <h2>Recent reports </h2>
            <table class="table-adjust">
                <tr style="border-bottom-style:solid;border-color: #20a668;">
                    <th style="padding:10px;" colspan="7">Recent Reports</th>
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

                $sql = "SELECT * FROM reports WHERE TIMESTAMPDIFF(DAY, Date(NOW()),datee)>-30  ORDER BY datee Desc,timee DESC";

                $resultfinal = mysqli_query($conn, $sql);
                $rowcount = mysqli_num_rows($result);


                while ($row = mysqli_fetch_assoc($resultfinal)) {
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
                        break;
                    }
                    $reportid = $row['reportid'];


                    if (!in_array($reportid, $indexarray)) {
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
                        echo $row['reportid'];
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
                            echo '<a class="form__link" href="../projects/uploads/' . $row['reportid'] . "/" . $row['document'] . '">' . "Uploaded file" . '</a>';
                        } else {
                            echo "No uploaded file";
                        }
                        echo "</td>";
                        echo "</tr>";
                        echo "<tr style='border-bottom-style:solid;border-color: #20a668;'>";
                        echo "<td colspan='7' class='trinf'>";
                        echo "Incident type: " . $row['report_type'] . '<br>';
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