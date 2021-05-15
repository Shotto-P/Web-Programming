<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "UniBNBDB";

    $connection = new mysqli($servername, $username, $password, $dbname);
    if($connection->connect_error){
        die("Connectioin failed: ".$connection->connect_error);
    }
?>