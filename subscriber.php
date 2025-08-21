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
    global $db;
    logMsg("📩 Received [$topic]: $msg");

    try {
        // 1. Trạng thái từng slot
        if (preg_match('/^parking\/slot\/([A-Z])(\d+)\/status$/', $topic, $m)) {
            if (isset($m[1], $m[2])) {
                $area = $db->real_escape_string($m[1]);
                $slotCode = $db->real_escape_string($m[2]);
            } else {
                logMsg("⚠ Topic slot/status không hợp lệ: $topic");
                return;
            }

            $data = json_decode($msg, true);
            if ($data && isset($data['event'], $data['slot'], $data['status'])) {
                if ($data['event'] === "slot_change") {
                    if ($data['status'] === "X") {
                        // Lấy SlotID
                        $res = $db->query("SELECT SlotID FROM parkingslot WHERE Area='$area' AND SlotCode='$slotCode' LIMIT 1");
                        if ($res && $row = $res->fetch_assoc()) {
                            $slotId = intval($row['SlotID']);

                            // Tìm RFID mới vào gần nhất
                            $res2 = $db->query("SELECT RFID FROM parkinghistory WHERE SlotID IS NULL ORDER BY HistoryID DESC LIMIT 1");
                            if ($res2 && $row2 = $res2->fetch_assoc()) {
                                $rfid_last = $db->real_escape_string($row2['RFID']);

                                // Cập nhật parkinghistory
                                $db->query("UPDATE parkinghistory SET SlotID=$slotId WHERE SlotID IS NULL AND RFID='$rfid_last' ORDER BY HistoryID DESC LIMIT 1");

                                // Update trạng thái slot
                                $db->query("UPDATE parkingslot SET Status=1, CurrentRFID='$rfid_last' WHERE SlotID=$slotId");

                                logMsg("✅ Gán SlotID $slotId cho RFID $rfid_last, đánh dấu slot $area$slotCode là đang dùng");
                            }
                        }
                    }
                    else if ($data['status'] === "O") {
                        // Slot trống: cập nhật EXIT
                        $res = $db->query("SELECT SlotID, CurrentRFID FROM parkingslot WHERE Area='$area' AND SlotCode='$slotCode' LIMIT 1");
                        if ($res && $row = $res->fetch_assoc()) {
                            $slotId = intval($row['SlotID']);
                            $rfid_exit = $row['CurrentRFID'];

                            if ($rfid_exit) {
                                // Cập nhật TimeOut cho lịch sử
                                $db->query("UPDATE parkinghistory 
                                            SET TimeOut=NOW() 
                                            WHERE RFID='$rfid_exit' AND TimeOut IS NULL 
                                            ORDER BY HistoryID DESC LIMIT 1");

                                // Đánh dấu slot trống
                                $db->query("UPDATE parkingslot SET Status=0, CurrentRFID=NULL WHERE SlotID=$slotId");

                                logMsg("🚗 RFID $rfid_exit rời slot $area$slotCode, cập nhật TimeOut và slot trống");
                            }
                        }
                    }
                }
            } else {
                logMsg("⚠ Invalid JSON for slot status: $msg");
            }
        }

        // 2. Log cổng
        else if ($topic == "parking/gate/status") {
            if (strpos($msg, "ENTRY") === 0)
                $gateType = "ENTRY";
            else if (strpos($msg, "EXIT") === 0)
                $gateType = "EXIT";
            else
                $gateType = "UNKNOWN";

            $action = (strpos($msg, "OPEN") !== false) ? "Open" : "Close";

            if (
                !$db->query("INSERT INTO gatelog(GateType, Action, Time, TriggeredBy) 
                            VALUES('$gateType', '$action', NOW(), 'SYSTEM')")
            ) {
                throw new Exception("DB insert gatelog failed: " . $db->error);
            }
            logMsg("✅ Gate log: $gateType - $action");
        }

        // 3. Log JSON từ Wemos
        else if ($topic == "parking/log") {
            $data = json_decode($msg, true);
            if (json_last_error() === JSON_ERROR_NONE && isset($data['event'])) {
                if ($data['event'] === 'slot_change' && isset($data['slot'], $data['status'])) {
                    $slotId = $db->real_escape_string($data['slot']); // Ví dụ "B1"
                    $status = $data['status'];

                    $area = substr($slotId, 0, 1); // "B"
                    $slotCode = substr($slotId, 1); // "1"

                    if ($status === "X") {
                        // ENTRY
                        $res = $db->query("SELECT SlotID FROM parkingslot WHERE Area='$area' AND SlotCode='$slotCode' LIMIT 1");
                        if ($res && $row = $res->fetch_assoc()) {
                            $slotIdNum = intval($row['SlotID']);
                            $res2 = $db->query("SELECT RFID FROM parkinghistory WHERE SlotID IS NULL ORDER BY HistoryID DESC LIMIT 1");
                            if ($res2 && $row2 = $res2->fetch_assoc()) {
                                $rfid_last = $db->real_escape_string($row2['RFID']);
                                $db->query("UPDATE parkinghistory SET SlotID=$slotIdNum WHERE SlotID IS NULL AND RFID='$rfid_last' ORDER BY HistoryID DESC LIMIT 1");
                                $db->query("UPDATE parkingslot SET Status=1, CurrentRFID='$rfid_last' WHERE SlotID=$slotIdNum");
                                logMsg("✅ Gán slot $slotId cho RFID $rfid_last (đang dùng)");
                            }
                        }
                    } elseif ($status === "O") {
                        // EXIT
                        $res = $db->query("SELECT SlotID, CurrentRFID FROM parkingslot WHERE Area='$area' AND SlotCode='$slotCode' LIMIT 1");
                        if ($res && $row = $res->fetch_assoc()) {
                            $slotIdNum = intval($row['SlotID']);
                            $rfid_exit = $row['CurrentRFID'];
                            if ($rfid_exit) {
                                $db->query("UPDATE parkinghistory SET TimeOut=NOW() WHERE RFID='$rfid_exit' AND TimeOut IS NULL ORDER BY HistoryID DESC LIMIT 1");
                                $db->query("UPDATE parkingslot SET Status=0, CurrentRFID=NULL WHERE SlotID=$slotIdNum");
                                logMsg("🚗 RFID $rfid_exit rời slot $slotId (slot trống)");
                            }
                        }
                    }
                }
                // Giữ lại xử lý cũ cho log gate
                elseif (isset($data['gate'], $data['action'], $data['by'])) {
                    $gate = $db->real_escape_string($data['gate']);
                    $action = $db->real_escape_string($data['action']);
                    $by = $db->real_escape_string($data['by']);
                    $time = date("Y-m-d H:i:s");
                    $db->query("INSERT INTO gatelog(GateType, Action, Time, TriggeredBy) VALUES('$gate', '$action', '$time', '$by')");
                    logMsg("✅ JSON Gate log inserted: $gate - $action by $by");
                } else {
                    logMsg("⚠ Unrecognized JSON: $msg");
                }
            } else {
                logMsg("⚠ Invalid JSON: $msg");
            }
        }

        // 4. Slot count
        else if ($topic == "parking/slots/count") {
            $free = intval($msg);
            logMsg("ℹ Free slots count: $free");
        }

        // 5. RFID card check auth
        else if ($topic == "parking/rfid") {
            global $mqtt; // để publish

            // Nhận dạng ENTRY hoặc EXIT
            if (preg_match('/^(ENTRY|EXIT):(.+)$/', $msg, $matches)) {
                $gateType = $matches[1]; // ENTRY hoặc EXIT
                $rfid = $matches[2];     // mã RFID

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
                        $mqtt->publish("parking/gate/cmd", "OPEN_ENTRY", 0);
                        logMsg("🚪 Mở cổng vào");
                        if(!$db->query("INSERT INTO parkinghistory (RFID, SlotID, TimeIn) VALUES ('$rfid_safe', NULL, NOW())")) {
                            logMsg("❌ Lỗi insert parkinghistory: " . $db->error);
                        } else {
                            logMsg("✅ Đã thêm bản ghi parkinghistory cho RFID $rfid (ENTRY)");
                        }
                    } elseif ($gateType === "EXIT") {
                        $mqtt->publish("parking/gate/cmd", "OPEN_EXIT", 0);
                        logMsg("🚪 Mở cổng ra");
                        // Tìm SlotID đang chứa RFID này
                        $res = $db->query("SELECT SlotID FROM parkingslot WHERE CurrentRFID='$rfid_safe' LIMIT 1");
                        if ($res && $row = $res->fetch_assoc()) {
                            $slotIdExit = intval($row['SlotID']);
                            
                            // Cập nhật TimeOut
                            $db->query("UPDATE parkinghistory 
                                        SET TimeOut=NOW() 
                                        WHERE RFID='$rfid_safe' AND TimeOut IS NULL 
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


    } catch (Exception $e) {
        logMsg("❌ Error: " . $e->getMessage());
    }
}