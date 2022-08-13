<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<script>
    function DropDown() {
        document.getElementById("myDropdown").classList.toggle("show");
    }
    window.onclick = function(e) {
        if (!e.target.matches('.dropbtn')) {
            var myDropdown = document.getElementById("myDropdown");
            if (myDropdown.classList.contains('show')) {
                myDropdown.classList.remove('show');
            }
        }
    }
</script>

<nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
        <i class="fas fa-bars"></i>
    </label>
    <ul class="imagecontainer">
        <li><a class="imagelink" href="../2_Dashboard.php"><img class="imagelogo" src="../Media/Rateopia.png"><span class="logotxt"> Rateopia </span></a></li>
    </ul>
    <ul class="dashNav">
        <li><a href="register.php">New User</a></li>
        <li><a href="../2_searchprofile.php"><i class="fa fa-fw fa-search"></i>Search</a></li>
        <li><a href=../2_crimes.php">Crimes</a></li>
        <div class="dropdown">
            <button class="dropbtn" onclick="DropDown()">Reports
                <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content" id="myDropdown">
                <a href="../2_Changes.php">Urgent Reports</a>
                <a href="../2_Randomreports.php">Random Reports</a>
            </div>
        </div>
        <li><a href="includes/logout.php">Logout</a></li>
    </ul>
</nav>   
</body>
</html>

