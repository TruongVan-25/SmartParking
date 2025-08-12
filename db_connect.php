<?php
$host = "localhost";
$user = "smartparking";       // đổi theo XAMPP
$pass = "cyber@2025";           // đổi theo XAMPP
$dbname = "smart_parking";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>