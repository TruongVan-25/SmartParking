<?php
require("phpMQTT.php"); // thư viện phpMQTT

error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0); // chạy liên tục
ob_implicit_flush(true);

// Hàm log ra web
function logMsg($msg){
    echo "[".date("Y-m-d H:i:s")."] " . htmlspecialchars($msg) . "<br>\n";
    ob_flush();
    flush();
}

// Kết nối DB
try {
    $db = new mysqli("localhost", "smartparking", "cyber@2025", "smart_parking");
    if ($db->connect_error) throw new Exception("DB connect failed: ".$db->connect_error);
    logMsg("✅ DB connected");
} catch(Exception $e){
    logMsg("❌ ".$e->getMessage());
    exit;
}

// Kết nối MQTT
$server = "172.16.2.4";     
$port = 1883;
$username = "";             
$password = "";
$client_id = "php_control_".uniqid();

try {
    $mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
    if(!$mqtt->connect(true, NULL, $username, $password)) throw new Exception("Cannot connect to MQTT Broker");
    logMsg("✅ MQTT connected to $server:$port");
} catch(Exception $e){
    logMsg("❌ ".$e->getMessage());
    exit;
}

// Đăng ký topic
$topics['parking/#'] = array("qos"=>0, "function"=>"procMsg");
$mqtt->subscribe($topics, 0);
logMsg("➡ Subscribed to topics: parking/#");

// Vòng lặp nhận tin
while($mqtt->proc()){
    // giữ script chạy liên tục
}

$mqtt->close();
logMsg("MQTT connection closed");


// Hàm xử lý tin nhắn
function procMsg($topic, $msg){
    global $db;
    logMsg("📩 Received [$topic]: $msg");

    try {
        // 1. Trạng thái từng slot
        if (preg_match('/^parking\/slot\/(.+)\/status$/', $topic, $m)) {
            $slotCode = $db->real_escape_string($m[1]); 
            $status = ($msg == "O") ? 1 : 0;

            if(!$db->query("UPDATE parkingslot SET Status=$status WHERE SlotCode='$slotCode'")) {
                throw new Exception("DB update parkingslot failed: ".$db->error);
            }
            logMsg("✅ Slot $slotCode updated to status $status");
        }

        // 2. Log cổng
        else if ($topic == "parking/gate/status") {
            if (strpos($msg, "ENTRY") === 0) $gateType = "ENTRY";
            else if (strpos($msg, "EXIT") === 0) $gateType = "EXIT";
            else $gateType = "UNKNOWN";

            $action = (strpos($msg, "OPEN") !== false) ? "Open" : "Close";

            if(!$db->query("INSERT INTO gatelog(GateType, Action, Time, TriggeredBy) 
                            VALUES('$gateType', '$action', NOW(), 'SYSTEM')")) {
                throw new Exception("DB insert gatelog failed: ".$db->error);
            }
            logMsg("✅ Gate log: $gateType - $action");
        }

        // 3. Log JSON từ Wemos
        else if ($topic == "parking/log") {
            $data = json_decode($msg, true);
            if ($data && isset($data['gate'], $data['action'], $data['by'])) {
                $gate = $db->real_escape_string($data['gate']);
                $action = $db->real_escape_string($data['action']);
                $by = $db->real_escape_string($data['by']);
                $time = date("Y-m-d H:i:s");

                if(!$db->query("INSERT INTO gatelog(GateType, Action, Time, TriggeredBy) 
                                VALUES('$gate', '$action', '$time', '$by')")) {
                    throw new Exception("DB insert gatelog failed: ".$db->error);
                }
                logMsg("✅ JSON Gate log inserted: $gate - $action by $by");
            } else {
                logMsg("⚠ Invalid JSON: $msg");
            }
        }

        // 4. Slot count
        else if ($topic == "parking/slots/count") {
            $free = intval($msg);
            logMsg("ℹ Free slots count: $free");
        }
        else if ($topic == "parking/rfid") {
            global $mqtt;
            if (strpos($msg, "EXIT:") === 0) {
                $rfid = substr($msg, 5); // Lấy mã RFID

                // Kiểm tra RFID trong DB
                $rfid_safe = $db->real_escape_string($rfid);
                $result = $db->query("SELECT RFID FROM rfidcard WHERE RFID = '$rfid_safe'");

                if ($result && $result->num_rows > 0) {
                    $response = $rfid . ":yes";
                } else {
                    $response = $rfid . ":no";
                }

                // Publish kết quả
                $mqtt->publish("parking/rfid/auth", $response, 0);
                logMsg("🔄 Checked RFID {$rfid} → {$response}");
            } else if ($msg === "OPEN") {
                logMsg("📡 Received OPEN command");
            } else {
                logMsg("⚠ Invalid RFID message: $msg");
            }
        }


    } catch(Exception $e){
        logMsg("❌ Error: ".$e->getMessage());
    }
}
