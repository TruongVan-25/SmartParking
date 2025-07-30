<?php
	include("../php/connectSQL.php");

	
	$distance=$_POST['distance'];
	 
	//$dista=$_GET['dista'];
	//$distance = 250;

	echo "get data: distance =  $distance";
	  	
	$query = "INSERT INTO `distance` (`distance`)
	  	VALUES ('".$distance."')";

	mysqli_query($conn, $query);
	mysqli_close($conn);
	// header("Location: ../index.php");
?>