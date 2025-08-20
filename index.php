<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}
if ($_SESSION['LoginInto'] == "TRUE") {
    $current = 'home';
    require_once("includes/header.php");
} else {
    header('Location: /smartparking/login.php');
}
?>



<div class="wrap" style="background: url(image/3.jpg); color:white">
    <div class="row" style="padding: 20px">
        <div class="col-sm-5">
            <img src="image/system.jpg" style="max-width: 100%; height: auto;">
        </div>

        <div class="col-sm-7">
            <h4>A Mobile smartparking System for Surveillance of Environments</h4>

            <p style="text-align: justify;">The rapid advancement of intelligent parking management has driven the
                adoption of IoT-based solutions to optimize space utilization,
                reduce manual supervision, and enhance user experience. However, integrating heterogeneous sensors,
                real-time data processing, and automation remains a design challenge.
                This study presents a smart parking system capable of autonomously performing core management
                functions—such as space detection, vehicle identification, occupancy monitoring,
                and real-time data visualization—while supporting advanced monitoring. The proposed system is applicable
                to various contexts, including shopping malls, airports, office complexes,
                and public areas, enabling efficient space usage, reduced congestion, and improved service quality..</p>

        </div>
    </div>

    <div class="row" style="padding: 20px">
        <div class="col-sm-7">
            <h4>System Overview</h4>

            <p>This section presents the three-layer architecture of the IoT-based Smart Parking System, comprising
                sensing, control, and application layers.
                The sensing layer integrates IR sensors, RFID readers, ESP8266/Arduino controllers, and ESP32-CAM
                modules for vehicle detection, user identification,
                and visual monitoring. The control layer processes sensor data, operates servo-driven barriers, updates
                slot availability in real time,
                and communicates with the server via Wi-Fi. The application layer offers a web interface for monitoring,
                gate control, and administrative management.
                This modular design supports reconfiguration for various parking layouts, scalability, and integration
                of additional IoT components.</p>
        </div>

        <div class="col-sm-5">
            <img src="image/overview12.jpg" style="max-width: 100%; height: auto;">
        </div>
    </div>

</div>

<!-- 
     <script src="js/jquery.js"></script>
     <script src="js/index.js"></script>
 -->
<?php require_once("includes/footer.php"); ?>