<?php
	include("../php/connectSQL.php");

	// just for testing
		$temp=50;
		$hum=70;
		$query = "INSERT INTO `monitor` (`temperature`, `humidity`)
		  	VALUES ('".$temp."','".$hum."')";

		mysqli_query($conn, $query);
		mysqli_close($conn);
	
?>