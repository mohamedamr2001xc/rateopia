<?php
session_start();

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
    <title>Search Results</title>
</head>

<body>
<?php include 'user_navbar.php'?>

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
            $htmlname = $_SESSION['htmlname'];
            $htmlnationalid = $_SESSION['htmlnationalid'];
            $htmlimagepath = $_SESSION['htmlimagepath'];
            for ($i = 0; $i < count($htmlname); $i++) {
                echo "<tr>";
                echo "<td class='trinf'>";
                echo '<a class="form__link" href="ouputinformation.php?subject=' . $htmlnationalid[$i] . '">' . $htmlname[$i] . '</a>';
                echo "</td>";
                echo "<td class='trinf'>";
                echo '<a class="form__link" href="ouputinformation.php?subject=' . $htmlnationalid[$i] . '">' . $htmlnationalid[$i] . '</a>';
                echo "</td>";
                echo "<td class='trinf'>";
                echo '<a class="form__link" href="face_rec/faces/' . $htmlnationalid[$i] . ".jpg" . '"' . ">" . "<img src=" . '"face_rec/faces/' . $htmlnationalid[$i] . ".jpg" . '" ' . 'width="100px" height="100px">' . "</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
    </div>
</body>

</html>