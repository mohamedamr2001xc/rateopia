
<?php
if (isset($_POST['submit']) && $_POST['g-recaptcha-response'] != "") {
    require 'database.php';
    session_start();
    $nationalid = $_POST['National_id'];
    $_SESSION['National_id'] = $nationalid;
    $nationalid = mysqli_real_escape_string($conn, $nationalid);
    $password = $_POST['password'];
    $password = mysqli_real_escape_string($conn, $password);
    $passwordcheck = strtolower($password);
    if (!is_numeric($nationalid)) {
        header("Location: ../2_login.php?error=worngnationalid");
        exit();
    }
    $sql = "SELECT * FROM blocked_users where userid='$nationalid'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        header("Location: ../2_login.php?error=blockeduser");
        exit();
    }



    $sql = "SELECT * FROM login_info where id='$nationalid' ORDER BY datee DESC , timee DESC Limit 10";
    $result = mysqli_query($conn, $sql);
    $counter = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['login_stat'] == 0) {
            $counter++;
        } else {
            continue;
        }
    }

    if ($counter == 10) {
        $sql = "INSERT INTO blocked_users (userid) values ('$nationalid')";
        mysqli_query($conn, $sql);
        header("Location: ../2_login.php?error=blockeduser");
        exit();
    }
    if (!ctype_alnum($passwordcheck)) {
        header("Location: ../2_login.php?error=wrongpassword");
        exit();
    }

    if (empty($nationalid) || empty($password)) {
        header("Location: ../2_login.php?error=emptyfields");
        exit();
    } else {
        $sql = "SELECT * FROM adminn WHERE ID = $nationalid";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            header("Location: ../2_login.php?error=idisnotvalid");
            exit();
        }
        $password = hash('sha256', $password);
        $sql = "SELECT * FROM adminn WHERE ID = '$nationalid'";
        $password2 = "SELECT passwordd FROM adminn WHERE ID = '$nationalid'";
        $sql2 = "SELECT ID FROM adminn WHERE ID = '$nationalid'";
        $result = mysqli_query($conn, $sql);
        $result2 = mysqli_query($conn, $password2);
        $result3 = mysqli_query($conn, $sql2);
        $row = mysqli_fetch_assoc($result2);
        $row2 = mysqli_fetch_assoc($result3);
        if (mysqli_fetch_assoc($result) && $password == $row['passwordd']) {
            /*$_SESSION['Name']=$all_data['Name'];*/
            $sql = "INSERT INTO login_info (id,login_stat,datee,timee) Values (?,1,NOW(),TIME (NOW()))";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_login.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $nationalid);
                mysqli_stmt_execute($stmt);
            }
            $_SESSION['ID'] = $row2['ID'];
            /*$_SESSION['Age']=$all_data['Age'];
            $_SESSION['']*/

            $sql = "INSERT INTO monitor (id,event_type,datee) values (?,'admin login',NOW())";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_login.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $nationalid);
                mysqli_stmt_execute($stmt);
            }
            $sql = "SELECT * from monitor where id='$id' AND datee > (NOW() - INTERVAL 1 Minute)";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 20) {
                $sql = "INSERT INTO blocked_users (userid) values '$nationalid'  ";
                mysqli_query($conn, $sql);
                header("Location: ../2_login.php?error=blockeduser");
                exit();
            }

            header("Location: ../2_Dashboard.php");
            exit();
        } else {
            $sql = "INSERT INTO login_info (id,login_stat,datee,timee) Values (?,0,NOW(),TIME (NOW()))";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../2_login.php?error=sqlerror");
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $nationalid);
                mysqli_stmt_execute($stmt);
            }

            header("Location: ../2_login.php?Invalid=Pleaseentercorrectusernameandpassword");
        }
    }
    $secret = '6Lcc1JkgAAAAAB-Lnt_X6lDAlcZRIs9P7Yq5oqK6';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);
} elseif (isset($_POST['submit']) && $_POST['g-recaptcha-response'] == "") {
    header("Location:../2_login.php?error=emptyfields");
}
?>






















<?php
/*<?php
if (isset($_POST['submit'])) {

    require 'database.php';

    $nationalid = $_POST['National_id'];
    $password = $_POST['password'];

    if (empty($nationalid) || empty($password)) {
        header("Location: ../login.php?error=emptyfields");
        exit();
    } else {


        $sql="SELECT * FROM citizen WHERE National_id = ?"
    
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Locationmysqli_stmt_prepare: ../index.php?error=sqlerror");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $nationalid);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $pass_l = "SELECT password FROM citizen WHERE National_id = $nationalid";
    
                if ($password !=$pass_l) {
                    header("Location: ../login.php?error=wrongpass");
                    exit();
                } elseif ($password ==$pass_l) {
                    session_start();
                    $_SESSION['sessionId'] = $row['id'];
                    $_SESSION['sessionnationalid'] = $row['nationalid'];
                    header("Location: ../index.php?success=loggedin");
                    exit();
                } else {
                    header("Location: ../login.php?error=wrongpassword");
                    exit();
                }
            } else {
                header("Location: ../login.php?error=nouser");
                exit();
            }
        }
    }
}*/

?>
