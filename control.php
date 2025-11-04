<?php
session_start();

if ($_SESSION['LoginInto'] == "TRUE") {
	$current = 'control';
	require_once("includes/header.php");
} else {
	header('Location: /smartparking/login.php');
}
?>

<div class="wrap" style="background: url(image/3.jpg); padding-bottom: 10px; display: flex; justify-content: center;">
	<div class="row">
		<div class="col-sm-3">
			<h1 class="sub-1">GATE CONTROL PANEL</h1>
			<div class="column" style="background: #f0f0f0; padding: 15px; border-radius: 10px;">
				<h2>GATE CONTROL</h2>

				<!-- Entrance Gate -->
				<div style="background: #d9f7d9; padding: 10px; margin-bottom: 15px; border-radius: 8px;">
					<h3 style="color: green;">Entrance</h3>
					<button id="open_gate_entrance" class="btn btn-success">Open Gate</button>
					<button id="close_gate_entrance" class="btn btn-danger">Close Gate</button>
				</div>

				<!-- Exit Gate -->
				<div style="background: #f7d9d9; padding: 10px; border-radius: 8px;">
					<h3 style="color: red;">Exit</h3>
					<button id="open_gate_exit" class="btn btn-success">Open Gate</button>
					<button id="close_gate_exit" class="btn btn-danger">Close Gate</button>
				</div>
			</div>
		</div>

		<div class="col-sm-6" style="text-align: center; padding: 0px;">
			<h1 class="sub-1">SURVEILLANCE CAMERA</h1>
			<div class="row">
				<div class="col-sm-6">
					<h4>Entry gate</h4>
					<iframe width="100%" height="240" style="border: 2px solid green; border-radius: 5px"
						src="https://iot.eiu.com.vn/picam/cam_pic_new.php?pDelay=40000"></iframe>
				</div>
				<div class="col-sm-6">
					<h4>Exit gate</h4>
					<iframe width="100%" height="240" style="border: 2px solid red; border-radius: 5px"
						src="http://172.16.10.170:81/stream"></iframe>
				</div>
			</div>
		</div>

		<div class="col-sm-3">
			<h1 class="sub-1">OVERAL STATUS</h1>
			<div class="center">
				<p style="color: orange; font-weight: bold; text-align: center;">System Time: <span
						id="system_timer"></span></p>
				<div id="system_refresh">
					<?php
					include("php/connectSQL.php");

					$sql = "SELECT COUNT(*) AS total_slots FROM parkingslot";
					$result = mysqli_query($conn, $sql);
					$total_slots = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['total_slots'] : 0;

					$sql = "SELECT COUNT(*) AS occupied FROM parkingslot WHERE Status = 1";
					$result = mysqli_query($conn, $sql);
					$occupied_slots = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result)['occupied'] : 0;

					$available_slots = $total_slots - $occupied_slots;
					?>
					<div style="color: white; text-align: left;">
						<ul style="list-style-type:disc;">
							<li>Total slots: <?php echo $total_slots; ?> </li>
							<li>Available: <?php echo $available_slots; ?></li>
							<li>Occupied: <?php echo $occupied_slots; ?></li>
						</ul>
					</div>
				</div>
				<button id="data_detail" class="btn btn-info" style="font-size: 15px;">Details</button>
			</div>
			<div style="text-align: center; padding: 5px">
				<button id="shutdown_pi" class="btn btn-primary"
					style="height: 50px; width: 70px; background-color: #006289; border-color: #006289;"
					data-toggle="tooltip" title="Shutdown system!"><img style="height: 40px;"
						src="image/shutdown.png"></button>
				<button id="restart_pi" class="btn btn-primary"
					style="height: 50px; width: 70px; background-color: #006289; border-color: #006289;"
					data-toggle="tooltip" title="Restart system!" data-placement="top"><img style="height: 40px;"
						src="image/restart.png"></button>
			</div>
		</div>

		<div class="col-sm-12">
			<h2 class="sub-1" style="text-align: center;">TOTAL AVAILABLE SLOTS: <span id="available-count">0</span>
			</h2>
		</div>
		<div class="col-sm-12" style="display: flex; justify-content: center;">
			<div class="parking-area"
				style="border: 2px solid #ccc; padding: 10px; border-radius: 10px; background: #fff; display: inline-block; margin: 20px auto;">
				<div id="slot-container"
					style="display: grid; grid-template-columns: repeat(10, 1fr); gap: 10px; justify-items: center; width: 100%; max-width: 1200px;">
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	const slotContainer = document.getElementById("slot-container");
	async function fetchSlotsFromDB() {
		try {
			const slotsRes = await fetch('services/get_slot.php');
			const slots = await slotsRes.json();

			const occupiedRes = await fetch('services/get_occupied.php');
			const occupiedSlots = await occupiedRes.json();

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
					slotDiv.style.background = "#ffcccc";
					slotDiv.innerHTML = `<h4>${slotName}</h4><p>Status: <strong style="color:red;">Occupied</strong></p>`;
				} else {
					slotDiv.style.background = "#ccffcc";
					slotDiv.innerHTML = `<h4>${slotName}</h4><p>Status: <strong style="color:green;">Available</strong></p>`;
					availableCount++;
				}

				slotContainer.appendChild(slotDiv);
			});

			document.getElementById("available-count").textContent = availableCount;
		} catch (error) {
			console.error("Lỗi lấy slot từ DB:", error);
		}
	}
	fetchSlotsFromDB();
