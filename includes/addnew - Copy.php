<?php
	include("../php/connectSQL.php");

	//$temp = $_GET['temp'];
	//$hum = $_GET['humidity'];
	
	// for testing data
	//$temp = 40; $hum = 90;
	if(isset($_POST['mq2'])){
		$mq2=$_POST['mq2'];
		$query = "INSERT INTO `mq2sensor` (`mq2`) VALUES ('".$mq2."')";
		mysqli_query($conn, $query);
		mysqli_close($conn);
	}
	if(isset($_GET['distance'])){
		$distance=$_GET['distance'];
		$query = "INSERT INTO `distance` (`distance`) VALUES ('".$distance."')";
		mysqli_query($conn, $query);
		mysqli_close($conn);
		echo $distance;
	}
	if(isset($_POST['temp'])){
		$temp=$_POST['temp'];
		$hum=$_POST['hum'];
		$query = "INSERT INTO `monitor` (`temperature`, `humidity`)
		  	VALUES ('".$temp."','".$hum."')";

		mysqli_query($conn, $query);
		mysqli_close($conn);
	}
	$distance=$_GET['distance'];
	echo $distance;
	echo "add data ok...";
?>