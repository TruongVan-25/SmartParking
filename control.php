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

<div class="wrap" style="background: url(image/3.jpg); padding-bottom: 10px; display: flex; justify-content: center;">
	<div class="row">
		<div class="col-sm-3">				
            <h1 class="sub-1">CONTROL PANEL</h1>

            <!-- ENTRY GATE CONTROL -->
            <div class="column cyan mb-3 p-2">
                <h2>ENTRY</h2>
                <button id="open_entry_gate" class="btn btn-info d-block w-100 mb-2">Open</button>
                <button id="close_entry_gate" class="btn btn-danger d-block w-100">Close</button>
            </div>

            <!-- EXIT GATE CONTROL -->
            <div class="column bg-warning p-2">
                <h2>EXIT</h2>
                <button id="open_exit_gate" class="btn btn-info d-block w-100 mb-2">Open</button>
                <button id="close_exit_gate" class="btn btn-danger d-block w-100">Close</button>
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
			<h1 class="sub-1">OVERALL STATUS</h1>		
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
		<div class="row" style="margin-top: 20px;">
			<div class="col-sm-12">
				<h2 class="sub-1" style="text-align: center;">
					TOTAL AVAILABLE SLOTS: <span id="available-count">0</span>
				</h2>
			</div>
			<div class="col-sm-12" style="display: flex; justify-content: center;">
				<div class="parking-area" 
					style="border: 2px solid #ccc; padding: 10px; border-radius: 10px; background: #fff; display: flex; justify-content: center; width: 90%; box-sizing: border-box;">
					<div id="slot-container" 
						style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; justify-items: center; width: 100%; max-width: 900px;">
					</div>
				</div>
			</div>
		</div>
    </div>			
	</div> <!--End of row section -->
</div> <!--End of wrap section -->
<script type="text/javascript">
	const slotContainer = document.getElementById("slot-container");
	async function fetchSlotsFromDB() {
		try {
			// Lấy danh sách tất cả slot
			const slotsRes = await fetch('services/get_slot.php');
			const slots = await slotsRes.json(); // ["A1", "A2", ...]

			// Lấy danh sách slot bị occupied
			const occupiedRes = await fetch('services/get_occupied.php');
			const occupiedSlots = await occupiedRes.json(); // ["A2", "B3", ...]

			slotContainer.innerHTML = "";
			let availableCount = 0;

			slots.forEach((slotName, index) => {
				const slotDiv = document.createElement("div");
				slotDiv.className = "slot";
				slotDiv.id = `slot-${index + 1}`;
				slotDiv.style.border = "1px solid #ccc";
				slotDiv.style.borderRadius = "8px";
				slotDiv.style.padding = "10px";

				if (occupiedSlots.includes(slotName)) {
					// Slot bị chiếm
					slotDiv.style.background = "#ffcccc"; // đỏ nhạt
					slotDiv.innerHTML = `
						<h4>${slotName}</h4>
						<p>Status: <strong style="color:red;">Occupied</strong></p>
					`;
				} else {
					// Slot trống
					slotDiv.style.background = "#ccffcc"; // xanh nhạt
					slotDiv.innerHTML = `
						<h4>${slotName}</h4>
						<p>Status: <strong style="color:green;">Available</strong></p>
					`;
					availableCount++;
				}

				slotContainer.appendChild(slotDiv);
			});

			// Hiển thị số slot trống
			document.getElementById("available-count").textContent = availableCount;

		} catch (error) {
			console.error("Lỗi lấy slot từ DB:", error);
		}
	}

// Gọi hàm khi load trang
	fetchSlotsFromDB();

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

<script>
document.getElementById("open_gate").addEventListener("click", function() {
    sendCommand("OPEN_ENTRY");
});

document.getElementById("close_gate").addEventListener("click", function() {
    sendCommand("CLOSE_ENTRY");
});

function sendCommand(action) {
    fetch("mqtt_control.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "action=" + action
    })
    .then(response => response.text())
    .then(result => {
        alert("Server response: " + result);
        console.log(result);
    })
    .catch(error => console.error("Error:", error));
}
</script>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/index.js"></script>
	<!-- <script src="php/conn2.php"></script> -->
	<!-- <script src="php/mainfunction.php"></script>	 -->

<?php require_once("includes/footer.php"); ?> 

			