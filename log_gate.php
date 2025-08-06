<?php
include 'db_connect.php';

$gate = $_GET['gate'];   // ENTRY / EXIT
$action = $_GET['action']; // OPEN / CLOSE
$by = $_GET['by'];       // RFID / Manual / System

$conn->query("INSERT INTO GateLog (GateType, Action, Time, TriggeredBy) 
              VALUES ('$gate', '$action', NOW(), '$by')");

echo "OK";
?>