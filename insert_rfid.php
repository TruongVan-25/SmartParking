<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sensor_db";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$RFID = $_GET['RFID'];
$ArriveTime = $_GET['ArriveTime'];

$sql = "INSERT INTO rfid_db (RFID, ArriveTime) VALUES ('$RFID', '$ArriveTime')";

if ($conn->query($sql) === TRUE) {
  echo "OK";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
