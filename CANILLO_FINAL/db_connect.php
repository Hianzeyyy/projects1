<?php
$servername = "127.0.0.1"; // The IP
$username = "root";
$password = ""; 
$dbname = "final_canillo_db";
$port = 3306; // Adding the port explicitly based on your config

$conn = mysqli_connect($servername, $username, $password, $dbname, $port);

if (!$conn) {
    // If 3307 fails, try the default 3306
    $conn = mysqli_connect($servername, $username, $password, $dbname, 3306);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
?>