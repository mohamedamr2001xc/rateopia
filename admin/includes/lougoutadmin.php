<?php
session_start();
echo $_SESSION['ID'];
unset($_SESSION['ID']);
session_destroy();
header("Location: ../../projects/index.php");
