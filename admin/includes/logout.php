<?php
session_start();
unset($_SESSION['ID']);
session_destroy();
header("Location: ../2_login.php");