</script>

<script>
	var myVar = setInterval(myTimer, 1000);
	function myTimer() {
		var d = new Date();
		document.getElementById("system_timer").innerHTML = d.toLocaleTimeString();
	}
</script>

<script type="text/javascript">
	setInterval("my_function();", 5000);
	function my_function() {
		$('#system_refresh').load(location.href + ' #system_refresh');
	}
</script>

<script>
	document.getElementById("open_gate_entrance").addEventListener("click", function () {
		sendCommand("OPEN_ENTRY");
	});
	document.getElementById("close_gate_entrance").addEventListener("click", function () {
		sendCommand("CLOSE_ENTRY");
	});
	document.getElementById("open_gate_exit").addEventListener("click", function () {
		sendCommand("OPEN_EXIT");
	});
	document.getElementById("close_gate_exit").addEventListener("click", function () {
		sendCommand("CLOSE_EXIT");
	});

	function showNotification(message, color = "#4CAF50") {
		const noti = document.getElementById("notification");
		noti.style.display = "block";
		noti.style.backgroundColor = color;
		noti.textContent = message;
		noti.style.opacity = "1";
		setTimeout(() => {
			noti.style.opacity = "0";
			setTimeout(() => { noti.style.display = "none"; }, 1000);
		}, 2500);
	}

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
				let message = "";
				let color = "#4CAF50";

				if (action === "OPEN_ENTRY") message = "Open Entrance successfully";
				else if (action === "CLOSE_ENTRY") message = "Close Entrance successfully";
				else if (action === "OPEN_EXIT") message = "Open Exit successfully";
				else if (action === "CLOSE_EXIT") message = "Close Exit successfully";
				else {
					message = "Unknown action";
					color = "#f44336";
				}

				showNotification(message, color);
				console.log(result);
			})
			.catch(error => {
				console.error("Error:", error);
				showNotification("Error sending command!", "#f44336");
			});
	}
</script>

<!-- 🔔 Notification box -->
<div id="notification"></div>

<!-- 💅 Style for notification -->
<style>
	#notification {
		display: none;
		position: fixed;
		top: 20px;
		right: 20px;
		padding: 12px 20px;
		background-color: #4CAF50;
		color: white;
		border-radius: 4px;
		box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
		z-index: 1000;
		transition: opacity 0.5s ease;
	}
</style>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/index.js"></script>

<?php require_once("includes/footer.php"); ?>