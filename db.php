<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "cakeshop";

$conn = mysqli_connect(
    $servername,
    $username,
    $password,
    $dbname
);

if (!$conn)
{
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

?>
