<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "epiz_24927018_commerce";

// Create connection
$con = mysqli_connect($servername, $username, $password,$db);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


?>