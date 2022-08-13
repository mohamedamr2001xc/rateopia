<?php
session_start();
unset($_SESSION['National_id']);
session_destroy();
header("Location: ../index.php")

?>