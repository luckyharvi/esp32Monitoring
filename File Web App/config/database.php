<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "myiot";

$conn = mysqli_connect($servername, $username, $password, $database);

//Periksa koneksi
if(!$conn){
    die("Connection failed". mysqli_connect_error());
}

//echo"Connection success";

?>