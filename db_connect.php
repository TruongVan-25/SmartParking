<?php
$host = "localhost";
$user = "root";       // đổi theo XAMPP
$pass = "";           // đổi theo XAMPP
$dbname = "smart_parking";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>