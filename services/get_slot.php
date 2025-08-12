<?php
include("../php/connectSQL.php");

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT CONCAT(Area, SlotCode) AS SlotName FROM parkingslot";
$result = $conn->query($sql);

$slots = array();
while ($row = $result->fetch_assoc()) {
    $slots[] = $row["SlotName"];
}

header('Content-Type: application/json');
echo json_encode($slots);

$conn->close();
?>