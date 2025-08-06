<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "smart_parking"; 

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT CONCAT(Area, SlotCode) AS SlotName FROM parkingslot";
$result = $conn->query($sql);

$slots = array();
$i = 1;
while ($row = $result->fetch_assoc()) {
    $slots[] = array(
        "id" => $i,
        "slot_name" => $row["SlotName"]
    );
    $i++;
}

echo json_encode($slots);

$conn->close();
?>