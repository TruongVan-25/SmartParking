<?php
include 'db_connect.php';

$rfid = $_GET['rfid'];

// Kiểm tra RFID tồn tại trong bảng RFIDCard
$res = $conn->query("SELECT * FROM RFIDCard WHERE RFID='$rfid'");
if ($res->num_rows == 0) {
    echo json_encode(["status" => "FAIL", "msg" => "RFID không hợp lệ"]);
} else {
    echo json_encode(["status" => "OK", "msg" => "RFID hợp lệ"]);
}
?>