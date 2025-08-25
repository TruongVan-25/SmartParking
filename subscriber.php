<?php
require("phpMQTT.php"); // thư viện phpMQTT

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0); // chạy liên tục
ob_implicit_flush(true);

// Hàm log ra web
function logMsg($msg)
{
    echo "[" . date("Y-m-d H:i:s") . "] " . htmlspecialchars($msg) . "<br>\n";
    ob_flush();
    flush();
}

// Kết nối DB
try {
    $db = new mysqli("localhost", "smartparking", "cyber@2025", "smart_parking");
    if ($db->connect_error)
        throw new Exception("DB connect failed: " . $db->connect_error);
    logMsg("✅ DB connected");
} catch (Exception $e) {
    logMsg("❌ " . $e->getMessage());
    exit;
}

// Kết nối MQTT
$server = "172.16.2.4";
$port = 1883;
$username = "";
$password = "";
$client_id = "php_control_" . uniqid();

try {
    $mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
    if (!$mqtt->connect(true, NULL, $username, $password))
        throw new Exception("Cannot connect to MQTT Broker");
    logMsg("✅ MQTT connected to $server:$port");
} catch (Exception $e) {
    logMsg("❌ " . $e->getMessage());
    exit;
}

$irStatus = [
    'ENTRY' => null,
    'EXIT'  => null
];

$slotStatus = [];

// Đăng ký topic
$topics['parking/#'] = array("qos" => 0, "function" => "procMsg");
$mqtt->subscribe($topics, 0);
logMsg("➡ Subscribed to topics: parking/#");

// Vòng lặp nhận tin
while ($mqtt->proc()) {
    // giữ script chạy liên tục
}

$mqtt->close();
logMsg("MQTT connection closed");


