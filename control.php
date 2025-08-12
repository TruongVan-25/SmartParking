<?php
	
	//include 'php/draw.php';
	session_start();
	
	//include('php/conn2.php');
	if($_SESSION['LoginInto'] == "TRUE") {
		$current = 'control';
		require_once("includes/header.php");
	}
	else {
		 header('Location: /smartparking/login.php');
	}
	

?>

<div class="wrap" style="background: url(image/3.jpg); padding-bottom: 10px;">
	<div class="row">
		<div class="col-sm-3">				
			<h1 class="sub-1">GATE CONTROL PANEL</h1>
			<div class="column cyan">
				<h2>GATE CONTROL</h2>
				<button id="open_gate" class="btn btn-info">Open Gate</button>
				<button id="close_gate" class="btn btn-danger">Close Gate</button>
			</div>			

			<h2 class="sub-1">TOTAL AVAILABLE SLOTS: <span id="available-count">0</span></h2>

			<div class="parking-area" style="border: 2px solid #ccc; padding: 10px; border-radius: 10px; background: #fff;">
				<div id="slot-container" class="grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;"></div>
			</div>
		</div> <!-- end of col-3 -->

		<div class="col-sm-6" style="text-align: center; padding: 0px;">			
			<h1 class="sub-1">SURVEILLANCE CAMERA</h1>
			<div class="row">
				<div class="col-sm-6">
					<h4>Entry gate</h4>
					<iframe width="100%" height="240" style="border: 2px solid green; border-radius: 5px" src="http://172.16.10.22/picam/cam_pic_new.php?pDelay=40000"></iframe>
				</div>
				<div class="col-sm-6">
					<h4>Exit gate</h4>
					<iframe width="100%" height="240" style="border: 2px solid red; border-radius: 5px" src="http://172.16.10.70:81/stream"></iframe>
				</div>
			</div>

		</div> <!-- end of col-6 -->
		<div class="col-sm-3">
			<!-- chỗ này để số lượng chỗ đã đỗ, còn trống, trên tổng số -->
			<h1 class="sub-1">SYSTEM INFORMATION</h1>		
			<div class="center">
				<p style="color: orange; font-weight: bold; text-align: center;">System Time: <span id="system_timer"></span></p>
				<div id="system_refresh"> 	
					<?php  
	                    
	                    include("php/connectSQL.php");                    
	                    // Lấy tổng số lượng slot trong bảng parkingslot
						$sql = "SELECT COUNT(*) AS total_slots FROM parkingslot";
						$result = mysqli_query($conn, $sql);

						if ($result && mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_assoc($result);
							$total_slots = $row['total_slots'];
						} else {
							$total_slots = 0;
						}

	                    // Lấy tổng số lượng slot đã có xe đỗ
						$sql = "SELECT COUNT(*) AS occupied FROM parkinghistory WHERE TimeOut IS NULL";
						$result = mysqli_query($conn, $sql);

						if ($result && mysqli_num_rows($result) > 0) {
							$row = mysqli_fetch_assoc($result);
							$occupied_slots = $row['occupied'];
						} else {
							$occupied_slots = 0;
						}

						$available_slots = $total_slots - $occupied_slots;
	                  
	                            
	                ?>
	                <!-- print environmental data -->
	                <div style="color: white; text-align: left;">   				
					
		                <ul style="list-style-type:disc;">
						  	<li>Total slots: <?php echo $total_slots;?>	</li>
						  	<li>Available: <?php echo $available_slots;?></li>
						  	<li>Occupied: <?php echo $occupied_slots;?></li>
						</ul>  
	            	</div>
					
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
	const TOTAL_SLOTS = 6;

	const slotContainer = document.getElementById("slot-container");
	for (let i = 1; i <= 6; i++) {
		const slotDiv = document.createElement("div");
		slotDiv.className = "slot";
		slotDiv.id = "slot-" + i;
		slotDiv.style.border = "1px solid #ccc";
		slotDiv.style.borderRadius = "8px";
		slotDiv.style.padding = "10px";
		slotDiv.style.background = "#ccffcc"; // màu mặc định: trống

		slotDiv.innerHTML = `
			<h4>Slot ${i}</h4>
			<p>Status: <strong>Loading...</strong></p>
		`;
		slotContainer.appendChild(slotDiv);
	}

	async function fetchDataFromWemos() {
		try {
			const response = await fetch('http://192.168.1.123/status');
			const data = await response.json();
			updateUI(data);
		} catch (error) {
			console.error("Lỗi kết nối Wemos:", error);
		}
	}

	function updateUI(data) {
		let availableCount = 0;

		for (let i = 1; i <= TOTAL_SLOTS; i++) {
			const slotData = data.slots.find(s => s.id === i);
			const slotDiv = document.getElementById("slot-" + i);

			if (!slotDiv) continue;

			if (slotData && slotData.status === "occupied") {
				slotDiv.style.background = "#ffcccc";
				slotDiv.innerHTML = `
					<h4>Slot ${i}</h4>
					<p>Trạng thái: <strong>Đã có xe</strong></p>
					<p>RFID: ${slotData.rfid}</p>
					<p>Giờ vào: ${slotData.timeIn}</p>
				`;
			} else {
				availableCount++;
				slotDiv.style.background = "#ccffcc";
				slotDiv.innerHTML = `
					<h4>Bãi ${i}</h4>
					<p>Trạng thái: <strong>Trống</strong></p>
				`;
			}
		}

		document.getElementById("available-count").textContent = availableCount;
	}

	fetchDataFromWemos();
	setInterval(fetchDataFromWemos, 5000);
	</script>

	<!-- real time -->
	<script>
		var myVar = setInterval(myTimer, 1000);
		function myTimer() {
			var d = new Date();
			document.getElementById("system_timer").innerHTML = d.toLocaleTimeString();
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

			
