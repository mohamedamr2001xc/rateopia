<?php
if (isset($_POST['submit'])) {
    require 'database.php';
    session_start();
    $nationalid = $_POST['National_id'];
    $password = $_POST['password'];
    if (empty($nationalid) || empty($password)) {
        header("Location: ../2_login.php?error=emptyfields");
        exit();
    } 
    else {
        $sql="SELECT * FROM admin WHERE ID = $nationalid";
        $password2="SELECT passwordd FROM admin WHERE ID = $nationalid";
        $sql2="SELECT ID FROM admin ID = $nationalid";
        $result=mysqli_query($conn,$sql);
        $result2=mysqli_query($conn,$password2);
        $result3=mysqli_query($conn,$sql2);
        $row=mysqli_fetch_assoc($result2);
        $row2=mysqli_fetch_assoc($result3);
        if(mysqli_fetch_assoc($result) && $password==$row['passwordd'] ){
            /*$_SESSION['Name']=$all_data['Name'];*/
            $_SESSION['National_id']=$row2['National_id'];
            /*$_SESSION['Age']=$all_data['Age'];
            $_SESSION['']*/
            header("Location: ../1_Dashboard.php");
        }
        else
        {
            header("Location: ../login.php?Invalid=Pleaseentercorrectusernameandpassword");
        }
    }
}
  /* elseif () {
                header("Location: ../login.php?error=accessforbidden");
                exit();
            }*/
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
