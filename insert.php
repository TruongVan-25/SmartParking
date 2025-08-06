<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sensor_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$temp = $_GET['temp'];
$hum = $_GET['hum'];

$sql = "INSERT INTO dht_data (temperature, humidity) VALUES ('$temp', '$hum')";

if ($conn->query($sql) === TRUE) {
  echo "OK";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
