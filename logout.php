<?php
    session_start();
    unset($_SESSION["loginUserid"]);
    header("Location: home.php");
    exit();
?>