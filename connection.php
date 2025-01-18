<?php
session_start();
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "administrator";

    //Creating db connection
    $con = mysqli_connect($host, $username, $password, $database);
    
    //Check db connection
    if(!$con)
    {
        die("Connection Failed:".mysqli_connect_error());
    }
?>