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
    <nav>
        <input type="checkbox" id="check">
        <label for="check" class="checkbtn">
            <i class="fas fa-bars"></i>
        </label>
        <ul class="imagecontainer">
            <li><a class="imagelink" href="1_Dashboard.php"><img class="imagelogo" src="Media/Rateopia_Black.png"><span class="logotxt"> Rateopia </span></a></li>
        </ul>
        <ul class="dashNav">
            <li><a class="active" href="1_Search.php"><i class="fa fa-fw fa-search"></i>Search</a></li>
            <li><a href="1_Changes.php">Recent Changes</a></li>
            <li><a href="includes/logout.php">Logout</a></li>
        </ul>
    </nav>
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
           
        </table>
    </div>
    </div>
</body>

</html>