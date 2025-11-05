<?php
include("../php/connectSQL.php");

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy các slot đang occupied (TimeOut IS NULL)
$sql = "SELECT CONCAT(Area, SlotCode) AS SlotName FROM parkingslot WHERE Status = 1";

$result = $conn->query($sql);

$occupiedSlots = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $occupiedSlots[] = $row["SlotName"];
    }
}


header('Content-Type: application/json');
echo json_encode($occupiedSlots);

$conn->close();
?>