<?php
require("phpMQTT.php"); // thư viện phpMQTT (phpMQTT.php đặt cùng folder)

$server = "172.16.2.4";     // MQTT Broker IP
$port = 1883;
$username = "smartparking";             // nếu broker có auth thì điền
$password = "cyber@2025";
$client_id = "php_subscriber_01";

// Kết nối DB
$db = new mysqli("localhost", "smartparking", "cyber@2025", "smart_parking");
if ($db->connect_error) {
    die("DB connect failed: " . $db->connect_error);
}

$mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);

if(!$mqtt->connect(true, NULL, $username, $password)) {
    exit(1);
}

$topics['parking/#'] = array("qos"=>0, "function"=>"procMsg");
$mqtt->subscribe($topics, 0);

while($mqtt->proc()){}

$mqtt->close();

function procMsg($topic, $msg){
    global $db;

    // 1. Trạng thái từng slot
    if (preg_match('/^parking\/slot\/(.+)\/status$/', $topic, $m)) {
        $slotCode = $db->real_escape_string($m[1]); // A1, B1,...
        $status = ($msg == "O") ? 1 : 0;

        // Cập nhật bảng parkingslot
        $db->query("UPDATE parkingslot 
                    SET Status=$status 
                    WHERE SlotCode='$slotCode'");
    }

    // 2. Log cổng (OPEN/CLOSE)
    else if ($topic == "parking/gate/status") {
        // msg ví dụ: ENTRY_OPEN
        if (strpos($msg, "ENTRY") === 0) $gateType = "ENTRY";
        else if (strpos($msg, "EXIT") === 0) $gateType = "EXIT";
        else $gateType = "UNKNOWN";

        $action = (strpos($msg, "OPEN") !== false) ? "Open" : "Close";

        $db->query("INSERT INTO gatelog(GateType, Action, Time, TriggeredBy) 
                    VALUES('$gateType', '$action', NOW(), 'SYSTEM')");
    }

    // 3. Log JSON từ Wemos
    else if ($topic == "parking/log") {
        $data = json_decode($msg, true);
        if ($data && isset($data['gate']) && isset($data['action'])) {
            $gate = $db->real_escape_string($data['gate']);
            $action = $db->real_escape_string($data['action']);
            $by = $db->real_escape_string($data['by']);
            $time = date("Y-m-d H:i:s");

            $db->query("INSERT INTO gatelog(GateType, Action, Time, TriggeredBy) 
                        VALUES('$gate', '$action', '$time', '$by')");
        }
    }

    // 4. Nếu muốn lưu số slot trống
    else if ($topic == "parking/slots/count") {
        $free = intval($msg);
        // Có thể log riêng hoặc bỏ qua, vì parkingslot đã có trạng thái chi tiết
    }
?>