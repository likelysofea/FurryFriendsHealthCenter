<?php

$sname = "localhost";
$uname = "root";
$password = "aliesyasofea";
$db_name = "vet_clinic";

$conn = mysqli_connect($sname, $uname, $password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
