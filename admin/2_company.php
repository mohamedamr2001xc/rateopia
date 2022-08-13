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
            <h2>Companies inquiries </h2>
            <table class="table-adjust">
                <tr style="border-bottom-style:solid;border-color: #20a668;">
                    <th style="padding:10px;" colspan="7">Companies inquiries</th>
                </tr>
                <tr style="border-bottom-style:solid;border-color: #20a668;">
                    <td class='trinf'><b>National id</b></td>
                    <td class='trinf'><b>Date</b></td>
                    <td class='trinf'><b>Company name</td>
                    <td class='trinf'><b>Company mail</b></td>

                </tr>
                <?php

                $sql = "SELECT * FROM companies_inquiry WHERE TIMESTAMPDIFF(DAY, Date(NOW()),submit_date)>-30  ORDER BY submit_date Desc";

                $resultfirst = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($resultfirst)) {
                    $nationalid = $row['National_id'];
                    $sql = "SELECT * FROM citizen where id='$nationalid'";
                    $result = mysqli_query($conn, $sql);
                    $rowoutput = mysqli_fetch_assoc($result);
                    echo "<tr>";
                    echo "<td class='trinf'>";
                    echo '<a class="form__link"  href="../projects/ouputinformation.php?subject=' . $rowoutput['National_id'] . '"' . ">" . $rowoutput['id'] . "</a>" . "<br>";
                    echo "</td>";

                    echo "<td class='trinf'>";
                    echo $row['submit_date'] . "<br>";
                    echo "</td>";
                    echo "<td class='trinf'>";
                    echo $row['company_name'] . "<br>";
                    echo "</td>";
                    echo "<td class='trinf'>";
                    echo $row['email'] . "<br>";
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