<?php
require 'database.php';
$outputvideo = array();
$mydir = '../face_rec/faces/';
$myfiles = array_diff(scandir($mydir), array('.', '..'));
for ($i = 2; $i < (2 + count($myfiles)); $i++) {
    $myfiles[$i] = substr($myfiles[$i], 0, strrpos($myfiles[$i], '.'));
}

$command = 'cd C:\xampp\htdocs\dev.rateopia.com\projects\face_rec && python cam_rec.py';
$output = shell_exec($command);
#echo $output; 
for ($i = 2, $j = 0; $i < (2 + count($myfiles)); $i++, $j++) {

    if (str_contains($output, $myfiles[$i])) {
        /*echo $myfiles[$
            echo "<br>";*/

        array_push($outputvideo, $myfiles[$i]);
    }
}
echo "<br>";



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
    <title>Search ouput</title>
</head>

<body>
    <div>
        <table class="table-adjust">
            <tr>
                <th style="padding:10px;" colspan="3">Results</th>
            </tr>
            <tr>
                <td class='trinf'><b>Name</b></td>
                <td class='trinf'><b>ID</b></td>
                <td class='trinf'><b>Image</b></td>
            </tr>
            <?php
            /*$htmlname=$_SESSION['htmlname'];
                        $htmlnationalid=$_SESSION['htmlnationalid'];
                        $htmlimagepath= $_SESSION['htmlimagepath'];*/
            for ($i = 0; $i < count($outputvideo); $i++) {
                $sql = "SELECT * From citizen WHERE National_id='$outputvideo[$i]' ";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
                echo "<tr>";
                echo "<td class='trinf'>";
                echo '<a target="_blank" class="form__link" href="../ouputinformation.php?subject=' . $outputvideo[$i] . '">' . $row['Name'] . '</a>';
                echo "</td>";
                echo "<td class='trinf'>";
                echo '<a target="_blank" class="form__link" href="../ouputinformation.php?subject=' . $outputvideo[$i] . '">' . $row['National_id'] . '</a>';
                echo "</td>";
                echo "<td class='trinf'>";
                echo '<a target="_blank" class="form__link" href="../face_rec/faces/' . $outputvideo[$i] . ".jpg" . '"' . ">" . "<img src=" . '"../face_rec/faces/' . $outputvideo[$i] . ".jpg" . '" ' . 'width="100px" height="100px">' . "</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>