// Hàm xử lý tin nhắn
function procMsg($topic, $msg)
{
    global $db, $mqtt, $pendingEntry, $irStatus, $slotStatus;
    logMsg("📩 Received [$topic]: $msg");

    try {

        // 1. Cập nhật trạng thái IR gate
        if ($topic === "parking/gate/entry/ir") {
            $irStatus['ENTRY'] = trim($msg); // O hoặc X
            logMsg("ℹ Entry IR status: {$irStatus['ENTRY']}");
            return;
        } elseif ($topic === "parking/gate/exit/ir") {
            $irStatus['EXIT'] = trim($msg);
            logMsg("ℹ Exit IR status: {$irStatus['EXIT']}");
            return;
        }

        if (preg_match('/^parking\/slot\/([A-Z])(\d+)\/status$/', $topic, $m)) {
            $area = $m[1];
            $slotCode = $m[2];
            $data = json_decode($msg, true);
            if ($data && isset($data['status'])) {
                $slotStatus[$area . $slotCode] = $data['status']; // 'O' hoặc 'X'
                logMsg("ℹ Slot {$area}{$slotCode} status updated: {$data['status']}");
            }
        }
        
        // 5. RFID card check auth
        else if ($topic == "parking/rfid") {

            // Nhận dạng ENTRY hoặc EXIT
            if (preg_match('/^(ENTRY|EXIT):(.+)$/', $msg, $matches)) {
                $gateType = $matches[1]; // ENTRY hoặc EXIT
                $rfid = $matches[2];     // mã RFID

               
                
                // ✅ Kiểm tra IR trước khi xử lý
                if ($irStatus[$gateType] !== "O") {
                    logMsg("⛔ IR $gateType không có xe (status={$irStatus[$gateType]}), bỏ qua RFID $rfid");
                    return; 
                }

                

                // Kiểm tra DB
                $rfid_safe = $db->real_escape_string($rfid);
                $result = $db->query("SELECT RFID FROM rfidcard WHERE RFID = '$rfid_safe'");

                if ($result && $result->num_rows > 0) {
                    // Có trong DB
                    $authMsg = $rfid . ":yes";
                    $mqtt->publish("parking/rfid/auth", $authMsg, 0);
                    logMsg("✅ RFID $rfid hợp lệ, gửi $authMsg");

                    // Điều khiển mở cổng
                    if ($gateType === "ENTRY") {

                        // Kiểm tra xe đã ở trong bãi chưa
                        $checkIn = $db->query("SELECT 1 FROM parkinghistory WHERE RFID='$rfid_safe' AND TimeOut IS NULL LIMIT 1");
                        if ($checkIn && $checkIn->num_rows > 0) {
                            logMsg("⛔ Xe RFID $rfid đã ở trong bãi, không mở cổng vào");
                            return; // Không xử lý tiếp
                        }

                        $mqtt->publish("parking/gate/cmd", "OPEN_ENTRY", 0);
                        logMsg("🚪 Mở cổng vào");
                        
                        $pendingEntry = [
                            'rfid' => $rfid_safe,
                            'time' => time()
                        ];

                        if(!$db->query("INSERT INTO parkinghistory (RFID, SlotID, TimeIn) VALUES ('$rfid_safe', NULL, NOW())")) {
                            logMsg("❌ Lỗi insert parkinghistory: " . $db->error);
                        } else {
                            logMsg("✅ Đã thêm bản ghi parkinghistory cho RFID $rfid (ENTRY)");
                        }
                    } elseif ($gateType === "EXIT") {

                        // Kiểm tra xe có trong bãi không
                        $checkOut = $db->query("SELECT 0 FROM parkinghistory WHERE RFID='$rfid_safe' AND TimeOut IS NULL LIMIT 1");
                        if (!$checkOut || $checkOut->num_rows === 0) {
                            logMsg("⛔ RFID $rfid không có xe trong bãi, không mở cổng ra");
                            return; // Không xử lý tiếp
                        }

                        $mqtt->publish("parking/gate/cmd", "OPEN_EXIT", 0);
                        logMsg("🚪 Mở cổng ra");

                        // Tìm SlotID đang chứa RFID này
                        $res = $db->query("SELECT SlotID FROM parkingslot WHERE CurrentRFID='$rfid_safe' LIMIT 1");
                        if ($res && $row = $res->fetch_assoc()) {
                            $slotIdExit = intval($row['SlotID']);
                            
                            // Cập nhật TimeOut và tính phí
                            $db->query("UPDATE parkinghistory 
                                        SET TimeOut = NOW()
                                        WHERE RFID='$rfid_safe' AND TimeOut IS NULL 
                                        ORDER BY HistoryID DESC LIMIT 1");

                            $db->query("UPDATE parkinghistory 
                                        SET Duration = TIMESTAMPDIFF(MINUTE, TimeIn, TimeOut),
                                            Fee = TIMESTAMPDIFF(MINUTE, TimeIn, TimeOut) * 10
                                        WHERE RFID='$rfid_safe' AND TimeOut IS NOT NULL
                                        ORDER BY HistoryID DESC LIMIT 1");
                            
                            // Đánh dấu slot trống
                            $db->query("UPDATE parkingslot SET Status=0, CurrentRFID=NULL WHERE SlotID=$slotIdExit");

                            logMsg("🚗 RFID $rfid_safe rời SlotID $slotIdExit, cập nhật slot trống");
                        }
                    }
                } else {
                    // Không có trong DB
                    $authMsg = $rfid . ":no";
                    $mqtt->publish("parking/rfid/auth", $authMsg, 0);
                    logMsg("❌ RFID $rfid không hợp lệ, gửi $authMsg");
                }

            } else {
                logMsg("⚠ Dữ liệu RFID không hợp lệ: $msg");
            }
        }
        else if ($topic == "parking/log"){
            global $pendingEntry;

            $data = json_decode($msg, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['event'])) {
                if ($data['event'] === 'slot_change' && isset($data['slot'], $data['status'])) {
                    $slotIdStr = $data['slot']; // Ví dụ "B1"
                    $status = $data['status'];

                    $area = substr($slotIdStr, 0, 1);
                    $slotCode = substr($slotIdStr, 1);

                    // Chỉ xử lý ENTRY khi có pendingEntry
                    if ($status === "X" && $pendingEntry && (time() - $pendingEntry['time'] <= 300)) {
                        $res = $db->query("SELECT SlotID FROM parkingslot WHERE Area='$area' AND SlotCode='$slotCode' LIMIT 1");
                        if ($res && $row = $res->fetch_assoc()) {
                            $slotIdNum = intval($row['SlotID']);

                            // Update bản ghi parkinghistory vừa tạo
                            $db->query("UPDATE parkinghistory SET SlotID=$slotIdNum 
                                        WHERE RFID='{$pendingEntry['rfid']}' AND SlotID IS NULL 
                                        ORDER BY HistoryID DESC LIMIT 1");

                            // Update trạng thái slot
                            $db->query("UPDATE parkingslot SET Status=1, CurrentRFID='{$pendingEntry['rfid']}' WHERE SlotID=$slotIdNum");

                            logMsg("✅ Gán Slot $slotIdStr cho RFID {$pendingEntry['rfid']}");
                            $pendingEntry = null; // reset trạng thái
                        }
                    }
                }
            }
        }


    } catch (Exception $e) {
        logMsg("❌ Error: " . $e->getMessage());
    }
}