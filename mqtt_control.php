<?php
require("phpMQTT.php"); // Thư viện MQTT

$server = "172.16.2.4"; // MQTT broker IP
$port = 1883;
$username = "";
$password = "";
$client_id = "php_control_" . uniqid();

$topic = "parking/gate/cmd";

if (isset($_POST['action'])) {
    $action = $_POST['action']; // OPEN hoặc CLOSE

    $mqtt = new Bluerhinos\phpMQTT($server, $port, $client_id);
    if ($mqtt->connect(true, NULL, $username, $password)) {
        $mqtt->publish($topic, $action, 0);
        $mqtt->close();
        echo "OK";
    } else {
        echo "Failed to connect MQTT broker";
    }
}
?>