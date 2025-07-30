<?php
	
	//include 'php/draw.php';
	session_start();
	
	//include('php/conn2.php');
	if($_SESSION['LoginInto'] == "TRUE") {
		$current = 'control';
		require_once("includes/header.php");
	}
	else {
		 header('Location: /robotic/login.php');
	}
	

?>

<div class="wrap" style="background: url(image/3.jpg); padding-bottom: 10px;">
	<div class="row">
		<div class="col-sm-3">				
			<h1 class="sub-1">ROBOT CONTROL PANEL</h1>
			<div class="column cyan">
				<h1 class="sub-1">ROBOT DIRECTION CONTROL</h1>
				<button id="robot_forward" class="btn btn-success" style="padding: 0px"><img style="height: 40px" src="image/forward.png"></button> <br>
				<button id="robot_left" class="btn btn-success" style="padding: 0px"><img style="height: 40px"src="image/left.png"></button>
				<button id="buzzer_on" class="btn btn-success" style="padding: 0px" data-toggle="tooltip" title="Turn speaker ON!"><img style="height: 60px; border-radius: 3px;"
					src="image/speaker.png"></button>
				<button id="robot_right" class="btn btn-success" style="padding: 0px"><img style="height: 40px"src="image/right.png"></button> <br>
				<button id="robot_back" class="btn btn-success" style="padding: 0px"><img style="height: 40px"src="image/back.png"></button>
			</div>			

			<div class="column blue">
				<h2 class="sub-1">CAMERA POSITION CONTROL</h2>
				<button id="camera_up" class="btn btn-primary" style="padding: 0px"><img style="height: 40px" src="image/forward.png"></button> <br>
				<button id="camera_left" class="btn btn-primary" style="padding: 0px"><img style="height: 40px"src="image/left.png"></button>
				<button id="camera_center" class="btn btn-primary" style="padding: 0px" data-toggle="tooltip" title="Turn camera to center position!"><img style="height: 60px"src="image/center.png"></button>
				<button id="camera_right" class="btn btn-primary" style="padding: 0px"><img style="height: 40px"src="image/right.png"></button> <br>
				<button id="camera_down" class="btn btn-primary" style="padding: 0px"><img style="height: 40px"src="image/back.png"></button>
			</div>
			<div style="text-align: center; padding: 5px">			
				<!-- <button id="take_photo" class="btn btn-success" style="font-size: 20px;">Take Photo</button>				 -->
				<!-- <button id="video_record" class="btn btn-success" style="font-size: 20px;">Take Video</button>	 -->
				
				<button id="light_on" class="btn btn-success" data-toggle="tooltip" title="Turn light ON!"><img style="height: 30px" src="image/lighton.png"></button>
				<button id="light_off" class="btn" data-toggle="tooltip" title="Turn light OFF!"><img style="height: 30px"src="image/lightoff.png"></button>
			</div>
		</div> <!-- end of col-3 -->

		<div class="col-sm-6" style="text-align: center; padding: 0px;">			
			<h1 class="sub-1">REAL-TIME VIDEO MONITORING</h1>
			<div id="stream_windows" style="padding: 3px; text-align: center;">
				<!-- <iframe width="90%" height="500" style="border: 1px solid #cccccc;" src="https://www.youtube.com/embed/5dJG_DdOuOM?wmode=transparent"></iframe> -->
				<iframe width="100%" height="480" style="text-align: center; padding: 3px; border: 1px solid red" src="https://172.16.10.21:8443/"></iframe>
				<!-- <iframe id="video_feed" width="100%" height="480" style="text-align: center; padding: 3px; border: 1px solid red" src="http://192.168.1.112:8000/stream.mjpg"></iframe> -->
			</div>
			<div style="text-align: center;">
				<button id="start_stream_video" class="btn btn-success" data-toggle="tooltip" title="Start live video!" style="padding: 0px; width: 60px;"><img style="height: 40px"src="image/camera_on.png"></button>
				<button id="stop_stream_video" class="btn" style="padding: 0px;" data-toggle="tooltip" title="Stop live video!"><img style="height: 40px"src="image/camera_off.png"></button>
				<button id="take_photo" class="btn btn-primary" style="padding: 0px; width: 60px; background-color: #006289;" data-toggle="tooltip" title="Take photo!"><img style="height: 40px; "src="image/take_photo.png"></button>		
				<button id="video_record" class="btn btn-primary" style="padding: 0px; width: 60px;background-color: #006289;" data-toggle="tooltip" title="Start video record!"><img style="height: 40px;"src="image/record.png"></button>
				<button id="start_sensor" class="btn btn-success" style="padding: 0px; width: 60px" data-toggle="tooltip" title="Enable sensor!"><img style="height: 40px;" src="image/sensor.png"></button>
				<button id="stop_sensor" class="btn" style="padding: 0px; width: 60px;" data-toggle="tooltip" title="Disable sensor!"><img style="height: 40px; border-color: #006289;"src="image/sensor.png"></button>
				<button id="start_detection" class="btn btn-warning" style="padding: 0px;width: 60px;" data-toggle="tooltip" title="Enable object detection!"><img style="height: 40px"src="image/detection.png"></button>
				<button id="stop_detection" class="btn" style="padding: 0px;width: 60px;" data-toggle="tooltip" title="Stop object detection!"><img style="height: 40px"src="image/detection.png"></button>
				
			</div>

		</div> <!-- end of col-6 -->
		<div class="col-sm-3">
			<h1 class="sub-1">SYSTEM INFORMATION</h1>		
			<div class="center">
				<p style="color: orange; font-weight: bold; text-align: center;">System Time: <span id="system_timer"></span></p>
				<div id="system_refresh"> 	
					<?php  
	                    
	                    include("php/connectSQL.php");                    
	                    $sql = 'SELECT * FROM monitor ORDER BY `date` DESC';                    
	                    $result = mysqli_query($conn, $sql);
	                    $row = mysqli_fetch_array($result);
	                    $temperature = $row['temperature'];
	                    $humidity = $row['humidity'];

	                    // get MQ2 data
	                    $sql = 'SELECT * FROM mq2sensor ORDER BY `date` DESC';                    
	                    $result = mysqli_query($conn, $sql);
	                    $row = mysqli_fetch_array($result);
	                    $mq2 = $row['mq2'];

	                    // get current distance 
	                    $sql = 'SELECT * FROM distance ORDER BY `date` DESC';                    
	                    $result = mysqli_query($conn, $sql);
	                    $row = mysqli_fetch_array($result);
	                    $distance = $row['distance'];
	                  
	                    if ($temperature < 35) {
							// echo "Environmetal Status: SAFE";
							$background_color = "Green";
							$environmental_status = "SAFE";
						}
						elseif($temperature >= 35 and $temperature < 45){
							// echo "Environmetal Status: WARNING";
							$background_color = "orange";
							$environmental_status = "WARNING";
						}
						else{
							// echo "Environmetal Status: WARNING";
							$background_color = "red";
							$environmental_status = "DANGER";
						}
						if ($distance < 20) {
							// echo "Environmetal Status: SAFE";
							$background_color_distance = "red";
							
						}
						elseif($distance >= 20 and $distance < 100){
							// echo "Environmetal Status: WARNING";
							$background_color_distance = "orange";
							
						}
						else{
							// echo "Environmetal Status: WARNING";
							$background_color_distance = "green";
							
						}	                   
	                ?>
	                <!-- print environmental data -->
	                <div style="color: white; text-align: left;">   				
					
		                <ul style="list-style-type:disc;">
						  	<li>Temperature: <?php echo $temperature;?>	</li>
						  	<li>Humudity: <?php echo $humidity;?></li>
						  	<li>MQ2 Level: <?php echo $mq2;?></li>
						  	<li style="background-color: <?php echo $background_color_distance;?>; "> Distance to obstacle: <?php echo $distance;?> cm</li>
						</ul>  
	            	</div>

					<p id="systeminfo" style=" width: 95%; text-align: center; color: white; background-color: <?php echo $background_color;?>; ">Environmetal Status: <?php echo $environmental_status;?></p>
					
				</div> <!-- end of system refresh -->
				<button id="data_detail" class="btn btn-info" style="font-size: 15px;">Details</button>
			</div>	
			<div style="text-align: center; padding: 5px">
				<button id="shutdown_pi" class="btn btn-primary" style="height: 50px; width: 70px; background-color: #006289; border-color: #006289;" data-toggle="tooltip" title="Shutdown system!"><img style="height: 40px;" src="image/shutdown.png"></button>
				<button id="restart_pi" class="btn btn-primary" style="height: 50px; width: 70px; background-color: #006289; border-color: #006289;" data-toggle="tooltip" title="Restart system!" data-placement="top"><img style="height: 40px;" src="image/restart.png"></button>
			</div>		
			
			
		</div> <!-- end of col-3 -->				
	</div> <!--End of row section -->
</div> <!--End of wrap section -->
<script type="text/javascript">
	document.getElementById('data_detail').onclick = function () {
	    window.location = 'data.php';
}
</script>

<script>
var myVar = setInterval(myTimer, 1000);

function myTimer() {
  var d = new Date();
  var t = d.toLocaleTimeString();
  document.getElementById("system_timer").innerHTML = t;
}
</script>
<script type="text/javascript">
    setInterval("my_function();",5000); 
    function my_function(){
      $('#system_refresh').load(location.href + ' #system_refresh');
    }
 </script>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/index.js"></script>
	<!-- <script src="php/conn2.php"></script> -->
	<!-- <script src="php/mainfunction.php"></script>	 -->

<?php require_once("includes/footer.php"); ?> 

			

