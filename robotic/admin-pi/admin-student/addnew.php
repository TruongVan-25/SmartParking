<?php
	$temp = $_POST['temp'];
	$hum = $_POST['humidity'];
// echo "get data: temp1 =  $temp1,  hum1 = $hum1";
	$db_host = 'localhost'; // Server Name
	$db_user = 'root'; // Username
	$db_pass = 'abc@123'; // Password
	$db_name = 'controlcar'; // Database Name
	//ket noi mysql
	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	$query = "INSERT INTO `monitor` (`temperature`, `humidity`)
	  	VALUES ('".$temp."','".$hum."')";

	mysqli_query($conn, $query);
	mysqli_close($conn);
?>