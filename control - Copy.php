<?php
	
	//include 'php/draw.php';
	session_start();
	
	//include('php/conn2.php');
	if($_SESSION['LoginInto'] == "TRUE") {
		require_once("includes/header.php");
	}
	else {
		 header('Location: /robotic/login.php');
	}


	if (!isset($background_color)) {
		$background_color = 'green';
	} 
	

?>

<div class="wrap" style="background: url(image/3.jpg); padding-bottom: 10px;">
	<!-- <div class="row">
        <div class="col-12" >
            <h1 class="Title" style="color: white"> SAFE MONITORING AND CONTROL SYSTEM </h1>      
        </div>
    </div> -->
	<div class="row">
		<div class="col-sm-3">				
			<h1 class="sub-1">SYSTEM CONTROL PANEL</h1>
			<div class="column cyan">
				<h2 class="sub-1">DIRECTION CONTROL</h2>
				<button id="robot_forward" class="btn btn-success">FORWARD</button> <br><br>
				<button id="robot_left" class="btn btn-success btn-arrow-left">LEFT</button>
				<button id="robot_stop" class="btn btn-warning">STOP</button>
				<button id="robot_right" class="btn btn-success">RIGHT</button> <br><br>
				<button id="robot_back" class="btn btn-success"> BACK </button>
			</div>
			

			<div class="column blue">
				<h2 class="sub-1">CAMERA CONTROL</h2>
				<button id="camera_up" class="btn btn-success">UP</button> <br><br>
				<button id="camera_left" class="btn btn-success">LEFT</button>
				<button id="camera_center" class="btn btn-warning">CENTER</button>
				<button id="camera_right" class="btn btn-success">RIGHT</button> <br><br>
				<button id="camera_down" class="btn btn-success">DOWN</button>
			</div>
		<!-- 	<div class="column">
				<p id="MonitorTitle">DATA MONITORING</p>
					<button id="goMonitor" class="btn btn-success" style="font-size: 20px;">View Data</button>
			</div>
			 -->
			
		</div>		

		<div class="col-sm-6" style="text-align: center; padding: 0px;">
			
			<h1 class="sub-1">LIVE STREAM MONITORING</h1>
				<!-- <p id="LiveStreamTitle" style="text-align:left !important;">LIVE STREAM</p> -->
			
				<div id="stream_windows" class="row" style="padding-bottom: 3px; text-align: center;">
					<!-- <iframe width="90%" height="500" style="border: 1px solid #cccccc;" src="https://www.youtube.com/embed/5dJG_DdOuOM?wmode=transparent"></iframe> -->
					<!-- <iframe width="650" height="490" style="border: 1px solid #cccccc;" src="http://192.168.1.211:9000/"></iframe> -->
					<iframe width="100%" height="470" style="border: 1px solid red;" src="http://192.168.1.111:8000/"></iframe>
				</div>
				<button id="start_stream_video" class="btn btn-success" style="font-size: 20px;">Start Stream</button>
				<button id="stop_stream_video" class="btn btn-warning" style="font-size: 20px;">Stop Stream</button>
				<button id="take_photo" class="btn btn-success" style="font-size: 20px;">Take Photo</button>				
				<button id="video_record" class="btn btn-success" style="font-size: 20px;">Video Record</button>

		</div>
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
                   
                ?>
                <!-- print environmental data -->
                <div style="color: white; text-align: left;">   				
				
	                <ul style="list-style-type:disc;">
					  	<li>Temperature: <?php echo $temperature;?>	</li>
					  	<li>Humudity: <?php echo $humidity;?></li>
					  	<li>MQ2 Level: <?php echo $mq2;?></li>
					  	<li> Distance to obstacle: <?php echo $distance;?> cm</li>
					</ul>  
            	</div>

				<p id="systeminfo" style="background-color: <?php echo $background_color;?>; text-align: center; color: white;">Environmetal Status: <?php echo $environmental_status;?></p>
				
			</div> <!-- end of system refresh -->
				<button id="data_detail" class="btn btn-info" style="font-size: 20px;">Details</button>
			</div>	
			
			<!-- <div style="text-align: center; padding: 5px">
				<button id="data_detail123" class="btn btn-info" style="font-size: 20px;">Details</button>				
			</div> -->
			<div style="text-align: center; padding: 5px">
				<button id="start_sensor" class="btn btn-primary" style="font-size: 20px;">Start Sensor</button>
				<button id="detection" class="btn btn-primary" style="font-size: 20px;">Detection</button>
			<div style="text-align: center; padding: 5px">
				<button id="shutdown_pi" class="btn btn-warning" style="font-size: 20px;">Shutdown</button>
				<button id="restart_pi" class="btn btn-warning" style="font-size: 20px;">Restart</button>
			</div>
			
			
			
			</div>
		</div>				
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

			

