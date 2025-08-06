<?php
include 'db_connect.php';

$rfid = $_GET['rfid'];
$mode = $_GET['mode']; // entry / exit

// Kiểm tra RFID tồn tại
$res = $conn->query("SELECT * FROM RFIDCard WHERE RFID='$rfid'");
if ($res->num_rows == 0) {
    echo json_encode(["status" => "FAIL", "msg" => "RFID không hợp lệ"]);
    exit;
}

if ($mode == "entry") {
    // Tìm slot trống
    $slot = $conn->query("SELECT * FROM ParkingSlot WHERE Status=0 LIMIT 1");
    if ($slot->num_rows == 0) {
        echo json_encode(["status" => "FAIL", "msg" => "Hết chỗ"]);
        exit;
    }

    $slotData = $slot->fetch_assoc();
    $slotID = $slotData['SlotID'];

    // Cập nhật slot + lưu history
    $conn->query("UPDATE ParkingSlot SET Status=1, CurrentRFID='$rfid' WHERE SlotID=$slotID");
    $conn->query("INSERT INTO ParkingHistory (RFID, SlotID, TimeIn) VALUES ('$rfid', $slotID, NOW())");

    echo json_encode(["status" => "OK", "slot" => $slotData['SlotCode']]);
}

else if ($mode == "exit") {
    // Tìm slot đang giữ RFID
    $slot = $conn->query("SELECT * FROM ParkingSlot WHERE CurrentRFID='$rfid'");
    if ($slot->num_rows == 0) {
        echo json_encode(["status" => "FAIL", "msg" => "Không tìm thấy xe"]);
        exit;
    }

    $slotData = $slot->fetch_assoc();
    $slotID = $slotData['SlotID'];

    // Tính phí
    $history = $conn->query("SELECT * FROM ParkingHistory WHERE RFID='$rfid' AND TimeOut IS NULL ORDER BY HistoryID DESC LIMIT 1");
    $hData = $history->fetch_assoc();
    $timeIn = strtotime($hData['TimeIn']);
    $timeOut = time();
    $duration = round(($timeOut - $timeIn) / 60);
    $fee = $duration * 1000; // 1000đ/phút

    // Update history + slot
    $conn->query("UPDATE ParkingHistory SET TimeOut=NOW(), Duration=$duration, Fee=$fee WHERE HistoryID=".$hData['HistoryID']);
    $conn->query("UPDATE ParkingSlot SET Status=0, CurrentRFID=NULL WHERE SlotID=$slotID");

    echo json_encode(["status" => "OK", "fee" => $fee, "duration" => $duration]);
}
?>