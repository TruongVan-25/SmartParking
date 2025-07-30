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
         header('Location: /robotic/login.php');
    }
?>



    <div class="wrap" style="background: url(image/3.jpg); color:white">
        <div class="row" style="padding: 20px">
            <div class="col-sm-5">
                <img src="image/robotsystem.jpg" style="width: 100%;">
            </div>

            <div class="col-sm-7">
                <h4>A Mobile Robotic System for Surveillance of Environments</h4>

                <p style="text-align: justify;">The development of intelligent surveillance systems is an active research area. In this context, mobile and multi-functional robots are generally adopted as means to reduce the environment structuring and the number of devices needed to cover a given area. Nevertheless, the number of different sensors mounted on the robot, and the number of complex tasks related to exploration, monitoring, and surveillance make the design of the overall system extremely challenging. In this word, we present our mobile robot for surveillance of indoor environments. We propose a system able to handle autonomously general-purpose tasks and complex surveillance issues simultaneously. It is shown that the proposed robotic surveillance scheme successfully addresses a number of basic problems related to environment mapping, localization and autonomous navigation, as well as surveillance tasks, like scene processing to detect abandoned or removed objects and people detection. Real world applications of the proposed system include surveillance of wide areas (e.g. airports and museums) and buildings, and monitoring of safety equipment.</p>
    
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