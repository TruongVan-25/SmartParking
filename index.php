<?php 
    session_start();  
    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: login.php");
    } 
    if($_SESSION['LoginInto'] == "TRUE") {
        $current = 'home';
        require_once("includes/header.php");
    }
    else {
         header('Location: /smartparking/login.php');
    }
?>



    <div class="wrap" style="background: url(image/3.jpg); color:white">
        <div class="row" style="padding: 20px">
            <div class="col-sm-5">
                <img src="image/robotsystem.jpg" style="width: 100%;">
            </div>

            <div class="col-sm-7">
                <h4>A Mobile smartparking System for Surveillance of Environments</h4>

                <p style="text-align: justify;">The development of intelligent parking management systems is an active research area. In this context, smart and multi-functional IoT-based solutions are increasingly adopted to optimize space utilization, reduce the need for manual supervision, and improve user experience. Nevertheless, the integration of various sensors, real-time data processing, and automation features makes the design of a complete smart parking system challenging. In this work, we present our smart parking solution for monitoring and managing parking spaces in real time. We propose a system capable of autonomously handling core parking management functions and advanced monitoring tasks simultaneously. The proposed smart parking scheme effectively addresses key issues such as space detection, vehicle recognition, occupancy monitoring, and real-time data visualization for users and administrators. Real-world applications of the proposed system include parking facilities in shopping malls, airports, office buildings, and public areas, enabling efficient space usage, reduced congestion, and improved service quality.</p>
    
            </div>
        </div>

        <div class="row" style="padding: 20px">
            <div class="col-sm-7">
                <h4>System Overview</h4>

                <p>This section describes the three-layer architecture developed for the surveillance system. We propose a reconfigurable component-based approach in which the three main components can be viewed as containers of dynamic libraries that can be configured for the particular scenario. More specifically we can select what primitive behaviors (e.g. avoid obstacles, wandering, go forward, etc.), complex tasks (e.g. robot localization with RFID and vision, detect removed or abandoned objects, detect people, etc.) and control algorithms (e.g. event detection, task sequencing, human operator interaction, etc.) have to start.</p>
              
            </div>

            <div class="col-sm-5">
                <img src="image/overview.jpg" style="width: 100%;">
            </div>
        </div>       

    </div>

<!-- 
     <script src="js/jquery.js"></script>
     <script src="js/index.js"></script>
 -->
<?php require_once("includes/footer.php"); ?> 