<?php

// Connect to the Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "loginpage"; 

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>