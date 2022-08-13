<?php
session_start();
$national_id = $_SESSION['National_id'];
if (!isset($_SESSION['National_id'])) {
    header("Location: 0_login.php");
    exit();
}

include 'includes/database.php';
$nationalidcheck = $national_id;
$sql = "SELECT * From citizen where National_id='$nationalidcheck' ";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$nationalidcheck = $row['id'];
$sql = "SELECT * From blocked_users where userid='$nationalidcheck'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    header("Location: 0_login.php?error=blockeduer");
    exit();
}

$id = $_SESSION['National_id'];
$sql = "INSERT INTO monitor (id,event_type,datee) values (?,'check changes',NOW())";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../0_login.php?error=sqlerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
}
$sql = "SELECT * from monitor where id='$id' AND datee > (NOW() - INTERVAL 1 Minute)";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 20) {
    $sql = "INSERT INTO blocked_users (userid) values '$nationalidcheck'  ";
    mysqli_query($conn, $sql);
    header("Location: ../0_login.php?error=blockeduser");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Recent Reports</title>
</head>

<body>
    <?php include 'user_navbar.php' ?>

    <section>
        <div>
            <table class="table-adjust">
                <tr style="border-bottom-style:solid;border-color: #20a668;">
                    <th style="padding:10px;" colspan="7">Monthly Reports</th>
                </tr>
                <tr style="border-bottom-style:solid;border-color: #20a668;">
                    <td class='trinf'><b>Accused ID</b></td>
                    <td class='trinf'><b>Report ID</b></td>
                    <td class='trinf'><b>Date</b></td>
                    <td class='trinf'><b>Time</b></td>
                    <td class='trinf'><b>Uploaded File</b></td>
                </tr>
                <?php

                $sql = "SELECT * FROM reports WHERE reportsNational_id_2='$national_id' AND  TIMESTAMPDIFF(DAY, Date(NOW()),datee)<-30  ORDER BY datee DESC";
                $result = mysqli_query($conn, $sql);
                $rowcount = mysqli_num_rows($result);

                for ($i = 0; $i < $rowcount; $i++) {
                    echo "<tr>";

                    $row = mysqli_fetch_assoc($result);
                    $reporter_id = $row['National_id_1'];
                    $sql = "SELECT id FROM citizen where National_id='$reporter_id'";
                    $ouputecho = mysqli_query($conn, $sql);
                    $rowoutput = mysqli_fetch_assoc($ouputecho);
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
                    echo $row['report_info'];
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </section>
</body>

</html>
<?php include 'footer.php'; ?>